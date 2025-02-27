<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CategoryController extends Controller
{
    public function index()
    {
        $records = Category::orderBy('id', 'desc')->get();
        if($records->isEmpty()){
            return response()->json(['status'=>0,'message'=>'No record found'], 404);
        }
        else{
            return response()->json(['status'=>1,'message'=>'Success','data'=>$records], 200);
        }
    }
    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        $record = Category::create($request->all());
        return response()->json(['status'=>1,'message'=>'Category created successfully','data'=>$record], 201);
    }
    public function show($id)
    {
        $record = Category::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        else{
            return response()->json(['status'=>1,'message'=>'Success','data'=>$record], 200);
        }
    }
    public function update($id, Request $request)
    {
        $record = Category::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        $record->update($request->all());
        return response()->json(['status'=>1,'message'=>'Category updated successfully','data'=>$record], 200);
    }
    public function destroy($id)
    {
        $record = Category::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $record->delete();
        return response()->json(['status'=>1,'message'=>'Category deleted successfully'], 200);
    }
}
