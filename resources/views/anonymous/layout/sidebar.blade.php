<aside class="page-sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-section">
            <div class="page-logo">
                <img src="{{ asset('ui_assets/img/logo.svg') }}" alt="Scopebuilder">
            </div>
        </div>

        <div class="sidebar-section">

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="{{ route('client.register.form') }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#user') }}"></use>
                            </svg>
                            <span class="link-text">{{ __('frontend.create_account') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.login.form') }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#user') }}"></use>
                            </svg>
                            <span class="link-text">{{ __('frontend.login') }}</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</aside>
