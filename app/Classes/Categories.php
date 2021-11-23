<?php


namespace App\Classes;

use App\Models\Category;
use Exception;

class Categories
{
    /**
     * Getting all category records
     * Output: Collection
     */
    public function getAll()
    {
        return Category::all();
    }

    /**
     * Input: category identificator
     * Output: Collection of the categories
     */
    public function getById($id)
    {
        return Category::where('catg_id', $id)->with('products', 'products.names', 'products.images', 'products.descriptions');
    }

    /**
     * Input: filed name with lang tag
     * Output: Collection of the categories
     * Description: Return collection of categoris on the specific language
     */

    public function orderByLang($locale)
    {
        return Category::orderBy($locale)->get();
    }

    /**
     * Input: Validated request data
     * Output: None
     * Description: Formation data to array and create record in db
     */
    public function add($request)
    {
        $data = [
            'catg_name_en' => $request['category_name_en'],
            'catg_name_de' => $request['category_name_de'],
            'catg_name_uk' => $request['category_name_uk'],
            'catg_name_ru' => $request['category_name_ru']
        ];
        Category::create($data);
    }

    /**
     * Input: Validated request data
     * Output: None
     * Formation data to array and update record in db
     */
    public function update($request, $id)
    {
        $data = [
            'catg_name_en' => $request['category_name_en'],
            'catg_name_de' => $request['category_name_de'],
            'catg_name_uk' => $request['category_name_uk'],
            'catg_name_ru' => $request['category_name_ru']
        ];
        Category::findOrFail($id)->update($data);
    }

    /**
     * Input: category identificator
     * Output: None
     * Getting model category and category products then delete records
     */
    public function delete($id)
    {
        Category::findOrFail($id)->products()->delete();
        Category::findOrFail($id)->delete();
    }

    /**
     * Input: language tag
     * Output: categories array
     * Description: Getting all categories formation them to array only on user language
     * for dislpay in <select> tag
     */
    public function get_local($local)
    {
        $catg_array = array();
        $catg_name = "catg_name_" . $local;
        $catg = Category::all();
        foreach ($catg as $cat) {
            $catg_array[$cat->catg_id] = $cat->$catg_name;
        }
        return $catg_array;
    }
}
