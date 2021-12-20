<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Values\StatusValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::orderBy('created_at', 'DESC')->get();
            return response ([
                'data' => new ProductCollection($products)
            ], StatusValue::HTTP_OK);
            
        } catch (\Exception $e) {
            return response([
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], StatusValue::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'image' => 'required|string',
            ]);

            $image = $request->image; 
            $extension = explode('/', mime_content_type($image))[1];
            $image = str_replace('data:image/' . $extension . ';base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.jpg';
            Storage::disk('public')->put('images/' . $imageName, base64_decode($image));

            $product = new Product();
            $product->name = $request['name'];
            $product->description = $request['description'];
            $product->image = $imageName;
            $product->price = $request['price'];
            $product->save();

            return response(
                [
                    'data' => new ProductResource($product)
                ],
                StatusValue::HTTP_OK
            );
        } catch (\Exception $e) {
            return response([
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], StatusValue::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
