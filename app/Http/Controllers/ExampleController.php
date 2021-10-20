<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExampleController extends Controller
{
    public function index(Request $request, $view = 'object-define-property')
    {
        // 保存処理
        if ($request->getMethod() === 'POST') {
            DB::transaction(function () use ($request) {
                Address::query()->delete();
                foreach ($request->get('addresses') as $data) {
                    $address = new Address();
                    $address->fill($data)->save();
                }
            });
        }

        // 全件取得 なければ単一エンティティ作成
        $datas = Address::all();
        if (!isset($datas) || count($datas) <= 0) {
            $datas[] = new Address();
        }

        // 市区町村のプルダウン情報作成
        $cities = [];
        foreach ($datas as $index => $data) {
            $cities[$index] = null;
            if (isset($data->pref) && !is_null($data->pref)) {
                $cities[$index] = json_decode(Storage::disk('public_json')->get("{$data->pref}.json"), true);
            }
        }

        return view('example.' . str_replace('-', '_', $view) , compact('datas', 'cities'));
    }

    /**
     * 行追加処理
     * @param Request $request
     */
    public function addRow(Request $request)
    {
        $data = new Address();
        $cities = [];
        $index = $request->get('index');

        return view('example.form', compact('data', 'cities', 'index'));
    }
}
