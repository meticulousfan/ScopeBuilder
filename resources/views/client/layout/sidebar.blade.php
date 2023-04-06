<aside class="page-sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-section">
            <div class="user-block">
                <div class="user-avatar">
                    <img src="{{ !empty(Auth::user()) && !empty(Auth::user()->getFirstMediaUrl('avatar', 'thumb')) ? Auth::user()->getFirstMediaUrl('avatar', 'thumb') : asset('ui_assets/img/content-images/user-avatar.jpg') }}" alt="{{ Auth::user()->name??'' }}">
                </div>

                <div class="user-name">Welcome <strong>{{ Auth::user()->name??'' }}</strong></div>
            </div>
        </div>

        <div class="sidebar-section">
            <h2 class="sidebar-caption">Overview</h2>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="/client/questionnaires/list">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#tiles') }}"></use>
                            </svg>
                            <span class="link-text">Questionnaires</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.projects') }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#tiles') }}"></use>
                            </svg>
                            <span class="link-text">My Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.calls') }}">
                            <img class="link-icon"
                                src="{{ asset('ui_assets/img/icons/vuesax-bulk-stop-circle.svg') }}">
                            <span class="link-text">Calls</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.profile') }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#user') }}"></use>
                            </svg>
                            <span class="link-text">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#logout') }}"></use>
                            </svg>
                            <span class="link-text">Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</aside>
