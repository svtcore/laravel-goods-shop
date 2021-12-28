<?php

namespace App\Http\Controllers\admin;

use App\Classes\Categories;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\products\StoreRequest;
use App\Http\Requests\admin\products\UpdateRequest;
use App\Http\Requests\admin\products\SearchRequest;
use Exception;
use App\Classes\Products;

class ProductController extends Controller
{

    private $products;

    public function __construct()
    {
        $this->products = new Products();
        $this->categories = new Categories();
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if (count(($prod =  $this->products->getAll())->get()) > 0)
                return view('admin.products.index')->with('products', $prod->paginate(15));
            else return view('admin.products.index')->with('products', null);
        }
        catch(Exception $e){
            return 0;
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{    
            $local = app()->getLocale();
            return view('admin.products.create')->with('catg', $this->categories->get_local($local));
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\admin\products\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $filenames = $this->products->uploadfiles($validated, $request);
            $this->products->add($validated, $filenames);
            return redirect()->route('admin.products.index');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $local = app()->getLocale();
            if (!empty($product = $this->products->getById($id)))
                return view ('admin.products.edit')
                                ->with('product', $product)
                                ->with('catg', $this->categories->get_local($local));
            else return abort(404);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\admin\products\UpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $validated = $request->validated();
            $filenames = $this->products->uploadfiles($validated, $request);
            $this->products->update($validated, $filenames, $id);
            return redirect()->route('admin.products.index');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $this->products->delete($id);
            return redirect()->route('admin.products.index');
        }
        catch(Exception $e){
            return 0;
        }
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
