@php
    $countries = [
        'China',
        'Dubai',
        'Europe',
        'Turkey',
        'USA'
    ];
    
@endphp

<div class="mb-3 row">
   <div class="mb-3 col-sm-12 col-md-12 col-lg-12">
   <label for="type" class="form-label">@lang('Country')</label> 
   <select class="form-select @error('shipping_country') is-invalid @enderror" id="shipping_country" name="shipping_country">
                @isset($category)
                    <option value="" @if (is_null($shipment_country)) selected @endif>@lang('Select an Option')</option>
                    <option value="China"  @if ($shipment_country== 'China')  selected @endif>@lang('China')</option>
                    <option value="Dubai"  @if ($shipment_country== 'Dubai')  selected @endif>@lang('Dubai')</option>
                    <option value="Europe" @if ($shipment_country== 'Europe') selected @endif>@lang('Europe')</option>
                    <option value="Turkey" @if ($shipment_country== 'Turkey') selected @endif>@lang('Turkey')</option>
                    <option value="USA"    @if ($shipment_country== 'USA')    selected @endif>@lang('USA')</option>
                    
                @else
                    <option value="" selected>@lang('Select an Option')</option>
                    <option value="China">@lang('China')</option>
                    <option value="Dubai">@lang('Dubai')</option>
                    <option value="Europe">@lang('Europe')</option>
                    <option value="Turkey">@lang('Turkey')</option>
                    <option value="USA">@lang('USA')</option>
                @endisset
            </select>
            @error('shipping_country')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

   </div>
   <label for="type" class="form-label">@lang('Mode')</label>
   <div class="mb-3 col-sm-12 col-md-12 col-lg-12">
   
       <select class="form-select @error('shipping_mode') is-invalid @enderror" id="shipping_mode" name="shipping_mode">
                @if(!empty($shipment_mode))
                        <option value="" @if (is_null($shipment_mode)) selected @endif>@lang('Select an Option')</option>
                        <option value="By Plane" @if($shipment_mode== 'By Plane') selected @endif>
                           @lang('By Plane')
                        </option>
                        <option value="By Sea" @if($shipment_mode=='By Sea') selected @endif>
                           @lang('By Sea')
                        </option>
                        <option value="By Land" @if($shipment_mode=='By Land') selected @endif>
                           @lang('By Land')
                        </option>
                 
               
                @else
                <option value="" selected>@lang('Select an Option')</option>
                <option value="By Plane">@lang('By Plane')</option>
                <option value="By Sea">@lang('By Sea')</option>
                <option value="By Sea">@lang('By Land')</option>
               
                @endif
                
   </select>
   @error('shipment_mode')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
    @enderror

   </div>
   <label for="type" class="form-label">@lang('Price')</label>
   <div class="mb-3 col-sm-12 col-md-12 col-lg-12">
     <div class="input-group">
              <span class="input-group-text">$</span>  
              @if(!empty($shipment_price))
              <input type="number" 
                     name="shipping_price" 
                     class="form-control shipping_price" 
                     id="shipping_price" 
                     
                     min="0" 
                     step="any"
                     value="{{$shipment_price}}">
              @else
                    <input type="number" 
                     name="shipping_price" 
                     id="shipping_price" 
                     class="form-control shipping_price" 
                     step="any"
                     >

              @endif
                      
            </div>
         
   </div>
</div>      

