@extends('layouts.app')

@section('title', __('Manage Shipment Boxes'))

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <x-page-title>@lang('Manage Shipment Boxes')</x-page-title>
    </div>

    <x-card>
        <!-- Filters Section -->
        <div class="row mb-3">
            <!-- Shipment Selection -->
            <div class="mb-3 col-md-3">
                <label for="shipment_id" class="form-label">@lang('Select Shipment')</label>
                <select class="form-select select2" name="shipment_id" id="shipment_id">
                    <option value="">@lang('Select a Shipment')</option>
                    @foreach($shipments as $shipment)
                        <option value="{{ $shipment->id }}">{{ $shipment->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Customer Selection -->
            <div class="mb-3 col-md-3">
                <label for="customer_id" class="form-label">@lang('Select Customer')</label>
                <select class="form-select select2" name="customer_id" id="customer_id" disabled>
                    <option value="">@lang('Select a Customer')</option>
                </select>
            </div>

            <!-- Zone Selection -->
            <div class="mb-3 col-md-3">
                <label for="zone" class="form-label">@lang('Select Zone')</label>
                <select class="form-select" name="zone" id="zone">
                    <option value="">@lang('Select a Zone')</option>
                    <option value="north">North</option>
                    <option value="south">South</option>
                    <option value="east">East</option>
                    <option value="west">West</option>
                </select>
            </div>

            <!-- Delivery Selection -->
            <div class="mb-3 col-md-3">
                <label for="delivery_id" class="form-label">@lang('Select Delivery')</label>
                <select class="form-select select2" name="delivery_id" id="delivery_id">
                    <option value="">@lang('Select a Delivery')</option>
                    @foreach($deliveries as $delivery)
                        <option value="{{ $delivery->id }}">{{ $delivery->name }} ({{ $delivery->phone }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Shipment Boxes Table -->
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
            $('.select2').select2({
                placeholder: "@lang('Select...')",
                allowClear: true
            });

            // Initialize DataTable
            let dataTable = $('#box-table').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: '{{ asset("datatables/i18n/{$settings->lang}.json") }}',
                },
                ajax: {
                    url: "{{ route('api.boxes.index') }}",
                    data: function (d) {
                        d.shipment_id = $('#shipment_id').val();
                        d.customer_id = $('#customer_id').val();
                    },
                    dataSrc: 'aaData'
                },
                columns: [
                    { data: "customer.name", defaultContent: "@lang('N/A')", searchable: true },
                    { data: "customer.phone", defaultContent: "@lang('N/A')", searchable: true },
                    { data: "box_name", searchable: true },
                    { data: "box_barcode", searchable: true },
                    { data: "box_weight", searchable: true },
                    { data: "box_price", searchable: true },
                    {
                        orderable: false,
                        data: function (data) {
                            let editUrl = "{{ route('shipments.boxes.edit', [':shipmentId', ':boxId']) }}"
                                .replace(':shipmentId', data.shipment_id)
                                .replace(':boxId', data.id);

                            let deleteUrl = "{{ route('shipments.boxes.destroy', [':shipment_id', ':box_id']) }}"
                                .replace(':shipment_id', data.shipment_id)
                                .replace(':boxId', data.id);

                            let deliverUrl = "{{ route('shipments.boxes.deliver', [':shipmentId', ':boxId']) }}"
                                .replace(':shipmentId', data.shipment_id)
                                .replace(':boxId', data.id);

                            return `<div class="dropdown d-flex">
                    <button class="btn btn-link text-black p-0" type="button" id="dropdownMenuButton${data.id}" data-bs-toggle="dropdown" aria-expanded="false">
                        <x-heroicon-o-ellipsis-horizontal class="hero-icon" />
                    </button>
                    <x-dropdown-menu class="dropdown-menu-end" aria-labelledby="dropdownMenuButton${data.id}">
                        <x-dropdown-item href="${editUrl}">
                            <x-heroicon-o-pencil class="hero-icon-sm me-2 text-gray-400" />
                            @lang('Edit')
                        </x-dropdown-item>
                        <x-dropdown-item href="#" onclick="confirmDeliverBox('${data.id}', '${deliverUrl}')">
                            <x-heroicon-o-truck class="hero-icon-sm me-2 text-gray-400" />
                            @lang('Deliver Box')
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
                    }

                ]
            });

            // Reload DataTable when shipment or customer changes
            $('#shipment_id, #customer_id').change(function () {
                dataTable.ajax.reload();
            });

            // Load customers based on shipment selection
            $('#shipment_id').change(function () {
                let shipmentId = $(this).val();
                if (shipmentId) {
                    $('#customer_id').prop('disabled', false);
                    $.ajax({
                        url: "{{ route('api.customers.byShipment') }}",
                        type: "GET",
                        data: { shipment_id: shipmentId },
                        success: function (data) {
                            console.log(data);
                            $('#customer_id').html('<option value="">@lang("Select a Customer")</option>');
                            $.each(data, function (index, customer) {
                                $('#customer_id').append(`<option value="${customer.id}">${customer.name} (${customer.mobile})</option>`);
                            });
                        }
                    });
                } else {
                    $('#customer_id').prop('disabled', true).html('<option value="">@lang("Select a Customer")</option>');
                }
                dataTable.ajax.reload();
            });
        });


        function confirmDeliverBox(boxId, deliverUrl) {
            console.log("Delivering Box ID:", boxId);
            console.log("Deliver URL:", deliverUrl);

            Swal.fire({
                title: "@lang('Confirm Delivery?')",
                text: "@lang('Are you sure you want to mark this box as delivered?')",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "@lang('Yes, Deliver it!')"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deliverUrl,
                        type: "POST",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (response) {
                            Swal.fire("@lang('Success!')", "@lang('The box has been marked as delivered.')", "success");
                            $('#box-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            console.error(xhr);
                            Swal.fire("@lang('Error!')", xhr.responseJSON?.message || "@lang('Something went wrong, please try again.')", "error");
                        }
                    });
                }
            });
        }

    </script>
@endpush