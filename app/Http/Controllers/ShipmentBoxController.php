<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ShipmentBox;
use App\Models\Shipments;
use Illuminate\Http\Request;
use Log;

class ShipmentBoxController extends Controller
{


    public function deliveredBoxesIndex($shipmentId)
    {
        $shipment = Shipments::findOrFail($shipmentId);
        return view('delivered-box-index', compact('shipment'));
    }

    public function getAllBoxes(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $length = $request->get("length");

        $order = $request->get('order');
        $columns = $request->get('columns');
        $search = $request->get('search');
        $columnIndex = $order[0]['column'];
        $columnName = $columns[$columnIndex]['data'];
        $columnSortOrder = $order[0]['dir'];
        $searchValue = $search['value'];

        // Fetch all data with customer relation (Eager Loading)
        $query = ShipmentBox::with('customer')->where(['delivered_at', null]);

        // Filter by Shipment ID
        if ($request->has('shipment_id') && !empty($request->shipment_id)) {
            $query->where('shipment_id', $request->shipment_id);
        }

        // Filter by Customer ID
        if ($request->has('customer_id') && !empty($request->customer_id)) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filtering by search
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('box_name', 'LIKE', "%$searchValue%")
                    ->orWhere('box_barcode', 'LIKE', "%$searchValue%")
                    ->orWhereHas('customer', function ($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%$searchValue%")
                            ->orWhere('phone', 'LIKE', "%$searchValue%");
                    });
            });
        }

        // Fetch all records first (Since SQLite cannot order by a joined table)
        $records = $query->get();

        // Apply sorting manually (For customer.name or customer.phone)
        $sortedRecords = $records->sort(function ($a, $b) use ($columnName, $columnSortOrder) {
            if ($columnName === 'customer.name') {
                $valueA = optional($a->customer)->name ?? '';
                $valueB = optional($b->customer)->name ?? '';
            } elseif ($columnName === 'customer.phone') {
                $valueA = optional($a->customer)->phone ?? '';
                $valueB = optional($b->customer)->phone ?? '';
            } else {
                $valueA = $a->$columnName;
                $valueB = $b->$columnName;
            }

            return $columnSortOrder === 'asc' ? strcmp($valueA, $valueB) : strcmp($valueB, $valueA);
        });

        // Paginate manually
        $paginatedRecords = $sortedRecords->slice($start, $length)->values();

        // Prepare response data
        $aaData = [];
        foreach ($paginatedRecords as $record) {
            $aaData[] = [
                "id" => $record->id,
                "box_name" => $record->box_name,
                "box_barcode" => $record->box_barcode,
                "box_weight" => $record->box_weight,
                "box_price" => $record->box_price,
                "status" => $record->status,
                "created_at" => $record->created_at,
                "customer" => [
                    "id" => optional($record->customer)->id,
                    "name" => optional($record->customer)->name ?? 'N/A',
                    "phone" => optional($record->customer)->phone ?? 'N/A',
                ]
            ];
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => ShipmentBox::count(),
            "iTotalDisplayRecords" => $sortedRecords->count(),
            "aaData" => $aaData
        ]);
    }

    public function getBoxes(Request $request, $shipmentId)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $length = $request->get("length");

        $order = $request->get('order');
        $columns = $request->get('columns');
        $search = $request->get('search');
        $columnIndex = $order[0]['column'];
        $columnName = $columns[$columnIndex]['data'];
        $columnSortOrder = $order[0]['dir'];
        $searchValue = $search['value'];

        // Fetch all data with customer relation (Eager Loading)
        $query = ShipmentBox::where('shipment_id', $shipmentId)->where('box_delivery_date',null)->with('customer');

        // Filtering by search
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('box_name', 'LIKE', "%$searchValue%")
                    ->orWhere('box_barcode', 'LIKE', "%$searchValue%")
                    ->orWhereHas('customer', function ($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%$searchValue%")
                            ->orWhere('phone', 'LIKE', "%$searchValue%");
                    });
            });
        }

        // Fetch all records first (Since SQLite cannot order by a joined table)
        $records = $query->get();

        // Apply sorting manually (For customer.name or customer.phone)
        $sortedRecords = $records->sort(function ($a, $b) use ($columnName, $columnSortOrder) {
            if ($columnName === 'customer.name') {
                $valueA = optional($a->customer)->name ?? '';
                $valueB = optional($b->customer)->name ?? '';
            } elseif ($columnName === 'customer.phone') {
                $valueA = optional($a->customer)->phone ?? '';
                $valueB = optional($b->customer)->phone ?? '';
            } else {
                $valueA = $a->$columnName;
                $valueB = $b->$columnName;
            }

            return $columnSortOrder === 'asc' ? strcmp($valueA, $valueB) : strcmp($valueB, $valueA);
        });

        // Paginate manually
        $paginatedRecords = $sortedRecords->slice($start, $length)->values();

        // Prepare response data
        $aaData = [];
        foreach ($paginatedRecords as $record) {
            $aaData[] = [
                "id" => $record->id,
                "box_name" => $record->box_name,
                "box_barcode" => $record->box_barcode,
                "box_weight" => $record->box_weight,
                "box_price" => $record->box_price,
                "status" => $record->status,
                "created_at" => $record->created_at,
                "customer" => [
                    "id" => optional($record->customer)->id,
                    "name" => optional($record->customer)->name ?? 'N/A',
                    "phone" => optional($record->customer)->phone ?? 'N/A',
                ]
            ];
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => ShipmentBox::where('shipment_id', $shipmentId)->count(),
            "iTotalDisplayRecords" => $sortedRecords->count(),
            "aaData" => $aaData
        ]);
    }
    public function getDBoxes(Request $request, $shipmentId)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $length = $request->get("length");

        $order = $request->get('order');
        $columns = $request->get('columns');
        $search = $request->get('search');
        $columnIndex = $order[0]['column'];
        $columnName = $columns[$columnIndex]['data'];
        $columnSortOrder = $order[0]['dir'];
        $searchValue = $search['value'];

        // Fetch all data with customer relation (Eager Loading)
        $query = ShipmentBox::where('shipment_id', $shipmentId)
            ->whereNotNull('delivered_at') // Ensures delivery date is not null
            ->with('customer');

        // Filtering by search
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('box_name', 'LIKE', "%$searchValue%")
                    ->orWhere('box_barcode', 'LIKE', "%$searchValue%")
                    ->orWhereHas('customer', function ($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%$searchValue%")
                            ->orWhere('phone', 'LIKE', "%$searchValue%");
                    });
            });
        }

        // Fetch all records first (Since SQLite cannot order by a joined table)
        $records = $query->get();

        // Apply sorting manually (For customer.name or customer.phone)
        $sortedRecords = $records->sort(function ($a, $b) use ($columnName, $columnSortOrder) {
            if ($columnName === 'customer.name') {
                $valueA = optional($a->customer)->name ?? '';
                $valueB = optional($b->customer)->name ?? '';
            } elseif ($columnName === 'customer.phone') {
                $valueA = optional($a->customer)->phone ?? '';
                $valueB = optional($b->customer)->phone ?? '';
            } else {
                $valueA = $a->$columnName;
                $valueB = $b->$columnName;
            }

            return $columnSortOrder === 'asc' ? strcmp($valueA, $valueB) : strcmp($valueB, $valueA);
        });

        // Paginate manually
        $paginatedRecords = $sortedRecords->slice($start, $length)->values();

        // Prepare response data
        $aaData = [];
        foreach ($paginatedRecords as $record) {
            $aaData[] = [
                "id" => $record->id,
                "box_name" => $record->box_name,
                "box_barcode" => $record->box_barcode,
                "box_weight" => $record->box_weight,
                "box_price" => $record->box_price,
                "status" => $record->status,
                "created_at" => $record->created_at,
                "customer" => [
                    "id" => optional($record->customer)->id,
                    "name" => optional($record->customer)->name ?? 'N/A',
                    "phone" => optional($record->customer)->phone ?? 'N/A',
                ]
            ];
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => ShipmentBox::where('shipment_id', $shipmentId)->count(),
            "iTotalDisplayRecords" => $sortedRecords->count(),
            "aaData" => $aaData
        ]);
    }




    public function index($shipmentId, Request $request)
    {
        $shipment = Shipments::findOrFail($shipmentId);
        $query = ShipmentBox::where('shipment_id', $shipmentId);

        // Filtering and searching
        if ($request->has('search') && !empty($request->search)) {
            $query->where('box_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('box_barcode', 'LIKE', '%' . $request->search . '%');
        }

        $boxes = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('shipments.box-index', compact('shipment', 'boxes'));
    }

    public function create($shipmentId)
    {
        $shipment = Shipments::findOrFail($shipmentId);
        $customers = Customer::orderBy('name')->get();

        return view('shipments.add-box', compact('shipment', 'customers'));
    }

    public function store(Request $request, $shipmentId)
    {
        $request->validate([
            'box_name' => 'required|string|max:255',
            'box_barcode' => 'required|string|unique:shipment_boxes,box_barcode',
            'box_weight' => 'required|numeric|min:0',
            'box_price' => 'required|numeric|min:0',
            'box_shipment_charge' => 'nullable|numeric|min:0',
            'box_shipping_date' => 'nullable|date',
            'box_delivery_date' => 'nullable|date|after_or_equal:box_shipping_date',
            'vat' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        ShipmentBox::create([
            'shipment_id' => $shipmentId,
            'customer_id' => $request->customer_id ?? null,
            'box_name' => $request->box_name,
            'box_barcode' => $request->box_barcode,
            'box_weight' => $request->box_weight,
            'box_price' => $request->box_price,
            'box_shipment_charge' => $request->box_shipment_charge,
            'box_shipping_date' => $request->box_shipping_date,
            'box_delivery_date' => $request->box_delivery_date,
            'vat' => $request->vat,
            'tax' => $request->tax,
        ]);

        return redirect()->route('shipments.boxes.index', $shipmentId)->with('success', 'Box added successfully!');
    }

    public function edit($shipmentId, $boxId)
    {
        $shipment = Shipments::findOrFail($shipmentId);
        $box = ShipmentBox::findOrFail($boxId);
        $customers = Customer::all(); // Fetch customers for dropdown

        return view('shipments.box-edit', compact('shipment', 'box', 'customers'));
    }

    public function update(Request $request, $shipmentId, $boxId)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'box_name' => 'required|string|max:255',
            'box_barcode' => 'nullable|string|max:255',
            'box_weight' => 'nullable|numeric|min:0',
            'box_price' => 'nullable|numeric|min:0',
            'box_shipment_charge' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0|max:100',
            'box_shipping_date' => 'nullable|date',
            'box_delivery_date' => 'nullable|date|after_or_equal:box_shipping_date',
        ]);

        $box = ShipmentBox::findOrFail($boxId);
        $box->update($request->all());

        return redirect()->route('shipments.boxes.index', $shipmentId)
            ->with('success', __('Box updated successfully.'));
    }
    public function bulkDeliver(Request $request, $shipmentId)
    {
        // Validate that 'box_ids' is required and is an array
        $request->validate([
            'box_ids' => 'required|array',
            'box_ids.*' => 'exists:shipment_boxes,id'
        ]);

        // Fetch the selected boxes
        $boxes = ShipmentBox::where('shipment_id', $shipmentId)
            ->whereIn('id', $request->box_ids)
            ->get();

        if ($boxes->isEmpty()) {
            return response()->json(['message' => 'No valid boxes found'], 400);
        }

        // Ensure all selected boxes belong to the same customer
        $customerId = $boxes->first()->customer_id;
        $differentCustomer = $boxes->pluck('customer_id')->unique()->count() > 1;

        if ($differentCustomer) {
            return response()->json(['message' => 'All selected boxes must belong to the same customer.'], 400);
        }

        // Update the status to 'delivered' for all selected boxes
        ShipmentBox::whereIn('id', $request->box_ids)->update(['status' => 'delivered']);

        return response()->json(['message' => 'Shipment boxes delivered successfully.']);
    }


    public function deliverBox(Request $request, $shipmentId, $boxId)
    {
        // Log the IDs
        Log::info("Delivering Box - Shipment ID: $shipmentId, Box ID: $boxId");

        // Validate that IDs exist
        if (!$boxId) {
            return response()->json(['message' => 'Invalid shipment or box ID.'], 400);
        }

        // Find the box
        $box = ShipmentBox::where('id', $boxId)->first();

        // Check if box exists
        if (!$box) {
            return response()->json(['message' => 'Shipment box not found.'], 404);
        }

        // Update status to delivered
        $box->update(['status' => 'delivered', 'delivered_at' => now()]);

        return response()->json(['message' => 'Box delivered successfully.'], 200);
    }



}
