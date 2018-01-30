<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ action('Admin\EventController@viewLiveEvent') }}" class="site_title">
                <span>{{ config('app.name') }}</span>
                <img width="50px" src="{{ URL::to('/') }}/assets/admin/img/myevent_logo.png" alt="Logo">
            </a>
        </div>

        <div class="clearfix"></div>

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <!-- Users -->
            <div class="menu_section">
                <h3>Registration</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ action('Admin\FieldCreationController@view') }}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            Fields Creation
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End -->
            <!-- Events -->
            <div class="menu_section">
                <h3>Events</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ action('Admin\EventController@viewLiveEvent') }}">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            Live
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('Admin\EventController@showEventList') }}">
                            <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
                            Closed
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('Admin\EventController@eventEndedSummary') }}">
                            <i class="fa fa-flag-checkered" aria-hidden="true"></i>
                            Ended
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End -->
            <!-- QRCode -->
            <div class="menu_section">
                <h3>Resources</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ action('Admin\QRCodeController@index') }}">
                            <i class="fa fa-qrcode" aria-hidden="true"></i>
                            QRCode
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('Admin\GiftController@index') }}">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                            Gifts
                        </a>
                    </li>

                    <li>
                        <a href="{{ action('Admin\EventManagerController@index') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            Managers
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End -->
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
