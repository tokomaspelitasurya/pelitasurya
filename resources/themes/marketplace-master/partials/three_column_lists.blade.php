<section class="container padding-bottom-2x">
    <div class="row">
        @if(!($topSellersProducts = \Shop::getTopSellers())->isEmpty())
            <div class="col-lg-12 col-md-6 col-sm-6">
                <div class="widget widget-featured-products">
                    <h3 class="widget-title">@lang('corals-marketplace-master::labels.partial.top_sellers')</h3>
                    <div class="owl-carousel"
                        data-owl-carousel="{ &quot;rtl&quot;: @if(\Language::isRTL()){{'true'}}@else {{'false'}}@endif,&quot;nav&quot;: false, &quot;dots&quot;: true, &quot;margin&quot;: 30, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1},&quot;576&quot;:{&quot;items&quot;:2},&quot;768&quot;:{&quot;items&quot;:3},&quot;991&quot;:{&quot;items&quot;:4},&quot;1200&quot;:{&quot;items&quot;:4}} }">
                        @foreach($topSellersProducts as $product)
                            @include('partials.product_grid_item',compact('product'))
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        @if(!($newArrivalsProducts = \Shop::getNewArrivals())->isEmpty())
            <div class="col-lg-12 col-md-6 col-sm-6">
                <div class="widget widget-featured-products">
                    <h3 class="widget-title">@lang('corals-marketplace-master::labels.partial.new_arrivals')</h3>
                    <div class="owl-carousel"
                        data-owl-carousel="{ &quot;rtl&quot;: @if(\Language::isRTL()){{'true'}}@else {{'false'}}@endif,&quot;nav&quot;: false, &quot;dots&quot;: true, &quot;margin&quot;: 30, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1},&quot;576&quot;:{&quot;items&quot;:2},&quot;768&quot;:{&quot;items&quot;:3},&quot;991&quot;:{&quot;items&quot;:4},&quot;1200&quot;:{&quot;items&quot;:4}} }">
                        @foreach($newArrivalsProducts as $product)
                            @include('partials.product_grid_item',compact('product'))
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        @if(!($bestRatedProducts = \Shop::getBestRated())->isEmpty())
            <div class="col-lg-12 col-md-6 col-sm-6">
                <div class="widget widget-featured-products">
                    <h3 class="widget-title">@lang('corals-marketplace-master::labels.partial.best_rated')</h3>
                    
                    <div class="owl-carousel"
                        data-owl-carousel="{ &quot;rtl&quot;: @if(\Language::isRTL()){{'true'}}@else {{'false'}}@endif,&quot;nav&quot;: false, &quot;dots&quot;: true, &quot;margin&quot;: 30, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1},&quot;576&quot;:{&quot;items&quot;:2},&quot;768&quot;:{&quot;items&quot;:3},&quot;991&quot;:{&quot;items&quot;:4},&quot;1200&quot;:{&quot;items&quot;:4}} }">
                        @foreach($bestRatedProducts as $product)
                            @include('partials.product_grid_item',compact('product'))
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>