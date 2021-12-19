<?php


namespace App\Classes;

use App\Models\Order;
use App\Classes\Products;
use App\Http\Traits\ResultDataTrait;
use Exception;

class Orders
{
    use ResultDataTrait;
    /**
     * Getting full data of orders which were created by specific user
     * 
     * @param int $id
     * @return Collection
     * 
     */
    public function getByUserId(int $id): object
    {
        try {
            $orders = Order::where('f_user_id', $id)
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
            if ($this->check_result($orders)) return $orders;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Adding order data, user address and ordered products, calculate and update total price
     * 
     * @param array $request, int $id, object $order_array
     * @return int or bool
     * 
     */
    public function add(array $request, int $id, object $order_array): int|bool
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
            if (isset($order->order_id)) return $order->order_id;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update order data, user address and ordered products, calculate and update total price
     * 
     * @param int $id, object $request, int $manager
     * @return bool
     * 
     */
    public function update(int $id, object $request, ?int $manager): bool
    {
        try {
            $validation_rules = $this->validate_rules($id, $request);
            $validated = $request->validate($validation_rules);
            $prod_obj = new Products();
            $order_prod = new OrderProducts();
            foreach ($validated as $key => $value) {
                if (str_starts_with($key, 'product_count')) {
                    $apart = explode("_", $key);
                    $product_id = $apart[2];
                    if ($value != 0) {
                        $product_data = $prod_obj->getPrice($product_id);
                        $new_price = $product_data->product_price * $value;
                        $order_prod->updateCountPrice($product_id, $value, $new_price);
                    } elseif ($value == 0) {
                        $order_prod->deleteByProductId($product_id, $id);
                    }
                }
            }
            $sum = $order_prod->getSumByOrderId($id);
            if ($sum > 0) {
                $data = [
                    'order_fname' => $validated['f_name'],
                    'order_lname' => $validated['l_name'],
                    'order_phone' => $validated['phone'],
                    'f_pay_t_id' => $validated['payment'],
                    'order_full_price' => $sum,
                    'order_note' => $validated['note'],
                    'order_code' => $validated['code'],
                    'order_status' => $validated['status']
                ];
                Order::where('order_id', $id)->update($data);
                $data = [
                    'user_str_name' => $validated['street'],
                    'user_house_num' => $validated['house'],
                    'user_ent_num' => $validated['entrance'],
                    'user_apart_num' => $validated['appart'],
                    'user_code' => $validated['code']
                ];
                $result = Order::findOrFail($id)->user_addresses()->update($data);
                if ($result) return true;
                else return false;
            } else {
                $result = $this->updateStatus($id, "canceled", $manager);
                if ($result) return true;
                else return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting and convert cookies to array when get cart page
     * 
     * @param string $cookies
     * @return array
     * 
     */
    public function formatCookies(string $cookies): iterable
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
            $result = array($total, $order_data);
            if (count($result) > 0) return $result;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Generate validate rules data through method because some of orders have unknown id
     * so request validate form doesnt correct for this task
     * 
     * @param int $id
     * @return array
     * 
     */
    public function validate_rules(int $id): iterable
    {
        try {
            $product_obj = new OrderProducts();
            $validation_array = array();
            $data = $product_obj->getByOrderId($id);
            foreach ($data as $product)
                $validation_array["product_count_" . $product->f_product_id] = "required|numeric|min:0";
            $validation_array['f_name'] = 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50';
            $validation_array['l_name'] = 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50';
            $validation_array['phone'] = 'required|digits_between:10,25';
            $validation_array['street'] = 'required|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:2|max:100';
            $validation_array['house'] = 'required|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:1|max:5';
            $validation_array['appart'] = 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:1|max:5';
            $validation_array['entrance'] = 'nullable|digits_between:1,5';
            $validation_array['code'] = 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№,-]+$/i|min:1|max:10';
            $validation_array['payment'] = 'required|digits_between:1,100|min:1|max:3';
            $validation_array['note'] = 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%()?!:;=,-]+$/i|min:1|max:255';
            $validation_array['status'] = 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50';
            return $validation_array;
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * formation product name by user language
     * 
     * @param object $data
     * @return string
     * 
     */
    public function getCurrentProductLangName(object $data): string
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
            return "en";
        }
    }

    /**
     * Getting collection of orders by status
     * 
     * @param string $status
     * @return object or bool
     * 
     */
    public function getByStatus(string $status): object|bool
    {
        try {
            $orders = Order::where('order_status', $status)
                ->with([
                    'users',  'payment_types' => function ($q) {
                        $q->withTrashed();
                    }, 'user_addresses', 'order_products', 'order_products.products' => function ($q) {
                        $q->withTrashed();
                    }, 'order_products.products.names' => function ($q) {
                        $q->withTrashed();
                    }
                ])
                ->orderby('order_id', 'asc');
            if (isset($orders)) return $orders;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Getting all orders data
     * 
     * @param null
     * @return object
     * 
     */
    public function getAll(): ?object
    {
        try {
            $orders = Order::with([
                'users',  'payment_types' => function ($q) {
                    $q->withTrashed();
                }, 'user_addresses', 'order_products', 'order_products.products' => function ($q) {
                    $q->withTrashed();
                }, 'order_products.products.names' => function ($q) {
                    $q->withTrashed();
                }
            ])
                ->orderby('order_id', 'desc');
            if ($this->check_result($orders)) return $orders;
            else return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Update order status
     * 
     * @param int $id, string $status, int $manager
     * @return bool
     * 
     */
    public function updateStatus(int $id, string $status, ?int $manager): bool
    {
        try {
            $result = Order::where('order_id', $id)->update([
                'order_status' => $status,
                'f_manager_id' => $manager
            ]);
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting data of order by id
     * 
     * @param int $id
     * @return object or bool
     * 
     */
    public function getById(int $id): object|bool
    {
        try {
            $order = Order::where('order_id', $id)
                ->with([
                    'order_products',
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
            if (isset($order->order_id)) return $order;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting first 5 result of search query
     * 
     * @param string $query 
     * @return Collection
     * 
     */
    public function search(string $query): iterable
    {
        try {
            $orders = Order::select(
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
            if ($this->check_result($orders)) return $orders;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Getting count of orders by range of dates
     * 
     * @param string $status, string $from, string $to 
     * @return int
     * 
     */
    public function getDayCount(string $status, string $from, string $to): int
    {
        try {
            $result = Order::where('order_status', $status)->whereBetween('created_at', [$from, $to])->count();
            if ($result >= 0) return $result;
            else return 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Getting sum of orders by range of dates
     * 
     * @param string $from, string $to 
     * @return float
     * 
     */
    public function getMoneyData(string $from, string $to): float
    {
        try {
            $result = Order::where('order_status', "completed")->whereBetween('created_at', [$from, $to])->sum('order_full_price');
            if ($result >= 0) return $result;
            else return 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Delete order products and order by id
     * 
     * @param int $id
     * @return bool
     * 
     */
    public function delete(int $id): bool
    {
        try {
            $order = Order::findOrFail($id);
            $order->order_products()->delete();
            $result = $order->delete();
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
