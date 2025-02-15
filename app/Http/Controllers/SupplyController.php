<?php

namespace App\Http\Controllers;

use App\Models\Shipments;
use Illuminate\Http\Request;
use App\Models\ShipmentBox;
use App\Models\Customer;
use App\Models\Delivery;

class SupplyController extends Controller
{
    /**
     * Display the Supply Shipment Boxes page.
     */
    public function index()
    {
        $shipments = Shipments::all();
        $customers = Customer::all();
        $deliveries = Delivery::all();

        // Fetch available boxes that are NOT delivered yet
        $shipmentBoxes = ShipmentBox::whereNull('deleted_at')
            ->with('customer') // Ensure customer relation is loaded
            ->get();

        return view('supply.index', compact('customers', 'deliveries', 'shipmentBoxes', 'shipments'));
    }
    /**
     * Handle the supply of shipment boxes.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'zone_id' => 'required|string',
            'delivery_id' => 'required|exists:deliveries,id',
            'box_ids' => 'required|array',
            'box_ids.*' => 'exists:shipment_boxes,id'
        ]);

        // Update Shipment Boxes to mark them as supplied
        ShipmentBox::whereIn('id', $request->box_ids)->update([
            'delivered_by' => $request->delivery_id,
            'status' => 'Supplied'
        ]);

        return response()->json(['message' => 'Shipment Boxes Supplied Successfully!']);
    }

    public function deliverBoxes(Request $request)
    {
        // Validate request
        $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'customer_id' => 'required|exists:customers,id',
            'delivery_id' => 'required|exists:deliveries,id',
            'boxes' => 'required|array',
            'boxes.*' => 'exists:shipment_boxes,id'
        ]);

        // Update status and delivery date
        ShipmentBox::whereIn('id', $request->boxes)->update([
            'status' => 'delivered',
            'delivered_at' => now(), // Updating the delivery timestamp
        ]);

        return response()->json(['message' => 'Shipment boxes have been delivered successfully.'], 200);
    }



}
