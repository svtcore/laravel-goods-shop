<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public $categories;

    /**
     * Load category list to navbar
     */
    public function __construct()
    {
        $this->categories = Category::All();
    }
    
    /**
     * Get products by categorie id
     */
    public function show($id)
    {
        if (!empty($category = Category::getById($id))){
            return view('user.category')
            ->with('category_products', $category->paginate(12))
            ->with('categories', $this->categories);
        }else return abort(404);

    }
}
