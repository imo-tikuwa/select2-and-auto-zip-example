<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExampleController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $cities = [];
        if (isset($data['pref']) && !is_null($data['pref'])) {
            $cities = json_decode(Storage::disk('public_json')->get("{$data['pref']}.json"), true);
        }
        return view('example/index', compact('data', 'cities'));
    }
}
