<?php

/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\quality;

use app\common\controller\Backend;
use think\Db;
use think\Session;

//通知书列表
class Rectifylist extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }
  //整改通知书列表

    //整改书页面
    public function index($ids = null)
    {
        session::set('proect_rectify_id',$ids);//把项目存放在session
        return $this->view->fetch();
        
    }

    //整改书列表
    public function list()
    {
        $id = Session::get('proect_rectify_id');//取出项目id;
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                $total = db('quality_rectify')
                    ->where($where)
                    ->where('project_id',$id)
                    ->count();
    
                $list = db('quality_rectify')
                    ->where($where)
                    ->where('project_id',$id)
                    ->select();
         
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
      
    }

    //整改书详情
    public function detail($ids){
        $data =db('quality_rectify')->where('id',$ids)->find();
        $data['images'] = "http://47.107.235.179/supervision/public".$data['images'];
        $this->assign('data',$data);
        return $this->fetch();
    }
  

}
