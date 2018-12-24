<?php
use app\common\model\Area;
use app\common\model\Version;


 //时间转换
 function convertTime($val){
    if($val != null){
        $val = date("Y-m-d",$val);
    }else{
        $val = "暂无";
        
    }
return $val;
}


