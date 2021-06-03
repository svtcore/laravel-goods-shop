<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductName;
use App\Models\ProductDescription;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Getting product list
     * return view with recived data
     */
    public function index()
    {
        $prod =  Product::getProductsData()->get();
        if (count($prod) > 0)
            return view('admin.products')->with('products', Product::getProductsData()->paginate(15));
        else return view('admin.products')->with('products', null);
    }
    
    /**
     * Getting all categories
     * formation them to array only on user language
     * for dislpay in <select> tag
     * Return view with categories array
     */

    public function formationCategories($catg){
        $catg_array = array();
        try{
            $local = app()->getLocale();
            $catg_name = "catg_name_".$local;
            foreach($catg as $cat){
                $catg_array[$cat->catg_id] = $cat->$catg_name;
            }
        }
        catch(Exception $e){
            //
        }
        return $catg_array;
    }

    public function create()
    {
        return view('admin.product_new')->with('catg', $this->formationCategories(Category::all()));
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
    public function uploadfiles($request){
        $filenames = array();
        try{
            for ($i = 1; $i < 5; $i++){
                $name = "default_".$i;
            if ($request->$name == 1){
                $file = $request->file('file_image_'.$i);
                $filename = md5($file->getClientOriginalName().$today = date("Y-m-d H:i:s")).".".$file->clientExtension();
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
     * Using for update product
     * Upload files to server and formation array
     * with filenames
     * Checking if param default_id has one of these states
     * 1 = upload (replace) images
     * 0 = set null (delete exits)
     * -1 = dont do nothing (when user dont want delete or change current image)
     * Return array with filenames to updae or null if delete or -1 if do nothing
     */

    public function updateuploadfiles($request){
        $filenames = array();
        try{
            for ($i = 1; $i < 5; $i++){
                $name = "default_".$i;
            if ($request->$name == 1){
                $file = $request->file('file_image_'.$i);
                $filename = md5($file->getClientOriginalName().$today = date("Y-m-d H:i:s")).".".$file->clientExtension();
                Storage::putFileAs("public/assets/img/products", $request->file('file_image_'.$i), $filename);
                array_push($filenames, $filename);
            }
            elseif ($request->$name == 0) {
                array_push($filenames, null);
            }
            elseif ($request->$name == -1){
                array_push($filenames, -1);
            }
            }
            return $filenames;
        }
        catch(Exception $e){
            //
        }
    }


    /**
     * Formation data and added records to db
     */
    public function addProductData($request, $filenames){
        try{
            $product = Product::create([
                'product_price' => $request->product_price,
                'product_weight' => $request->product_weight,
                'f_catg_id' => $request->product_categ,
                'product_exst' => $request->product_exst
            ]);
            foreach($filenames as $name)
                $product->images()->create([
                    'product_image_name' => $name]);
            $product->names()->create([
                'product_name_lang_en' => $request->product_name_en,
                'product_name_lang_de' => $request->product_name_de,
                'product_name_lang_uk' => $request->product_name_uk,
                'product_name_lang_ru' => $request->product_name_ru
            ]);
            $product->descriptions()->create([
                'product_desc_lang_en' => $request->product_description_en,
                'product_desc_lang_de' => $request->product_description_de,
                'product_desc_lang_uk' => $request->product_description_uk,
                'product_desc_lang_ru' => $request->product_description_ru
            ]);
        }
        catch(Exception $e){
            //
        }
    }


    /**
     * Formation data and update records
     */
    public static function updateProductData($request, $filenames, $id){
        try{
            $product = Product::find($id)->update([
                'product_price' => $request->product_price,
                'product_weight' => $request->product_weight,
                'f_catg_id' => $request->product_categ,
                'product_exst' => $request->product_exst
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
                'product_name_lang_en' => $request->product_name_en,
                'product_name_lang_de' => $request->product_name_de,
                'product_name_lang_uk' => $request->product_name_uk,
                'product_name_lang_ru' => $request->product_name_ru
            ]);
            $product->descriptions()->update([
                'product_desc_lang_en' => $request->product_description_en,
                'product_desc_lang_de' => $request->product_description_de,
                'product_desc_lang_uk' => $request->product_description_uk,
                'product_desc_lang_ru' => $request->product_description_ru
            ]);
        }
        catch(Exception $e){
            //
        }
    }

    /**
     * Validate data and call function to formation and
     * update data
     */
    public function store(ProductRequest $request)
    {
        $filenames = $this->uploadfiles($request);
        $this->addProductData($request, $filenames);
        return redirect()->route('admin.products');
    }

    /**
     * Getting data by product id
     * and call function to formation categories on user language
     */
    public function edit($id)
    {
        $data = Product::getProductById($id);
        if (!empty($data))
            return view ('admin.product_edit')->with('products', $data)->with('catg', $this->formationCategories(Category::all()));
        else return abort(404);
    }

    /**
     * Validate data through Request\ProductRequest
     * and call function to update data
     */
    public function update(ProductRequest $request, $id)
    {
        $filenames = $this->updateuploadfiles($request);
        $this->updateProductData($request, $filenames, $id);
        return redirect()->route('admin.products');
    }

    /**
     * Delete record
     */
    public function destroy($id)
    {
        $images = ProductImage::where('f_product_id', $id);
        foreach($images->get() as $img){
            if ($img->product_image_name != null)
                Storage::delete("public/assets/img/products/".$img->product_image_name);
        }
        $images->update(['product_image_name' => null]);
        Product::where('product_id', $id)->delete();
        return redirect()->route('admin.products');
    }

    /**
     * Validate input params
     * get results from query and adding 2 fields into results
     * user_language_product_name - name of product on user language
     * user_language_category_name - name of category on user language
     * made for easiest locale display in ProductSearchComponent.vue file
     * all results display on user language in spite of this even if query was on another language
     * return json with results
     */
    public function search(){
        try{
            $lang = app()->getLocale();
            $query = preg_replace( '/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%()[]?!\/,-]+$/i', '', $_GET['query']);
            $results = Product::search(urldecode($query));
            foreach ($results as $result){
                $name = "product_name_lang_".$lang;
                $category = "catg_name_".$lang;
                $result->user_language_product_name = $result->$name;
                $result->user_language_category_name = $result->$category;
            }
            return response()->json($results);
        }
        catch(Exception $e){
            return 0;
        }
    }
}
