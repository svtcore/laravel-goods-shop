<?php


namespace App\Classes;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductName;
use Illuminate\Support\Facades\Storage;
use Exception;

class Products{

    public function getById($id){
        return Product::where('product_id', $id)->with('categories', 'names','images', 'descriptions')->first();
    }

    public function getPrice($id){
        return Product::select('product_price')->where('product_id', $id)->withTrashed()->first();
    }

    public function getRandom($count){
        return Product::where('product_exst', 1)->with('names','images','descriptions')->inRandomOrder()->take($count)->get();
    }

    public function getAll(){
        return Product::with('names', 'images', 'descriptions', 'categories')->orderby('product_id', 'desc');
    }

    public function search($query){
        return Product::join('product_names', 'f_product_id', '=','product_id')
                        ->join('categories', 'f_catg_id', '=','catg_id')
                        ->where('product_name_lang_en' , 'LIKE', '%'.$query.'%')
                        ->OrWhere('product_name_lang_de' , 'LIKE', '%'.$query.'%')
                        ->OrWhere('product_name_lang_uk' , 'LIKE', '%'.$query.'%')
                        ->OrWhere('product_name_lang_ru' , 'LIKE', '%'.$query.'%')
                        ->limit(5)->orderby('products.created_at', 'desc')->get();
    }

    /**
     * Using for create new product
     * Upload files to server and formation array
     * with filenames
     * Checking if param default_id has one of these states
     * 1 = upload images
     * 0 = set null (no image)
     * -1 = dont do nothing
     * Return array with filenames to create records with uploaded images
     */
    public function uploadfiles($validated, $request){
        $filenames = array();
        try{
            for ($i = 1; $i < 5; $i++){
                $name = "default_".$i;
                if ($validated[$name] == 1){
                    $file = $request->file('file_image_'.$i);
                    $filename = md5($file->getClientOriginalName().date("Y-m-d H:i:s")).".".$file->clientExtension();
                    Storage::putFileAs("public/assets/img/products", $request->file('file_image_'.$i), $filename);
                    array_push($filenames, $filename);
                }
                elseif ($request->$name == 0 || $request->$name == -1) {
                    array_push($filenames, null);
                }
            }
        }
        catch(Exception $e){
            //
        }
        return $filenames;
    }

    /**
     * Formation data and added records to db
     */
    public function add($validated, $filenames){
        try{
            $product = Product::create([
                'product_price' => $validated['product_price'],
                'product_weight' => $validated['product_weight'],
                'f_catg_id' => $validated['product_categ'],
                'product_exst' => $validated['product_exst']
            ]);
            foreach($filenames as $name)
                $product->images()->create([
                    'product_image_name' => $name]);
            $product->names()->create([
                'product_name_lang_en' => $validated['product_name_en'],
                'product_name_lang_de' => $validated['product_name_de'],
                'product_name_lang_uk' => $validated['product_name_uk'],
                'product_name_lang_ru' => $validated['product_name_ru']
            ]);
            $product->descriptions()->create([
                'product_desc_lang_en' => $validated['product_description_en'],
                'product_desc_lang_de' => $validated['product_description_de'],
                'product_desc_lang_uk' => $validated['product_description_uk'],
                'product_desc_lang_ru' => $validated['product_description_ru']
            ]);
        }
        catch(Exception $e){
            //
        }
    }

    /**
     * Formation data and update records
     */
    public static function update($validated, $filenames, $id){
        try{
            $product = Product::find($id)->update([
                'product_price' => $validated['product_price'],
                'product_weight' => $validated['product_weight'],
                'f_catg_id' => $validated['product_categ'],
                'product_exst' => $validated['product_exst']
            ]);

            /**
             * Update images
             * Checking  states of images
             * if value in array has null then delete image name from db (set null)
             * otherwise if value not equals -1 then update exist record
             * (-1 state means "do nothing" escape image record)
             */

            $product_img = ProductImage::where('f_product_id', $id)->get();
            if (count($filenames) > 0){
                foreach($product_img as $index => $image)
                if ($filenames[$index] == null){
                    ProductImage::where('product_image_id', $image->product_image_id)
                            ->update(['product_image_name' => null]);
                    if ($image->product_image_name != null)
                        Storage::delete("public/assets/img/products/".$image->product_image_name);
                }elseif($filenames[$index] != -1){
                    ProductImage::where('product_image_id', $image->product_image_id)->update([
                        'product_image_name' => $filenames[$index]]);
                    if ($image->product_image_name != null)
                        Storage::delete("public/assets/img/products/".$image->product_image_name);
                }
            }
            /**
             * Formation data
             * and update product_names and product_description table
             */
            $product = Product::find($id);
            $product->names()->update([
                'product_name_lang_en' => $validated['product_name_en'],
                'product_name_lang_de' => $validated['product_name_de'],
                'product_name_lang_uk' => $validated['product_name_uk'],
                'product_name_lang_ru' => $validated['product_name_ru']
            ]);
            $product->descriptions()->update([
                'product_desc_lang_en' => $validated['product_description_en'],
                'product_desc_lang_de' => $validated['product_description_de'],
                'product_desc_lang_uk' => $validated['product_description_uk'],
                'product_desc_lang_ru' => $validated['product_description_ru']
            ]);
        }
        catch(Exception $e){
            //
        }
    }

    public function delete($id){
        $images = ProductImage::where('f_product_id', $id);
        foreach($images->get() as $img){
            if ($img->product_image_name != null)
                Storage::delete("public/assets/img/products/".$img->product_image_name);
        }
        $images->update(['product_image_name' => null]);
        return Product::where('product_id', $id)->delete();
    }
}
