<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index(){
        $records = Size::orderBy('id','ASC')->get();
        if($records)
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
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        $record = Size::create($request->all());
        return response()->json(['status'=>1,'message'=>'Size created successfully','data'=>$record], 201);
    }

    public function show($id){
        $record = Size::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        else{
            return response()->json(['status'=>1,'message'=>'Success','data'=>$record], 200);
        }
    }

    public function update(Request $request,$id){
        $record = Size::find($id);
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
        return response()->json(['status'=>1,'message'=>'Size updated successfully','data'=>$record], 200);

    }

    public function destroy($id)
    {
        $record = Size::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $record->delete();
        return response()->json(['status'=>1,'message'=>'Size deleted successfully'], 200);
    }
}
