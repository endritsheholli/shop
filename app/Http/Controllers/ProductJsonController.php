<?php

namespace App\Http\Controllers;

use App\Services\DocumentManagement;
use App\Models\ProductParameter;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class ProductJsonController extends Controller
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

    public function createJson(Request $request)
    {
        DB::beginTransaction();
        try {
            $url = $request->input('url') . "";
            if ($url) {
                $client = new Client();
                $response = $client->get($url);

                $result = json_decode($response->getBody(), true);

                if ($result && isset($result['response'])) {
                    $json = $result['response'];
                } else {
                    DB::rollBack();
                    return $this->respondWithError('error');
                }
            } else {
                $json = json_decode(file_get_contents($request->file('file')), true);
            }
            // return ($json);
            if (!$json || !is_array($json)) {
                return $this->respondWithError('json not is valid');
            }

            $insert_data = [];

            $title = $request->input('title');
            $description = $request->input('description');
            $price = $request->input('price');
            $previous_price  = $request->input('previous_price');
            $weight  = $request->input('weight');
            $quantity  = $request->input('quantity');

            $products = $json;

            foreach ($products as $item) {
                $data = [
                    'user_id' => $this->user_id,
                    'title' =>  isset($item[$title]) ? $item[$title] : null,
                    'description' =>  isset($item[$description]) ? $item[$description] : null,
                    'price' =>  isset($item[$price]) ? $item[$price] : null,
                    'previous_price' =>  isset($item[$previous_price]) ? $item[$previous_price] ? $item[$previous_price] : null : null,
                    'quantity' =>  isset($item[$quantity]) ? $item[$quantity] ? $item[$quantity] : 1 : 1,
                    'weight' =>  isset($item[$weight]) ? $item[$weight] : null,
                    'slug' => $this->slugify(isset($item[$title]) ? $item[$title] : null, 'products'),
                    'image_urls' => '[]'
                ];
                $insert_data[] = $data;
            }
            $insert_data = collect($insert_data);
            // return $insert_data;

            $chunks = $insert_data->chunk(100);
            foreach ($chunks as $chunk) {
                Products::insert($chunk->toArray());
            }
            DB::commit();

            return $this->respondWithSuccess('success');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getMessage());
        }
    }

    public function jsonProductTest()
    {
        $insert_data = [];

        for ($i = 0; $i < 2; $i++) {
            $data = [
                'title' =>  'prod2',
                'description' =>  "test",
                'price' =>  "25",
                'previous_price' =>  "",
                'quantity' =>  "1",
                'weight' => "0",
            ];
            $insert_data[] = $data;
        }
        // dd($data);
        return $this->respondWithSuccess($insert_data);
    }
}
