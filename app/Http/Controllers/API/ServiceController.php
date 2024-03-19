<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function getAll()
    {
        return response()->json(Service::all());
    }
}
