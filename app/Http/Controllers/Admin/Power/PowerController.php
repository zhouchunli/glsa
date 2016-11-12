<?php

namespace App\Http\Controllers\Admin\Power;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
use Session;
use App\Models\Admin;
use App\Models\Menu;
class PowerController extends Controller
{

    public function index()
    {

        if(Session::get('user_id') !== 88888888){
            echo "非法入口";die;
        }

        $s = Session::get("power");
        $title = '后台登陆/login';
        $data = Admin::all();
        $menu = Menu::all();

        return view('admin/power/power',[
            "title"=>$title,
            "session"=>$s,
            "data"=>$data,
            "menu"=>$menu,
        ]);
    }
    public function powerpost(){
        $adminid = Input::get("admin_id");
        $powerid = array();
        foreach(Input::get() as $key=>$value){
            if(substr($key,0,6) == "power_" && $value == 2){
                array_push($powerid,substr($key,6));
            }
        }
        $powerid = implode(",",$powerid);
        $db = Admin::find($adminid);
        $db->power = $powerid;
        $db->save();
        return Redirect::to('admin/poweredit');

    }


}