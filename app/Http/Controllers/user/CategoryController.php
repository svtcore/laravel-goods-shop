<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Classes\Categories;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    public $categories;

    /**
     * Load category list to navbar
     */
    public function __construct()
    {
        $this->categories = new Categories();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            if (!empty($this->categories->getById($id))){
                return view('user.categories.index')
                    ->with('category_products', $this->categories->getById($id)->paginate(12))
                    ->with('categories', $this->categories->getAll());
                }else abort(404);
        }
        catch(NotFoundHttpException $e){
            return abort(404);
        }
        catch(Exception $e){
            print($e);
        }


    }
}
