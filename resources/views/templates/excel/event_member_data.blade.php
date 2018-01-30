<!DOCTYPE html>
<html>

    <tr>
        <td><b>Name</b></td>
        <td><b>Email</b></td>
        <td><b>Mobile Number</b></td>
        <td><b>IC / Passport</b></td>
        <td><b>Gender</b></td>
        <td><b>Activated</b></td>
        <td><b>Pre Registration</b></td>
        <td><b>Score</b></td>
        <td><b>Rewarded</b></td>
        <td><b>Gift Awarded</b></td>
        <td><b>Created at</b></td>
        <td><b>Last updated</b></td>
        @foreach($fields as $field)
            <td><b>{{ $field->translate('en')->name }}</b></td>
        @endforeach
    </tr>

    @foreach($event->participants as $participant)

        <tr>
            <td>{{ $participant->name }}</td>
            <td>{{ $participant->email }}</td>
            <td>{{ $participant->mobile_number }}</td>
            <td>{{ $participant->identity_passport }}</td>
            <td>{{ $participant->gender }}</td>
            <td>
                @if ($participant->activated)
                    Activated
                @else
                    Inactive
                @endif
            </td>
            <td>
                @if ($participant->pre_registration)
                    Yes
                @else
                    No
                @endif
            </td>
            <td>{{ $participant->score }}</td>
            <td>
                @if ($participant->rewarded)
                    Yes
                @else
                    No
                @endif
            </td>
            <td>{{ $participant->getAllGiftsString() }}</td>
            <td>{{ $participant->created_at }}</td>
            <td>{{ $participant->updated_at }}</td>
            @foreach($fields as $field)
                <td>{{ $participant->getFieldValue($field->id) }}</td>
            @endforeach
        </tr>

    @endforeach

</html>
