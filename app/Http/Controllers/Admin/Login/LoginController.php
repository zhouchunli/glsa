<?php

namespace App\Http\Controllers\Admin\Login;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Input;
use Redirect;
use Session;
class LoginController extends Controller
{

    public function index()
    {

//        if(Session::get("user_id")){
//            return Redirect::back()->withInput();
//        }
        $title = '后台登陆/login';
        return view('admin/login',[
            "title"=>$title]);
    }
    public function loginpost(){
        $user = Input::get("username");
        $password = md5(md5(Input::get("password")))  ;
        $userinfo = Admin::selectRaw("*")
            ->where("user_id","=",$user)
            ->get();
        if($password !==@$userinfo[0]->password){
            return Redirect::back()->withInput()->with("msg",'帐号或密码错误！！');
        }else{
            Session::put("user_id",$userinfo[0]->id);
            Session::put("username_i",$userinfo[0]->name);
            Session::put("power",$userinfo[0]->power);
            return Redirect::to("admin/index");
        }
        //dd($userinfo[0]->password);

    }
    public function Logout()
    {
        //
        Session::put('user_id','');
        return Redirect::to('admin/login');
    }


} 
