<?php
/*
|--------------------------------------------------------------------------
|     引入微信类库
|--------------------------------------------------------------------------
|
|     自定义类库，主要用于微信开发
|     方便开发需求
|     全局应用，不需要实例化
|     例子：调用function test($msg){} 项目中：test($msg);
|
|
|                                                               By--Yancey
|--------------------------------------------------------------------------
*/
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Config;
use App\Models\Users;
use App\Models\User_details;
use Illuminate\Session;
use Illuminate\Support\Facades\DB;
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


//access_token获取（包括全局缓存）
function get_access_token(){
    if (Cache::has('wx_access_token'))
    {
        //Cache::forget('wx_access_token');

        return Cache::get('wx_access_token');
    }else{
        $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".config('app.appid')."&secret=".config('app.appsecret'));
        $data = json_decode($data,true);
        $expiresAt = Carbon::now()->addMinutes(100);
        Cache::put('wx_access_token',@$data['access_token'], $expiresAt); 
        return Cache::get('wx_access_token');
    }

}
//微信JS  API
function getJsApi(){
    if(Cache::has('wx_jsapi')){
        return Cache::get('wx_jsapi');
    }else{
        $token = get_access_token();
        $data = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token."&type=jsapi");
        $data = json_decode($data,true);
        $expiresAt = Carbon::now()->addMinutes(100);
        Cache::put('wx_jsapi',@$data['ticket'], $expiresAt);
        return Cache::get('wx_jsapi');
    }
}

function getSignature($url){
    $noncestr="fjsisKGjdkicLHid";
    $jsapi_ticket= getJsApi();
    $timestamp= 1470813557;
    $str = "jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url;
    return sha1($str);
}
//通过code换取用户信息
function getUserinfo($code){
        $str = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".config('app.appid')."&secret=".config('app.appsecret')."&code=".$code."&grant_type=authorization_code");
        $str = json_decode($str,true);
        if(isset($str['access_token'])){
            $userinfo = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".@$str['access_token']."&openid=".@$str['openid']."&lang=zh_CN");
            $userinfo = json_decode($userinfo,true);
            $userinfoset = Users::where("openid","=",$userinfo['openid'])->get();
            if(!$userinfoset->isEmpty()){
                $userid = $userinfoset[0]->user_id;
                $userdetail = array();
                $userdetail['openid'] = $userinfo['openid'];
                $userdetail['user_id'] = $userid;
                $userdetail['headimgurl'] = $userinfo['headimgurl'];
                //DB::UPDATE('UPDATE   user_details SET headimgurl = ? where user_id= ?',[$userinfo['headimgurl'],$userid]);
                Session::put("userinfo",$userdetail);
                return Session::get("userinfo");
            }else{
                //过滤名字表情符号
                $name = preg_replace('/[\xF0-\xF7].../s', '', $userinfo['nickname']);
                $user = Users::create(['openid' => $userinfo['openid'],'head_img_url'=>$userinfo['headimgurl']]);
                $userdetailsv = new User_details();
                $userdetailsv->user_id = $user->user_id;
                $userdetailsv->gender = $userinfo['sex'];
                $userdetailsv->nickname = $name;
                $userdetailsv->save();
                $userdetail = array();
                $userdetail['openid'] = $user->openid;
                $userdetail['user_id'] = $user->user_id;
                Session::put("userinfo",$userdetail);
                return Session::get("userinfo");
            }
        }else{
            return Session::get("userinfo");
        }
}

 function Msg_order($user_id,$orderid,$time,$oid){
     //$ranurl = config("app.orderinfopt");
     $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
     $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
     $user_openid = $user_openid[0]->openid;
     $timeout = 5;
     $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
     $post_data = "
		 {
           \"touser\":\"$user_openid\",
           \"template_id\":\"0Fr6gyVycAXoPjEDSHwduHsrzYAHMHoksBUCX7pWx_A\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"您有新订单，请及时处理，谢谢。\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"新订单\",
                       \"color\":\"#000\"
                   },
				   \"keyword2\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"内详\",
                       \"color\":\"#000\"
                   },
				   \"remark\":{
                       \"value\":\"您有新订单,点击查看详情。\",
                       \"color\":\"#FD725D\"
                   }


           }
       }
		";


     $ch = curl_init();

     curl_setopt ($ch, CURLOPT_URL, $url);

     curl_setopt ($ch, CURLOPT_POST, 1);

     if($post_data != ''){

         curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

     }

     curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

     curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

     curl_setopt($ch, CURLOPT_HEADER, false);

     $file_contents = curl_exec($ch);

     curl_close($ch);

 }


function Msg_forgtt($user_id,$orderid,$time,$oid){
    //$ranurl = config("app.orderinfopt");
    $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"0Fr6gyVycAXoPjEDSHwduHsrzYAHMHoksBUCX7pWx_A\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"对方已同意您的预约，请及时付款\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"对方同意\",
                       \"color\":\"#000\"
                   },
           \"keyword2\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"内详\",
                       \"color\":\"#000\"
                   },
           \"remark\":{
                       \"value\":\"对方已同意预约，点击查看详情\",
                       \"color\":\"#00e54b\"
                   }
           }
       }
    ";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}
function Msg_forgtt_no($user_id,$orderid,$time,$message,$oid){
    $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
		 {
           \"touser\":\"$user_openid\",
           \"template_id\":\"0Fr6gyVycAXoPjEDSHwduHsrzYAHMHoksBUCX7pWx_A\",
           \"url\":\"\",
           \"data\":{
                   \"first\": {
                       \"value\":\"对方已拒绝您的预约，您可以继续浏览其他信息\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"拒绝\",
                       \"color\":\"#000\"
                   },
				   \"keyword2\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"内详\",
                       \"color\":\"#000\"
                   },
				   \"remark\":{
                       \"value\":\"对方拒绝预约，$message\",
                       \"color\":\"#00e54b\"
                   }


           }
       }
		";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}

function Msg_wxpay($oid,$user_id,$orderid,$time,$jine,$state){
    $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $jine = $jine*0.01;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    if($state == 1){
        $post_data = "
		 {
           \"touser\":\"$user_openid\",
           \"template_id\":\"AQjTI3fdnvttmd592fU_q86eiW-B8DCo1d008btejY4\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"恭喜您支付成功，这是您的支付凭证\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
				   \"keyword2\":{
                       \"value\":\"$jine 元\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"微信支付\",
                       \"color\":\"#000\"
                   },
				   \"remark\":{
                       \"value\":\"支付成功，如有疑问请联系客服\",
                       \"color\":\"#00e54b\"
                   }


           }
       }
		";
    }else{
        $post_data = "
		 {
           \"touser\":\"$user_openid\",
           \"template_id\":\"AQjTI3fdnvttmd592fU_q86eiW-B8DCo1d008btejY4\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"您的订单，对方已支付，开启约会旅程吧\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
				   \"keyword2\":{
                       \"value\":\"$jine 元\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"微信支付\",
                       \"color\":\"#000\"
                   },
				   \"remark\":{
                       \"value\":\"对方已支付，请查看订单详情，如有疑问请联系客服\",
                       \"color\":\"#FD725D\"
                   }


           }
       }
		";
    }



    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}

function Msg_audit($user_id,$name,$tel){
    $ranurl = config("app.orderinfopt");
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"6i4eNKUyutgXFAOoJ5MbIy-kEkM0DodwMn0U3yuktv8\",
           \"url\":\"\",
           \"data\":{
                   \"first\": {
                       \"value\":\"审核通知\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$name\",
                       \"color\":\"#000\"
                   },
                   \"keyword2\":{
                               \"value\":\"$tel\",
                               \"color\":\"#000\"
                           },
                   \"remark\":{
                       \"value\":\"您已通过审核\",
                       \"color\":\"#FD725D\"
                   }
           }
       }
    ";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}

function Msg_audit_no($user_id,$name,$tel,$content){
    $ranurl = config("app.orderinfopt");
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"6i4eNKUyutgXFAOoJ5MbIy-kEkM0DodwMn0U3yuktv8\",
           \"url\":\"\",
           \"data\":{
                   \"first\": {
                       \"value\":\"审核通知\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$name\",
                       \"color\":\"#000\"
                   },
                   \"keyword2\":{
                               \"value\":\"$tel\",
                               \"color\":\"#000\"
                           },
                   \"remark\":{
                       \"value\":\"您未通过审核:$content\",
                       \"color\":\"#FD725D\"
                   }
           }
       }
    ";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}

//通知英语版本

 function Msg_order_en($user_id,$orderid,$time,$oid){
     //$ranurl = config("app.orderinfopt");
  $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
     $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
     $user_openid = $user_openid[0]->openid;
     $timeout = 5;
     $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
     $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"0Fr6gyVycAXoPjEDSHwduHsrzYAHMHoksBUCX7pWx_A\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"You have a new reservation. Please deal with it in time. Thanks. \",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"New Reservation\",
                       \"color\":\"#000\"
                   },
           \"keyword2\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"Click to view Details\",
                       \"color\":\"#000\"
                   },
           \"remark\":{
                       \"value\":\"You have a new reservation. Click to view the details. \",
                       \"color\":\"#FD725D\"
                   }


           }
       }
    ";


     $ch = curl_init();

     curl_setopt ($ch, CURLOPT_URL, $url);

     curl_setopt ($ch, CURLOPT_POST, 1);

     if($post_data != ''){

         curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

     }

     curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

     curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

     curl_setopt($ch, CURLOPT_HEADER, false);

     $file_contents = curl_exec($ch);

     curl_close($ch);

 }


function Msg_forgtt_en($user_id,$orderid,$time,$oid){
    $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"0Fr6gyVycAXoPjEDSHwduHsrzYAHMHoksBUCX7pWx_A\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"Your order has been approved. Please pay in time\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"Agreed\",
                       \"color\":\"#000\"
                   },
           \"keyword2\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"Click to view details\",
                       \"color\":\"#000\"
                   },
           \"remark\":{
                       \"value\":\"Your order has been approved. Please click to see the details.\",
                       \"color\":\"#00e54b\"
                   }


           }
       }
    ";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}
function Msg_forgtt_no_en($user_id,$orderid,$time,$message,$oid){
    $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"0Fr6gyVycAXoPjEDSHwduHsrzYAHMHoksBUCX7pWx_A\",
           \"url\":\"\",
           \"data\":{
                   \"first\": {
                       \"value\":\"Your order has not been approved. You could book other mentors.\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"Declined\",
                       \"color\":\"#000\"
                   },
           \"keyword2\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"Click to view details\",
                       \"color\":\"#000\"
                   },
           \"remark\":{
                       \"value\":\"Your order has not been approved.$message\",
                       \"color\":\"#00e54b\"
                   }


           }
       }
    ";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}

function Msg_wxpay_en($oid,$user_id,$orderid,$time,$jine,$state){
    $ranurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd3dfc07729fdbd67&redirect_uri=http%3a%2f%2fweixin.linguasolutions.cn%2fhome%2forderinfo&response_type=code&scope=snsapi_userinfo&state='.$oid.'&connect_redirect=1#wechat_redirect';
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $jine = $jine*0.01;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    if($state == 1){
        $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"AQjTI3fdnvttmd592fU_q86eiW-B8DCo1d008btejY4\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"Congrats on your successful payment! This is your receipt.\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
           \"keyword2\":{
                       \"value\":\"$jine yuan\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"WeChat payment\",
                       \"color\":\"#000\"
                   },
           \"remark\":{
                       \"value\":\"Successful payment,If you have any questions, please contact 13911397024.\",
                       \"color\":\"#00e54b\"
                   }


           }
       }
    ";
    }else{
        $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"AQjTI3fdnvttmd592fU_q86eiW-B8DCo1d008btejY4\",
           \"url\":\"$ranurl\",
           \"data\":{
                   \"first\": {
                       \"value\":\"Your order has been paid. Please contact to decide when and where to meet up.Thanks.\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$orderid\",
                       \"color\":\"#000\"
                   },
           \"keyword2\":{
                       \"value\":\"$jine yuan\",
                       \"color\":\"#000\"
                   },
                   \"keyword3\":{
                       \"value\":\"$time\",
                       \"color\":\"#000\"
                   },
                   \"keyword4\":{
                       \"value\":\"WeChat payment\",
                       \"color\":\"#000\"
                   },
           \"remark\":{
                       \"value\":\"Your oder has been successfully paid! if you have any question, please contact 13911397024.\",
                       \"color\":\"#FD725D\"
                   }


           }
       }
    ";
    }



    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}

function Msg_audit_en($user_id,$name,$tel){
    $ranurl = config("app.orderinfopt");
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"6i4eNKUyutgXFAOoJ5MbIy-kEkM0DodwMn0U3yuktv8\",
           \"url\":\"\",
           \"data\":{
                   \"first\": {
                       \"value\":\"Registration Notice\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$name\",
                       \"color\":\"#000\"
                   },
                   \"keyword2\":{
                               \"value\":\"$tel\",
                               \"color\":\"#000\"
                           },
                   \"remark\":{
                       \"value\":\"Your profile has been approved.\",
                       \"color\":\"#FD725D\"
                   }
           }
       }
    ";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}

function Msg_audit_no_en($user_id,$name,$tel,$content){
    $ranurl = config("app.orderinfopt");
    $user_openid = DB::table("users")->where("user_id","=",$user_id)->get();
    $user_openid = $user_openid[0]->openid;
    $timeout = 5;
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
    $post_data = "
     {
           \"touser\":\"$user_openid\",
           \"template_id\":\"6i4eNKUyutgXFAOoJ5MbIy-kEkM0DodwMn0U3yuktv8\",
           \"url\":\"\",
           \"data\":{
                   \"first\": {
                       \"value\":\"Registration Notice\",
                       \"color\":\"#000\"
                   },
                   \"keyword1\":{
                       \"value\":\"$name\",
                       \"color\":\"#000\"
                   },
                   \"keyword2\":{
                               \"value\":\"$tel\",
                               \"color\":\"#000\"
                           },
                   \"remark\":{
                       \"value\":\"Your profile hasn't been approved.$content\",
                       \"color\":\"#FD725D\"
                   }
           }
       }
    ";


    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

}






