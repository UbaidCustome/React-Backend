<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        $records = Brand::orderBy('id', 'desc')->get();
        if($records->isEmpty()){
            return response()->json(['status'=>0,'message'=>'No record found'], 404);
        }
        else{
            return response()->json(['status'=>1,'message'=>'Success','data'=>$records], 200);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        $record = Brand::create($request->all());
        return response()->json(['status'=>1,'message'=>'Brand created successfully','data'=>$record], 201);
    }
    public function show($id)
    {
        $record = Brand::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        else{
            return response()->json(['status'=>1,'message'=>'Success','data'=>$record], 200);
        }
    }
    public function update($id, Request $request)
    {
        $record = Brand::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        $record->update($request->all());
        return response()->json(['status'=>1,'message'=>'Brand updated successfully','data'=>$record], 200);
    }
    public function destroy($id)
    {
        $record = Brand::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $record->delete();
        return response()->json(['status'=>1,'message'=>'Brand deleted successfully'], 200);
    }
}
