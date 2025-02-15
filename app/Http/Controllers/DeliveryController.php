<?php

namespace App\Http\Controllers;
use App\Traits\Availability;
use Illuminate\Http\Request;
use App\Models\Delivery;   
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class DeliveryController extends Controller
{
    
    use Availability;
       /**
     * Index resources.
     * 
     * @return \Illuminate\View\View
     */


    public function index(Request $request)
    {
        return view("deliveries.index");
    }

    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view("deliveries.create");
    }
    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function edit(Delivery $delivery): View
    {


        return view("deliveries.edit", [
            'delivery' => $delivery        
        ]);
    }
    /**
     * Delete resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Delivery $delivery): RedirectResponse
    {
        $delivery->delete();
        return Redirect::back()->with("success", __("Deleted"));
    }
    /**
     * Delete resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function imageDestroy(Delivery $delivery): RedirectResponse
    {
        $delivery->deleteImage();
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
            'phone' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png', 'max:2024'],
            'status' => ['required', 'string'],
        ]);

        $delivery = Delivery::create([
            'name' => $request->name,
            'phone' => $request->phone ?? 1,
            'is_active' => $this->isAvailable($request->status) 
         ]);

        if ($request->has('image')) {
            $delivery->updateImage($request->image);
        }
        return Redirect::back()->with("success", __("Created"));
    }

    /**
     * update resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Delivery $delivery): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png', 'max:2024'],
            'status' => ['required', 'string']
        ]);

        $delivery->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'is_active' => $this->isAvailable($request->status)
        ]);
        if ($request->has('image')) {
            $delivery->updateImage($request->image);
        }
        return Redirect::back()->with("success", __("Updated"));
    }

    public function getDelivery()
    {
        $list = Delivery::get();  
        return response()->json($list);
    }

}
