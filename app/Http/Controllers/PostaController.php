<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class PostaController extends Controller
{
    private $user_id = 0;

    public function __construct()
    {

        if (\Auth::user()) {
            $this->user_id = \Auth::user()->id;
        }
    }

    public function company()
    {
        try {
            $client = new Client();

            $response = $client->request('GET', 'https://posta.dy-po.com/api/company/getAll');
            $result = json_decode($response->getBody(), true);
            if ($result && $result['status'] == 'success') {
                return $this->respondWithSuccess($result['response']);
            } else {
                return $this->respondWithError('can not get data form api');
            }
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function status(Request $request)
    {
        try {
            $token  = $request->input('token');
            $client = new Client();

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $response = $client->request('GET', 'https://posta.dy-po.com/api/get/status', [
                'headers' => $headers
            ]);
            $result = json_decode($response->getBody(), true);
            if ($result && $result['status'] == 'success') {
                return $this->respondWithSuccess($result['response']);
            } else {
                return $this->respondWithError('can not get data form api');
            }
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function ordersSalesman(Request $request)
    {
        try {
            $token  = $request->input('token');
            $order_id  = $request->input('order_id');
            $status  = $request->input('status');
            $from_date  = $request->input('from_date');
            $to_date  = $request->input('to_date');
            $page  = $request->input('page');

            $client = new Client();

            $url =  "https://posta.dy-po.com/api/order/get/orders/by/salesman/paginate?id=" .
                // $url =  "http://localhost/posta-zog/public/api/order/get/orders/by/salesman/paginate?id=" .
                $order_id .
                "&status=" .
                $status .
                "&from_date=" .
                $from_date .
                "&to_date=" .
                $to_date .
                "&page=" .
                $page;

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $response = $client->request('GET', $url, [
                'headers' => $headers
            ]);
            $result = json_decode($response->getBody(), true);
            if ($result && $result['status'] == 'success') {
                return $this->respondWithSuccess($result['response']);
            } else {
                return $this->respondWithError('can not get data form api');
            }
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function raportSalesman(Request $request)
    {
        try {
            $date  = $request->input('date');
            $token  = $request->input('token');
            $endDate  = $request->input('endDate');
            $status  = $request->input('status') ? $request->input('status') : 0;

            $client = new Client();

            $url =  "https://posta.dy-po.com/api/order/get/orders/by/salesman/" .
                $date .
                "/" .
                $endDate .
                "/" .
                $status;
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $response = $client->request('GET', $url, [
                'headers' => $headers
            ]);
            $result = json_decode($response->getBody(), true);
            if ($result && $result['status'] == 'success') {
                return $this->respondWithSuccess($result['response']);
            } else {
                return $this->respondWithError('can not get data form api');
            }
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function login(Request $request)
    {
        try {
            $client = new Client();
            $response = $client->request('POST', 'https://posta.dy-po.com/api/login', [
                'form_params' => [
                    'email' => $request->input('email'),
                    'password' => $request->input('password'),
                ]
            ]);
            $result = json_decode($response->getBody(), true);
            return $this->respondWithSuccess($result);
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function createOrder(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'price' => 'required',
            'buyer_location' => 'required',
            'buyer_email' => 'required',
            'city_id' => 'required',
            'buyer_phone' => 'required',
            'buyer_name' => 'required',
            'openable' => 'required',
            'weight' => 'required',
            'reversible' => 'required',
            'quantity' => 'required',
            'reference' => 'required',
            'token' => 'required',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return $this->respondWithError($errors);
        }

        try {
            $client = new Client();

            $token  = $request->input('token');
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $response = $client->request('POST', 'https://posta.dy-po.com/api/order/create', [
                'form_params' => [
                    'name' => $request->input('name'),
                    'price' => $request->input('price'),
                    'seller_location' => $request->input('seller_location'),
                    'buyer_location' => $request->input('buyer_location'),
                    'buyer_email' => $request->input('buyer_email'),
                    'city_id' => $request->input('city_id'),
                    'buyer_phone' => $request->input('buyer_phone'),
                    'buyer_name' => $request->input('buyer_name'),
                    'openable' => $request->input('openable'),
                    'weight' => $request->input('weight'),
                    'reversible' => $request->input('reversible'),
                    'quantity' => $request->input('quantity'),
                    'reference' => $request->input('reference'),
                    'company_id' => $request->input('company_id')
                ],
                'headers' => $headers
            ]);
            $result = json_decode($response->getBody(), true);
            return $this->respondWithSuccess($result);
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function declineOrder(Request $request)
    {
        try {
            $client = new Client();
            $id  = $request->input('id');
            $token  = $request->input('token');
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $url = "https://posta.dy-po.com/api/order/decline/order/from/shop/" . $id;
            $response = $client->request(
                'POST',
                $url,
                [
                    'headers' => $headers
                ]
            );
            $result = json_decode($response->getBody(), true);
            return $this->respondWithSuccess($result);
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function user(Request $request)
    {
        try {
            $token  = $request->input('token');
            $client = new Client();

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $response = $client->request('GET', 'https://posta.dy-po.com/api/user', [
                'headers' => $headers
            ]);
            $result = json_decode($response->getBody(), true);
            if ($result) {
                return $this->respondWithSuccess($result);
            } else {
                return $this->respondWithError('can not get data form api');
            }
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function deactivateAcc($id)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://posta.dy-po.com/api/deactivate/account/' . $id);
        // $response = $client->request('POST', 'http://localhost/posta-zog/public/api/deactivate/account/' . $id);
        $result = json_decode($response->getBody(), true);
        if ($result && $result['status'] == 'success') {
            return true;
        } else {
            return false;
        }
    }

    public function updateProfile(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'token' => 'required',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return false;
        }

        try {
            $client = new Client();

            $token  = $request->input('token');
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $response = $client->request(
                'POST',
                'https://posta.dy-po.com/api/profile/shop',
                // $response = $client->request(
                //     'POST',
                //     'http://localhost/posta-zog/public/api/profile/shop',
                [
                    'form_params' => [
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'phone_number' => $request->input('phone'),
                        'address' => $request->input('address'),
                        'city_id' => $request->input('city')
                    ],
                    'headers' => $headers
                ]
            );
            $result = json_decode($response->getBody(), true);
            if ($result && $result['status'] == 'success') {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $ex) {
            return $this->respondWithError($ex->getMessage());
        }
    }
    public function updatePassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return false;
        }

        try {
            $client = new Client();

            $token  = $request->input('token');
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            $response = $client->request(
                'PATCH',
                'https://posta.dy-po.com/api/settings/password',
                [
                    'form_params' => [
                        'password' => $request->input('password'),
                        'password_confirmation' => $request->input('password_confirmation')
                    ],
                    'headers' => $headers
                ]
            );
            $result = json_decode($response->getBody(), true);
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
