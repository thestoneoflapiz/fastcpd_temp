<?php 
    $my_cart_items = _get_my_cart_items(); 
    $total_price = 0;
    $total_discounted_price = 0;
    $total_add_to_discount_total = 0;
?>
<div class="kt-header__topbar-item dropdown">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
        <span class="kt-header__topbar-icon my-cart-ui-icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                    <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000" />
                </g>
            </svg>  
            @if(count($my_cart_items))
            <span class="kt-badge kt-badge--danger cart-badge" style="position:absolute;top:15;right:2;padding:5px;font-size:11px;" id="my-cart-ui-badge">{{ count($my_cart_items) }}</span>
            @endif
        </span>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
        <div class="kt-mycart" id="my-cart-ui">
            <div class="kt-mycart__head kt-head" style="background-image: url({{asset('/media/misc/bg-1.jpg')}});">
                <div class="kt-mycart__info">
                    <span class="kt-mycart__icon"><i class="flaticon2-shopping-cart-1 kt-font-success"></i></span>
                    <h3 class="kt-mycart__title">My Cart</h3>
                </div>
                <div class="kt-mycart__button">
                    <button type="button" class="btn btn-success btn-sm">{{ count($my_cart_items) }} {{ count($my_cart_items) > 1 ? "Items" : "Item" }}</button>
                </div>
            </div>
            <div class="kt-mycart__body kt-scroll" data-scroll="true" style="max-height:200px">
                @if(count($my_cart_items) > 0)
                @foreach($my_cart_items as $mci)
                <?php 
                    $total_price += $mci["price"]; 
                    $total_discounted_price += $mci["discounted_price"] ?? 0; 
                    if($mci["discount"]==null){
                        $total_add_to_discount_total += $mci["price"]; 
                    }
                ?>
                <div class="kt-mycart__item">
                    <div class="kt-mycart__container">
                        <div class="kt-mycart__info">
                            <a href="javascript:;" class="kt-mycart__title">{{ strlen($mci["title"]) > 50 ? substr($mci["title"], 0, 50)."..." : $mci["title"] }}</a>
                            @if($mci["accreditation"])
                            <span class="kt-mycart__desc">
                                @foreach($mci["accreditation"] as $acc)
                                {{ substr($acc->title,0,10) }}... ({{ $acc->program_no }} &#9679; {{ $acc->units }}) <br/>
                                @endforeach
                            </span>
                            @endif
                            <div class="kt-mycart__action">
                                @if($mci["discount"])
                                <span class="kt-mycart__price">₱{{ number_format($mci["total_amount"], 2, '.', ',') }} <text style="text-decoration:line-through;font-weight:none;">₱{{ number_format($mci["price"]) }}</text></span>
                                @else
                                <span class="kt-mycart__price">₱{{ number_format($mci["total_amount"], 2, '.', ',') }}</span>
                                @endif
                            </div>
                        </div>
                        <a href="#" class="kt-mycart__pic">
                            <img alt="FastCPD Courses <?=$mci["title"]?>" src="{{ $mci['poster'] }}">
                        </a>
                    </div>
                </div>
                @endforeach
                @else
                <div class="kt-mycart__item default-item">
                    <div class="kt-mycart__container">
                        <div class="kt-mycart__info">
                            <a href="#" class="kt-mycart__title">
                                No items
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="kt-mycart__footer">
                <div class="kt-mycart__section">
                    <div class="kt-mycart__subtitel">
                        <span>
                            Total
                        </span>
                    </div>
                    <div class="kt-mycart__prices">
                        <span class="kt-font-brand">
                            @if($total_discounted_price)
                            <label class="kt-font-dark">&#8369;{{ number_format(($total_discounted_price+$total_add_to_discount_total), 2, '.', ',') }}</label>
                            <label style="text-decoration:line-through;font-weight:none;" class="kt-label-font-color-1">&#8369;{{ number_format($total_price, 2, '.', ',') }}</label>
                            @else
                            <label class="kt-font-dark">&#8369;{{ number_format($total_price, 2, '.', ',') }}</label>
                            @endif
                        </span>	
                    </div>
                </div>
                @if(count($my_cart_items))
                <div class="kt-mycart__button kt-align-right">
                    <button type="button" class="btn btn-danger btn-lg btn-upper kt-font-bolder" style="width:100%;" onclick="window.location='/cart'">Go to Cart</button>
                </div>
                @else
                <div class="kt-mycart__button kt-align-right">
                    <button type="button" class="btn btn-outline-info btn-lg btn-upper kt-font-bolder" style="width:100%;">Keep Shopping</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>