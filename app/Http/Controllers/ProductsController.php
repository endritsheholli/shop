<?php

namespace App\Http\Controllers;

use App\Services\DocumentManagement;
use App\Models\LikedProducts;
use App\Models\ProductParameter;
use App\Models\Products;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use \Exception;

class ProductsController extends Controller
{
    private $user_id = 0;
    private $dms;

    public function __construct(DocumentManagement $dms)
    {
        if (\Auth::user()) {
            $this->user_id = \Auth::user()->id;
        }
        $this->dms = $dms;
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $validation = Validator::make($request->all(), [
                'category_id' => 'required',
                'title' => 'required|max:255',
                'price' => 'required',
                'discount' => 'min:1,max:100',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {

                $product = new Products();

                $product->user_id = $this->user_id;
                $product->title = $request->input('title');
                $product->slug = $this->slugify($request->input('title'), 'products');
                $product->description = $request->input('description');
                $product->price = $request->input('price');
                $product->weight = $request->input('weight');
                $product->quantity = $request->input('quantity');
                $product->previous_price = $request->input('previous_price');
                $product->status = $request->input('status');
                $product->category_id = $request->input('category_id');
                $product->subcategory_id = $request->input('subcategory');
                $product->posta_free = $request->input('posta_free') == 'true' ? 1 : 0;
                $product->discount = $request->input('discount');

                $product->save();
                $record_id = $product->id;

                $path_url = array();
                $index = 0;
                for ($i = 0; $i < $request->input('countImages'); $i++) {
                    $path = $this->dms->storeFile($request->file('images' . $i), 'products', $record_id);
                    $path_url[$index] = $path;
                    $index++;
                }
                $product->image_urls = $path_url;
                $product->save();

                $parameters = json_decode($request->input(['parameters']));
                if ($parameters && is_array($parameters)) {
                    foreach ($parameters as $p) {
                        $parameters = new ProductParameter();
                        $parameters->product_id = $product->id;
                        $parameters->parameter_id = $p;
                        $parameters->save();
                    }
                }

                DB::commit();
                return $this->respondWithSuccess($product);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->input('id');

            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'category_id' => 'required',
                'title' => 'required|max:255',
                'price' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $product = Products::where(['id' => $id])->first();

                if (!$product) {
                    return $this->respondWithError('Record not found!');
                }

                $this->dms->removeMultipleFile('products', $product->id, $product->image_urls);

                $this->dms->removeMultipleFromS3($product->image_urls);

                $product->title = $request->input('title');
                $product->description = $request->input('description');
                $product->price = $request->input('price');
                if ($request->input('previous_price') && $request->input('previous_price') != 'null') {
                    $product->previous_price = $request->input('previous_price');
                }
                $product->status = $request->input('status');
                $product->weight = $request->input('weight');
                $product->quantity = $request->input('quantity');
                $product->category_id = $request->input('category_id');
                $product->subcategory_id = $request->input('subcategory');
                $product->posta_free = $request->input('posta_free') == 'true' ? 1 : 0;

                $record_id = $product->id;

                $path_url = array();
                $index = 0;
                for ($i = 0; $i < $request->input('countImages'); $i++) {
                    $path = $this->dms->storeFile($request->file('images' . $i), 'products', $record_id);
                    $path_url[$index] = $path;
                    $index++;
                }

                $product->image_urls = $path_url;
                $product->save();

                ProductParameter::where('product_id', $product->id)->delete();
                for ($i = 0; $i < $request->input('countParameters'); $i++) {
                    $parameters = new ProductParameter();
                    $parameters->product_id = $product->id;
                    $parameters->parameter_id = $request->input('parameters' . $i);
                    $parameters->save();
                }
                DB::commit();

                return $this->respondWithSuccess($product);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getMessage(), 'exception');
        }
    }
    public function remove($id)
    {
        try {
            $product = Products::find($id);
            if (!$product) {
                return $this->respondWithError('Record not found!');
            }
            $this->dms->removeMultipleFile('products', $product->id, $product->image_urls);

            ProductParameter::where('product_id', $product->id)->delete();
            $product->delete();
            $this->dms->removeMultipleFromS3($product->image_urls);

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getById($id)
    {
        $customResponse = DB::table('products')
            ->join('liked_products', 'liked_products.product_id', 'products.id')
            ->join('users', 'users.id', 'liked_products.user_id')
            ->join('users as u', 'u.id', 'products.user_id')
            ->where("products.id", $id)
            ->where('u.active', 1)
            ->where("products.quantity", '>', 0)
            ->select('products.*', 'liked_products.product_like', 'users.shop_name')
            ->get();
        if ($customResponse->isEmpty()) {
            $customResponse = Products::select('products.*', 'users.shop_name')->join('users', 'users.id', 'products.user_id')->where("products.quantity", '>', 0)->find($id);
        }
        return $this->respondWithSuccess($customResponse);
    }

    public function getBySlug($slug)
    {
        // $customResponse = DB::table('products')
        //     ->join('liked_products', 'liked_products.product_id', 'products.id')
        //     ->join('users', 'users.id', 'liked_products.user_id')
        //     ->where('products.slug', $slug)
        //     ->select('products.*', 'liked_products.product_like', 'users.shop_name')
        //     ->first();
        // if (!$customResponse) {
        $customResponse = Products::select('products.*', 'users.shop_name', 'users.img_path')
            ->join('users', 'users.id', 'products.user_id')
            ->where('slug', $slug)
            ->where('users.active', 1)
            ->where("products.quantity", '>', 0)
            ->first();
        // }
        return $this->respondWithSuccess($customResponse);
    }
    public function getAllProducts()
    {
        try {
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->where('users.active', 1)
                ->where("products.quantity", '>', 0)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function insertImageForProduct($id)
    {
        $product = Products::find($id);

        $record_id = $product->id;

        $files = Input::file('file');
        $path_url = $product->image_urls;
        foreach ($files as $one) {
            $path = $this->dms->storeFile($one, 'products', $record_id);
            $path_url = $path_url . $path . ';';
        }
        $product->image_urls = $path_url;
        $product->save();
        return $this->respondWithSuccess($product);
    }
    public function deleteImageForProduct(Request $request)
    {
        $product_id = $request->input('product_id');
        $image_name = $request->input('image_name');

        $product = Products::find($product_id);
        $path_url = $product->image_urls;
        $parts = explode(';', $path_url);
        $path_url = '';
        foreach ($parts as $part) {
            if (strpos($part, $image_name) !== false) {
                $url = 'public/' . $part;
                if (Storage::exists($url)) {
                    Storage::delete($url);
                }
            } else {
                $path_url = $path_url . $part . ';';
            }
        }
        $product->image_urls = $path_url;
        $product->save();
        return $this->respondWithSuccess($product);
    }
    public function getAllProductsWithPagination(Request $request)
    {
        try {
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->where('users.active', 1)
                ->where("products.quantity", '>', 0);

            $title = $request->input('title');
            $price = $request->input('price');
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $status = $request->input('status');
            $shop = $request->input('shop');

            if ($title) {
                $result = $result->where('products.title', 'like', "%" . $title . "%");
            }
            if ($price) {
                $result = $result->where('products.price', 'like', "%" . $price . "%");
            }
            if ($startDate) {
                $result = $result->whereDate('products.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $result = $result->whereDate('products.created_at', '<=', $endDate);
            }
            if ($status && $status > 0) {
                if ($status == 2) {

                    $result = $result->where('products.isImportant', 0);
                }
                if ($status == 1) {
                    $result = $result->where('products.isImportant', 1);
                }
            }
            if ($shop) {
                $result = $result->whereRaw("JSON_EXTRACT(users.shop_name, '$.name') like '%" . $shop . "%'");

                // ->where('users.shop_name', 'like', '%' . $shop . "%");
            }

            $result = $result->paginate(15);
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function likedProducts()
    {
        $liked_product_ids = LikedProducts::where('user_id', $this->user_id)->pluck('product_id')->toArray();
        $liked_products = Products::find($liked_product_ids);

        return $this->respondWithSuccess($liked_products);
    }
    public function getProductsByCategoryId($id)
    {
        try {
            $result = Products::select('products.*', 'users.*', 'products.id as product_id')
                ->join('users', 'users.id', 'products.user_id')
                ->where('users.active', 1)
                ->where(['category_id' => $id])
                ->where("products.quantity", '>', 0)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getProductsByCategorySlug($id)
    {
        try {
            $result = Products::select('products.*', 'users.*', 'products.id as product_id')
                ->join('users', 'users.id', 'products.user_id')
                ->join('categories', 'categories.id', 'products.category_id')
                ->where('users.active', 1)
                ->where(['categories.slug' => $id])
                ->where("products.quantity", '>', 0)
                ->paginate(1);
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getProductsByUserId($id)
    {
        try {
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->where(['user_id' => $id])
                ->where('users.active', 1)
                ->where("products.quantity", '>', 0)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getMyProducts()
    {
        try {
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->where(['user_id' => $this->user_id])
                ->where("products.quantity", '>', 0)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getMyProductsPaginate(Request $request)
    {

        try {
            $result = Products::select(DB::raw("
            products.id, products.user_id,products.category_id,products.title,products.likes,
            products.price,products.slug,products.image_urls,users.shop_name,count(liked_products.product_id) as likedProduct"))
                ->join('users', 'users.id', 'products.user_id')
                ->leftjoin('liked_products', 'liked_products.product_id', 'products.id')
                ->where(['products.user_id' => $this->user_id])
                // ->where("products.quantity", '>', 0)
                ->where('users.active', 1)
                ->groupBy(
                    'products.id',
                    'products.user_id',
                    'products.category_id',
                    'products.title',
                    'products.price',
                    'products.likes',
                    'products.slug',
                    'users.shop_name',
                    'products.image_urls'
                );
            $title = $request->input('title');
            $price = $request->input('price');
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $likes = $request->input('likes');
            $status = $request->input('status');

            if ($title) {
                $result = $result->where('products.title', 'like', "%" . $title . "%");
            }
            if ($price) {
                $result = $result->where('products.price', 'like', "%" . $price . "%");
            }
            if ($startDate) {
                $result = $result->whereDate('products.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $result = $result->whereDate('products.created_at', '<=', $endDate);
            }
            if ($likes) {
                $result = $result->where('liked_products.product_id', 'like', "%" . $likes . "%");
            }
            if ($status && $status > 0) {
                if ($status == 2) {

                    $result = $result->where('products.isImportant', 0);
                }
                if ($status == 1) {
                    $result = $result->where('products.isImportant', 1);
                }
            }

            $result = $result->paginate(12);
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function getProductsByShopName(Request $request)
    {
        try {
            $shop_name = $request->input('shop_name');
            $title = $request->input('title');
            $order_status = $request->input('order_status');
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                // ->where(['shop_name' => $shop_name])
                ->whereRaw("JSON_EXTRACT(shop_name, '$.slug') = '" . $shop_name . "'")
                ->where('users.active', 1)
                ->where("products.quantity", '>', 0);

            if ($title && strlen($title) > 0) {
                $result = $result->where('products.title', 'like', '%' . $title . '%');
            }
            if ($order_status > 0) {
                //Cmimi me i lart
                if ($order_status == 1) {
                    $result = $result->orderBy('price', 'desc');
                }
                //Cmimi me i ulet
                if ($order_status == 2) {
                    $result = $result->orderBy('price', 'asc');
                }
            }

            //Data me e vjeter
            if ($order_status == 3) {
                $result = $result->orderBy('created_at', 'asc');
            } else {
                $result = $result->orderBy('created_at', 'desc');
            }
            $result = $result->paginate(18);
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function searchProduct($title, $category_id)
    {
        try {
            $title = $title ? $title : "";
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->where('users.active', 1)
                ->where('title', 'like', '%' . $title . '%')
                ->where("products.quantity", '>', 0);
            if ($category_id && $category_id > 0) {
                $result = $result->where('category_id', $category_id);
            }
            return $this->respondWithSuccess($result->get());
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function searchProductPaginate(Request $request)
    {
        try {
            $title = $request->input('title');
            $category_id = $request->input('category_id');
            $title = $title ? $title : "";
            $from_price = $request->input('from_price');
            $to_price = $request->input('to_price');
            $subcategory = $request->input('subcategory');
            $order_status = $request->input('order_status');
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->leftJoin('product_parameter as pp', 'products.id', 'pp.product_id')
                ->where('users.active', 1)
                ->where('title', 'like', '%' . $title . '%')
                ->where("products.quantity", '>', 0);

            if ($category_id && $category_id > 0) {
                $result = $result->where('category_id', $category_id);
            }

            //to be shown only if the user selects one category
            // if(Products::where('category_id', $category_id)->exists()){
            $parameter_id = $request->input('parameter_id');
            if ($parameter_id && is_array($parameter_id) && count($parameter_id) > 0) {
                $result = $result->where('pp.parameter_id', $parameter_id);
            }
            //}

            if ($subcategory && is_array($subcategory) && count($subcategory) > 0) {
                $result = $result->whereIn('subcategory_id', $subcategory);
            }

            $max_price = $result->max('price');

            if ($from_price && $from_price > 0) {
                $result = $result->where('price', '>=', $from_price);
            }
            if ($to_price && $to_price > 0) {
                $result = $result->where('price', '<=', $to_price);
            }
            if ($order_status && $order_status > 0) {
                //Cmimi me i lart
                if ($order_status == 1) {
                    $result = $result->orderBy('price', 'desc');
                }

                //Cmimi me i ulet
                if ($order_status == 2) {
                    $result = $result->orderBy('price', 'asc');
                }
            }

            //Data me e vjeter
            if ($order_status == 3) {
                $result = $result->orderBy('created_at', 'asc');
            } else if ($order_status == 5) {
                $result = $result->inRandomOrder();
            } else {
                $result = $result->orderBy('created_at', 'desc');
            }
            $object['result'] = $result->paginate(18);
            $object['max_price'] = $max_price;

            return $this->respondWithSuccess($object);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function getImportantProducts()
    {
        try {
            $result = Products::select('products.*', 'categories.name as cat_name', 'categories.slug as cat_slug', 'users.shop_name', 'categories.id as cat_id')
                ->join('categories', 'categories.id', 'products.category_id')
                ->join('users', 'users.id', 'products.user_id')
                ->where(['products.isImportant' => 1])
                ->where('users.active', 1)
                ->where("products.quantity", '>', 0)
                ->orderBy('products.updated_at', 'desc')
                ->take(5)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function createOrRemoveImportantProducts($id, $status)
    {
        try {
            $result = Products::find($id);
            if ($result) {
                $result->isImportant = $status;
                $result->save();
                return $this->respondWithSuccess($result);
            } else {
                return $this->respondWithError("This product not found");
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getLastProducts()
    {
        try {
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->orderBy('products.created_at', 'desc')
                ->where('users.active', 1)
                ->where("products.quantity", '>', 0)
                ->take(10)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getRelatedProducts($cat_id, $id)
    {
        try {
            $result = Products::select('products.*', 'users.shop_name')
                ->join('users', 'users.id', 'products.user_id')
                ->where('users.active', 1)
                ->where('products.category_id', $cat_id)
                ->where('products.id', '!=', $id)
                ->where("products.quantity", '>', 0)
                ->orderBy('products.created_at', 'desc')
                ->take(4)
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function getProductByIdShop($id)
    {
        try {
            $result = Products::find($id);
            $parameters = ProductParameter::where('product_id', $id)->pluck('parameter_id');
            $result->parameters  = $parameters->toJson();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function soldProduct($id)
    {
        try {
            $result = Products::find($id);
            if ($result) {
                $result->status = 2;
                $result->save();
            }
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function chaneImportant(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',
            ]);
            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            }
            $result = Products::find($request->input('id'));
            if (!$result) {
                return $this->respondWithError('Record not found!');
            } else {
                $result->isImportant = $request->input('status');
                $result->save();
            }
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function getProductsByStatus()
    {
        try {
            $status = Products::select("products.isImportant")->get();
            return $this->respondWithSuccess($status);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function getPopularProducts()
    {
        try {

            $result = DB::table("products")
            ->select("products.*")
            ->where("products.quantity", '>', 0)
            ->groupBy("products.likes")
            ->take(5)
            ->get();
            
                return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
      
    }

    public function getBestSellings()
    {
        try {
        
        $result = DB::table("products")
        ->select("products.*", "orders.product_id", DB::raw("COUNT(orders.product_id) as count_click"))
        ->where("products.quantity", '>', 0)
        ->join("orders","orders.product_id","=","products.id")
        ->groupBy("products.id")
        ->take(5)
	    ->get();
           
           echo $result;
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
      
    }

    public function getProductsWithDiscount()
    {
        try {
            $products = Products::all()->sortByDesc('created_at')->sortByDesc('discount')->take(5)->get();
            return $this->respondWithSuccess($products);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function getBestSellerProducts(){
        try{
           $products = Products::leftJoin('orders', 'product_id', '=', 'orders.product_id')
            ->select('products.id', 'products.title', DB::raw('count(products.id) as sales_count'))
            ->groupBy('products.id')
            ->orderBy('sales_count', 'desc')
            ->get();
            
            return $this->respondWithSuccess($products);

        } catch(Exception $ex){
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function search(Request $request , $value)
	{
		Products::query()
                ->where('title', 'LIKE', "%{$value}%") 
                ->orderBy('likes', 'desc')
                ->orderBy('price', 'asc')
				->take(); //paginate ne design kqyr
    }





}
