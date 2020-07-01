@extends('layouts.auth')

@section('content')
    <div class="wrap-banner">
        <nav class="breadcrumb-bg">
            <div class="container no-index">
                <div class="breadcrumb">
                    <ol>
                        <li>
                            <a href="#">
                                <span>@lang('corals-marketplace-pro::labels.auth.login_register')</span>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </nav>

    </div>
    <div class="user-login ">
        <div id="wrapper-site">
            <div id="content-wrapper" class="full-width">
                <div id="main">
                    <div class="container">
                        <h1 class="text-center title-page">@lang('corals-marketplace-pro::labels.auth.login')</h1>
                        <div class="login-form">
                            <form id="customer-form" method="post" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <div class="p-2">
                                    @php \Actions::do_action('pre_login_form') @endphp
                                </div>
                                <h3 class="text-center">@lang('corals-marketplace-pro::labels.auth.sign_in_start_session')</h3>
                                <div class="row margin-bottom-1x justify-content-center">
                                    @if(config('services.facebook.client_id'))


                                        <div class="col"><a
                                                    class="btn btn-sm btn-block facebook-btn"
                                                    href="{{ route('auth.social', 'facebook') }}"><i
                                                        class="fa fa-facebook"></i>&nbsp;@lang('corals-marketplace-pro::labels.auth.sign_in_facebook')
                                            </a></div>
                                    @endif
                                    @if(config('services.twitter.client_id'))

                                        <div class="col"><a
                                                    class="btn btn-sm btn-block twitter-btn"
                                                    href="{{ route('auth.social', 'twitter') }}"><i
                                                        class="fa fa-twitter"></i>&nbsp;@lang('corals-marketplace-pro::labels.auth.sign_in_twitter')
                                            </a></div>
                                    @endif
                                    @if(config('services.google.client_id'))
                                        <div class="col"><a
                                                    class="btn btn-sm btn-block google-btn"
                                                    href="{{ route('auth.social', 'google') }}"><i
                                                        class="fa fa-gplus"></i>@lang('corals-marketplace-pro::labels.auth.sign_in_google')
                                            </a></div>
                                    @endif

                                </div>

                                <h4 class="margin-bottom-1x text-center">@lang('corals-marketplace-pro::labels.auth.or_using_form')</h4>

                                <div class="form-group text-center">
                                    @if(session('confirmation_user_id'))
                                        <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                                    @endif
                                </div>
                                <div class="form-group input-group d-block has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="input-icon">
                                        <i class="lni-user"></i>
                                        <input type="text" id="email" class="form-control" name="email"
                                               placeholder="@lang('User::attributes.user.email')"
                                               value="{{ old('email') }}" autofocus><span
                                                class="input-group-addon"><i class="icon-mail"></i></span>
                                    </div>
                                    @if ($errors->has('email'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif

                                </div>
                                <div class="form-group input-group d-block has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="input-icon">
                                        <i class="lni-lock"></i>
                                        <input type="password" name="password" class="form-control" id="password"
                                               placeholder="@lang('User::attributes.user.password')"><span
                                                class="input-group-addon"><i class="icon-lock"></i></span>
                                    </div>
                                    @if ($errors->has('password'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group mb-3 has-feedback">
                                    <div class="checkbox">
                                        <input type="checkbox"
                                               name="remember" {{ old('remember') ? 'checked' : '' }}/>
                                        @lang('corals-marketplace-pro::labels.auth.remember_me')
                                    </div>
                                </div>
                                <div class="text-center text-sm-right">
                                    <div class="row">
                                        <div class="col">

                                            <button type="submit"
                                                    class="btn btn-primary btn-block margin-bottom-none float-left">@lang('corals-marketplace-pro::labels.auth.login')</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <a href="{{ route('register') }}"
                                               class="btn btn-primary bg-blue margin-bottom-none btn-block">@lang('corals-marketplace-pro::labels.auth.register')</a>

                                        </div>
                                        <div class="col">
                                            <a href="{{ route('password.request') }}"
                                               class="btn bg-yellow btn-social btn-primary btn-block">
                                                <span class="fa fa-question"></span>
                                                @lang('corals-marketplace-pro::labels.auth.forget_password')
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@component('components.modal',['id'=>'terms','header'=>\Settings::get('site_name').' Terms and policy'])
    {!! \Settings::get('terms_and_policy') !!}
@endcomponent