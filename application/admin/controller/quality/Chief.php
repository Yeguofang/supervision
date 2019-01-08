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
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_IOFactory;
//质监员的项目管理
class Chief extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index()
    {
        $adminId = Session::get('admin')['id'];
        $result = db('check_msg')->where('quality_id', $adminId)->where('status', 2)->select();

        $time = array();//发起时间
        $ids = array();//项目名称
        $task = array();//检查任务
        $quality_list = array();//协助的监督人员
        for ($i = 0; $i < count($result); $i++) {
            array_push($ids, $result[$i]['project_id']);
            array_push($time, $result[$i]['open_time']);
            array_push($task, $result[$i]['task']);
            array_push($quality_list, $result[$i]['c_supervisor']);
        }
        $project = db('project')->field('project_name')->where('id', 'in', $ids)->select();
        $this->assign('project', $project);
        $this->assign('time', $time);
        $this->assign('task', $task);
        $this->assign('count',count($project));//需要检查的项目总数，用作显示。
        $this->assign('quality_list', $quality_list);

        //查出自己被指派的项目，指派主责
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            //自己指派的项目quality_id
            $map['quality_id'] = $adminId;
            $field = "project.id,project.build_dept,project.project_name,project.quality_code,project.address,i.project_kind `i.project_kind`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('quality_info i', 'project.quality_info=i.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project", '')
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('quality_info i', 'project.quality_info=i.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }


    //下发告知书
    public function deal($ids)
    {
        $adminId = Session::get('admin')['id'];
        //查出排除自己的质检员 12
        $map['admin.id'] = ['neq', $adminId];
        $assistant['assistant'] = db('admin admin')->where($map)->field('admin.id,admin.nickname name,admin.mobile')->join('auth_group_access a', 'a.uid=admin.id and a.group_id=12')->select();
        $quality = [];
        foreach ($assistant['assistant'] as $v) {
            $quality[$v['id']] = "姓名" . $v['name'] . " 手机号" . $v['mobile'];
        }
        $this->assign('quality', $quality);
        if ($this->request->isAjax()) {
                //通过获取编号
            $up['quality_code'] = $this->request->post("code");
            $up['quality_time'] = strtotime($this->request->post("time"));
                //质检人员
            $person = $this->request->post('quality/a');
            if ($person[0] == "") {
                $this->error("失败！通过请选择成员");
            }
            $list = [];
            foreach ($person as $v) {
                $d['id'] = guid();
                $d['admin_id'] = $v;
                $d['project_id'] = $ids;
                $d['create_time'] = time();
                $d['type'] = 1;
                array_push($list, $d);
            }
            db('person_project')->insertAll($list);
            db('project')->where(['id' => $ids])->update($up);
            $this->success();

        }
        return $this->view->fetch();
    }

    //修改责任人
    public function select($ids)
    {
        $adminId = Session::get('admin')['id'];
        //查出排除自己的质检员 12
        $map['admin.id'] = ['neq', $adminId];
        $assistant['assistant'] = db('admin admin')->where($map)->field('admin.id,admin.nickname name,admin.mobile')->join('auth_group_access a', 'a.uid=admin.id and a.group_id=12')->select();
        $quality = [];
        foreach ($assistant['assistant'] as $v) {
            $quality[$v['id']] = "姓名" . $v['name'] . " 手机号" . $v['mobile'];
        }
        $this->assign('quality', $quality);
        //查出当前质检员  项目id，type为质检员
        $map['p.project_id'] = $ids;
        $map['p.type'] = 1;
        $assistant['now'] = db('person_project p')->join('admin admin', 'admin.id=p.admin_id')->where($map)->select();
        $now = [];
        $before = '';
        foreach ($assistant['now'] as $value) {
            $now[] = $value['admin_id'];
            $before = $before . $value['admin_id'] . ',';
        }
        $before = rtrim($before, ',');
        $this->assign('now', $now);
        if ($this->request->isAjax()) {
            //新增的质检人员
            $person = $this->request->post('quality/a');
            if ($person[0] == "") {
                $this->error("不能为空！通过请选择成员");
            }
            $list = [];
            $after = "";
            foreach ($person as $v) {
                $after = $after . $v . ',';
                $d['id'] = guid();
                $d['admin_id'] = $v;
                $d['project_id'] = $ids;
                $d['create_time'] = time();
                $d['type'] = 1;
                array_push($list, $d);
            }
            $after = rtrim($after, ',');

            //做记录
            Db::startTrans();
            try {

                //更改表
                $change['id'] = guid();
                $change['project_id'] = $ids;
                $change['extra_id'] = $adminId;
                $change['person_type'] = 3;
                $change['before_person'] = $before;
                $change['create_time'] = time();
                $change['after_person'] = $after;
                //插入修改记录
                db('change_person_log')->insert($change);
                //删除原来的人员
                db('person_project')->where(['project_id' => $ids])->delete();
                //新增新的人员
                db('person_project')->insertAll($list);
                Db::commit();
                $this->success();
            } catch (Exception $e) {
                Db::rollback();
                $this->error("操作失败");
            }
        }
        return $this->view->fetch();
    }

    //详细信息
    public function detail($ids)
    {
        $row = db('project')->where(['id' => $ids])->find();
        $infoId = $row['quality_info'];
        $info = db('quality_info')->where(['id' => $infoId])->find();
        $info['floor_up'] = explode(",", $info['floor'])[0];
        $info['floor_down'] = explode(",", $info['floor'])[1];
        $extra = explode(",", $info['status_extra']);
        if (count($extra) == 2) {
            $info['extra_type'] = $extra[0];
            $info['extra_floor'] = $extra[1];
        }
        //施工联系人 监理联系人
        $licence = db('licence')->field('supervision_person,construction_person')->where(['id' => $row['licence_id']])->find();
        $this->assign('licence', $licence);
        $this->assign('row', $row);
        $this->assign('info', $info);
        return $this->view->fetch();
    }

    //登记告知书
    public function quality($ids)
    {
        quality_inform($ids);
    }

    //主责发起检查
    public function applyCheck($ids)
    {
        if ($this->request->isAjax()) {
            $result = db('check_msg')->where('project_id', $ids)->where('c_status', 1)->find();//判断是否发起过
            if ($result) {
                return $this->error("已发起过检查！，等待指派人员");
            }
            $result = db('check_msg')->where('project_id', $ids)->where('status', 2)->find();//判断该项目是否以指派人员
            if ($result) {
                return $this->error("已指派人员。请完成检查");
            }
            $quality_id = db('project')->where('id', $ids)->find();
            $data['status'] = 1;//未指派人员
            $data['c_status'] = 1;//主责发起，未指派人员。
            $data['project_id'] = $ids;//项目id;
            $data['open_time'] = Date("Y-m-d");//发起时间
            $row['quality_id'] = Session::get('admin')['id'];
            $data['quality_assistant'] = $quality_id['quality_assistant'];
            $res = db('check_msg')->insert($data);
            if ($res == 1) {
                return $this->success("已发起检查！");
            }
            return $this->error("发起检查失败！");
        }
    }

}