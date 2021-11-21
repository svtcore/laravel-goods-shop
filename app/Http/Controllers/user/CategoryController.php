<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Categories;

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
     * Get products by categorie id
     */
    public function show($id)
    {
        if (!empty($this->categories->getById($id))){
        return view('user.category')
            ->with('category_products', $this->categories->getById($id)->paginate(12))
            ->with('categories', $this->categories->getAll());
        }else abort(404);

    }
}
