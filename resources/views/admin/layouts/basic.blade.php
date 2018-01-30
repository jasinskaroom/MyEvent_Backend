@extends('layouts.app')

@section('page')
    @if (session()->has('system_status'))
        <p style="display:none;" class="system-status" data-message="{{ session('system_status') }}"></p>
    @endif

    @yield('content')
@stop

@section('styles')
    {{ Html::style(mix('assets/admin/css/admin-core.css')) }}
    {{ Html::style(mix('assets/admin/css/admin.css')) }}

    <style media="screen">
        body {
            background: white;
            padding: 20px;
        }
    </style>
@endsection

@section('scripts')
    {{ Html::script(mix('assets/admin/js/admin-core.js')) }}

    @if(Auth::user()->isManager())
        <script type="text/javascript">
            $(function(){
                $('#menu_toggle').click();
            })
        </script>
    @endif

    <script type="text/javascript">
        $(function() {
            // check if can find system_status
            var $systemStatus = $('.system-status');
            if ($systemStatus.length > 0) {
                var message = $systemStatus.attr('data-message');
                new PNotify({
                    'text': message,
                    'type': 'notice',
                    'styling': 'bootstrap3'
                });
            }
        });
    </script>
@endsection
