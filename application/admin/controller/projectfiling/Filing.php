<?php

/**
 * Created by Visual Studio code.
 * User:  Yeguofang
 * Date: 2018/12/15
 * Time: 12:35
 */
namespace app\admin\controller\projectfiling;

use app\common\controller\Backend;
use think\Session;
use think\Db;
//建管中心——工程备案

class Filing extends Backend
{
    protected $noNeedRight = ['*'];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->alias('project')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model->alias('project')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['begin_time'] = DataTiem($list[$i]['begin_time']);
                $list[$i]['finish_time'] = DataTiem($list[$i]['finish_time']);
                $list[$i]['check_time'] = DataTiem($list[$i]['check_time']);
                $list[$i]['supervise_time'] = DataTiem($list[$i]['supervise_time']);
                $list[$i]['record_time'] = DataTiem($list[$i]['record_time']);
                $list[$i]['push_time'] = DataTiem($list[$i]['push_time']);
            }
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //工程备案状态
    public function recodeStatus($ids)
    {
        if ($this->request->isAjax()) {
            $data['recode_status'] = 1;
            $data['record_time'] = strtotime(date('Y-m-d H:i:s', time()));
            $res = db('project')->where('id', $ids)->update($data);

            if ($res == 1) {
               return $this->success("备案成功");
            }
        }
    }

}