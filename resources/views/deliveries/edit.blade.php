@extends('layouts.app')
@section('title', __('Edit') . ' ' . __('Delivery'))

@section('content')
    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="flex-grow-1">
            <x-page-title>@lang('Edit Delivery')</x-page-title>
        </div>
      </div>
    <x-card>
        @include('deliveries.partials.form')
    </x-card>

@endsection
