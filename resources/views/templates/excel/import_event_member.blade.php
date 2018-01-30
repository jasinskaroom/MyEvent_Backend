<!DOCTYPE html>
<html>

    <tr>
        <td><b>Name</b></td>
        <td><b>Email</b></td>
        <td><b>Mobile Number</b></td>
        <td><b>IC / Passport</b></td>
        <td><b>Gender (male/female)</b></td>
        @foreach (\App\Models\RegisterFormField::orderBy('order', 'asc')->get() as $field)
            <td><b>{{ $field->name }}</b></td>
        @endforeach
    </tr>

</html>
