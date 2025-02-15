@extends('layouts.app')

@section('title', __('Manage Shipment Boxes'))

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <x-page-title>@lang('Manage Shipment Boxes')</x-page-title>
    </div>

    <x-card>
        <div class="row mb-3">
            <div class="mb-3 col-md-3">
                <label for="shipment_id" class="form-label">@lang('Select Shipment')</label>
                <select class="form-select select2" name="shipment_id" id="shipment_id">
                    <option value="">@lang('Select a Shipment')</option>
                    @foreach($shipments as $shipment)
                        <option value="{{ $shipment->id }}">{{ $shipment->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 col-md-3">
                <label for="customer_id" class="form-label">@lang('Select Customer')</label>
                <select class="form-select select2" name="customer_id" id="customer_id" disabled>
                    <option value="">@lang('Select a Customer')</option>
                </select>
            </div>

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
                    <x-th>@lang('Box Name')</x-th>
                    <x-th>@lang('Barcode')</x-th>
                    <x-th>@lang('Zone')</x-th>
                    <x-th>@lang('Weight (kg)')</x-th>
                    <x-th>@lang('Price ($)')</x-th>
                    <x-th>@lang('Actions')</x-th>
                </tr>
            </x-thead>
        </x-table>
    </x-card>

    <!-- Delivery Confirmation Modal -->
    <div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveryModalLabel">@lang('Confirm Delivery')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>@lang('Please select the zone before confirming delivery.')</p>
                    <select class="form-select" name="selected_zone" id="selected_zone">
                        <option value="">@lang('Select a Zone')</option>
                        <option value="north">North</option>
                        <option value="south">South</option>
                        <option value="east">East</option>
                        <option value="west">West</option>
                    </select>
                    <input type="hidden" id="selected_box_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="button" class="btn btn-success" id="confirm-delivery-btn">@lang('Confirm Delivery')</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "@lang('Select...')",
            allowClear: true
        });

        let dataTable = $('#box-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('api.boxes.index') }}",
                data: function (d) {
                    d.shipment_id = $('#shipment_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.zone = $('#zone').val();
                },
                dataSrc: 'aaData'
            },
            columns: [
                { data: "customer.name", defaultContent: "@lang('N/A')" },
                { data: "box_name" },
                { data: "box_barcode" },
                { data: "zone" },
                { data: "box_weight" },
                { data: "box_price" },
                {
                    orderable: false,
                    data: function (data) {
                        return `<button class="btn btn-primary btn-sm deliver-btn" data-id="${data.id}">@lang('Deliver Box')</button>`;
                    }
                }
            ]
        });

        $('#shipment_id').change(function () {
            let shipmentId = $(this).val();
            $('#customer_id').prop('disabled', shipmentId ? false : true).html('<option value="">@lang("Select a Customer")</option>');

            if (shipmentId) {
                $.ajax({
                    url: "{{ route('api.customers.byShipment') }}",
                    type: "GET",
                    data: { shipment_id: shipmentId },
                    success: function (data) {
                        $.each(data, function (index, customer) {
                            $('#customer_id').append(`<option value="${customer.id}">${customer.name} (${customer.mobile})</option>`);
                        });
                    }
                });
            }
            dataTable.ajax.reload();
        });

        $(document).on('click', '.deliver-btn', function () {
            let boxId = $(this).data('id');
            $('#selected_box_id').val(boxId);
            $('#deliveryModal').modal('show');
        });

        $('#confirm-delivery-btn').on('click', function () {
            let boxId = $('#selected_box_id').val();
            let selectedZone = $('#selected_zone').val();

            if (!selectedZone) {
                alert("@lang('Please select a zone')");
                return;
            }

            $.ajax({
                url: "{{ route('shipments.boxes.deliver') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    shipment_id: $('#shipment_id').val(),
                    customer_id: $('#customer_id').val(),
                    delivery_id: $('#delivery_id').val(),
                    zone: selectedZone,
                    boxes: [boxId]
                },
                success: function (response) {
                    alert("@lang('Box delivered successfully!')");
                    $('#deliveryModal').modal('hide');
                    dataTable.ajax.reload();
                },
                error: function () {
                    alert("@lang('Something went wrong, please try again.')");
                }
            });
        });
    });
</script>
@endpush
