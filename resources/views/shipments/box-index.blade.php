@extends('layouts.app')

@section('title', __('Manage Shipment Boxes'))

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <x-page-title>@lang('Manage Boxes in') {{ $shipment->title }}</x-page-title>
        <a href="{{ route('shipments.boxes.create', $shipment->id) }}" class="btn btn-primary">
            <x-heroicon-o-plus class="hero-icon-sm me-2 text-white" />
            @lang('Add Box to Shipment')
        </a>
    </div>

    <x-card>
        <x-table id="box-table">
            <x-thead>
                <tr>
                    <x-th>@lang('Customer Name')</x-th>
                    <x-th>@lang('Customer Phone')</x-th>
                    <x-th>@lang('Box Name')</x-th>
                    <x-th>@lang('Barcode')</x-th>
                    <x-th>@lang('Weight (kg)')</x-th>
                    <x-th>@lang('Price ($)')</x-th>
                    <x-th>@lang('Actions')</x-th>
                </tr>
            </x-thead>
        </x-table>
    </x-card>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            let dataTable = $('#box-table').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: '{{ asset("datatables/i18n/{$settings->lang}.json") }}',
                },
                ajax: {
                    url: "{{ route('api.shipments.boxes.index', $shipment->id) }}",
                    dataSrc: 'aaData'
                },
                columns: [
                    { 
                        data: "customer.name",
                        defaultContent: "@lang('N/A')",
                        searchable: true
                    },
                    { 
                        data: "customer.phone",
                        defaultContent: "@lang('N/A')",
                        searchable: true
                    },
                    { data: "box_name", searchable: true },
                    { data: "box_barcode", searchable: true },
                    { data: "box_weight", searchable: true },
                    { data: "box_price", searchable: true },
                    {
                        orderable: false,
                        data: function (data, type, dataToSet) {
                            var editUrl = "{{ route('shipments.boxes.edit', [':shipmentId', ':boxId']) }}"
                                .replace(':shipmentId', '{{ $shipment->id }}')
                                .replace(':boxId', data.id);

                            var deleteUrl = "{{ route('shipments.boxes.destroy', [':shipment_id', ':box_id']) }}"
                                .replace(':shipment_id', '{{ $shipment->id }}')
                                .replace(':box_id', data.id);

                            return `<div class="dropdown d-flex">
                                        <button class="btn btn-link text-black p-0" type="button" id="dropdownMenuButton${data.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <x-heroicon-o-ellipsis-horizontal class="hero-icon" />
                                        </button>
                                        <x-dropdown-menu class="dropdown-menu-end" aria-labelledby="dropdownMenuButton${data.id}">
                                            <x-dropdown-item href="${editUrl}">
                                                <x-heroicon-o-pencil class="hero-icon-sm me-2 text-gray-400" />
                                                @lang('Edit')
                                            </x-dropdown-item>
                                            <x-dropdown-item href="#">
                                                <form action="${deleteUrl}" method="POST" id="form-${data.id}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-link p-0 m-0 w-100 text-start text-decoration-none text-danger align-items-center btn-sm" onclick="submitDeleteForm('${data.id}')">
                                                        <x-heroicon-o-trash class="hero-icon-sm me-2 text-gray-400" />
                                                        @lang('Delete')
                                                    </button>
                                                </form>
                                            </x-dropdown-item>
                                        </x-dropdown-menu>
                                    </div>`;
                        }
                    },
                ]
            });
        });

        function submitDeleteForm(id) {
            const form = document.querySelector(`#form-${id}`);
            Swal.fire({
                title: "@lang('Are you sure?')",
                text: "@lang('This action cannot be undone!')",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "@lang('Yes, delete it!')"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush
