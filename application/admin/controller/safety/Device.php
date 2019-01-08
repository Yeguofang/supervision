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
class Device extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = false;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('device');
    }

    //机械管理
    public function index($ids = null)
    {
       //把当前项目的id存在session，
        Session::set('deviceId', $ids);
        return $this->view->fetch();
    }

    public function list()
    {

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $id = Session::get('deviceId');
            $total = $this->model
                ->where('project_id', $id)
                ->where($where)
                ->count();

            $list = $this->model
                ->where('project_id', $id)
                ->where($where)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

    }

   //添加机械
    public function add()
    {
        $id = session::get('deviceId');
        $project = db('project')->field('project_name')->where('id', $id)->find();
        $this->assign('project', $project);
        if ($this->request->isAjax()) {
            $row = $this->request->post('row/a');
            $data['project_id'] = $id;
            $data['supervision_number'] = $row['supervision_number'];
            $data['project_name'] = $row['project_name'];
            $data['type'] = $row['type'];
            $data['device_record'] = $row['device_record'];
            $data['install_unit'] = $row['install_unit'];
            $data['install_time'] = $row['install_time'];
            $data['test_time'] = $row['test_time'];
            $data['test_end_time'] = $row['test_end_time'];
            $data['handle_time'] = $row['handle_time'];
            $res = db('device')->insert($data);
            if ($res) {
                $this->success('添加成功');
            }
            $this->error('添加失败');
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

   

   //改变消息状态，已处理
    public function status($ids)
    {
        if ($this->request->isAjax()) {
            $data['status'] = 1;
            $res = db('device')->where('id', $ids)->update($data);
            if ($res) {
                return 1;
            }
            return 0;
        }
    }

}