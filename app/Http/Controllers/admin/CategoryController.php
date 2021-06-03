<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Get locale and sort categories by current language
     */
    public function index()
    {
        $locale = app()->getLocale();
        return view('admin.categories')->with('categories', Category::orderBy('catg_name_'.$locale)->get());
    }

    /**
     * Return view adding category page
     */
    public function create()
    {
        return view('admin.category_new');
    }

    /**
     * Validate request data by Request\CategoryRequest
     * run function to formate validate data
     * Returns redirect to categories page or back page with error
     */
    public function store(CategoryRequest $request)
    {
       if ($this->addCategory($request) != 0) return redirect()->route('admin.categories');
       else return redirect()->back()->withErrors(['message' => 'Error while adding category']);
    }

    /**
     * Return view edit category page and variable with data
     */
    public function edit($id)
    {
        $catg = Category::where('catg_id', $id)->first();
        if (!empty($catg)){
            return view('admin.category_edit')->with('category', $catg);
        }else return abort(404);
    }

    /**
     * Validate data through Requests\CategoryRequest and update data
     */
    public function update(CategoryRequest $request, $id)
    {
       if ($this->updateCategory($request, $id) != 0) 
            return redirect()->route('admin.categories');
       else
            return redirect()->back()->withErrors(['message' => 'Error while update category']);

    }
    /**
     * Getting model category and delete record
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->products()->delete();
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories');
    }


    /**
     * Formation data to array and create record in db
     * return 1 if success and 0 if fail
     */
    public function addCategory($request){
        try{
            $data = [
                'catg_name_en' => $request->category_name_en,
                'catg_name_de' => $request->category_name_de,
                'catg_name_uk' => $request->category_name_uk,
                'catg_name_ru' => $request->category_name_ru
            ];
            Category::create($data);
            return 1;
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Formation data to array and update record in db
     * return 1 if success and 0 if fail
     */
    public function updateCategory($request, $id){
        try{
            $data = [
                'catg_name_en' => $request->category_name_en,
                'catg_name_de' => $request->category_name_de,
                'catg_name_uk' => $request->category_name_uk,
                'catg_name_ru' => $request->category_name_ru
            ];
            Category::findOrFail($id)->update($data);
            return 1;
        }
        catch(Exception $e){
            return 0;
        }
    }
}
