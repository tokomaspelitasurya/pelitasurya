{!! Form::open( ['url' => url($urlPrefix.'checkout/step/shipping-method'),'method'=>'POST','class'=>'ajax-form','id'=>'checkoutForm']) !!}
<div class="row">
    <div class="col-md-8 shipping-options">
        <h4>@lang('Marketplace::labels.settings.shipping.select_method')</h4>
        @if($shipping_methods)
            @foreach($shipping_methods as $store_id =>$store_shipping_methods)
                @php
                    $store = \Corals\Modules\Marketplace\Models\Store::find($store_id);

                @endphp

                @if($store_shipping_methods)


                    {!! CoralsForm::radio('selected_shipping_methods['.$store_id.']', trans( 'Marketplace::labels.checkout.shipping_options',['store'=>$store->name,'products'=>implode(',',$shippable_items[$store_id])])    ,true, $store_shipping_methods ) !!}

                @else
                    <div class="form-group">
                        <span data-name="selected_shipping_methods[{{$store_id}}]"></span>
                        <p class="d-block label label-warning badge badge-warning p-15" data-><i
                                    class="fa fa-info-circle"></i> @lang('Marketplace::labels.settings.shipping.no_available_shipping',['store'=>$store->name,'products'=>implode(',',$shippable_items[$store_id])])
                        </p>
                    </div>

                @endif
            @endforeach
        @else
            <div class="form-group">
                <span data-name="selected_shipping_methods"></span>
            </div>
            <span class="label label-warning" data-><i
                        class="fa fa-info-circle"></i> @lang('Marketplace::labels.settings.shipping.no_available_shipping')</span>
        @endif

    </div>
</div>

{!! Form::close() !!}
