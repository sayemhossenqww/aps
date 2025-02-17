<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Shipments;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $length = $request->get("length"); // Rows display per page

        $order = $request->get('order');
        $columns = $request->get('columns');
        $search = $request->get('search');
        $columnIndex = $order[0]['column']; // Column index
        $columnName = $columns[$columnIndex]['data']; // Column name
        $columnSortOrder = $order[0]['dir']; // asc or desc
        $searchValue = $search['value']; // Search value

        $totalRecords = Shipments::select('count(*) as allcount')->count();   // Total records
        $iTotalDisplayRecords = Shipments::select('count(*) as allcount')->search($searchValue)->count();

        // Fetch records
        $records = Shipments::search($searchValue)
            ->orderBy($columnName == 'sort_number' ? 'sort_order' : $columnName, $columnSortOrder)
            ->skip($start)
            ->take($length)
            ->get();

        $aaData = array();

        foreach ($records as $record) {
            $aaData[] = array(
                "id" => $record->id,
                "title" => $record->title,
                "date" => $record->date,
                "country" => $record->country,
                "is_active" => $record->is_active,
                "status" => $record->is_active,
                "status_badge_bg_color" => $record->status_badge_bg_color,
                "created_at" => $record->created_at,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $aaData
        );

        return response()->json($response);
    }

    public function show(Request $request)
    {

        $totalRecords = Shipments::select('count(*) as allcount')->count();   // Total records

        $response = array(
            "totalCount" => $totalRecords,
        );

        return response()->json($response);
    }

    public function getCustomersByShipment(Request $request)
    {
        $customers = Customer::whereHas('shipmentBoxes', function ($query) use ($request) {
            $query->where('shipment_id', $request->shipment_id);
        })->get(['id', 'name', 'mobile']);

        return response()->json($customers);
    }


}
