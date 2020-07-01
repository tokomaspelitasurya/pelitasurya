<div class="custom-field-form" id="custom-field-form-{{$index}}">
    @if($index>=1)
        <div class="row">
            <div class="col-md-12">
                <button class="remove-custom-field btn btn-sm btn-danger pull-right" type="button"
                        title="Remove custom-field"><i class="fa fa-remove"></i> Remove
                </button>
            </div>
        </div>
    @endif
    @include('Settings::custom_fields.partials.custom_fields_form_fields', compact('index'))
    <hr/>
</div>