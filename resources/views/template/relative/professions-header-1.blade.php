<!-- Resources:bar -->
<div class="kt-header__topbar-item kt-header__topbar-item--user resources-dropdown">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
        <div class="kt-header__topbar-user">
            <span class="kt-header__topbar-username">
                &nbsp; Professions &nbsp; <i class="fa fa-chevron-down" style="margin:auto;"></i>
            </span>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
        <!--begin: Navigation -->
        <div class="kt-notification">
            @if(count(_professions()) > 0)
            @foreach(_professions() as $pro)
                <a href="//courses/{{ $pro['url'] }}" class="kt-notification__item">
                    <div class="kt-notification__item-details">
                        <div class="kt-notification__item-title">
                            <span class="kt-badge kt-badge--danger" style="padding:0.5rem;">{{ $pro["total_courses"] > 99 ? "99+" : $pro["total_courses"] }}</span> &nbsp; &nbsp;<span class="kt-menu__link-text">{{ $pro['profession'] }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
            @endif
        </div>
        <!--end: Navigation -->
    </div>
</div>
<!-- Resources:bar -->