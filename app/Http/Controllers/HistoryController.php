<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function create(Request $request)
    {
        try{
            $validation = Validator::make($request->all(), [
                'product_id' => 'required',
                'buyer_id' => 'required',
                'seller_id' => 'required',
                'status' => 'required',
                'date' => 'date',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $history = new History();
                $history->product_id = $request->input('product_id');
                $history->buyer_id = $request->input('buyer_id');
                $history->seller_id = $request->input('seller_id');
                $history->status = $request->input('status');
                $history->date = $request->input('date');

                $history->save();
                $result = Products::find($request->product_id);
                $result->status = 0;
                $result->save();
                return $this->respondWithSuccess($history);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getByBuyerId($id)
    {
        $history = History::where(['buyer_id' => $id])->get();
        return $this->respondWithSuccess($history);
    }
    public function getBySellerId($id)
    {
        $history = History::where(['seller_id' => $id])->get();
        return $this->respondWithSuccess($history);
    }
}
