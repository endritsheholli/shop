<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BrandsController extends Controller
{
    public function create(Request $request)
    {
        try{
            $validation = Validator::make($request->all(), [
                'name' => 'required|max:255',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $brand = new Brands();
                $brand->name = $request->input('name');

                $brand->save();

                return $this->respondWithSuccess($brand);
            }
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
                'name' => 'required|max:255',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $brand = Brands::where(['id' => $id])->first();

                if (!$brand) {
                    return $this->respondWithError('Record not found!');
                }

                $brand->name = $request->input('name');

                $brand->save();
                DB::commit();

                return $this->respondWithSuccess($brand);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getResponse(), 'exception');
        }
    }

    public function remove($id)
    {
        try {
            $brand = Brands::find($id);
            if (!$brand) {
                return $this->respondWithError('Record not found!');
            }

            $brand->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getById($id)
    {
        $result = Brands::find($id);
        return $this->respondWithSuccess($result);
    }
    public function getAllBrands()
    {
        try {
            $result = Brands::get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
}
