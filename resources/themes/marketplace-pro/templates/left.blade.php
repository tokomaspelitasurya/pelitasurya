@extends('layouts.public')

@section('content')
    @include('partials.page_header')

    <div class="container padding-bottom-3x mb-1">
        <div class="row">
            <div class="col-md-9 flex-xs-first main-blogs col-lg-8 order-lg-1">
                {!! $item->rendered !!}
            </div>
            <div class="sidebar-3 sidebar-collection col-lg-3 col-md-3 col-sm-4 order-lg-2">
                @include('partials.sidebar')
            </div>
        </div>
    </div>
@stop
