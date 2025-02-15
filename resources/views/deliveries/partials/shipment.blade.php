@php
    $countries = [
        'China',
        'Dubai',
        'Europe',
        'Turkey',
        'USA'
    ];
    
@endphp

  
@for($i=0; $i<5; $i++)

<div class="mb-3 row">
   <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
   <select class="form-select @error('shipping_country') is-invalid @enderror" id="shipping_country" name="shipping_country[]">
                @isset($category)
                    <option value="" @if (is_null($shipment_country[$i])) selected @endif>@lang('Select an Option')</option>
                    <option value="China"  @if ($shipment_country[$i]== 'China')  selected @endif>@lang('China')</option>
                    <option value="Dubai"  @if ($shipment_country[$i]== 'Dubai')  selected @endif>@lang('Dubai')</option>
                    <option value="Europe" @if ($shipment_country[$i]== 'Europe') selected @endif>@lang('Europe')</option>
                    <option value="Turkey" @if ($shipment_country[$i]== 'Turkey') selected @endif>@lang('Turkey')</option>
                    <option value="USA"    @if ($shipment_country[$i]== 'USA')    selected @endif>@lang('USA')</option>
                    
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
   <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
   
       <select class="form-select @error('shipping_mode') is-invalid @enderror" id="shipping_mode" name="shipping_mode[]">
                @if(!empty($shipment_mode[$i]))
                        <option value="" @if (is_null($shipment_mode[$i])) selected @endif>@lang('Select an Option')</option>
                        <option value="By Plane" @if($shipment_mode[$i]== 'By Plane') selected @endif>
                           @lang('By Plane')
                        </option>
                        <option value="By Sea" @if($shipment_mode[$i]=='By Sea') selected @endif>
                           @lang('By Sea')
                        </option>
                 
               
                @else
                <option value="" selected>@lang('Select an Option')</option>
                <option value="By Plane">@lang('By Plane')</option>
                <option value="By Sea">@lang('By Sea')</option>
               
                @endif
                
   </select>
   @error('shipment_mode')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
    @enderror


   </div>
   <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
   <div class="input-group">
              <span class="input-group-text">$</span>  
              @if(!empty($shipment_price[$i]))
              <input type="number" 
                     name="shipping_price[]" 
                     class="form-control shipping_price" 
                     min="0" 
                     step="any"
                     oninput="formatPrice(this)" 
                     value="{{$shipment_price[$i]}}">
              @else
                    <input type="number" 
                     name="shipping_price[]" 
                     class="form-control shipping_price" 
                     step="any"
                     oninput="formatPrice(this)" >

              @endif
                      
            </div>
         
   </div>
</div>      
@endfor  
