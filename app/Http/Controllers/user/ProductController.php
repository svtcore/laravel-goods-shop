<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Exception;
use App\Classes\Categories;
use App\Classes\Products;
use App\Http\Requests\user\products\SearchRequest;

class ProductController extends Controller
{
    public $categories;
    public $locale;

    public function __construct()
    {
        $this->categories = new Categories();
        $this->products = new Products();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.home')
                ->with('products_main', $this->products->getRandom(8))
                ->with('categories', $this->categories->getAll());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!empty($product = $this->products->getById($id)))
            return view('user.products.index')
                ->with('product_info', $product)
                ->with('categories', $this->categories->getAll());
        else abort(404);

    }

    public function search(SearchRequest $request){
        try{
            $validated = $request->validated();
            $lang = app()->getLocale();
            $results = $this->products->search($validated['query']);
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
