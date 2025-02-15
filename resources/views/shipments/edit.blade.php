@extends('layouts.app')
@section('title', __('Edit') . ' ' . __('Shipment'))

@section('content')
    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="flex-grow-1">
            <x-page-title>@lang('Edit Shipment')</x-page-title>
        </div>
      </div>
    <x-card>
        @include('shipments.partials.form')
    </x-card>

@endsection
