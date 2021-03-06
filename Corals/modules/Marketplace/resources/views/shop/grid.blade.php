@extends('layouts.crud.grid')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_shop') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
@endsection


@section('js')
    @parent
    @include('Marketplace::cart.cart_script')
@endsection