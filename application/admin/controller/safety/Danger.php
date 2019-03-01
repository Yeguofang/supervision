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


//重大危险源管理
class Danger extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = false;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('danger');
    }



    //项目管理
    public function index($ids = null)
    {
        //把当前项目的id存在session，
        Session::set('dangerId', $ids);
        return $this->view->fetch();
    }

    public function deList()
    {

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $id = Session::get('dangerId');
            $total = $this->model
                ->where('project_id', $id)
                ->count();

            $list = $this->model
                ->where('project_id', $id)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

    }
   
    
    //添加重大危险源
    public function add()
    {
        $id = session::get('dangerId');
        $project = db('project')->field('project_name,address')->where('id', $id)->find();
        $this->assign('project', $project);
        if ($this->request->isAjax()) {
            $row = $this->request->post('row/a');
            $data['project_id'] = $id;
            $data['supervision_number'] = $row['supervision_number'];
            $data['project_name'] = $row['project_name'];
            $data['project_address'] = $row['project_address'];
            $data['proof_time'] = $row['proof_time'];
            $data['proof_address'] = $row['proof_address'];
            $data['proof_content'] = $row['proof_content'];
            $data['proof_info'] =$row['proof_info'];
            $data['build_info'] = $row['build_info'];
            $res = db('danger')->insert($data);
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
        $data = db('danger')->where('id', $ids)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }



}