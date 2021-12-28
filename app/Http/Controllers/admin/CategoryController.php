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
     * Display a listing of the resource.
     * Get locale and sort categories by current language
     *
     * @return \Illuminate\Http\Response
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\admin\categories\StoreRequest  $request
     * @return \Illuminate\Http\Response
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\admin\categories\UpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
