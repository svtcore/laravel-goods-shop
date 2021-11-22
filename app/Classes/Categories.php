<?php


namespace App\Classes;
use App\Models\Category;
use Exception;

class Categories{

    public function getAll(){
        return Category::all();
    }

    public function getById($id){
        return Category::where('catg_id', $id)->with('products','products.names', 'products.images', 'products.descriptions');
    }

    public function orderByLang($locale){
        return Category::orderBy($locale)->get();
    }

    /**
     * Formation data to array and create record in db
     * return 1 if success and 0 if fail
     */
    public function add($request){
        try{
            $data = [
                'catg_name_en' => $request['category_name_en'],
                'catg_name_de' => $request['category_name_de'],
                'catg_name_uk' => $request['category_name_uk'],
                'catg_name_ru' => $request['category_name_ru']
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
    public function update($request, $id)
    {
        try {
            $data = [
                'catg_name_en' => $request['category_name_en'],
                'catg_name_de' => $request['category_name_de'],
                'catg_name_uk' => $request['category_name_uk'],
                'catg_name_ru' => $request['category_name_ru']
            ];
            Category::find($id)->update($data);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Getting model category and delete record
     */
    public function delete($id)
    {
        Category::findOrFail($id)->products()->delete();
        Category::findOrFail($id)->delete();
    }
}
