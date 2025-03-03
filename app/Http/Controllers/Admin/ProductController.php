<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $records = Product::orderBy('id','ASC')->get();
        if(!$records->isEmpty())
        {
            return response()->json(['status'=>1,'message'=>'Success','data'=>$records], 200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No record found'], 404);           
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'brand_id' => 'required',
            'sku' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        $record = Product::create($request->all());
        return response()->json(['status'=>1,'message'=>'Product created successfully','data'=>$record], 201);
    }

    public function show($id){
        $record = Product::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        else{
            return response()->json(['status'=>1,'message'=>'Success','data'=>$record], 200);
        }
    }

    public function update(Request $request,$id){
        $record = Product::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'brand_id' => 'required',
            'sku' => 'required|unique:products,sku',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        $record->update($request->all());
        return response()->json(['status'=>1,'message'=>'Product updated successfully','data'=>$record], 200);

    }

    public function destroy($id)
    {
        $record = Product::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $record->delete();
        return response()->json(['status'=>1,'message'=>'Product deleted successfully'], 200);
    }    
}
