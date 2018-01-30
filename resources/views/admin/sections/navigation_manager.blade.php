<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ action('Admin\EventController@viewLiveEvent') }}" class="site_title">
                <span>{{ config('app.name') }}</span>
            </a>
        </div>

    
            <!-- End -->
            <!-- Events -->
            <div class="menu_section">
                <h3>Events</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ action('Admin\EventController@viewLiveEvent') }}">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                           My Events
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End -->
            <!-- QRCode -->
            
            <!-- End -->
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
