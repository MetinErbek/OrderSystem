<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProducts;
use Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function addToBasket(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'qty' => 'required|integer|min:1'
            ]);
    
            if ($validator->fails()) {
                return jsonResponse(FALSE, $validator->errors()->first(), []);
            }
    
            $productId = $request->product_id;
            $quantity = $request->qty;
    
            $Order = $this->getBasketOrder();

            //var_dump($Order->OrderProducts()->where('product_id', $productId));exit;
            $orderProductRS = $Order->OrderProducts()->where('product_id', $productId);
            
            if ($orderProductRS->exists()) {
                $orderProduct = $orderProductRS->first();
                // Product already exists in the basket, increase quantity and update price
                $orderProduct->increment('qty', $quantity);
                $orderProduct->update(['price' => $orderProduct->product->price * $orderProduct->qty]);
            } else {
                // Add new product to the basket
                $product = Product::findOrFail($productId);
                $Order->OrderProducts()->create([
                    'order_id'  => $Order->id,
                    'product_id' => $productId,
                    'product_price' => $product->price,
                    'qty' => $quantity,
                    'price' => $product->price * $quantity
                ]);
                //$Order->OrderProducts()->saveMany([$newOrderProduct]);
            }
            $this->updateTotalPrice($Order);
            return jsonResponse(TRUE, 'Product added to basket successfully.', []);
    
        } catch (Exception $e) {
            return jsonResponse(FALSE, $e->getMessage(), []);
        }
    }
    public function removeFromBasket(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'qty' => 'required|integer|min:1'
            ]);
    
            if ($validator->fails()) {
                return jsonResponse(FALSE, $validator->errors()->first(), []);
            }
    
            $productId = $request->product_id;
            $quantity = $request->qty;
    
            $Order = $this->getBasketOrder();
            $orderProductRS = $Order->OrderProducts()->where('product_id', $productId);
            if ($orderProductRS->exists()) {
                $orderProduct = $orderProductRS->first() ;
                if ($orderProduct->qty > $quantity) {
                    // Decrease quantity
                    $orderProduct->decrement('qty', $quantity);
                    $orderProduct->update(['price' => $orderProduct->product->price * $orderProduct->qty]);
                } else {
                    // Remove product from basket
                    $orderProduct->delete();
                }
    
                $this->updateTotalPrice($Order);
    
                // Check if product quantity is 0 and delete the order product
                if ($orderProduct->qty == 0) {
                    $orderProduct->delete();
                }
    
                return jsonResponse(TRUE, 'Product removed from basket successfully.', []);
    
            } else {
                return jsonResponse(FALSE, 'Product not found in basket.', []);
            }
    
        } catch (Exception $e) {
            return jsonResponse(FALSE, $e->getMessage(), []);
        }
    }
    public function updateTotalPrice($Order)
    {
        $totalPrice = $Order->OrderProducts()->sum('price');
        $Order->update(['total_price' => $totalPrice]);
    }

    public function getBasketOrder()
    {
        try {
            $BasketOrderRS = Order::where('user_id', Auth::guard('api')->user()->id)
                ->where('order_status', 'basket');
            if($BasketOrderRS->exists())
            {
                // Has Basket Order
                return $BasketOrderRS->with('OrderProducts.product')->first();
            } else {
                // Create basket Order
                $Order = Order::create([
                    'user_id'       =>  Auth::guard('api')->user()->id,
                    'order_status'  => 'basket'
                ]);
                return $Order->with('OrderProducts.product');
            }

        } catch (Exception $e) {
            return jsonResponse(FALSE, $e->getMessage(), []);
        }

    }

    public function makeBasketToOrder()
    {
        try {
            $order = $this->getBasketOrder();
            if ($order->OrderProducts()->exists()) {
                $order->update(['order_status' => 'order']);
                return jsonResponse(TRUE, 'Order created successfully.', []);
            } else {
                return jsonResponse(FALSE, 'Cannot create empty order.', []);
            }
        } catch (Exception $e) {
            return jsonResponse(FALSE, $e->getMessage(), []);
        }
    }

    public function getBasket()
    {
        $Basket =  $this->getBasketOrder();
        return jsonResponse(TRUE, '', ['Basket'=>$Basket ]);
    }



}
