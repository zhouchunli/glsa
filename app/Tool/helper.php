<?php
/*
|--------------------------------------------------------------------------
|     引入自定义类库
|--------------------------------------------------------------------------
|
|     自定义类库，主要用于帮助类工具等
|     方便开发需求
|     全局应用，不需要实例化
|     例子：调用function test($msg){} 项目中：test($msg);
|
|
|                                                               By--Yancey
|--------------------------------------------------------------------------
*/
use App\Models\Log;
use Illuminate\Session;
use App\Models\Users;
use App\Models\User_details;
use App\Models\Question;
use App\Models\Accusation;


//格式化输出
function v($msg){
    echo "<pre>";print_r($msg);echo "<pre>";
}
//问答统计
function countquestion($user_id){
    $count = Question::selectRaw('count(*) as count')
        ->where("user_id","=",$user_id)->get();
    return $count[0]->count;
}
//被举报统计
function countAccusation($user_id){
    $count = Accusation::selectRaw('count(*) as count')
        ->where("g_user_id","=",$user_id)->get();
    return $count[0]->count;
}




















