<?php

namespace App\Http\Controllers\Checkout;

use App\{
    User, Provider, Course,
    Purchase, Purchase_Item, My_Cart
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use GuzzleHttp\Client;

use Response; 
use Session;

class CheckoutController extends Controller 
{ 
    function index(){
        $data = _get_checkout_items();

        if(array_key_exists("free", $data)){
            if($data["data"]["total_items"] == 0){
                return redirect("/profile/settings");
            }

            return _checkout_free_items($data["data"]);
        }else{
            return view('page/cart/checkout_page', $data);
        }
    }
}
