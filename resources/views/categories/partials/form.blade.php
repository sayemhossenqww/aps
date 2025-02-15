<form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}" method="POST"
    enctype="multipart/form-data" role="form">
    @csrf
    @isset($category)
        @method('PUT')
    @endisset

    
   <div class="mb-3 row">
  
   <div class="col-sm-12 col-md-12 col-lg-12">
   <label for="type" class="form-label">@lang('Package')</label> 
      <select class="form-select @error('Package') is-invalid @enderror" id="package" name="shipment_id" onchange="onChangeSelection()">
        <option value="" selected>@lang('Select an Option')</option>
        @foreach($shipments as $ship)
        
         <option value="{{$ship->id}}" {{(isset($category) && category->shipment_id==$ship->id)?'selected':''}} >{{$ship->title}}</option>
        @endforeach     
       </select>
            @error('package')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror  
   </div> 
   </div>
    <div class="mb-3">
        <label for="name" class="form-label">@lang('Merchant Name')</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
            value="{{ old('name', isset($category) ? $category->name : '') }}">
        @error('merchant_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="type" class="form-label">@lang('Type')</label>
        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" id="type"
            value="{{ old('type', isset($category) ? $category->type : '') }}">
        @error('type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
  
 <div class="mb-3">
        <label for="name" class="form-label">@lang('Sum Weight')</label>
        <input type="text" name="weight" 
               class="form-control @error('weight') is-invalid @enderror" 
               id="weight" value="{{ old('weight', isset($category) ? $category->weight : '') }}">
        @error('weight')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
 
    
    <div class="mb-3">
        <label for="name" class="form-label">@lang('Shipment Date')</label>
        <input type="date" name="date_of_shipment" class="form-control @error('date_of_shipment') is-invalid @enderror" id="date_of_shipment"
            value="{{ old('name', isset($category) ? $category->date_of_shipment : '') }}">
        @error('date_of_shipment')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    @include('categories.partials.shipment')
    <div class="mb-3">
        <label for="name" class="form-label">@lang('Bar Code')</label>
        <input type="text" name="bar_code" 
               class="form-control @error('bar_code') is-invalid @enderror" 
               id="bar_code" value="{{ old('bar_code', isset($category) ? $category->bar_code : '') }}">
        @error('bar_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
 
    <x-number-input label="Sort Order" name="sort_order"
        value="{{ old('sort_order', isset($category) ? $category->sort_order : '') }}" />


    <div class="mb-3">
        <label for="status" class="form-label">@lang('status.text')</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
            @isset($category)
                <option value="available" @if ($category->is_active) selected @endif>@lang('Visible')</option>
                <option value="unavailable" @if (!$category->is_active) selected @endif>@lang('Hidden')</option>
            @else
                <option value="available">@lang('Visible')</option>
                <option value="unavailable">@lang('Hidden')</option>
            @endisset
        </select>
        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @else
            <div id="categoryStatusHelp" class="form-text">
                @lang('If set to hidden, all items of this category, will not appear in the POS.')
            </div>
        @enderror
    </div>   
     
    <div class="mb-5">
        <label for="image" class="form-label">@lang('Image')</label>
        <input class="form-control @error('image') is-invalid @enderror" name="image" type="file" id="image-input"
            accept="image/png, image/jpeg">
        @error('image')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-5 text-center">
        <div class="mb-3">
            @isset($category)
                <img src="{{ $category->image_url }}" height="250"
                    class="object-fit-cover border rounded  @if (!$category->image_path) d-none @endif"
                    alt="{{ $category->name }}" id="image-preview">
            @else
                <img src="#" height="250" class="object-fit-cover border rounded  d-none" alt="image"
                    id="image-preview">
            @endisset
        </div>
        @isset($category)
            @if ($category->image_path)
                <div class="mb-3">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#removeCategoryImageModal">
                        @lang('Remove Image')
                    </button>
                </div>
            @endif
        @endisset

    </div>

    <div class="mb-3">
        <x-save-btn>
            @lang(isset($category) ? 'Update Category' : 'Save Category')
        </x-save-btn>
    </div>
</form>

@isset($category)
    <div class="modal" id="removeCategoryImageModal" tabindex="-1" aria-labelledby="removeCategoryImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="removeCategoryImageModalLabel">@lang('Are you sure?')</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('categories.image.destroy', $category) }}" method="POST" role="form">
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        @lang('You cannot undo this action!')
                    </div>
                    <div class="row p-0 m-0 border-top">
                        <div class="col-6 p-0">
                            <button type="button"
                                class="btn btn-link w-100 m-0 text-danger btn-lg text-decoration-none rounded-0 border-end"
                                data-bs-dismiss="modal">@lang('Cancel')</button>
                        </div>
                        <div class="col-6 p-0">
                            <button type="submit"
                                class="btn btn-link w-100 m-0 text-black btn-lg text-decoration-none rounded-0 border-start">
                                @lang('Remove Image')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endisset
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("image-input").onchange = function() {
                previewImage(this, document.getElementById("image-preview"))
            };
        });
    
        function onChangeSelection(){
       
            
            const host = window.location.host;
            var package = document.getElementById("package").value;

            let xhr = new XMLHttpRequest();

           let url ='https://'+ host+'/sub-shipments/'+ package;
           
           
           xhr.open("GET", url, true);
           xhr.onreadystatechange = function () {
             var data=xhr.responseText;
              if(data){
            var jsonResponse = JSON.parse(data);
             document.getElementById("weight").value = jsonResponse["data"].sum_weight;
             document.getElementById("shipping_price").value = jsonResponse["data"].total_price;
             document.getElementById("name").value = jsonResponse["data"].supplier_name;
             document.getElementById("bar_code").value = jsonResponse["data"].bar_code;
           //  document.getElementById("shipping_country").text=jsonResponse["data"].supplier_name;
             document.getElementById("shipping_country")[0].innerHTML = jsonResponse["data"].country;
             document.getElementById("shipping_mode")[0].innerHTML = jsonResponse["data"].mode;
             document.getElementById("date_of_shipment").value = jsonResponse["data"].date;
             

              }  

            }
          
            xhr.send();


       }


    </script>
@endpush
