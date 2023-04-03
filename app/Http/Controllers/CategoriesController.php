<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;


class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Categories = Categories::get();
        return jsonResponse(TRUE, '', ['Categories' => $Categories]);
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
            $Category = new Categories($reqs);
            $Category->save();

            return jsonResponse(TRUE, 'Category Added !');

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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reqs = $request->toArray();
        try{
            $CategoryRS = Categories::where('id', $id);
            if($CategoryRS->exists())
            {
                
                $Category = $CategoryRS->first();
                
                $Category->fill($reqs);
                $Category->save();
                return jsonResponse(TRUE, __('Category Saved !'), []);
               
  
  
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
            $CategoryRS = Categories::where('id', $id);
            if($CategoryRS->exists())
            {
                $Category = $CategoryRS->first();
    
                $Del = $Category->delete();
                return jsonResponse(TRUE, __('Category Removed !'), []);
               
            }
          }catch(\Illuminate\Database\QueryException $e)
          {
              return jsonResponse(FALSE, $e->getMessage(), []);
          }
    }
}
