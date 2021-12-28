<?php


namespace App\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Exception;

class Products
{
    use ResultDataTrait;
    /**
     * Getting product by id
     * 
     * @param int 
     * @return object|bool
     * 
     */
    public function getById(int $id): object|bool
    {
        try {
            $product = Product::where('product_id', $id)->with([
                'categories',
                'names',
                'images',
                'descriptions'
            ])->first();
            if (isset($product->product_id)) return $product;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting price by product id
     * 
     * @param int $id
     * @return object|bool
     * 
     */
    public function getPrice(int $id): object|bool
    {
        try {
            $product = Product::select('product_price')->where('product_id', $id)->withTrashed()->first();
            if (isset($product->product_price)) return $product;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting N random products
     * 
     * @param int $count
     * @return array
     * 
     */
    public function getRandom(int $count): iterable
    {
        try {
            $products = Product::where('product_exst', 1)->with('names', 'images', 'descriptions')->inRandomOrder()->take($count)->get();
            if ($this->check_result($products)) return $products;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Getting all users
     * 
     * @param null
     * @return Collection
     * 
     */
    public function getAll(): object|iterable
    {
        try {
            $products = Product::with('names', 'images', 'descriptions', 'categories')->orderby('product_id', 'desc');
            if ($this->check_result($products)) return $products;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Getting first 5 result of search query
     * 
     * @param string $query 
     * @return Collection
     * 
     */
    public function search(string $query): iterable
    {
        try {
            $products = Product::join('product_names', 'f_product_id', '=', 'product_id')
                ->join('categories', 'f_catg_id', '=', 'catg_id')
                ->where('product_name_lang_en', 'LIKE', '%' . $query . '%')
                ->OrWhere('product_name_lang_de', 'LIKE', '%' . $query . '%')
                ->OrWhere('product_name_lang_uk', 'LIKE', '%' . $query . '%')
                ->OrWhere('product_name_lang_ru', 'LIKE', '%' . $query . '%')
                ->limit(5)->orderby('products.created_at', 'desc')->get();
            if ($this->check_result($products)) return $products;
            else return array();
        } catch (Exception $e) {
            return array();
        }
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
     * 
     * @param array $validated
     * @param object $request
     * @return array
     * 
     */
    public function uploadfiles(array $validated, object $request): array
    {
        try {
            $filenames = array();
            for ($i = 1; $i < 5; $i++) {
                $name = "default_" . $i;
                if ($validated[$name] == 1) {
                    $file = $request->file('file_image_' . $i);
                    $filename = md5($file->getClientOriginalName() . date("Y-m-d H:i:s")) . "." . $file->clientExtension();
                    Storage::putFileAs("public/assets/img/products", $request->file('file_image_' . $i), $filename);
                    array_push($filenames, $filename);
                } elseif ($request->$name == 0 || $request->$name == -1) {
                    array_push($filenames, null);
                }
            }
            return $filenames;
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Adding product data, images, filenames
     * 
     * @param array $validated
     * @param array $filenames
     * @return int|bool
     * 
     */
    public function add(array $validated, array $filenames): int|bool
    {
        try {
            $product = Product::create([
                'product_price' => $validated['product_price'],
                'product_weight' => $validated['product_weight'],
                'f_catg_id' => $validated['product_categ'],
                'product_exst' => $validated['product_exst']
            ]);
            foreach ($filenames as $name)
                $product->images()->create([
                    'product_image_name' => $name
                ]);
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
            if (isset($product->product_id)) return $product->product_id;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update product data, images, filenames
     * 
     * @param array $validated
     * @param array $filenames
     * @param int $id
     * @return bool
     * 
     */
    public function update(array $validated, array $filenames, int $id): bool
    {
        try {
            $product = Product::findOrFail($id)->update([
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
            if (count($filenames) > 0) {
                foreach ($product_img as $index => $image)
                    if ($filenames[$index] == null) {
                        ProductImage::where('product_image_id', $image->product_image_id)
                            ->update(['product_image_name' => null]);
                        if ($image->product_image_name != null)
                            Storage::delete("public/assets/img/products/" . $image->product_image_name);
                    } elseif ($filenames[$index] != -1) {
                        ProductImage::where('product_image_id', $image->product_image_id)->update([
                            'product_image_name' => $filenames[$index]
                        ]);
                        if ($image->product_image_name != null)
                            Storage::delete("public/assets/img/products/" . $image->product_image_name);
                    }
            }
            /**
             * Formation data
             * and update product_names and product_description table
             */
            $product = Product::findOrFail($id);
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
            if ($product) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete product images by data by product id
     * 
     * @param int $id
     * @return bool
     * 
     */
    public function delete(int $id): bool
    {
        try {
            $images = ProductImage::where('f_product_id', $id);
            foreach ($images->get() as $img) {
                if ($img->product_image_name != null)
                    Storage::delete("public/assets/img/products/" . $img->product_image_name);
            }
            $images->update(['product_image_name' => null]);
            $result = Product::where('product_id', $id)->delete();
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
