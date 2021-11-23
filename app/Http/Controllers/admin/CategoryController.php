<?php

namespace App\Http\Controllers\admin;

use App\Classes\Categories;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\categories\StoreRequest;
use App\Http\Requests\admin\categories\UpdateRequest;
use Exception;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->categories = new Categories();
        $this->middleware('auth:admin');
    }

    /**
     * Get locale and sort categories by current language
     */
    public function index()
    {
        try{
            $locale = app()->getLocale();
            return view('admin.categories.index')
                ->with('categories', $this->categories->orderByLang('catg_name_' . $locale));
        }
        catch(Exception $e){
            return 0;
        }

    }

    /**
     * Return view adding category page
     */
    public function create()
    {
        try{
            return view('admin.categories.create');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Validate request data by StoreRequest
     * run function to formate validate data
     * Returns redirect to categories page or back page with error
     */
    public function store(StoreRequest $request)
    {
        try{
            $validated = $request->validated();
            if ($this->categories->add($validated) != 0)
                return redirect()->route('admin.categories.index');
            else
                return redirect()->back()->withErrors(['message' => 'Error while adding category']);
        }
        catch(Exception $e){
            return 0;
        }

    }

    /**
     * Return view edit category page and variable with data
     */
    public function edit($id)
    {
        try{
            if (!empty($catg = $this->categories->getById($id)->first())) {
                return view('admin.categories.edit')->with('category', $catg);
            } else return abort(404);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Validate data through UpdateRequest and update data
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $validated = $request->validated();
            if ($this->categories->update($validated, $id) != 0)
                return redirect()->route('admin.categories.index');
            else
                return redirect()->back()->withErrors(['message' => 'Error while update category']);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Getting model category and delete record
     */
    public function destroy($id)
    {
        try{
            $this->categories->delete($id);
            return redirect()->route('admin.categories.index');
        }
        catch(Exception $e){
            return 0;
        }
    }
}
