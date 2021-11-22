<?php

namespace App\Http\Controllers\admin;

use App\Classes\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\admin\categories\StoreRequest;
use App\Http\Requests\admin\categories\UpdateRequest;

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
        $locale = app()->getLocale();
        return view('admin.categories')
            ->with('categories', $this->categories->orderByLang('catg_name_' . $locale));
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
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        if ($this->categories->add($validated) != 0)
            return redirect()->route('admin.categories');
        else
            return redirect()->back()->withErrors(['message' => 'Error while adding category']);
    }

    /**
     * Return view edit category page and variable with data
     */
    public function edit($id)
    {
        $catg = $this->categories->getById($id)->first();
        if (!empty($catg)) {
            return view('admin.category_edit')->with('category', $catg);
        } else return abort(404);
    }

    /**
     * Validate data through Requests\CategoryRequest and update data
     */
    public function update(UpdateRequest $request, $id)
    {
        $validated = $request->validated();
        if ($this->categories->update($validated, $id) != 0)
            return redirect()->route('admin.categories');
        else
            return redirect()->back()->withErrors(['message' => 'Error while update category']);
    }

    /**
     * Getting model category and delete record
     */
    public function destroy($id)
    {
        $this->categories->delete($id);
        return redirect()->route('admin.categories');
    }
}
