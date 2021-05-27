<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;

class CitiesController extends Controller
{
    public function __construct() {
    }

    public function getCities()
    {
        try {
            $result = City::get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithException($ex->getResponse());
        }
    }
}
