<div class="dropdown-content">
    <div class="cart-content">

        @if(\ShoppingCart::CountAllInstances() > 0)
            <table>
                <tbody>
                @foreach($items = \ShoppingCart::getAllInstanceItems() as $item)
                    <tr  >
                        <td class="product-image">
                            <a href="{{ url('shop', [$item->id->product->slug]) }}">
                                <img src="{{ $item->id->image }}" alt="Product">
                            </a>
                        </td>
                        <td>
                            <div class="product-name">
                                <a href="{{ url('shop', [$item->id->product->slug]) }}">{!! $item->id->product->name !!}</a>
                            </div>
                            <div>
                                {!! $item->qty !!} x
                                <span class="product-price"
                                      id="item-total-{{$item->getHash()}}">{{ \Payments::currency($item->qty * $item->price) }}</span>
                            </div>
                        </td>
                        <td class="action">
                            <a class="remove" rel="nofollow"
                               href="{{ url('cart/quantity', [$item->getHash()]) }}"
                               data-action="post" data-style="zoom-in"
                               data-request_data='@json(["action"=>"removeItem"])'
                               data-page_action="updateCart"
                               data-toggle="tooltip"
                               title="Remove item">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="2">@lang('corals-marketplace-pro::labels.template.cart.subtotal')
                        :
                    </td>
                    <td id="total"> {{ \ShoppingCart::totalAllInstances() }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="d-flex justify-content-center">

                    </td>
                </tr>
                </tbody>
            </table>
            <div class="cart-button">
                <a href="{{ url('cart') }}"
                   title="View Cart">@lang('corals-marketplace-pro::labels.template.checkout.detail')</a>
                <a href="{{ url('checkout') }}"
                   title="Checkout">@lang('corals-marketplace-pro::labels.template.cart.checkout')</a>

            </div>
        @else

            <b>@lang('corals-marketplace-pro::labels.template.cart.have_no_item_cart')</b>
        @endif
    </div>
</div>
