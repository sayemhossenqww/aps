@extends('layouts.app')

@section('title', __('Edit Box in Shipment'))

@section('content')
    <x-page-title>@lang('Edit Box in Shipment')</x-page-title>
    <x-card>
        <form action="{{ route('shipments.boxes.update', [$shipment->id, $box->id]) }}" method="POST" enctype="multipart/form-data" role="form">
            @csrf
            @method('PUT')

            <!-- Customer Selection -->
            <div class="mb-3 row">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="customer_id" class="form-label">@lang('Select Customer')</label>
                    <select class="form-select select2 @error('customer_id') is-invalid @enderror" name="customer_id" id="customer_id">
                        <option value="">@lang('Select a Customer')</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $box->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Box Details -->
            <div class="mb-3 row">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="box_name" class="form-label">@lang('Box Name')</label>
                    <input type="text" name="box_name" class="form-control @error('box_name') is-invalid @enderror"
                        id="box_name" value="{{ old('box_name', $box->box_name) }}">
                    @error('box_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="box_barcode" class="form-label">@lang('Box Barcode')</label>
                    <input type="text" name="box_barcode" class="form-control @error('box_barcode') is-invalid @enderror"
                        id="box_barcode" value="{{ old('box_barcode', $box->box_barcode) }}">
                    @error('box_barcode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- QR Code Scanner -->
            <div class="mb-3">
                <label class="form-label">@lang('Scan Barcode')</label>
                <div id="my-qr-reader"></div>
            </div>

            <div class="mb-3 row">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="box_weight" class="form-label">@lang('Box Weight (kg)')</label>
                    <input type="number" name="box_weight" class="form-control @error('box_weight') is-invalid @enderror"
                        id="box_weight" step="0.01" value="{{ old('box_weight', $box->box_weight) }}">
                    @error('box_weight')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="box_price" class="form-label">@lang('Box Price ($)')</label>
                    <input type="number" name="box_price" class="form-control @error('box_price') is-invalid @enderror"
                        id="box_price" step="0.01" value="{{ old('box_price', $box->box_price) }}">
                    @error('box_price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="box_shipment_charge" class="form-label">@lang('Box Shipment Charge ($)')</label>
                    <input type="number" name="box_shipment_charge"
                        class="form-control @error('box_shipment_charge') is-invalid @enderror" id="box_shipment_charge"
                        step="0.01" value="{{ old('box_shipment_charge', $box->box_shipment_charge) }}">
                    @error('box_shipment_charge')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="vat" class="form-label">@lang('VAT (%)')</label>
                    <input type="number" name="vat" class="form-control @error('vat') is-invalid @enderror" id="vat"
                        step="0.01" value="{{ old('vat', $box->vat) }}">
                    @error('vat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="tax" class="form-label">@lang('Tax (%)')</label>
                    <input type="number" name="tax" class="form-control @error('tax') is-invalid @enderror" id="tax"
                        step="0.01" value="{{ old('tax', $box->tax) }}">
                    @error('tax')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="box_shipping_date" class="form-label">@lang('Box Shipping Date')</label>
                    <input type="date" name="box_shipping_date"
                        class="form-control @error('box_shipping_date') is-invalid @enderror" id="box_shipping_date"
                        value="{{ old('box_shipping_date', $box->box_shipping_date) }}">
                    @error('box_shipping_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- <div class="mb-3 row">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                    <label for="box_delivery_date" class="form-label">@lang('Box Delivery Date')</label>
                    <input type="date" name="box_delivery_date"
                        class="form-control @error('box_delivery_date') is-invalid @enderror" id="box_delivery_date"
                        value="{{ old('box_delivery_date', $box->box_delivery_date) }}">
                    @error('box_delivery_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div> --}}

            <div class="mb-3">
                <x-save-btn>
                    @lang('Update Box')
                </x-save-btn>
            </div>
        </form>
    </x-card>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    @push('script')
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "@lang('Search...')",
                    allowClear: true
                });
            });

            // QR Code Scanner
            function onScanSuccess(qrCodeMessage) {
                document.getElementById("box_barcode").value = qrCodeMessage;
            }

            document.addEventListener("DOMContentLoaded", function () {
                let htmlscanner = new Html5QrcodeScanner(
                    "my-qr-reader",
                    { fps: 10, qrbox: 250 }
                );
                htmlscanner.render(onScanSuccess);
            });
        </script>
    @endpush
@endsection
