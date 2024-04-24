<!--begin: User Bar -->
<div class="kt-header__topbar-item kt-header__topbar-item--user">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
        <div class="kt-header__topbar-user">
            <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
            <span class="kt-header__topbar-username kt-hidden-mobile"><?=ucwords(Auth::user()->first_name);?></span>
            @if(Auth::user()->image)
            <img class="" alt="Pic" src="<?=Auth::user()->image?>" />
            @else
            <span class="kt-badge kt-badge--username kt-badge--unified-info kt-badge--lg kt-badge--rounded kt-badge--bold"><?=ucwords(Auth::user()->first_name[0]);?></span>
            @endif
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
        <!--begin: Navigation -->
        <div class="kt-notification">
            <a href="/<?=Auth::user()->instructor=='active' ? 'instructor' : 'user' ?>/{{ Auth::user()->username }}" class="kt-notification__item">
                <div class="kt-notification__item-icon">
                    <i class="flaticon2-cube kt-font-warning"></i>
                </div>
                <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title kt-font-bold">
                        View Public Profile
                    </div>
                </div>
            </a>
            <a href="/profile/settings" class="kt-notification__item">
                <div class="kt-notification__item-icon">
                    <i class="flaticon2-calendar-3 kt-font-info"></i>
                </div>
                <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title kt-font-bold">
                        Account Profile & Settings
                    </div>
                    <div class="kt-notification__item-time">
                        Manage your account and settings
                    </div>
                </div>
            </a>

            @if(in_array(Auth::user()->instructor, ["none", "denied"]))
            <a href="/instructor/register" class="kt-notification__item">
                <div class="kt-notification__item-icon">
                    <i class="flaticon2-rocket-1 kt-font-danger"></i>
                </div>
                <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title kt-font-bold">
                        Become an Instructor
                    </div>
                </div>
            </a>
            @endif

            @if(Auth::user()->provider_id <= 0)
            <a href="/provider/register" class="kt-notification__item">
                <div class="kt-notification__item-icon">
                    <i class="flaticon2-analytics-2 kt-font-success"></i>
                </div>
                <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title kt-font-bold">
                        Become a Provider
                    </div>
                </div>
            </a>
            @endif

            <div class="kt-notification__custom kt-space-between">
                <a href="javascript:signOUTuser('<?=Auth::user()->authSocial?>');" class="btn btn-label btn-label-brand kt-font-bold btn-sm" id="sign_out_social">Sign Out</a>
            </div>
        </div>
        <!--end: Navigation -->
    </div>
</div>
<!--end: User Bar -->