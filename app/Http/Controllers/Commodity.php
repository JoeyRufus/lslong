<?php

namespace App\Http\Controllers;

use App\Models\CommodityModel;
use Illuminate\Http\Request;

class Commodity extends Controller
{
    public function index()
    {
        $commodity = CommodityModel::orderBy('title')->get()->groupBy('genre');
        return view('shop.index', ['commodity' => $commodity]);
    }
    public function store(Request $request)
    {
        $r = CommodityModel::create($request->post());
        return response()->json([
            'code' => '200',
            'msg' => '添加成功~',
        ]);
    }
    public function update(Request $request)
    {
        $data = $request->post();
        $array = [
            'price' => $data['price'],
            'max' => $data['max'],
            'min' => $data['min'],
            'unit_price' => $data['unit_price'],
        ];
        $r = CommodityModel::where('id', $data['id'])->update($array);
        return response()->json([
            'code' => '200',
            'msg' => '添加成功~',
        ]);
    }
}
