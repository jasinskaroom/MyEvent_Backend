@extends('layouts.app')

@section('body_class','nav-md')

@section('page')
    @if (session()->has('system_status'))
        <p style="display:none;" class="system-status" data-message="{{ session('system_status') }}"></p>
    @endif

    <div class="container body">
        <div class="main_container">
            @section('header')
                @if(Auth::user()->isAdmin())
                    @include('admin.sections.navigation')
                @else

                    @include('admin.sections.navigation_manager')
                @endif
                @include('admin.sections.header')
            @show


            @yield('left-sidebar')

            <div class="right_col" role="main">
                <div class="page-title">
                    <div class="title_left">
                        <h1 class="h3">@yield('title')</h1>
                    </div>
                    @if(Breadcrumbs::exists())
                        <div class="title_right">
                            <div class="pull-right">
                                {!! Breadcrumbs::render() !!}
                            </div>
                        </div>
                    @endif
                </div>
                @yield('content')
            </div>

            <footer>
                @include('admin.sections.footer')
            </footer>
        </div>
    </div>
@stop

@section('styles')
    {{ Html::style(mix('assets/admin/css/admin-core.css')) }}
    {{ Html::style(mix('assets/admin/css/admin.css')) }}
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
