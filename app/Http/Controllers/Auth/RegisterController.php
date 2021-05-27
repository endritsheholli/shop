<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return $user;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();
        $shopname = '';
        $data_shop = '';
        $data_shop_name = '';
        if ($data['shop_name']) {
            $data_shop_name = $data['shop_name'];
            $shopname = $this->slugify($data['shop_name'], 'users', 'shop_name');
        }
        $data_shop = ["name" => $data_shop_name, "slug" => $shopname];

        $client = new Client();

        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'city' => $data['city_id'],
            'shop_name' => $data_shop,
            'phone' => $data['phone'],
            'address' => $data['address'],
            'interest_id' => isset($data['interest_id']) ? $data['interest_id'] : 0,
            'role' => $data['role'],
            'country_id' =>  isset($data['country_id']) ? $data['country_id'] : 0,
            'password' => bcrypt($data['password']),
        ]);
        if ($data['role'] == 'shop' && !isset($data['from_posta'])) {
            $response = $client->request('POST', 'https://posta.dy-po.com/api/register', [
                'form_params' => [
                    'name' =>  $data['name'],
                    'email' =>  $data['email'],
                    'password' => $data['password'],
                    'role' => 'salesman',
                    'phone_number' => $data['phone'],
                    'address' => $data['address'],
                    'city_id' =>  $data['city_id'],
                    'zogu_mall_id' => $user->id
                ]
            ]);
            $result = json_decode($response->getBody(), true);

            if ($result && $result['status'] == 'success') {
            } else {
                DB::rollBack();
                return;
            }
        }
        if($user->sameAddress==1){
            $address = Address::create([
                'user_id' => $user->id,
                'address' => $data['address'],
                'zip' => $data['email'],
                'city' => $data['city_id'],
            ]);
        }
        else{
            $address = Address::create([
                'user_id' => $user->id,
                'country' => $data['country'],
                'street_home_no' => $data['street_home_no'],
                'apartment_suite_address' => $data['apartment_suite_address'],
                'place' => $data['place'],
                'zip' => $data['zip'],
                'type'=> 0,
            ]);
            $address2 = Address::create([
                'user_id' => $user->id,
                'country' => $data['country'],
                'street_home_no' => $data['street_home_no'],
                'apartment_suite_address' => $data['apartment_suite_address'],
                'place' => $data['place'],
                'zip' => $data['zip'],
                'type'=> 1,
            ]);
        }
      
        
        DB::commit();


        return $user;
    }
}
