<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Orders;
use App\Models\PreOrders;
use App\Models\PreOrdersDetails;
use App\Models\Products;
use App\Models\ShoppingCard;
use FontLib\TrueType\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;
use GuzzleHttp\Client;
use App\User;
use Exception;

class OrdersController extends Controller
{
    private $user_id = 0;

    public function __construct()
    {
        if (\Auth::user()) {
            $this->user_id = \Auth::user()->id;
        }
    }

    public function create(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'product_id' => 'required',
                'status' => 'required',
                'date' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $orders = new Orders();
                $product = Products::find($request->input('product_id'));
                $orders->product_id = $request->input('product_id');
                $orders->buyer_id = $this->user_id;
                $orders->user_id = $product->user_id;
                $orders->status = $request->input('status');
                $orders->date = $request->input('date');

                $orders->save();

                return $this->respondWithSuccess($orders);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function createOrderFromZoguMall(Request $request)
    {
        try {
            DB::beginTransaction();
            $validation = Validator::make($request->all(), [
                'buyer_name' => 'required',
                'phone_number' => 'required',
                'address' => 'required'
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $product = $request->input('shopping_card');
                $user_buyer = User::find($this->user_id);

                if (is_array($product) && count($product) > 0) {
                    $dyqani = collect();
                    for ($i = 0; $i < sizeof($product); $i++) {
                        if (!$dyqani->contains($product[$i]['product']['user_id'])) {
                            $dyqani->push($product[$i]['product']['user_id']);
                        }
                    }
                    foreach ($dyqani as $d) {
                        $preorders = new PreOrders();
                        $preorders->buyer_name = $request->input('buyer_name');
                        $preorders->phone_number = $request->input('phone_number');
                        $preorders->address = $request->input('address');
                        $preorders->buyer_email = $user_buyer->email;
                        $preorders->buyer_id = $this->user_id;
                        $preorders->shop_id = $d;
                        //  $preorders->status = 0;
                        $preorders->city_id = $request->input('city_id');
                        $preorders->save();
                        $product_details_array = array();
                        foreach ($product as $p) {
                            if ($p['product']['user_id'] == $d) {
                                $preorder_details = new PreOrdersDetails();
                                $preorder_details->preorder_id = $preorders->id;
                                $quantity = $preorder_details->quantity = $p['quantity'];
                                $preorder_details->comment = $p['comment'];
                                $preorder_details->product_id = $p['product_id'];
                                $product_model = Products::find($p['product_id']);
                                $product_quantity = $p['product']['quantity'];
                                $subb = $product_model['quantity'] - $quantity;
                                if ($subb < 0) {
                                    return $this->respondWithError($p['id']);
                                }
                                $shopping = ShoppingCard::where('id', $p['id'])
                                    ->update(['status' => 1]);
                                $product_model->quantity = $subb;
                                $product_model->save();
                                $preorder_details->save();
                                $preorder_details->product = $product_model;
                                array_push($product_details_array, $preorder_details);
                            }
                        }
                        //dyqani
                        $user = User::where(['id' => $d, 'role' => 'shop'])->first();
                        $preorders->user = $user;
                        $preorders->product_details = $product_details_array;
                        // return $preorders;
                        $this->order_email($user->email, 'Porosi e krijuar', 'order', $preorders);
                    }
                    $user_data = User::find($this->user_id);
                    $this->order_email($user_data->email, 'Porosi e krijuar', 'order_client', $user_data);
                    //klientit
                    DB::commit();
                    return $this->respondWithSuccess('Success!');
                } else {
                    DB::rollBack();
                    return $this->respondWithError("Failed!");
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function assignStatusToOrders(Request $request)
    {
        try {
            DB::beginTransaction();
            $client = new Client();

            $response = $client->request('POST', 'https://posta.dy-po.com/api/order/create/zogu', [
                'form_params' => [
                    'company_id' => $request->input('company_id'),
                    'id' => $request->input('id'),
                    'buyer_name' => $request->input('buyer_name'),
                    'address' => $request->input('address'),
                    'phone_number' => $request->input('phone_number'),
                    'buyer_email' => $request->input('buyer_email'),
                    'buyer_id' => $request->input('buyer_id'),
                    'shop_id' => $request->input('shop_id'),
                    'city_id' => $request->input('city_id'),
                    'quantity' => $request->input('quantity'),
                    'preorders_details' => $request->input('preorders_details'),
                ]
            ]);
            $result = json_decode($response->getBody(), true);
            if ($result && $result['status'] == 'success') {

                $preorder = PreOrders::find($request->input('id'));
                $preorder->status = 1;
                $preorder->save();
                DB::commit();
            } else {
                DB::rollBack();
                return;
            }
            return $this->respondWithSuccess('success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getOrdersFromShopId()
    {
        try {
            $orders = PreOrders::with("preorders_details", "preorders_details.product")
                ->select("preorders.*")
                ->where(["shop_id" => $this->user_id, "status" => 0])
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->respondWithSuccess($orders);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function preOrdersBuyer()
    {
        try {
            $orders = PreOrders::with("preorders_details", "preorders_details.product")
                ->select("preorders.*")
                ->where(["buyer_id" => $this->user_id])
                ->orderBy('preorders.id', 'desc')
                ->get();
            return $this->respondWithSuccess($orders);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    //to be deleted?
    public function getOrdersFromAssignedStatus()
    {
        try {
            $orders = PreOrders::with("preorders_details")
                ->select("preorders.*")
                ->where("shop_id", $this->user_id)
                ->where("status", 1)
                ->get();
            return $this->respondWithSuccess($orders);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getAllOrdersByUserId($id)
    {
        try {
            $orders = Orders::where(['user_id' => $id])->get();
            return $this->respondWithSuccess($orders);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getById($id)
    {
        $result = Orders::find($id);
        return $this->respondWithSuccess($result);
    }

    public function remove($id)
    {
        try {
            $orders = Orders::find($id);
            if (!$orders) {
                return $this->respondWithError('Record not found!');
            }

            $orders->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->input('id');

            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'product_id' => 'required',
                'status' => 'required',
                'date' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $orders = Orders::where(['id' => $id])->first();

                if (!$orders) {
                    return $this->respondWithError('Record not found!');
                }

                $orders->product_id = $request->input('product_id');
                $orders->buyer_id = $request->input('buyer_id');
                $orders->user_id = $request->input('user_id');
                $orders->status = $request->input('status');
                $orders->date = $request->input('date');

                $orders->save();
                DB::commit();

                return $this->respondWithSuccess($orders);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getMessage(), 'exception');
        }
    }

    public function removePreOrders(Request $request)
    {
        try {
            $id = $request->input('id');
            $comment = $request->input('comment');
            $preOrder = PreOrders::where('id', $id)->where('status', '!=', 2)->first();
            if (!$preOrder) {
                return $this->respondWithError('Not found');
            }

            $preOrderDetails = PreOrdersDetails::where('preorder_id', $preOrder->id)->get();
            foreach ($preOrderDetails as $item) {
                $product = Products::find($item->product_id);
                $product->quantity = $product->quantity + $item->quantity;
                $product->save();
            }
            if ($comment) {
                $this->order_email($preOrder->buyer_email, 'Porosi u refuzua', 'order_refused', $comment);
            }
            $preOrder->status = 2;
            $preOrder->save();
            return $this->respondWithSuccess($preOrder);
        } catch (Exception $ex) {
            return $this->respondWithException($ex->getMessage());
        }
    }

    public function downloadPdf(){
      
        $data['title'] = 'Orders List';
        $data['orders'] =  Orders::get();
    
        $pdf = PDF::loadView('orders.list_orders', $data);
      
        return $pdf->download('orders.pdf');
       }

}
