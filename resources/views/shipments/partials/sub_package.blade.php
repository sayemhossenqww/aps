<x-page-title>@lang('Add A Package Contain Sub Boxes')</x-page-title>
<form method="POST" action="{{isset($id) ? route('shipments.sub-package-update', $id):route('shipments.sub-package') }}"> 
     @csrf
     
    @isset($id)
        @method('PUT')
    @endisset
    
  <div class="mb-3 row">
     <div class="mb-3 col-sm-6 col-md-12 col-lg-12">
   
     <label for="type" class="form-label">@lang('Package')</label> 
     
     <select class="form-select @error('shipment_id') is-invalid @enderror" id="shipment_id" 
             name="shipment_id"> 
        @foreach($shipment_all as $ship)
           <option value="{{$ship->id}}">{{$ship->title}}</option>
          
        @endforeach       
      </select>
       @error('shipment_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
      @enderror

   </div>
   <div >
     <div class="mb-3 row">
          <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Merchant Name')</label>
                
                @if(!empty($supplier_name))
                    <input type="text" name="supplier_name[]" 
                    class="form-control @error('supplier_name') is-invalid @enderror" 
                    id="suplier_name"
                    value="{{ old('supplier_name',  $supplier_name[0] ) }}">
            
                @else
                <input type="text" name="supplier_name[]" 
                    class="form-control @error('supplier_name') is-invalid @enderror" 
                    id="suplier_name" >
               
            
                @endif

                @error('supplier_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3 mb-3 col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Package Bar Code') </label>
                
                    @if(!empty($bar_code))     
                        <input type="text" name="bar_code[]" 
                            class="form-control @error('bar_code') is-invalid @enderror" 
                            id="result"
                            value="{{ old('bar_code', isset($bar_code) ? $bar_code[0] : '') }}">
                        @else

                    <input type="text" name="bar_code[]" 
                    class="form-control @error('bar_code') is-invalid @enderror" 
                    >
            
                    @endif

                @error('bar_code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
      </div>


     </div>
     <div class="mb-3 row">
          <div class="mb-3 col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Weight') (In KG)</label>
                
                @if(!empty($weight))
                    <input type="text" name="weight[]" 
                    class="form-control @error('weight') is-invalid @enderror" 
                    id="weight"
                    value="{{ old('weight',  $weight[0] ) }}">
            
                @else
                <input type="text" name="weight[]" 
                    class="form-control @error('weight') is-invalid @enderror" 
                    id="weight" >
               
            
                @endif

                @error('weight')
                    <div class="invalid-feedback">
                        {{ $weight }}
                    </div>
                @enderror
            </div>
            <div class="mb-3 mb-3 col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Price') </label>
                
                    @if(!empty($price))     
                        <input type="text" name="price[]" 
                            class="form-control @error('bar_code') is-invalid @enderror" 
                            id="result"
                            value="{{ old('price', isset($price) ? $price[0] : '') }}">
                        @else

                    <input type="text" name="price[]" 
                    class="form-control @error('price') is-invalid @enderror" 
                    >
                    @endif

                @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
       </div>
     </div>
     <div class="mb-3" >
     <button type="button" class="btn btn-primary btn-xs" id="btn-new-item-shipment">+ @lang('Add New Box')</button>
    <br/>
   </div>
  </div> 
  
 <div id="main-supplier-clone">
    
       
  </div>
  <div id="detail">

    @if(is_array($weight) )
        @for($i=1; $i<count($weight); $i++)
        <div class="mb-3 row">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Merchant Name')</label>
                @if(isset($supplier_name[$i]))
                    <input type="text" name="supplier_name[]" 
                    class="form-control @error('supplier_name') is-invalid @enderror" 
                    id="suplier_name"
                    value="{{ old('supplier_name',  isset($supplier_name[$i])?$supplier_name[$i]:'') }}">
            
                    @else
                <input type="text" name="supplier_name[]" 
                    class="form-control @error('supplier_name') is-invalid @enderror" 
                    id="suplier_name">
                    @endif
                
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Package Bar Code')</label>
                @if(isset($bar_code[$i]))
                    <input type="text" name="bar_code[]" 
                    class="form-control @error('bar_code') is-invalid @enderror" 
                    id="bar_code"
                    value="{{ old('bar_code',  isset($bar_code[$i])?$bar_code[$i]:'') }}">
            
                    @else
                <input type="text" name="supplier_name[]" 
                    class="form-control @error('bar_code') is-invalid @enderror" 
                    id="bar_code">
                    @endif
                
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Weight (In Tone)')</label>
                @if(isset($weight[$i]))
                    <input type="text" name="weight[]" 
                    class="form-control @error('weight') is-invalid @enderror" 
                    id="weight"
                    value="{{ old('weight',  isset($weight[$i])?$weight[$i]:'') }}">
            
                    @else
                <input type="text" name="weight[]" 
                    class="form-control @error('weight') is-invalid @enderror" 
                    id="weight">
                    @endif
                
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
                <label for="name" class="form-label">@lang('Price')</label>
                @if(isset($price[$i]))
                    <input type="text" name="price[]" 
                    class="form-control @error('price') is-invalid @enderror" 
                    id="suplier_name"
                    value="{{ old('price',  isset($price[$i])?$price[$i]:'') }}">
            
                    @else
                <input type="text" name="price[]" 
                    class="form-control @error('price') is-invalid @enderror" 
                    id="price">
                    @endif
                
            </div>
        </div>
        

        @endfor
    @endif
    
  
  
  </div>

    <div class="mb-3">
        <x-save-btn>
            @lang(isset($shipments) ? 'Update' : 'Save')
        </x-save-btn>
   </div>   
    
</form>