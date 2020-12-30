<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Redirect, Response;


class OrderController extends Controller
{
    public function orderData(){
        $data = DB::table('test_yongmin')->limit(20)->get();

        return response()->json($data);
    }
    public function getDestroy(Request  $request) {
        $id = $request->models;
        $order = DB::table('test_yongmin')->where('id', $id)->delete();

    }
    
    public function destroy($id)
    {
        $order = DB::table('test_yongmin')->where('id', $id)->delete();
        dd("im here deleting");
        return Response::json($order);
        //return redirect()->route('users.index');
    }
}
