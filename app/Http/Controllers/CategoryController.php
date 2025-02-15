<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\Availability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Shipments; 

class CategoryController extends Controller
{

    use Availability;
    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        return view("categories.index");
    }

    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function products(Request $request, Category $category): View
    {
        $products = $category->products()->search($request->search_query)->latest()->paginate(20);

        return view("categories.products", [
            'category' => $category,
            'products' => $products,
        ]);
    }
    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $shipment =  Shipments::get();
        return view("categories.create",
                       ["shipments"=>$shipment]);
    }
    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function edit(Category $category): View
    {
        $shipmentCountry = null;
        $shipmentMode    = null;
        $shipmentPrice   = null;

        if(!empty($category->shipment_country)){
            $shipmentCountry  =  $category->shipment_country;
        }

        if(!empty($category->shipment_mode)){
            $shipmentMode  =  $category->shipment_mode;
        }

        if(!empty($category->shipment_price)){
            $shipmentPrice  =  $category->shipment_price;
        }
        $shipment =  Shipments::get();
        
        return view("categories.edit", [
            'category'         => $category,
            'shipment_country' => $shipmentCountry,
            'shipment_mode'    => $shipmentMode,
            'shipment_price'   => $shipmentPrice,
            'shipments'=>$shipment
            
        ]);
    }
    /**
     * Delete resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return Redirect::back()->with("success", __("Deleted"));
    }
    /**
     * Delete resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function imageDestroy(Category $category): RedirectResponse
    {
        $category->deleteImage();
        return Redirect::back()->with("success", __("Image Removed"));
    }

    /**
     * Show resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png', 'max:2024'],
            'status' => ['required', 'string'],
        ]);

       
        $category = Category::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 1,
            'is_active' =>        $this->isAvailable($request->status),
            'type' =>   $request->type,
            'weight' =>          $request->weight,
            'shipment_country'=> $request->shipping_country,
            'shipment_mode'=>    $request->shipping_mode,
            'shipment_price'=>   $request->shipping_price,
            'date_of_shipment'=>   $request->date_of_shipment,
            'bar_code'=>   $request->bar_code,
            'shipment_id'=>   $request->shipment_id,
            'sub_shipment_id'=>   $request->sub_shipment_id,
        ]);

        if ($request->has('image')) {
            $category->updateImage($request->image);
        }
        return Redirect::back()->with("success", __("Created"));
    }

    /**
     * update resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png', 'max:2024'],
            'status' => ['required', 'string'],
            'type' => ['required', 'string'],
            'weight' => ['required', 'string'],
        ]);

        $category->update([
            'name' => $request->name,
            'sort_order' => $request->sort_order,
            'type' => $request->type,
            'weight' => $request->weight,
            'is_active' => $this->isAvailable($request->status),
            'shipment_country'=>$request->shipping_country,
            'shipment_mode'=>$request->shipping_mode,
            'shipment_price'=>$request->shipping_price,
            'date_of_shipment'=>   $request->date_of_shipment,
            'bar_code'=>   $request->bar_code,
            'shipment_id'=>   $request->shipment_id,
            'sub_shipment_id'=>   $request->sub_shipment_id,
            
        ]);
        if ($request->has('image')) {
            $category->updateImage($request->image);
        }
        return Redirect::back()->with("success", __("Updated"));
    }
}
