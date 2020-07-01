<div class="row">
    <div class="col-md-4">
        {!! CoralsForm::text("fields[$index][name]","Settings::attributes.custom_field.name",true) !!}

        {!! CoralsForm::select("fields[$index][type]", "Settings::attributes.custom_field.type", get_array_key_translation(config("settings.models.custom_field_setting.supported_types")), true,\Illuminate\Support\Arr::get($field,"type"),
        [!empty($field)?"readonly":'','class'=>'field_type','data-form_index'=>$index]) !!}

        {!! CoralsForm::radio("fields[$index][status]","Corals::attributes.status", true, trans("Corals::attributes.status_options")) !!}
    </div>
    <div class="col-md-4">
        {!! CoralsForm::text("fields[$index][label]","Settings::attributes.custom_field.label",false) !!}
        {!! CoralsForm::text("fields[$index][default_value]","Settings::attributes.custom_field.default_value",false) !!}
        {!! CoralsForm::text("fields[$index][validation_rules]","Settings::attributes.custom_field.validation_rules",false,null,['help_text'=>'Settings::attributes.custom_field.validation_rules_help']) !!}
    </div>
    <div class="col-md-4">
        <label>@lang("Settings::attributes.custom_field.attribute")</label>
        
        @include("components.key_value",[
        "label"=>["key"=> trans("Corals::labels.key"), "value"=>trans("Corals::labels.value")],
        "name"=>"fields[$index][custom_attributes]",
        "options"=>\Illuminate\Support\Arr::get($field,"custom_attributes",[])
        ])
        <div style="display: none;" id="options-field-{{$index}}">
            {!! CoralsForm::select("fields[$index][options_setting][source]", "Settings::attributes.custom_field.options_source", ["static"=>"Static","database"=>"Database"], true,\Illuminate\Support\Arr::get($field,"source") ,[
            !empty($field)?"readonly":'','class'=>'source_options','data-form_index'=>$index
            ]) !!}
            <div class="form-group options-source-{{$index}} options-source-{{$index}}-database" style="display: none;">
                {!! CoralsForm::select("fields[$index][options_setting][source_model]","Settings::attributes.custom_field.options_source_model", \Settings::getCustomFieldsModels(), true, null, !empty($field)?["readonly"]:[], !empty($field)?"select":"select2") !!}
                {!! CoralsForm::text("fields[$index][options_setting][source_model_column]","Settings::attributes.custom_field.options_source_model_column",true,null, !empty($field)?["readonly"]:[]) !!}

            </div>
            <div class="form-group options-source-{{$index}} options-source-{{$index}}-static" style="display: none;">
                <span data-name="options"></span>
                {!! CoralsForm::label("fields[$index][options]", "Settings::attributes.custom_field.options") !!}
                @include("components.key_value",[
                "label"=>["key" => trans("Corals::labels.key"), "value" => trans("Corals::labels.value")],
                "name"=>"fields[$index][options]",
                "options"=>\Illuminate\Support\Arr::get($field,"options",[])
                ])
            </div>
        </div>
    </div>
</div>