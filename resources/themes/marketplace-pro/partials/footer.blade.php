
<footer class="footer-one">
    <div class="inner-footer">
        <div class="container">
            <div class="footer-top col-lg-12 col-xs-12">
                <div class="row">
                    <div class="tiva-html col-lg-4 col-md-12 col-xs-12">
                        <div class="block">
                            <div class="block-content">
                                <p class="logo-footer">
                                    <a href="{{ url('/') }}">
                                        <img style="max-width: 200px" src="{{ \Settings::get('site_logo') }}"
                                             alt="{{ \Settings::get('site_name', 'Corals') }}">
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="block">
                            <div class="block-content">
                                <ul>
                                    @foreach(Menus::getMenu('frontend_footer','active') as $menu)
                                        <li>
                                            <a href="{{ url($menu->url) }}">@if($menu->icon)<i
                                                        class="{{ $menu->icon }} fa-fw"></i>@endif {{ $menu->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tiva-html col-lg-4 col-md-6">
                        <div class="block m-top">
                            <div class="title-block">
                                @lang('corals-marketplace-pro::labels.template.contact.contact_us')
                            </div>
                            <div class="block-content">
                                <div class="contact-us">
                                    <div class="title-content">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        <span>@lang('corals-marketplace-pro::labels.template.contact.email') :</span>
                                    </div>
                                    <div class="content-contact mail-contact">
                                        <p>{{ \Settings::get('contact_form_email','support@corals.io') }}</p>
                                    </div>
                                </div>
                                <div class="contact-us">
                                    <div class="title-content">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                        <span>@lang('corals-marketplace-pro::labels.template.contact.hotline') :</span>
                                    </div>
                                    <div class="content-contact phone-contact">
                                        <p>{{ \Settings::get('contact_mobile','+970599593301') }}</p>
                                    </div>
                                </div>
                                <div class="contact-us">
                                    <div class="title-content">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <span>@lang('corals-marketplace-pro::labels.template.contact.open_hours') :</span>
                                    </div>
                                    <div class="content-contact hours-contact">
                                        <p>Monday - Sunday / 08.00AM - 19.00</p>
                                        <span>(Except Holidays)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tiva-modules col-lg-4 col-md-6">
                        <div class="block m-top">
                            <div class="block-content">
                                <div class="title-block">@lang('corals-marketplace-pro::labels.template.home.subscribe')</div>
                                <div class="block-newsletter">
                                    {!! Form::open( ['url' => url('utilities/newsletter/subscribe'),'method'=>'POST', 'class'=>'ajax-form','id'=>'subscribe']) !!}
                                    <div class="input-group form-group">
                                        <input class="form-control enteremail" name="email" id="subscribe-email"
                                               placeholder="@lang('corals-marketplace-pro::labels.template.home.your_email')"
                                               spellcheck="false" type="text">
                                        <span class="input-group-btn">
                                                    <button class="effect-btn btn btn-secondary "
                                                            type="submit" id="subscribe-button">
                                                        <span>@lang('corals-marketplace-pro::labels.template.home.subscribe')</span>
                                                    </button>
                                                </span>
                                        <input type="hidden" name="list_id">

                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="block m-top1">
                            <div class="block-content">
                                <div class="social-content">
                                    <div class="title-block">
                                        Follow us on
                                    </div>
                                    <div id="social-block">
                                        <div class="social">
                                            <ul class="list-inline mb-0 justify-content-end">
                                                @foreach(\Settings::get('social_links',[]) as $key=>$link)
                                                    <li class="list-inline-item mb-0">

                                                        <a class="social-button shape-circle sb-{{ $key }} sb-light-skin"
                                                           href="{{ $link }}"
                                                           target="_blank"><i class="fa fa-{{ $key }}"></i>
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
    <div id="tiva-copyright">
        <div class="container">
            <div class="row">
                <div class="text-center col-lg-12 ">
                        <span>
							 {!! \Settings::get('footer_text','') !!}
                        </span>
                </div>
            </div>
        </div>
    </div>
</footer>