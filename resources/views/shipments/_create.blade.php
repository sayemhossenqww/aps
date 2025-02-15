@extends('layouts.app')
@section('title', __('Create') . ' ' . __('Shipment'))

@section('content')
    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="flex-grow-1">
            <x-page-title>@lang('Add Shipment')</x-page-title>
            
            <!--<div class="col-auto mt-3">
                    <form action="{{ route('shipments.scan-qr') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file"  name="qr_code"  required>
                        <button class="btn btn-success" >Import Shipment</button>
                    </form>
                </div>-->
        </div>
        <x-back-btn href="{{ route('shipments') }}" />
    </div>
    <x-card>
        @include('shipments.partials.form')
    </x-card>
@endsection
