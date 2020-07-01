@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('custom_field_settings_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! CoralsForm::openForm($customFieldSetting) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! CoralsForm::select('model','Settings::attributes.custom_field.model', \Settings::getCustomFieldsModels(), true, null, $customFieldSetting->exists?['readonly']:[], $customFieldSetting->exists?'select':'select2') !!}
                    </div>
                </div>
                <h4>Fields</h4>
                <hr/>
                @forelse($customFieldSetting->fields ?? [] as $field)
                    @include('Settings::custom_fields.partials.custom_fields_form',['index'=>$loop->index,'field'=>$field])
                @empty
                    @include('Settings::custom_fields.partials.custom_fields_form',['index'=>0,'field'=>[]])
                @endforelse

                <div id="new-custom-field-button">
                    <div class="form-group">
                        <button type="button" class="btn btn-success btn-sm"
                                id="add-new-custom-field">
                            <i class="fa fa-plus"></i>
                            Add New Custom Field
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($customFieldSetting) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#add-new-custom-field').on('click', function () {
                let customFieldFormIndex = $('.custom-field-form').length;
                $.get('{{url('customer-fields/get-form?index=')}}' + customFieldFormIndex, function (newCustomFieldForm) {
                    $('#new-custom-field-button').before(newCustomFieldForm);
                });
            });

            $(document).on('click', '.remove-custom-field', function () {
                $(this).closest('.custom-field-form').remove();
            });

            var $type = $(".field_type");
            var $options_source = $(".source_options");
            var optins_types = ['select', 'radio', 'multi_values'];

            $type.each(function () {
                let formIndex = $(this).data('form_index');

                if (_.includes(optins_types, $(this).val())) {
                    $(`#options-field-${formIndex}`).fadeIn();
                }
            });

            $options_source.each(function () {
                let formIndex = $(this).data('form_index');

                if ($(this).val()) {
                    $(`.options-source-${formIndex}`).fadeOut();
                    $(`.options-source-${formIndex}-${$(this).val()}`).fadeIn();

                }
            });
            $(document).on('change', '.field_type', function (event) {
                let formIndex = $(this).data('form_index');

                if (_.includes(optins_types, $(this).val())) {
                    $(`#options-field-${formIndex}`).fadeIn();
                } else {
                    $(`#options-field-${formIndex}`).fadeOut();
                }
            });

            $(document).on('change', '.source_options', function () {
                let formIndex = $(this).data('form_index');
                $(`.options-source-${formIndex}`).fadeOut();
                $(`.options-source-${formIndex}-${$(this).val()}`).fadeIn();
            });

        });
    </script>@endsection
