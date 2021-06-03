<?php

namespace App\Http\Controllers\user;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class ProductController extends Controller
{
    public $categories;
    public $locale;

    public function __construct()
    {
        $this->categories = Category::All();
    }

    /**
     * Home page
     * Get 8 randoms products
     * return them to view
     */
    public function index()
    {
        return view('user.home')->with('products_main', Product::getRandomProducts(8))->with('categories', $this->categories);
    }

    /**
     * Get product by id and return data to view
     */
    public function show($id)
    {
        if (!empty($product = Product::getById($id)))
            return view('user.product')->with('product_info', $product)->with('categories', $this->categories);
        else abort(404);

    }

    public function search(Request $request){
        try{
            $validated = $request->validate([
                'query' => 'regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%?!:;,-]+$/i|min:1|max:255',
            ]);

            $lang = app()->getLocale();
            $results = Product::search($validated['query']);
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
