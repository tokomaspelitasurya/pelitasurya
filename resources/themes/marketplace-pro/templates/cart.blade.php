@extends('layouts.public')

@section('content')
    @include('partials.page_header',['content' => '<h1><i class="fa fa-shopping-cart fa-fw"></i> Cart</h1>'])
    @php \Actions::do_action('pre_content', null, null) @endphp
    <div class="product-cart checkout-cart container">
        <div class="row" id="cart">
            <div id="content-wrapper" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 onecol">
                <section id="main">

                    @if (count(ShoppingCart::getAllInstanceItems()) > 0)
                        <div class="cart-grid row">
                            <div class="col-md-12 col-xs-12 check-info">
                                <h1 class="title-page d-inline-block">@lang('corals-marketplace-pro::labels.template.cart.product')</h1>
                                <a class="btn btn-sm float-right" href="{{ url('cart/empty') }}"
                                   title="Delete" data-action="post" data-page_action="site_reload">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                                <div class="cart-container">
                                    <div class="cart-overview js-cart">
                                        <ul class="cart-items">
                                            @foreach (\ShoppingCart::getAllInstanceItems() as $item)
                                                <li class="cart-item" id="item-{{$item->getHash()}}">
                                                    <div class="product-line-grid row justify-content-between">
                                                        <!--  product left content: image-->
                                                        <div class="product-line-grid-left col-md-2">
                                                            <span class="product-image media-middle">
                                                                <a href="{{ url('shop', [$item->id->product->slug]) }}">
                                                                    <img class="img-fluid" src="{{ $item->id->image }}"
                                                                         alt="SKU Image">
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <div class="product-line-grid-body col-md-6">
                                                            <div class="product-line-info">
                                                                <a class="label"
                                                                   href="{{ url('shop', [$item->id->product->slug]) }}"
                                                                   data-id_customization="0">
                                                                    {!! $item->id->product->name !!}
                                                                    [{{$item->id->code }}]
                                                                </a>
                                                                {!!  $item->id->presenter()['options']  !!}
                                                                {!! formatArrayAsLabels(\OrderManager::mapSelectedAttributes($item->product_options), 'success',null,true)    !!}
                                                            </div>
                                                            <div class="product-line-info product-price">
                                                                <span class="value"> {{ \Payments::currency( $item->price) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="product-line-grid-right text-center product-line-actions col-md-4">
                                                            <div class="row">
                                                                <div class="col-md-5 col qty">
                                                                    @if(!$item->id->isAvailable($item->qty))
                                                                        @lang('Marketplace::labels.shop.out_stock')
                                                                    @else
                                                                        @if($item->id->allowed_quantity != 1)
                                                                            <form action="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                                  method="POST"
                                                                                  class="ajax-form form-inline"
                                                                                  data-page_action="updateCart">
                                                                                {{ csrf_field() }}
                                                                                <div class="label">@lang('corals-marketplace-pro::labels.template.cart.quantity')</div>
                                                                                <div class="quantity">
                                                                                    <input step="1" min="1"
                                                                                           class="input-group form-control cart-quantity"
                                                                                           type="number"
                                                                                           name="quantity"
                                                                                           data-id="{{ $item->rowId }}"
                                                                                           {!! $item->id->allowed_quantity>0?('max="'.$item->id->allowed_quantity.'"'):'' !!} value="{{ $item->qty }}"/>
                                                                                    <span class="input-group-btn-vertical">

                                                                                <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                                   class="btn btn-touchspin js-touchspin bootstrap-touchspin-up"
                                                                                   title="Add One" data-action="post"
                                                                                   data-style="zoom-in"
                                                                                   data-page_action="updateCart"
                                                                                   data-request_data='@json(["action"=>"increaseQuantity"])'>
                                                                                    <i class="fa fa-fw fa-plus"></i>
                                                                                </a>
                                                                                <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                                   class="btn btn-touchspin js-touchspin bootstrap-touchspin-down"
                                                                                   title="Remove One"
                                                                                   data-action="post"
                                                                                   data-style="zoom-in"
                                                                                   data-request_data='@json(["action"=>"decreaseQuantity"])'
                                                                                   data-page_action="updateCart">
                                                                                    <i class="fa fa-fw fa-minus"></i>
                                                                                </a>
                                                                            </span>
                                                                                </div>
                                                                            </form>
                                                                        @else
                                                                            <input style="width:40px;text-align: center;"
                                                                                   value="1"
                                                                                   disabled/>

                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-5 col price">
                                                                    <div class="label">@lang('corals-marketplace-pro::labels.template.cart.subtotal')</div>
                                                                    <div class="text-center text-lg text-medium product-price"
                                                                         id="item-total-{{$item->getHash()}}">
                                                                        {{ \Payments::currency($item->qty * $item->price) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col text-xs-right align-self-end">
                                                                    <div class="cart-line-product-actions ">
                                                                        <a class="remove-from-cart" rel="nofollow"
                                                                           href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                           data-action="post" data-style="zoom-in"
                                                                           data-request_data='@json(["action"=>"removeItem"])'
                                                                           data-page_action="updateCart"
                                                                           data-toggle="tooltip"
                                                                           title="Remove item">
                                                                            <i class="fa fa-trash-o"
                                                                               aria-hidden="true"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <a href="{{ url('shop') }}" class="continue btn btn-primary pull-xs-right">
                                    @lang('corals-marketplace-pro::labels.template.cart.continue_shopping')
                                </a>
                                <span class="col-md-3 col price">
                                    <span class="label">@lang('corals-marketplace-pro::labels.template.cart.subtotal')</span>
                                    <span class="product-price total" id="total">
                                        {{ ShoppingCart::subTotalAllInstances() }}
                                    </span>
                                </span>
                                <a href="{{ url('checkout') }}" class="continue btn btn-primary float-right">
                                    @lang('corals-marketplace-pro::labels.template.cart.checkout')
                                </a>
                            </div>
                        </div>

                    @else
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h3>@lang('corals-marketplace-pro::labels.template.cart.have_no_item')</h3>

                                <a href="{{ url('shop') }}"
                                   class="btn btn-light">@lang('corals-marketplace-pro::labels.template.cart.continue_shopping')</a>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@stop

