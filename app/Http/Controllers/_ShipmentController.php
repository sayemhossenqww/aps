<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipments; 
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use App\Traits\Availability;
use Zxing\QrReader;
use Illuminate\Support\Facades\Storage;
use App\Models\SubShipments; 
use Illuminate\Support\Facades\DB;


class ShipmentController extends Controller
{
    
    use Availability;

     /**
     * Index resources.
     * 
     * @return \Illuminate\View\View
     */
 
     public function index(Request $request)
     {
         return view("shipments.index");
     }

   /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
       $shipments = Shipments::get();
        return view("shipments.create",[
            'shipment_all' => $shipments        
        ]);
    }

     /**
     * Show resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'title' => ['required', 'string', 'max:100']
        ]);

         $shipment = Shipments::create([
            'title' => $request->title,
            'date' => $request->date,  
            'is_active' => $this->isAvailable(1),
            'country'=>$request->country,
            'mode'=>$request->mode,
            'supplier_name'=>$request['supplier_name'],
            'bar_code'=>$request['bar_code'],
            'sum_weight'=>$request->sum_weight,
            'total_price'=>$request->total_price,
       
        ]);
         
        return Redirect::back()->with("success", __("Created"));
    }

    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function edit(Shipments $shipments): View
    {
        $shipment_all = Shipments::get();
        $shipmentData = SubShipments::where('shipment_id','=',$shipments['id'])->first();
        
        $supplierName = null;
        $barCode = null;
        $weight = null;
        $price = null;
        $id = null;

        if(!empty($shipmentData))
        {
             $id = $shipmentData['id'];   
            if(!empty($shipmentData['supplier_name']))
            {
              $supplierName = $shipmentData['supplier_name'];
            }
           
            if(!empty($shipmentData['bar_code']))
            {
              $barCode = $shipmentData['bar_code'];
            }

            if(!empty($shipmentData['weight']))
            {
              $weight = $shipmentData['weight'];
            }

            
            if(!empty($shipmentData['price']))
            {
              $price = $shipmentData['price'];
            }
            //$shipmentData = SubShipments::where('shipment_id','=',$shipments['id'])->first();
     
        }
        
        return view("shipments.edit", [
            'shipment' => $shipments,
            'shipment_all' => $shipment_all,
            'supplier_name'=> $supplierName,
            'bar_code'=> $barCode,
            'weight'=> $weight,
            'price'=> $price,   
            'id'=>$id     
        ]);
    }
    /**
     * Delete resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Shipments $shipment): RedirectResponse
    {
        $shipment->delete();
        return Redirect::back()->with("success", __("Deleted"));
    }

    public function scanQRCode(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        // Store the uploaded file temporarily
        $imagePath = $request->file('qr_code')->store('temp');
        $fullPath =  storage_path('app/'. $imagePath);
    
        try {
       
            $qrcode = new QrReader($fullPath);
            $text = $qrcode->text(); 
            // Extract QR code content
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error reading QR Code: ' . $e->getMessage()], 500);
        }

            // Delete the temporary file
        Storage::delete($fullPath);

        return response()->json([
            'data' => $text ?$text: 'QR Code not readable'
        ]);



    }

    
    /**
     * update resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Shipments $shipment): RedirectResponse
    {
        
        $request->validate([
            'title' => ['required', 'string', 'max:100']
        ]);
       

    $shipment->update([
        'title' => $request->title,
        'date' => $request->date, 
        'is_active' => 0,
        'country'=>$request->country,
        'mode'=>$request->mode,
        'supplier_name'=>json_encode($request['supplier_name']),
        'weight'=>json_encode($request['weight']),
        'price'=>json_encode($request['price']),
        'bar_code'=>json_encode($request['bar_code']),
        'sum_weight'=>$request->sum_weight,
        'total_price'=>$request->total_price
   
    ]);
        return Redirect::back()->with("success", __("Updated"));
    }

     /**
     * Show resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subPackage(Request $request): RedirectResponse
    {
        
         $shipment = SubShipments::create([
            'shipment_id'=> $request->shipment_id,
            'is_active' => $this->isAvailable(1),
            'supplier_name'=>json_encode($request['supplier_name']),
            'bar_code'=>json_encode($request['bar_code']),
            'weight'=>json_encode($request->weight),
            'price'=>json_encode($request->price),
       
        ]);
         
        return Redirect::back()->with("success", __("Created"));
    }

    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function getShipment($id)
    {
        $shipments = Shipments::where('id','=',$id)->first();
        return response()->json([
            'data' => $shipments 
        ]);
    }

    public function subPackageUpdate(Request $request,SubShipments $subShipments )
    {
        

         DB::table('shipment_sub_package')
        ->where('id', 1)
        ->update([
         'shipment_id'  => $request->shipment_id,
        'is_active'     => $this->isAvailable(1),
        'supplier_name' =>json_encode($request['supplier_name']),
        'bar_code'     =>json_encode($request['bar_code']),
        'weight'       =>json_encode($request['weight']),
        'price'        =>json_encode($request['price']),
      ]);

         
        return Redirect::back()->with("success", __("Updated"));
    }

 
}
