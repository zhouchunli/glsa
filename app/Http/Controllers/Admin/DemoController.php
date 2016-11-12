<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
use Session;
use App\Models\Admin;
use App\Models\Menu;
use App\Models\Users;
use App\Models\User_details;
use URL;
use Excel;
use Config;
class UsersController extends Controller
{

    public function index()
    {
        /*写入此页面ID*/$menuid = "1";//******用户权限验证*******
        /******/$data = Admin::all();$menu = Menu::all();//******
        /******/$powarr = explode(",",Session::get("power"));//**
        /******/if(!in_array($menuid,$powarr)){return view(//***
        'admin/power/nopower',["data"=>$data,"menu"=>//**********
        $menu,"title"=>"权限",]);}//*****************************
        //*******************************************************
        $pagesize = config("app.pagesize");
        $userdata = Users::selectRaw("*")
            ->join('user_details','users.user_id',"=","user_details.user_id")
            ->orderby("users.user_id","DESC")
            ->paginate($pagesize);
        $userdata->setPath('users');
        $title = '用户管理';
        return view('admin/users',[
            "data"=>$data,
            "menu"=>$menu,
            "title"=>$title,
            "userdata"=>$userdata,

        ]);
    }
    public function upmaster(){
        $db = Users::find(Input::get("uid"));
        $db->idcard = Input::get("state");
        $aa = $db->save();
        if($aa){
            echo "ok";
        }


    }
    public function userstate(){
        $userid = explode(',',Input::get("id"));
        if(Input::get("state") == 1){
            foreach($userid as $item){
                $db = Users::find($item);
                $db->state = "1";
                $aa = $db->save();
            }
        }else{
            foreach($userid as $item){
                $db = Users::find($item);
                $db->state = "0";
                $aa = $db->save();
            }
        }
        if($aa){
            echo "ok";
        }


    }
    public function excelpush(){
        $idarr = explode(",",Input::get("id"));
        $cellData = [
            ['用户编号','状态标识','昵称','性别','年龄','手机号','注册日期','提问次数','是否达人','被举报次数'],
        ];
        foreach($idarr as $item){
            $details = array();
            $userdata = Users::selectRaw("*")
                ->join('user_details','users.user_id',"=","user_details.user_id")
                ->where("users.user_id","=",$item)
                ->get();
            array_push($details,$userdata[0]->user_id);
            array_push($details,$userdata[0]->state);
            array_push($details,$userdata[0]->nickname);
            array_push($details,$userdata[0]->gender);
            array_push($details,$userdata[0]->age);
            array_push($details,$userdata[0]->tel);
            array_push($details,$userdata[0]->created_at);
            array_push($details,countquestion($userdata[0]->user_id));
            array_push($details,$userdata[0]->idcard);
            array_push($details,countAccusation($userdata[0]->user_id));

            array_push($cellData,$details);
        }
        Excel::create('用户信息导出',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

}