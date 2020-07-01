@extends('layouts.public')

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
                        <h1 class="text-center title-page">@lang('corals-marketplace-pro::labels.auth.no_account_register')</h1>
                        <form method="POST" action="{{ route('register') }}"
                              class="ajax-form login-box js-customer-form"
                              id="customer-form">
                            {{ csrf_field() }}
                            <div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <div class="form-group" id="first-name-col">
                                            <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <input type="text" name="name"
                                                       class="form-control"
                                                       placeholder="@lang('User::attributes.user.name')"
                                                       value="{{ old('name') }}" autofocus/>
                                                @if ($errors->has('name'))
                                                    <div class="help-block">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group" id="last-name-col">
                                            <div class="{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                                <input type="text" name="last_name"
                                                       class="form-control"
                                                       placeholder="@lang('User::attributes.user.last_name')"
                                                       value="{{ old('last_name') }}" autofocus/>
                                                <span class="glyphicon glyphicon-user form-control-feedback"></span>

                                                @if ($errors->has('last_name'))
                                                    <div class="help-block">
                                                        <strong>{{ $errors->first('last_name') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <input type="email" name="email"
                                                       class="form-control"
                                                       placeholder="@lang('User::attributes.user.email')"
                                                       value="{{ old('email') }}"/>
                                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                                                @if ($errors->has('email'))
                                                    <div class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <div class="input-group js-parent-focus">
                                                    <input type="password" name="password" class="form-control"
                                                           placeholder="@lang('User::attributes.user.password')"/>
                                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                                    @if ($errors->has('password'))
                                                        <div class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                                <div class="input-group js-parent-focus">
                                                    <input type="password" name="password_confirmation"
                                                           class="form-control"
                                                           placeholder="@lang('User::attributes.user.retype_password')"/>
                                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                                    @if ($errors->has('password_confirmation'))
                                                        <div class="help-block">
                                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            @if($is_two_factor_auth_enabled = \Settings::get('two_factor_auth_enabled', false))
                                                <div id="country-div"
                                                     class="form-group has-feedback {{ $errors->has('phone_country_code') ? ' has-error' : '' }}">
                                                    <label for="authy-countries"
                                                           class="control-label">@lang('User::attributes.user.phone_country_code')
                                                        :</label>
                                                    <select class="form-control" id="authy-countries"
                                                            name="phone_country_code"></select>
                                                    <span class="glyphicon glyphicon-flag form-control-feedback"></span>

                                                    @if ($errors->has('phone_country_code'))
                                                        <div class="help-block">
                                                            <strong>{{ $errors->first('phone_country_code') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="form-group has-feedback {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                                    <label for="authy-cellphone"
                                                           class="control-label">@lang('User::attributes.user.phone_number')
                                                        :</label>
                                                    <input class="form-control" id="authy-cellphone"
                                                           placeholder="@lang('User::attributes.user.cell_phone_number')"
                                                           type="text"
                                                           value="{{ old('phone_number') }}"
                                                           name="phone_number"/>
                                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                                    @if ($errors->has('phone_number'))
                                                        <div class="help-block">
                                                            <strong>{{ $errors->first('phone_number') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="{{ $errors->has('terms') ? ' has-error' : '' }}">
                                        <div class="checkbox icheck">
                                            <label>
                                                <input name="terms" value="1" type="checkbox"/>
                                                <strong>@lang('corals-marketplace-pro::labels.auth.agree')
                                                    <a href="#" data-toggle="modal" id="terms-anchor"
                                                       style="color:#41a5d2;"
                                                       data-target="#terms">@lang('corals-marketplace-pro::labels.auth.terms')</a>
                                                </strong>
                                            </label>
                                        </div>
                                        @if ($errors->has('terms'))
                                            <span class="help-block"><strong>@lang('corals-marketplace-pro::labels.auth.accept_terms')</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <div class="text-center text-sm-right">
                                        <button type="submit" class="btn btn-primary margin-bottom-none">
                                            @lang('corals-marketplace-pro::labels.auth.register')
                                        </button>
                                        <a class="btn btn-primary bg-blue margin-bottom-none check-out" href="{{ route('login') }}"
                                           rel="nofollow"
                                           title="Checkout">
                                            <span> @lang('corals-marketplace-pro::labels.partial.login')</span>
                                        </a>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@component('components.modal',['id'=>'terms','header'=>\Settings::get('site_name').' Terms and policy'])
    {!! \Settings::get('terms_and_policy') !!}
@endcomponent