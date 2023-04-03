<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Products = Product::paginate(15);
        return jsonResponse(TRUE, '', ['Products' => $Products]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reqs = $request->toArray();
        try{
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string',
                'category_id' => 'required|integer',
                'price' => 'required',
            ]);
    
            if ($validator->fails()) {
                return jsonResponse(FALSE, 'Has Some Errors', ['errors' => $validator->errors()], 422);
                
            }
            $Product = new Product($reqs);
            $Product->save();

            return jsonResponse(TRUE, 'Product Added !');

        } catch(\Illuminate\Database\QueryException $e)
        {
            return jsonResponse(FALSE, $e->getMessage(), []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reqs = $request->toArray();
        try{
            $ProducrRS = Product::where('id', $id);
            if($ProducrRS->exists())
            {
                
                $Product = $ProducrRS->first();
                $Product->update($reqs);

                $Baskets = Order::whereHas('OrderProducts', function($q) use ($id){
                    return $q->where('product_id', $id); 
                   })->where('order_status', 'basket')->with('OrderProducts')->get();
                
                foreach($Baskets as $Order)
                {
                    
                    foreach ($Order->OrderProducts as $orderProduct) 
                    {
                        $orderProduct->price = $orderProduct->qty * $Product->price;
                        $orderProduct->save();

                        $totalPrice = $Order->OrderProducts()->sum('price');
                        $Order->update(['total_price' => $totalPrice]);
                    }

                }

                return jsonResponse(TRUE, __('Product Saved !'), []);
            }
          }catch(\Illuminate\Database\QueryException $e)
          {
            return jsonResponse(FALSE, $e->getMessage(), []);
          }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $ProductRS = Product::where('id', $id);
            if($ProductRS->exists())
            {
                $Product = $ProductRS->first();
    
                $Del = $Product->delete();
                return jsonResponse(TRUE, __('Product Removed !'), []);
               
            }
          }catch(Exception $e)
          {
              return jsonResponse(FALSE, $e->getMessage(), []);
          }
    }

    public function makeProductToShowCase($product_id )
    {
        try {
            $ProductRS = Product::where('id', $product_id);
            if($ProductRS->exists())
            {
                $Product = $ProductRS->first();
                $Product->update(['status'=>'showcase']);
                return jsonResponse(TRUE, __('Product made showcase successfuly !'), []);
            }
        } catch (Exception $e) {
            return jsonResponse(FALSE, $e->getMessage(), []);
        }
    }

    public function getShowcaseProducts()
    {
        $Products = Product::where('status', 'showcase')->paginate(15);
        return jsonResponse(TRUE, '', ['Products' => $Products]);
    }

}
