@if ($errors->has($field_name))
    <p class="alert alert-danger">{$ $errors->first($field_name) $}</p>
@endif
