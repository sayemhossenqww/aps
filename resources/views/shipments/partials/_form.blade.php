<x-page-title>@lang('Add Package')</x-page-title>
            
<form action="{{ isset($shipment) ? route('shipments.update', $shipment): route('shipments.store') }}" 
   method="POST"
    enctype="multipart/form-data" role="form">
    @csrf
    @isset($shipment)
        @method('PUT')
    @endisset
  <div class="mb-3 row">
    <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
        <label for="name" class="form-label">@lang('Package Name')</label>
        <input type="text"
               name="title" 
               class="form-control @error('title') is-invalid @enderror" 
               id="title"
               value="{{ old('title', isset($shipment) ? $shipment->title : '') }}">
        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
        <label for="name" class="form-label">@lang('Package Date')</label>
        <input type="date" 
               name="date" 
               class="form-control @error('date') is-invalid @enderror" 
               id="date"
               value="{{ old('date', isset($shipment) ? $shipment->date : '') }}" required>
        @error('date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
  </div> 
  <div class="mb-3 row">
    <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
    <label for="name" class="form-label">@lang('Merchant Name')</label>
        
        @if(!empty($supplier_name))
            <input type="text" name="supplier_name[]" 
               class="form-control @error('supplier_name') is-invalid @enderror" 
               id="suplier_name"
               value="{{ old('supplier_name',  $supplier_name[0] ) }}">
       
        @else
        <input type="text" name="supplier_name" 
               class="form-control @error('supplier_name') is-invalid @enderror" 
               id="suplier_name"
        >
        @endif

        @error('supplier_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
          
  </div>
    <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
         <label for="name" class="form-label">@lang('Package Bar Code') </label>
        
               @if(!empty($bar_code))     
                <input type="text" name="bar_code" 
                    class="form-control @error('bar_code') is-invalid @enderror" 
                    id="result"
                    value="{{ old('bar_code', isset($bar_code) ? $bar_code[0] : '') }}">
                @else

              <input type="text" name="bar_code" 
               class="form-control @error('bar_code') is-invalid @enderror" 
               id="result">
      
        @endif

        @error('bar_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>      
</div> 
   
        <div class="mb-3 row">
        <div  class="mb-3 col-sm-6 col-md-6 col-lg-6">
            <div id="my-qr-reader"> 
            </div>
        </div>
     
     <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
   
     <label for="type" class="form-label">@lang('Package Country')</label> 
     <select class="form-select @error('country') is-invalid @enderror" 
             id="country" 
             name="country">
          
                @isset($shipment->country)
                    <option value="" @if (is_null($shipment->country))) selected @endif>@lang('Select an Option')</option>
                    <option value="China"  @if ($shipment->country== 'China')  selected @endif>@lang('China')</option>
                    <option value="Dubai"  @if ($shipment->country== 'Dubai')  selected @endif>@lang('Dubai')</option>
                    <option value="Europe" @if ($shipment->country== 'Europe') selected @endif>@lang('Europe')</option>
                    <option value="Turkey" @if ($shipment->country== 'Turkey') selected @endif>@lang('Turkey')</option>
                    <option value="USA"    @if ($shipment->country== 'USA')    selected @endif>@lang('USA')</option>
                    
                @else
                    <option value="" selected>@lang('Select an Option')</option>
                    <option value="China">@lang('China')</option>
                    <option value="Dubai">@lang('Dubai')</option>
                    <option value="Europe">@lang('Europe')</option>
                    <option value="Turkey">@lang('Turkey')</option>
                    <option value="USA">@lang('USA')</option>
                @endisset
            </select>
            @error('country')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

    <label for="type" class="form-label">@lang('Mode')</label>
   <div class="mb-3">
   
       <select class="form-select @error('mode') is-invalid @enderror" id="mode" name="mode">
                @if(!empty($shipment))
                        <option value="" @if (is_null($shipment)) selected @endif>@lang('Select an Option')</option>
                        <option value="By Plane" @if($shipment== 'By Plane') selected @endif>
                           @lang('By Plane')
                        </option>
                        <option value="By Sea" @if($shipment=='By Sea') selected @endif>
                           @lang('By Sea')
                        </option>
                        <option value="By Land" @if($shipment=='By Land') selected @endif>
                           @lang('By Land')
                        </option>
                 
               
                @else
                <option value="" selected>@lang('Select an Option')</option>
                <option value="By Plane">@lang('By Plane')</option>
                <option value="By Sea">@lang('By Sea')</option>
                <option value="By Sea">@lang('By Land')</option>
               
                @endif
                
       </select>
      @error('mode')
       <div class="invalid-feedback">
                    {{ $message }}
       </div>
      @enderror

   </div>
   </div>

        <div class="mb-3 row">
         <div class="mb-3 col-sm-6 col-md-6 col-lg-6">   
                <label for="name" class="form-label">@lang('Package Sum Weight')</label>
                <input type="text" 
                    name="sum_weight" 
                    class="form-control @error('sum_weight') is-invalid @enderror" 
                    id="weight" value="{{ old('sum_weight', isset($shipment) ? $shipment->sum_weight : '') }}">
                @error('sum_weight')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
          </div>
        
        <div class="mb-3 col-sm-6 col-md-6 col-lg-6">   
          <div class="mb-3">
        <label for="name" class="form-label">@lang('Package Total Price')</label>
        <input type="text" 
               name="total_price" 
               class="form-control @error('total_price') is-invalid @enderror" 
               id="total_price" 
               value="{{ old('total_price', isset($shipment) ? $shipment->total_price : '') }}">
        @error('total_price')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
       </div> 
     
   
    </div>
        </div> 
        <div class="mb-3">
        <x-save-btn>
            @lang(isset($shipments) ? 'Update Package' : 'Save Package')
        </x-save-btn>
 </form>
 <br/>
 @include('shipments.partials.sub_package',$shipment_all) 

<script src="https://unpkg.com/html5-qrcode"></script>
@push('script')
<script>
     
    
        // When scan is successful fucntion will produce data
            function onScanSuccess(qrCodeMessage) {
            document.getElementById("result").innerHTML =
                '<span class="result">' + qrCodeMessage + "</span>";
            }

            function domReady(fn) {
                if (
                    document.readyState === "complete" ||
                    document.readyState === "interactive"
                ) {
                    setTimeout(fn, 1000);
                } else {
                    document.addEventListener("DOMContentLoaded", fn);
                }
            }

            domReady(function () {

                // If found you qr code
                function onScanSuccess(decodeText, decodeResult) {
                    //alert("You Qr is : " + decodeText, decodeResult);
                    var result =  decodeText ;
                    document.getElementById("result").value = result;
                }

                let htmlscanner = new Html5QrcodeScanner(
                    "my-qr-reader",
                    { fps: 10, qrbos: 250 }
                );
                htmlscanner.render(onScanSuccess);
            });

    var tbody = document.querySelector('#main-supplier-clone');
    var newItemBtn = document.querySelector('#btn-new-item-shipment');

    var html='<div id="main-supplier"> <div class="mb-3 row"><div class="mb-3 col-sm-6 col-md-6 col-lg-6"> <label for="name" class="form-label">Merchant Name </label>'+
             '<input type="text" name="supplier_name[]" class="form-control" id="suplier_name"></div>'; 
          html+=' <div class="mb-3 mb-3 col-sm-6 col-md-6 col-lg-6"><label for="name" class="form-label"> Bar Code</label> <input type="text" name="bar_code[]" class="form-control"></div></div>'; 
          html+='<div class="mb-3 row"><div class="mb-3 col-sm-6 col-md-6 col-lg-6"> <label for="name" class="form-label">Weight (In Tone)</label><input type="text" name="weight[]" class="form-control "  id="weight"></div>';  
                   
          html+='<div class="mb-3 col-sm-6 col-md-6 col-lg-6"> <label for="name" class="form-label">Price </label><input type="text" name="price[]" class="form-control "  id="price"></div></div>';  
                
       
    newItemBtn.addEventListener('click', function() {
        tbody.insertAdjacentHTML(
                'beforeend',
                html + '<button type="button" class="btn btn-link p-0 text-danger btn-remove"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hero-icon-sm"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></button></div></div>'
            );

    });
    document.addEventListener('click', function(event) {
            if (event.target.matches('.btn-remove, .btn-remove *')) {
                event.target.closest('#main-supplier').remove();
            }
        }, false);

    document.addEventListener('click', function(event) {
            if (event.target.matches('.btn-remove, .btn-remove *')) {
                event.target.closest('#main-supplier').remove();
            }
        }, false);
     
        
</script>  
@endpush
