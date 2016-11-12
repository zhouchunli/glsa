<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
use Session;
class AdminController extends Controller
{

    public function index()
    {
        $title = '首页';
        return view('admin/index',[
            "title"=>$title,
        ]);
    }


}