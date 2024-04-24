<div class="kt-header__topbar-item kt-header__topbar-item--user dropdown">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
        <div class="kt-header__topbar-user">
            <span class="kt-header__topbar-username going-small">
                &nbsp; My Items 
            </span>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
        <!-- begin:: Mycart -->
        <div class="kt-mycart__body kt-scroll" data-scroll="true" style="max-height:300px;">
            <div class="kt-notification-v2"> 
                @if(count(_get_my_items()) > 0) 
                @foreach(_get_my_items() as $item)
                    @if($item["type"] == "course")
                    <a href="/course/live/{{ $item->url }}" class="kt-notification-v2__item" style="margin:10px;">
                        <div class="kt-notification-v2__item-icon">
                            <img alt="FastCPD Courses <?=$item->title?>" src="{{ $item->poster }}" style="height:50px;width:75px;background-position:center;background-size:cover;border-radius:4px;">
                        </div>
                        <div class="kt-notification-v2__itek-wrapper">
                            <div class="kt-notification-v2__item-title">
                                {{ Str::limit($item->title, 30, "...")}}
                            </div>
                            <div class="kt-notification-v2__item-desc">
                                @if(in_array($item->fast_status, ["passed", "complete"]))
                                <span class="kt-badge kt-badge--inline kt-badge--success">{{ $item->fast_status }}</span>
                                @elseif($item->fast_status=="failed")
                                <span class="kt-badge kt-badge--inline kt-badge--danger">{{ $item->fast_status }}</span>
                                @elseif($item->fast_status=="published")
                                <span class="kt-badge kt-badge--inline kt-badge--warning">coming soon</span>
                                <div class="kt-space-5"></div>
                                <span class="kt-font-dark">accessible on {{ date("M. d, Y", strtotime($item->session_start)) }}</span>
                                @else
                                <span class="kt-badge kt-badge--inline kt-badge--dark">{{ $item->fast_status }}</span>
                                <div class="kt-space-10"></div>
                                <div class="progress kt-progress progress-sm">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $item->progress > 0 ? $item->progress : 1 }}%;" aria-valuenow="{{ $item->progress > 0 ? $item->progress : 1 }}" aria-valuemin="1" aria-valuemax="100"></div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @else
                    <a href="<?=in_array($item->fast_status, ["passed", "complete"]) ? "javascript:;" : "/webinar/live/{$item->url}"?>" class="kt-notification-v2__item" style="margin:10px;">
                        <div class="kt-notification-v2__item-icon">
                            <img alt="FastCPD Courses <?=$item->title?>" src="{{ $item->poster }}" style="height:50px;width:75px;background-position:center;background-size:cover;border-radius:4px;">
                        </div>
                        <div class="kt-notification-v2__itek-wrapper">
                            <div class="kt-notification-v2__item-title">
                                {{ Str::limit($item->title, 30, "...")}}
                            </div>
                            <div class="kt-notification-v2__item-desc">
                                @if(in_array($item->fast_status, ["passed", "complete"]))
                                <span class="kt-badge kt-badge--inline kt-badge--success">{{ $item->fast_status }}</span>
                                @elseif($item->fast_status=="live")
                                <span class="kt-badge kt-badge--inline kt-badge--info">LIVE TODAY</span>
                                @elseif($item->fast_status=="published")
                                <span class="kt-badge kt-badge--inline kt-badge--warning">coming soon</span>
                                <div class="kt-space-5"></div>
                                <span class="kt-font-dark">accessible until {{ date("M. d, Y", strtotime($item->webinar_schedule)) }}</span>
                                @else
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif
                @endforeach
                @else
                <a href="javascript:;" class="kt-notification-v2__item" style="margin:10px;text-align:center;">
                    <div class="kt-notification-v2__itek-wrapper">
                        <div class="kt-notification-v2__item-title">
                            No courses yet
                        </div>
                        <div class="kt-notification-v2__item-desc">
                            Keep Shopping!
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
        <div class="kt-mycart__footer">
            <div class="kt-notification__custom kt-space-between" style="float:right;">
                <a href="/profile/settings" class="btn btn-clean btn-sm btn-bold">View More</a>
            </div>
        </div>
    </div>
</div>