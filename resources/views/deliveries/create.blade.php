@extends('layouts.app')
@section('title', __('Create') . ' ' . __('Delivery'))

@section('content')
    <div class="d-flex align-items-center justify-content-center mb-3">
        <div class="flex-grow-1">
            <x-page-title>@lang('New Delivery')</x-page-title>
        </div>
        <x-back-btn href="{{ route('deliveries') }}" />
    </div>
    <x-card>
        @include('deliveries.partials.form')
    </x-card>
@endsection
