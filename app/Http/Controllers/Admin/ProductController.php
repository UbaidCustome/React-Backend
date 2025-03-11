<?php

namespace App\Http\Controllers\Admin;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $records = Product::orderBy('id', 'ASC')->get();
    
        if (!$records->isEmpty()) {
            // Adding full image URL to each record
            $records = $records->map(function ($record) {
                $record->image_url = asset('images/product/' . $record->image);
                return $record;
            });
    
            return response()->json(['status' => 1, 'message' => 'Success', 'data' => $records], 200);
        } else {
            return response()->json(['status' => 0, 'message' => 'No record found'], 404);
        }
    }

    public function store(Request $request)
    {
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
        if ($request->hasFile('image')) {
            // return 123;
            $image = $request->file('image');
            // Unique image name generate karne ke liye timestamp use kar sakte hain
            $imageName = time() . '_' . $image->getClientOriginalExtension();
            // Image ko public/images directory mein move kar dein
            $image->move(public_path('images/product'), $imageName);
            // Image path ko blog model mein store karein
        }
        $record = Product::create([
            'title' => $request->input('title'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'sku' => $request->input('sku'),
            'status' => $request->input('status'),
            'image' => $imageName??null
        ]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('product/images'), $imageName); // Save to public/products/images

                Product_image::create([
                    'product_id' => $record->id,
                    'image' => $imageName, // Save only the image name
                ]);
            }
        }
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
        return $request->input();
        $record = Product::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'brand_id' => 'required',
            'sku' => 'required|unique:products,sku,'.$id.',id',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()->first()], 400);
        }
        if ($request->hasFile('image')) {
            // return 123;
            $oldImagePath = public_path('images/' . $record->image);
            if (file_exists($oldImagePath) && !empty($record->image)) {
                unlink($oldImagePath);
            }
            $image = $request->file('image');
            // Unique image name generate karne ke liye timestamp use kar sakte hain
            $imageName = time() . '_' . $image->getClientOriginalExtension();
            // Image ko public/images directory mein move kar dein
            $image->move(public_path('images'), $imageName);
            $record->image = $imageName;
            // Image path ko blog model mein store karein
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('product/images'), $imageName); // Save to public/products/images

                Product_image::create([
                    'product_id' => $record->id,
                    'image' => $imageName, // Save only the image name
                ]);
            }
        }
        $record->title = $request->title;
        $record->price = $request->price;
        $record->category_id = $request->category_id;
        $record->brand_id = $request->brand_id;
        $record->sku = $request->sku;
        $record->status = $request->status;
        $record->save();
        return response()->json(['status'=>1,'message'=>'Product updated successfully','data'=>$record], 200);

    }

    public function destroy($id)
    {
        $record = Product::find($id);
        if(is_null($record)){
            return response()->json(['status'=>0,'message'=>'Record not found'], 404);
        }
        $imagePath = public_path('images/product/' . $record->image);
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
        $multiImages = Product_image::where('product_id', $id)->get();
        foreach ($multiImages as $img) {
            $multiImagePath = public_path('product/images/' . $img->image);
            if (file_exists($multiImagePath) && is_file($multiImagePath)) {
                unlink($multiImagePath);
            }
        }
        $record->delete();
        return response()->json(['status'=>1,'message'=>'Product deleted successfully'], 200);
    }
}