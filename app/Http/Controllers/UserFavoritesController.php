<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFavorites;
use Auth;

class UserFavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $FavoritesRS = UserFavorites::with('product')->where('user_id', Auth::guard('api')->user()->id);
        if($FavoritesRS->exists())
        {
            $Favorites = $FavoritesRS->get();
            return jsonResponse(TRUE, '', ['Favorites' => $Favorites]);
        } else {
            return jsonResponse(FALSE, 'You didnt add any product to your favorites yet ! ', ['Favorites'=>[]]);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reqs = $request->toArray();
        try{
            $reqs['user_id'] = Auth::guard('api')->user()->id;

            $FavoriExists = UserFavorites::where('product_id', $reqs['product_id'])->where('user_id', $reqs['user_id']);
            if(!$FavoriExists->exists())
            {
                $UserFavorite = UserFavorites::create($reqs);
                return jsonResponse(TRUE, 'Product added to your favorite !');
            } else {
                return jsonResponse(FALSE, 'Before you added this product to your favorites already !', []);
            }



        } catch(Exception $e)
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
            $FavoritesRS = UserFavorites::where('product_id', $id);
            if($FavoritesRS->exists())
            {
                $Favorite = $FavoritesRS->first();
    
                $Del = $Favorite->delete();
                return jsonResponse(TRUE, __('Product Removed from your favorite!'), []);
               
            }
          }catch(Exception $e)
          {
              return jsonResponse(FALSE, $e->getMessage(), []);
          }
    }

    public function removeFromFavorites(Request $request)
    {
        
        try{
            $reqs = $request->toArray();
            $reqs['user_id'] = Auth::guard('api')->user()->id;
            $FavoritesRS = UserFavorites::where('product_id', $reqs['product_id'])->where('user_id',$reqs['user_id']);
            if($FavoritesRS->exists())
            {
                $Favorite = $FavoritesRS->first();
    
                $Del = $Favorite->delete();
                return jsonResponse(TRUE, __('Product Removed from your favorite!'), []);
               
            } else {
                return jsonResponse(TRUE, __('Product not in your favorite!'), []);
            }
          }catch(Exception $e)
          {
              return jsonResponse(FALSE, $e->getMessage(), []);
          }
    }
}
