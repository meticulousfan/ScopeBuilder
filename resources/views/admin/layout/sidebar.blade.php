<aside class="page-sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-section">
            <div class="user-block">
                <div class="user-avatar">
                    <img src="{{ !empty(Auth::user()->getFirstMediaUrl('avatar', 'thumb')) ? Auth::user()->getFirstMediaUrl('avatar', 'thumb') : asset('ui_assets/img/content-images/user-avatar.jpg') }}"
                        alt="{{ Auth::user()->name }}">
                </div>

                <div class="user-name">Welcome <strong>{{ Auth::user()->name }}</strong></div>
            </div>
        </div>

        <div class="sidebar-section">
            <h2 class="sidebar-caption">Overview</h2>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="{{ route('admin.clients') }}" class="{{ $menu == 'clients' ? 'active' : 'unactive' }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#users') }}"></use>
                            </svg>
                            <span class="link-text">Clients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.developers') }}" class="{{ $menu == 'freelancers' ? 'active' : 'unactive' }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#users') }}"></use>
                            </svg>
                            <span class="link-text">Freelancers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.questionnaire.display') }}" class="{{ $menu == 'questionnaires-display' ? 'active' : 'unactive' }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#users') }}"></use>
                            </svg>
                            <span class="link-text">Questionnaires</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="dropdown-btn">
                            <img class="link-icon" src="{{ asset('ui_assets/img/icons/vuesax-bulk-briefcase.svg') }}">
                            <span class="link-text">Projects</span>
                            <i id="dropdown-icon" class="fa fa-angle-up" style="margin-left: 100px;"></i>
                            <input type="hidden" id="dropdown-status"
                                value="{{ $menu == 'client_projects' || $menu == 'types' || $menu == 'skills' || $menu == 'questionnaires' ? 1 : 0 }}">
                        </a>
                        <ul class="dropdown-container">
                            <li>
                                <a href="{{ route('admin.projects') }}"
                                    class="{{ $menu == 'client_projects' ? 'active' : 'unactive' }}">
                                    <span class="link-text">Client Projects</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.types') }}" class="{{ $menu == 'types' ? 'active' : 'unactive' }}">
                                    <span class="link-text">Project Types</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.skills') }}"
                                    class="{{ $menu == 'skills' ? 'active' : 'unactive' }}">
                                    <span class="link-text">Skills</span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ route('admin.questionnaires') }}"
                                    class="{{ $menu == 'questionnaires' ? 'active' : 'unactive' }}">
                                    <span class="link-text">Questionnaires</span>
                                </a>
                            </li> -->
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin.recordings') }}"
                        class="{{ $menu == 'recordings' ? 'active' : 'unactive' }}">
                            <img class="link-icon"
                                src="{{ asset('ui_assets/img/icons/vuesax-bulk-stop-circle.svg') }}">
                            <span class="link-text">Recordings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.venttedprojects') }}"
                        class="{{ $menu == 'venttedprojects' ? 'active' : 'unactive' }}">
                            <img class="link-icon" src="{{ asset('ui_assets/img/icons/vuesax-bulk-code.svg') }}">
                            <span class="link-text">Vetted Freelancer Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="payout-dropdown-btn">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#wallet') }}"></use>
                            </svg>
                            <span class="link-text" style="width: 145px;">Payouts and Methods</span>
                            <i id="payout-dropdown-icon" class="fa fa-angle-up" style="margin-left: 10px;"></i>
                            <input type="hidden" id="payout-dropdown-status"
                                value="{{ $menu == 'payouts' || $menu == 'payout_methods' ? 1 : 0 }}">
                        </a>
                        <ul class="payout-dropdown-container">
                            <li>
                                <a href="{{ route('admin.payouts') }}"
                                    class="{{ $menu == 'payouts' ? 'active' : 'unactive' }}">
                                    <span class="link-text">Payouts</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.payout_methods') }}" class="{{ $menu == 'payout_methods' ? 'active' : 'unactive' }}">
                                    <span class="link-text">Payouts Methods</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.index') }}"
                        class="{{ $menu == 'settings' ? 'active' : 'unactive' }}">
                            <img class="link-icon" src="{{ asset('ui_assets/img/icons/vuesax-bulk-setting-2.svg') }}">
                            <span class="link-text">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.profile') }}"
                        class="{{ $menu == 'profile' ? 'active' : 'unactive' }}">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#user') }}"></use>
                            </svg>
                            <span class="link-text">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <svg class="link-icon">
                                <use xlink:href="{{ asset('ui_assets/img/icons-sprite.svg#logout') }}"></use>
                            </svg>
                            <span class="link-text">Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</aside>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
    var status = $('#dropdown-status').val();
    $('.dropdown-container').hide();
    $('#dropdown-icon').removeClass();
    $('#dropdown-icon').addClass("fa fa-angle-up");
    if (status == 1) {
        $('.dropdown-container').show();
        $('#dropdown-icon').removeClass();
        $('#dropdown-icon').addClass("fa fa-angle-down");
    }
    $('.dropdown-btn').click(function(event) {
        console.log(status);
        if (status == 0) {
            $('.dropdown-container').show();
            $('#dropdown-icon').removeClass();
            $('#dropdown-icon').addClass("fa fa-angle-down");
            status = 1;
        } else {
            $('.dropdown-container').hide();
            $('#dropdown-icon').removeClass();
            $('#dropdown-icon').addClass("fa fa-angle-up");
            status = 0;
        }
        console.log("asdfasd");
    });


    var payout_status = $('#payout-dropdown-status').val();
    $('.payout-dropdown-container').hide();
    $('#payout-dropdown-icon').removeClass();
    $('#payout-dropdown-icon').addClass("fa fa-angle-up");
    if (payout_status == 1) {
        $('.payout-dropdown-container').show();
        $('#payout-dropdown-icon').removeClass();
        $('#payout-dropdown-icon').addClass("fa fa-angle-down");
    }
    $('.payout-dropdown-btn').click(function(event) {
        console.log(payout_status);
        if (payout_status == 0) {
            $('.payout-dropdown-container').show();
            $('#payout-dropdown-icon').removeClass();
            $('#payout-dropdown-icon').addClass("fa fa-angle-down");
            payout_status = 1;
        } else {
            $('.payout-dropdown-container').hide();
            $('#payout-dropdown-icon').removeClass();
            $('#payout-dropdown-icon').addClass("fa fa-angle-up");
            payout_status = 0;
        }
    });
</script>
