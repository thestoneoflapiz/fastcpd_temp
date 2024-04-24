<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-text"> <i class="flaticon2-setup"></i>&nbsp; &nbsp; Professions</span>
    </a>
    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
        <ul class="kt-menu__subnav kt-scroll" data-scroll="true" style="max-height: 400px;"> 
            @if(count(_professions()) > 0)
            @foreach(_professions() as $pro)
                <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                    <a href="/courses/{{ $pro['url'] }}" class="kt-menu__link">
                        <span class="kt-badge kt-badge--danger" style="padding:0.5rem;">{{ $pro["totals"] > 99 ? "99+" : $pro["totals"] }}</span> &nbsp; &nbsp;<span class="kt-menu__link-text">{{ $pro['profession'] }}</span>
                    </a>
                </li>
            @endforeach
            @endif
            <li class="kt-menu__item  kt-menu__item--submenu kt-margin-t-10" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                <a href="javascript:;" class="kt-menu__link">
                    <span class="kt-menu__link-text kt-font-bolder">More professions coming soon!</span>
                </a>
            </li>
        </ul>
    </div>
</li>