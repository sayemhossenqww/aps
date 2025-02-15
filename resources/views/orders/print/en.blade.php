<html lang="en" dir="ltr">

<head>
    <title>{{ $order->number }} </title>
    <style>
        @page { size: auto;  margin: 0mm; }
        .table1 {
            border-collapse: collapse;
            word-break: break-all;
        }
        .table1>tbody:before{
            content: "-";
            display: block;
            line-height: 10px;
            color: transparent;
        }
        tr {
            min-height: 50px;
        }
        .table1>tbody>tr>td {
            border: 1px solid black;
            text-align: center;
            font-size: 1.2rem;
            padding: 5px;
            word-break: break-all;
        }
        .table2 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            word-break: break-all;
        }
        .table2>tbody>tr>td {
            border: 1px solid black;
            font-size: 1.5rem;
            width: 30px;
            padding: 5px;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <!-- QR Code -->
    <!-- <div style="position: absolute; top: 10px; right: 10px;">
        <img src="{{ asset('images/qr_code.png') }}" alt="QR Code" style="width: 100px; height: 100px;">
    </div> -->

    <div style="margin-bottom: 0.2rem;text-align: center !important;">
        @if ($settings->logo)
            <div style="padding-right: 1rem;padding-left: 1rem;margin-bottom: 0.5rem">
                {!! $settings->logo  !!}
            </div>
        @else
            @if ($settings->storeName)
                <div style="font-size: 1.50rem;">{{ $settings->storeName }}</div>
            @endif
        @endif

        @if ($settings->storeAddress)
            <div style="font-size: 1rem;">{{ $settings->storeAddress }}</div>
        @endif
        @if ($settings->storePhone)
            <div style="font-size: 0.875em;">{{ $settings->storePhone }}</div>
        @endif
        @if ($settings->storeWebsite)
            <div style="font-size: 0.875em;">{{ $settings->storeWebsite }}</div>
        @endif
        @if ($settings->storeEmail)
            <div style="font-size: 0.875em;">{{ $settings->storeEmail }}</div>
        @endif
    </div>
    <div style="margin: 1.5rem">
        @foreach ($order->order_details as $detail)
            <div>{{ $detail->product->name }}</div>
            <div style="display: flex">
                <div> {{ $detail->quantity }}* {{ $detail->view_price }}</div>
                <div style="margin-left: auto">{{ $detail->view_total }}</div>
            </div>
        @endforeach
    </div>

    @if ($order->discount > 0)
        <div style="display: flex;margin: 1.5rem">
            <div>DISCOUNT</div>
            <div style="margin-left: auto">{{ $order->discount_view }}</div>
        </div>
    @endif
    @if ($order->is_delivery)
        @if ($order->delivery_charge > 0)
            <div style="display: flex;margin: 1.5rem">
                <div>@lang('DELIVERY CHARGE')</div>
                <div style="margin-left: auto">{{ $order->delivery_charge_view }}</div>
            </div>
        @endif
    @endif
    @if ($order->tax_rate > 0)
        @if ($order->tax_type == 'add')
            <div style="display: flex;margin: 1.5rem">
                <div>VAT</div>
                <div style="margin-left: auto">{{ $order->tax_rate }}%</div>
            </div>
        @else
            <div style="display: flex;margin: 1.5rem">
                <div>SUBTOTAL</div>
                <div style="margin-left: auto">{{ $order->subtotal_view }}</div>
            </div>
            <div style="display: flex;margin: 1.5rem">
                <div>TAX.AMOUNT</div>
                <div style="margin-left: auto">{{ $order->tax_amount_view }}</div>
            </div>
            <div style="display: flex;margin: 1.5rem">
                <div>VAT {{ $order->tax_rate }}%</div>
                <div style="margin-left: auto">{{ $order->vat_view }}</div>
            </div>
        @endif
    @endif
    <div style="font-weight: 700;margin: 1.5rem">
        <div>TOTAL</div>
        <div style="display: flex;">
            <div style="margin-left: auto">
                {{ $order->total_view }} 
            </div>
        </div>
        <div style="display: flex;">
            <div style="margin-left: auto">
                {{ $order->receipt_exchange_rate }}
            </div>
        </div>
    </div>
    <div style="text-align: center !important;margin-bottom: 0.5rem !important;">
        <span style="margin-right: 1rem">{{ $order->date_view }}</span> <span>{{ $order->time_view }}</span>
    </div>
    <div style="text-align: center !important;margin-bottom: 0.5rem !important;">
        {{ $order->number }}
    </div>
    <div style="display: flex;align-items: center !important;justify-content: center;margin-bottom: 0.5rem !important;">
        {!! DNS1D::getBarcodeSVG($order->number, 'C128', 2, 30, 'black', false) !!}
    </div>
    @if ($settings->storeAdditionalInfo)
        <div style="font-size: 0.875em;text-align: center !important;">
            {!! nl2br($settings->storeAdditionalInfo) !!}
        </div>
    @endif
</body>

</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    
        window.print();
    });
</script>
