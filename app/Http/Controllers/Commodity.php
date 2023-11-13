<?php

namespace App\Http\Controllers;

use App\Models\CommodityModel;
use Illuminate\Http\Request;

class Commodity extends Controller
{
    public function index()
    {
        $commodity = CommodityModel::where('quality', 1)->orderBy('title')->get()->groupBy('genre');
        $arr = [];
        $array = [];
        foreach ($commodity as $val) {
            $arr[] = $val->count();
        }
        $arr = array_unique($arr);
        sort($arr);
        for ($i = 0; $i < count($arr); $i++) {
            foreach ($commodity as $val) {
                if ($val->count() == $arr[$i]) {
                    $array[] = $val;
                }
            }
        }
        $inferior = CommodityModel::where('quality', 0)->get();
        return view('shop.index', ['commodity' => $array, 'inferior' => $inferior]);
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
            'msg' => '修改成功~',
        ]);
    }

    public function quality($id)
    {
        $r = CommodityModel::where('id', $id)->update(['quality' => 0]);
        return response()->json([
            'code' => '200',
            'msg' => '修改成功~',
        ]);
    }
}
