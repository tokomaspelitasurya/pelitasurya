@extends('layouts.public')

@section('css')
    <style type="text/css">
        .sku-item {
            position: relative;
        }

        .sku-item .badge {
            font-size: 75%;
            /*width: 100%;*/
        }

        .img-radio {
            max-height: 100px;
            margin: 5px auto;
        }

        .middle {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .selected-radio > img {
            opacity: .45;
        }

        .selected-radio .middle {
            opacity: 1;
        }
    </style>
@endsection
@section('content')
    @php \Actions::do_action('pre_content',$product, null) @endphp
    <div class="container">
        <div class="content">
            <div class="row">
                @include('partials.shop_filter')
                <div class="col-sm-8 col-lg-9 col-md-9">
                    <div class="main-product-detail">
                        <h2>{{ $product->name }}</h2>
                        <div class="product-single row">
                            <div class="product-detail col-xs-12 col-md-5 col-sm-5">
                                <div class="page-content" id="content">
                                    <div class="images-container">
                                        <div class="js-qv-mask mask tab-content border">
                                            @if(!($medias = $product->getMedia('marketplace-product-gallery'))->isEmpty())
                                                @foreach($medias as $media)
                                                    @if($loop->first)
                                                        <div id="gItem_{{ $media->id }}"
                                                             class="tab-pane fade active in show">
                                                            <img src="{{$media->getFullUrl()}}" alt="img">
                                                        </div>
                                                    @else
                                                        <div id="gItem_{{ $media->id }}"
                                                             class="tab-pane fade in show">
                                                            <img src="{{$media->getFullUrl()}}" alt="img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="layer hidden-sm-down" data-toggle="modal"
                                                     data-target="#product-modal">
                                                    <i class="fa fa-expand"></i>
                                                </div>
                                            @else
                                                <div class="text-center text-muted">
                                                    <small>@lang('corals-marketplace-pro::labels.template.product_single.image_unavailable')</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($medias)
                                        <ul class="product-tab nav nav-tabs d-flex">
                                            @foreach($medias as $media)
                                                <li class="{{ $media->getCustomProperty('featured', false)?'active':'' }} col">
                                                    <a href="#gItem_{{ $media->id }}" data-hash="gItem_{{ $media->id }}"
                                                       data-toggle="tab">
                                                        <img src="{{ $media->getFullUrl() }}" alt="img">
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <div class="modal fade" id="product-modal" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div class="modal-body">
                                                        <div class="product-detail">
                                                            <div>
                                                                <div class="images-container">
                                                                    <div class="js-qv-mask mask tab-content">
                                                                        @foreach($medias as $media)
                                                                            @if($loop->first)
                                                                                <div id="modal_{{ $media->id }}"
                                                                                     class="tab-pane fade active in show">
                                                                                    <img src="{{$media->getFullUrl()}}"
                                                                                         alt="img">
                                                                                </div>
                                                                            @else
                                                                                <div id="modal_{{ $media->id }}"
                                                                                     class="tab-pane fade in show">
                                                                                    <img src="{{$media->getFullUrl()}}"
                                                                                         alt="img">
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                    <ul class="product-tab nav nav-tabs">
                                                                        @foreach($medias as $media)
                                                                            <li class="{{ $media->getCustomProperty('featured', false)?'active':'' }}">
                                                                                <a href="#modal_{{ $media->id }}"
                                                                                   data-toggle="tab"
                                                                                   aria-expanded="true"
                                                                                   class="">
                                                                                    <img src="{{ $media->getUrl() }}"
                                                                                         alt="img">
                                                                                </a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-info col-xs-12 col-md-7 col-sm-7">
                                <div class="detail-description">
                                    <div class="price-del">
                                        <span class="price">{!! $product->price !!}</span>
                                    </div>
                                    <p class="description">
                                        {!! $product->caption !!}
                                    </p>
                                    {!! Form::open(['url'=>'cart/'.$product->hashed_id.'/add-to-cart','method'=>'POST','class'=> 'ajax-form','data-page_action'=>"updateCart"]) !!}

                                    @if($product->globalOptions->count())
                                        {!! $product->renderProductOptions('global_options',null, ['class'=>'form-control-sm product-option']) !!}
                                    @endif
                                    @if(!$product->isSimple)
                                        @foreach($product->activeSKU as $sku)
                                            @if($loop->index%4 == 0)
                                                <div class="d-flex flex-wrap">
                                                    @endif
                                                    <div class="text-center sku-item mr-2" style="width: 240px;">
                                                        <img src="{{ asset($sku->image) }}"
                                                             class="img-responsive img-radio mx-auto img-fluid">
                                                        <div class="middle">
                                                            <div class="text text-success"><i
                                                                        class="fa fa-check fa-4x"></i></div>
                                                        </div>
                                                        <div>
                                                            {!! !$sku->options->isEmpty() ? $sku->presenter()['options']:'' !!}
                                                        </div>
                                                        @if($sku->stock_status == "in_stock")
                                                            <button type="button"
                                                                    class="btn btn-block btn-sm btn-default btn-secondary btn-radio m-t-5">
                                                                <b>{!! $sku->discount?'<del class="text-muted">'.\Payments::currency($sku->regular_price).'</del>':''  !!} {!! \Payments::currency($sku->price) !!}</b>
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                    class="btn btn-block btn-sm m-t-5 btn-danger">
                                                                <b> @lang('corals-marketplace-pro::labels.partial.out_stock')</b>
                                                            </button>
                                                        @endif
                                                        <input type="checkbox" id="left-item" name="sku_hash"
                                                               value="{{ $sku->hashed_id }}"
                                                               class="hidden d-none disable-icheck"/>
                                                    </div>
                                                    @if($lastLoop = $loop->index%4 == 3)
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(!$lastLoop)</div>@endif
                                <div class="form-group">
                                    <span data-name="sku_hash"></span>
                                </div>
                                @else
                                    <input type="hidden" name="sku_hash"
                                           value="{{ $product->activeSKU(true)->hashed_id }}"/>
                                @endif
                                <div class="has-border cart-area">
                                    <div class="product-quantity">
                                        <div class="qty">
                                            <div class="input-group">
                                                <div class="quantity">
                                                    <span class="control-label"></span>
                                                    <input type="number" name="quantity" id="quantity_wanted" value="1"
                                                           min="1" class="input-group form-control">
                                                    <span class="input-group-btn-vertical">
                                                <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-up"
                                                        type="button">
                                                    +
                                                </button>
                                                <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-down"
                                                        type="button">
                                                    -
                                                </button>
                                            </span>

                                                </div>
                                                <span class="add">
                                           @if($product->external_url)
                                                        <a href="{{ $product->external_url }}" target="_blank"
                                                           class="btn btn-success"
                                                           title="@lang('corals-marketplace-pro::labels.template.product_single.buy_product')">
<i class="fa fa-fw fa-cart-plus"
   aria-hidden="true"></i> @lang('corals-marketplace-pro::labels.template.product_single.buy_product')
</a>
                                                    @elseif($product->isSimple && $product->activeSKU(true)->stock_status != "in_stock")
                                                        <a href="#" class="btn btn-sm btn-outline-danger"
                                                           title="Out Of Stock">
@lang('corals-marketplace-pro::labels.partial.out_stock')
</a>
                                                    @else
                                                        {!! CoralsForm::button('corals-marketplace-pro::labels.partial.add_to_cart',
                                                        ['class'=>'btn add-to-cart btn-sm btn-primary'], 'submit') !!}
                                                    @endif
                                                    @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                                                        @include('partials.components.wishlist',['wishlist'=> $product->inWishList() ])
                                                    @endif
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <p class="product-minimal-quantity">
                                    </p>
                                </div>
                                {!! Form::close() !!}
                                <div class="d-flex2 has-border">
                                    <div class="btn-group">
                                        @include('partials.components.social_share',['url'=> URL::current() , 'title'=>$product->name ])
                                    </div>
                                </div>
                                <div class="content2" id="product-additionals">
                                    <p><i class="fa fa-list-alt"
                                          aria-hidden="true"></i>&nbsp;@lang('corals-marketplace-pro::labels.template.product_single.category')
                                        :
                                        <span class="content2">
                @foreach($product->activeCategories as $category)
                                                <a href="{{ url('shop?category='.$category->slug) }}">{{ $category->name }}</a>
                                            @endforeach
                                </span>
                                    </p>
                                    <p><i
                                                class="fa fa-home"></i>&nbsp;@lang('corals-marketplace-pro::labels.template.product_single.store')
                                        :
                                        <span class="content2">
                                    <a
                                            href="{{ $product->store->getUrl() }}">{{ $product->store->name }}</a>
                                </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="review">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#description" class="active show">
                                    @lang('corals-marketplace-pro::labels.template.product_single.description')
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#review">
                                    @lang('corals-marketplace-pro::labels.template.product_single.reviews',['count'=>$product->ratings->count()])
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="description" class="tab-pane fade in active show">
                                <p>
                                    {!! $product->description !!}
                                </p>
                            </div>
                            @if(\Settings::get('marketplace_rating_enable',true) ==  true)
                                <br>
                                @include('partials.tabs.reviews',['reviews'=>$product->ratings])
                            @endif
                        </div>
                    </div>
                    <div class="related">
                        <div class="title-tab-content  text-center">
                            <div class="title-product justify-content-start">
                                <h2>@lang('corals-marketplace-pro::labels.partial.featured_products')</h2>
                            </div>
                        </div>
                        @php $products = \Shop::getFeaturedProducts();@endphp
                        @if(!$products->isEmpty())
                            <div class="section best-sellers col-lg-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="groupproductlist">
                                            <div class="row d-flex align-items-center">
                                                <!-- column 4 -->
                                                <div class="block-content col-lg-12 flex-12">
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade in active show">
                                                            <div class="category-product-index owl-carousel owl-theme owl-loaded owl-drag">
                                                                @foreach($products as $product)
                                                                    <div class="item text-center">
                                                                        <div class="product-miniature js-product-miniature item-one first-item">
                                                                            <div class="thumbnail-container">
                                                                                <a href="{{ url('shop/'.$product->slug) }}">
                                                                                    <img class="img-fluid image-cover"
                                                                                         src="{{ $product->image }}"
                                                                                         alt="{{ $product->name }}">
                                                                                    <img class="img-fluid image-secondary"
                                                                                         src="{{ $product->non_featured_image }}"
                                                                                         alt="{{ $product->name }}">
                                                                                </a>
                                                                            </div>
                                                                            <div class="product-description">
                                                                                <div class="product-groups">
                                                                                    <div class="product-title">
                                                                                        <a href="{{ url('shop/'.$product->slug) }}">{{ $product->name }}</a>
                                                                                    </div>
                                                                                    <div class="product-group-price">
                                                                                        <div class="product-price-and-shipping">
                                                                                            <span class="price">{!! $product->price !!}</span>
                                                                                            @if($product->discount)
                                                                                                <del class="regular-price">{{ \Payments::currency($product->regular_price) }}</del>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="product-buttons d-flex justify-content-center">
                                                                                    <form class="formAddToCart">
                                                                                        @if(!$product->isSimple || $product->attributes()->count())
                                                                                            @if($product->external_url)
                                                                                                <a href="{{ $product->external_url }}"
                                                                                                   target="_blank"
                                                                                                   class="add-to-cart"
                                                                                                   title="Buy Product">
                                                                                                    @lang('corals-marketplace-pro::labels.partial.buy_product')
                                                                                                </a>
                                                                                            @else
                                                                                                <a href="{{ url('shop/'.$product->slug) }}"
                                                                                                   class="add-to-cart"
                                                                                                   data-toggle="tooltip"
                                                                                                   title="@lang('corals-marketplace-pro::labels.partial.add_cart')">
                                                                                                    <i class="fa fa-shopping-cart"
                                                                                                       aria-hidden="true"></i>
                                                                                                </a>
                                                                                            @endif
                                                                                        @else
                                                                                            @php $sku = $product->activeSKU(true); @endphp
                                                                                            @if($sku->stock_status == "in_stock")
                                                                                                @if($product->external_url)
                                                                                                    <a href="{{ $product->external_url }}"
                                                                                                       target="_blank"
                                                                                                       class="add-to-cart"
                                                                                                       title="Buy Product">
                                                                                                        <i class="fa fa-shopping-cart"
                                                                                                           aria-hidden="true"></i>
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a href="{{ url('cart/'.$product->hashed_id.'/add-to-cart/'.$sku->hashed_id) }}"
                                                                                                       data-action="post"
                                                                                                       data-page_action="updateCart"
                                                                                                       data-toggle="tooltip"
                                                                                                       title="@lang('corals-marketplace-pro::labels.partial.add_cart')"
                                                                                                       class="add-to-cart">
                                                                                                        <i class="fa fa-shopping-cart"
                                                                                                           aria-hidden="true"></i>
                                                                                                    </a>
                                                                                                @endif
                                                                                            @else
                                                                                                <a href="#"
                                                                                                   class="btn btn-sm btn-outline-danger"
                                                                                                   title="Out Of Stock">
                                                                                                    @lang('corals-marketplace-pro::labels.partial.out_stock')
                                                                                                </a>
                                                                                            @endif
                                                                                        @endif

                                                                                    </form>
                                                                                    @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                                                                                        @include('partials.components.wishlist',['wishlist'=> $product->inWishList()])
                                                                                    @endif
                                                                                    <a href="{{ url('shop/'.$product->slug) }}"
                                                                                       class="quick-view hidden-sm-down"
                                                                                       data-toggle="tooltip"
                                                                                       title="@lang('corals-marketplace-pro::labels.partial.show_details')"
                                                                                       data-link-action="quickview">
                                                                                        <i class="fa fa-eye"
                                                                                           aria-hidden="true"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop