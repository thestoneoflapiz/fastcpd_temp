<?php
namespace App\Http\Controllers\Callback;

use App\{
    User, Provider, Course,
    Purchase, Purchase_Item, My_Cart, CLogs 
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use App\Jobs\{EmailBigQueries};

use Response; 
use Session;

class DragonPayController extends Controller
{
    protected $dragonpay_;

    public function __construct() 
    {
        $this->dragonpay_ = [
            "secret" => config('services.dragonpay.secret'),
            "id" => config('services.dragonpay.id'),
        ];
    }

    function default_callback(Request $request){
        if($request->txnid && $request->refno){

            $stringify = "{$request->txnid}:{$request->refno}:{$request->status}:{$request->message}:{$this->dragonpay_['secret']}";
            $our_digest = hash('sha1', $stringify);

            if($our_digest==$request->digest){
                $purchase = Purchase::where([
                    "reference_number" => $request->txnid,
                ])->first();

                if($purchase){
                    if($purchase->payment_status == "waiting"){
                        $status = $request->status == "S" ? "paid" : ($request->status == "P" ? "pending" : ($request->status == "R" ? "cancelled" : "waiting"));
                        $purchase->payment_status = $status;
                        $purchase->payment_notes = $request->message;
                        $purchase->updated_at = date("Y-m-d H:i:s");
                        $purchase->payment_at = date("Y-m-d H:i:s");
                        $purchase->save();
    
                        Purchase_Item::where([
                            "user_id" => $purchase->user_id,
                            "purchase_id" => $purchase->id,
                        ])->whereIn("payment_status", ["waiting", "pending"])->update([
                            "payment_status" => $status,
                            "updated_at" => date("Y-m-d H:i:s")
                        ]);
    
                        switch ($request->status) {
                            case 'S': # success
                                $title = "Successful Transaction";
                                $message = "Thank you! Your payment transaction with reference number of <b>{$request->txnid}</b> is sucessfully <b>paid</b>!<br/>
                                Please check your purchase details to confirm, or if all else fail, try contacting us through <b><a target=\"_blank\" href=\"https://facebook.com/fastcpd\">HERE</a></b>";
                                CLogs::insert(["message" => "Payment transaction ref#{$purchase->reference_number} success at ".date("Y-m-d H:i:s")]);
                                _notification_insert("purchase", Auth::user()->id, $request->refno, "Successful Payment", "Your transaction payment with ref#{$request->refno} is completed", "/profile/settings");
                                $this->dispatch(new EmailBigQueries(Auth::user()->email, "confirmed_purchase", $purchase->reference_number));

                                Session::flash("success", $message);
                            break;
    
                            case 'P': # pending
                                $title = "Pending Payment Transaction";
                                $message = "Transaction is being processed. Please complete your payment, please check your email inbox and spam folder for the instructions. If you have completed payment, we’ll confirm it shortly. Please contact payments@fastcpd.com for any concerns.";
                                CLogs::insert(["message" => "Payment transaction ref#{$purchase->reference_number} pending at ".date("Y-m-d H:i:s")]);
                                _notification_insert("purchase", Auth::user()->id, $request->refno, "Pending for Payment", "Your transaction payment with ref#{$request->refno} is pending for payment", "/profile/settings");
                                Session::flash("info", $message);
                            break;
    
                            case 'R': # refund
                                $title = "Refund Transaction Attempt";
                                $message = "Hi, your payment transaction with reference number of <b>{$request->txnid}</b> is getting a refund.<br/>
                                Please check your purchase details to confirm, or if all else fail, try contacting us through <b><a target=\"_blank\" href=\"https://facebook.com/fastcpd\">HERE</a></b>";
                                CLogs::insert(["message" => "Payment transaction ref#{$purchase->reference_number} is getting refund at ".date("Y-m-d H:i:s")]);
                                _notification_insert("purchase", Auth::user()->id, $request->refno, "Refund Payment", "Your transaction payment with ref#{$request->refno} is getting refund", "/profile/settings");
                                Session::flash("info", $message);
                            break;
                        }
                    }else if($purchase->payment_status == "pending"){
                        $message = "Thank you! Your payment transaction with reference number of <b>{$request->txnid}</b> is currently <b>pending</b>!<br/>
                        Please check your purchase details to confirm, or if all else fail, try contacting us through <b><a target=\"_blank\" href=\"https://facebook.com/fastcpd\">HERE</a></b>";
                        Session::flash("info", $message);
                    }
                    return redirect("profile/settings");
                }
            }

            return view("page.cart.payment_forms.failed", ["reference_number" => false]);
        }

        return redirect("/");
    }
}
