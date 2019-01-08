<?php

/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\safety;

use app\common\controller\Backend;
use think\Db;
use think\Session;


//管理
class Devicecount extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = false;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('device');
    }

    //机械管理
    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = db('device')
                ->where($where)
                ->count();

            $list =  db('device')
                ->where($where)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }


   

   //重大危险源信息详情
    public function detail($ids)
    {
        $data = db('device')->where('id', $ids)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }

   


}