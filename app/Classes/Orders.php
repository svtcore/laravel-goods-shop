<?php


namespace App\Classes;

use App\Models\Order;
use App\Classes\Products;
use App\Models\Product;
use Carbon\Carbon;
use DB;
use Exception;

class Orders
{
    public function getByUserId($id)
    {
        return Order::where('f_user_id', $id)
            ->with([
                'payment_types' => function ($q) {
                    $q->withTrashed();
                },
                'order_products' => function ($q) {
                    $q->withTrashed();
                }, 'user_addresses', 'order_products.products' => function ($q) {
                    $q->withTrashed();
                }, 'order_products.products.names' => function ($q) {
                    $q->withTrashed();
                }, 'order_products.products.descriptions' => function ($q) {
                    $q->withTrashed();
                }, 'order_products.products.categories' => function ($q) {
                    $q->withTrashed();
                }
            ])
            ->orderby('order_id', 'desc');
    }

    /**
     * Adding order data to db
     * with function to add data to order_products table
     */
    public function add($request, $id, $order_array)
    {
        try {
            $order_data = [
                'f_user_id' => $id,
                'f_pay_t_id' => $request['payment'],
                'order_fname' => $request['f_name'],
                'order_lname' => $request['l_name'],
                'order_phone' => $request['phone'],
                'order_note' => $request['note'],
                'order_code' => $request['code'],
                'order_status' => 'created'
            ];
            $order = Order::create($order_data);
            $order->user_addresses()->create([
                'user_str_name' => $request['street'],
                'user_house_num' => $request['house'],
                'user_ent_num' => $request['entrance'],
                'user_apart_num' => $request['appart'],
                'user_code' => $request['code'],
            ]);
            $total_price = 0;
            $prod_obj = new Products();
            for ($i = 0; $i < count($order_array->items); $i++) {
                $product_price = ((($prod_obj->getPrice($order_array->items[$i]->id))->product_price) * $order_array->items[$i]->count);
                $total_price = $total_price + $product_price;
                $order->order_products()->create([
                    'f_product_id' => $order_array->items[$i]->id,
                    'order_p_price' => $product_price,
                    'order_p_count' => $order_array->items[$i]->count,
                ]);
            }
            $order->update(['order_full_price' => $total_price]);
            return $order->order_id;
        } catch (Exception $e) {
            return redirect()->route('user.cart');
        }
    }

    /**
     * Formation cookies to array when get cart page
     */
    public function formatCookies($cookies)
    {
        try {
            $order_array = json_decode($cookies);
            $order_data = array();
            $total_products_price = 0;
            for ($i = 0; $i < count($order_array->items); $i++) {
                $id = preg_replace('/[^0-9]+/', '', $order_array->items[$i]->id);
                $count = preg_replace('/[^0-9]+/', '', $order_array->items[$i]->count);
                $prod_obj = new Products();
                $data = $prod_obj->getById($id);
                if (!empty($data)) {
                    $order_data[$i]['id'] = $data->product_id;
                    $order_data[$i]['name'] = $this->getCurrentProductLangName($data);
                    $order_data[$i]['price'] = $data->product_price;
                    $order_data[$i]['image'] = $data->images[0]['product_image_name'];
                    $order_data[$i]['count'] = $count;
                    $order_data[$i]['one_price'] = $data->product_price;
                    $order_data[$i]['total_product_price'] = $data->product_price * $count;
                    $total_products_price += $order_data[$i]['total_product_price'];
                } else return null;
            }
            $total = $total_products_price;
            return array($total, $order_data);
        } catch (Exception $e) {
            return redirect()->route('user.cart');
        }
    }

    /**
     * Subfunction to formation product name by user language
     */
    public function getCurrentProductLangName($data)
    {
        try {
            $lang = app()->getLocale();
            if ($lang == "en") $name = $data->names->product_name_lang_en;
            elseif ($lang == "de") $name = $data->names->product_name_lang_de;
            elseif ($lang == "uk") $name = $data->names->product_name_lang_uk;
            elseif ($lang == "ru") $name = $data->names->product_name_lang_ru;
            else $name = $data->product_name_lang_en;
            return $name;
        } catch (Exception $e) {
            return 0;
        }
    }


    public static function updatePrice($id, $data)
    {
        return Order::where('order_id', $id)->update(['order_full_price' => $data]);
    }

    public static function getOrdersByStatus($status)
    {
        return Order::where('order_status', $status)
            ->with(['users',  'payment_types' => function ($q) {
                $q->withTrashed();
            }, 'user_addresses', 'order_products' => function ($q) {
                $q->withTrashed();
            }, 'order_products.products' => function ($q) {
                $q->withTrashed();
            }, 'order_products.products.names' => function ($q) {
                $q->withTrashed();
            }])
            ->orderby('order_id', 'asc');
    }

    public static function updateStatus($id, $status, $manager)
    {
        return Order::where('order_id', $id)->update(
            [
                'order_status' => $status,
                'f_manager_id' => $manager
            ]
        );
    }

    public static function getById($id)
    {
        return Order::where('order_id', $id)
            ->with([
                'order_products' => function ($q) {
                    $q->withTrashed();
                },
                'user_addresses',
                'order_products.products' => function ($q) {
                    $q->withTrashed();
                },
                'order_products.products.names' => function ($q) {
                    $q->withTrashed();
                },
                'order_products.products.descriptions' => function ($q) {
                    $q->withTrashed();
                },
                'order_products.products.categories' => function ($q) {
                    $q->withTrashed();
                },
                'payment_types' => function ($q) {
                    $q->withTrashed();
                }
            ])
            ->orderby('order_id', 'desc')->withTrashed()->first();
    }

    public static function search($query)
    {
        return Order::select(
            'orders.order_id',
            'orders.order_phone',
            'orders.created_at',
            'user_addresses.user_str_name',
            'user_addresses.user_house_num',
            'user_addresses.user_apart_num'
        )
            ->join('user_addresses', 'f_order_id', '=', 'order_id')
            ->join('users', 'f_user_id', '=', 'user_id')
            ->where('order_id', 'LIKE', $query . "%")
            ->Orwhere('order_phone', 'LIKE', "%" . $query . "%")
            ->Orwhere('order_fname', 'LIKE', "%" . $query . "%")
            ->Orwhere('order_lname', 'LIKE', "%" . $query . "%")
            ->Orwhere('user_phone', 'LIKE', "%" . $query . "%")
            ->Orwhere('user_str_name', 'LIKE', "%" . $query . "%")
            ->orderby('order_id', 'desc')->distinct('order_id')->limit(5)
            ->withTrashed()->get();
    }

    public static function getAllOrdersData()
    {
        return Order::with([
            'users', 'payment_types' => function ($q) {
                $q->withTrashed();
            }, 'user_addresses', 'order_products', 'order_products.products',
            'order_products.products.names', 'order_products.products.images', 'order_products.products.descriptions'
        ])
            ->withTrashed()->orderby('order_id', 'desc');
    }

    public static function getDayCount($status, $from, $to)
    {
        return Order::where('order_status', $status)->whereBetween('created_at', [$from, $to])->count();
    }

    public static function getMoneyData($from, $to)
    {
        return Order::where('order_status', "completed")->whereBetween('created_at', [$from, $to])->sum('order_full_price');
    }
}
