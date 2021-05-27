<?php

namespace App\Http\Services;

use App\Models\brands;
use Illuminate\Support\Facades\Storage;

class ValidateBrands
{
    public function validate($request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        return $validation;
    }
}
