<?php


namespace App\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\Category;
use Exception;

class Categories
{
    use ResultDataTrait;
    /**
     * Getting all category records
     * Output: Collection
     */
    public function getAll(): iterable
    {
        try {
            $categories = Category::all();
            if ($this->check_result($categories)) return $categories;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Input: category identificator
     * Output: object of the categories
     */
    public function getById(int $id): object|bool
    {
        try {
            $category = Category::where('catg_id', $id)->with([
                'products',
                'products.names',
                'products.images',
                'products.descriptions'
            ]);
            if (isset($category)) return $category;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: filed name with lang tag
     * Output: Collection of the categories
     * Description: Return collection of categoris on the specific language
     */

    public function orderByLang(string $locale): iterable
    {
        try {
            $categories = Category::orderBy($locale)->get();
            if ($this->check_result($categories)) return $categories;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Input: Validated request data
     * Output: int or bool
     * Description: Formation data to array and create record in db
     */
    public function add(array $request): int|bool
    {
        try {
            $data = [
                'catg_name_en' => $request['category_name_en'],
                'catg_name_de' => $request['category_name_de'],
                'catg_name_uk' => $request['category_name_uk'],
                'catg_name_ru' => $request['category_name_ru']
            ];
            $category = Category::create($data);
            if (isset($category->catg_id)) return $category->catg_id;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: Validated request data
     * Output: bool
     * Formation data to array and update record in db
     */
    public function update(array $request, int $id): bool
    {
        try {
            $data = [
                'catg_name_en' => $request['category_name_en'],
                'catg_name_de' => $request['category_name_de'],
                'catg_name_uk' => $request['category_name_uk'],
                'catg_name_ru' => $request['category_name_ru']
            ];
            $result = Category::findOrFail($id)->update($data);
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: category identificator
     * Output: None
     * Getting model category and category products then delete records
     */
    public function delete(int $id): bool
    {
        try {
            Category::findOrFail($id)->products()->delete();
            $result = Category::findOrFail($id)->delete();
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: language tag
     * Output: categories array
     * Description: Getting all categories formation them to array only on user language
     * for dislpay in <select> tag
     */
    public function get_local(string $local): array
    {
        try {
            $catg_array = array();
            $catg_name = "catg_name_" . $local;
            $catg = Category::all();
            foreach ($catg as $cat) {
                $catg_array[$cat->catg_id] = $cat->$catg_name;
            }
            return $catg_array;
        } catch (Exception $e) {
            return array();
        }
    }
}
