<?php

/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\quality;

use app\common\controller\Backend;
use think\Session;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_IOFactory;

//副站长项目管理
class Assistant extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //副站长项目管理列表
    public function index()
    {
        $adminId = Session::get('admin')['id'];
        $result = db('check_msg')->where('quality_assistant', $adminId)->where('c_status', 1)->select();
        
        $time = array();
        $ids = array();
        $quality_id =array();
        for ($i = 0; $i < count($result); $i++) {
            array_push($ids, $result[$i]['project_id']);
            array_push($time, $result[$i]['open_time']);
            array_push($quality_id, $result[$i]['quality_id']);
        }
        $project = db('project')->field('project_name')->where('id', 'in', $ids)->select();
        $name = db('admin')->field('nickname')->where('id', 'in', $quality_id)->select();
        $this->assign('project', $project);//著指责发起检擦，待带指派监督人员的项目
        $this->assign('count',count($project));//项目总数
        $this->assign('time', $time);//发起检查的时间
        $this->assign('name', $name);//发起检查的主责

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            //自己指派的项目quality_assistant
            $map['quality_assistant'] = $adminId;
            $field = "project.id,project.build_dept,project.project_name,project.address,project.quality_code,i.project_kind `i.project_kind`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->join('quality_info i', 'project.quality_info=i.id')
                ->field($field)
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project")
                ->join('quality_info i', 'project.quality_info=i.id')
                ->field($field)
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //选择主责
    public function select($ids)
    {
        //查出质检员 12
        $assistant['assistant'] = db('admin admin')
            ->field('admin.id,admin.nickname name,admin.mobile')
            ->join('auth_group_access a', 'a.uid=admin.id and a.group_id=12')
            ->select();
        //查出当前质检员
        $assistant['now'] = db('project')
            ->field('quality_id')
            ->where(['id' => $ids])
            ->find()['quality_id'];

        $this->assign('assistant', $assistant);
        if ($this->request->isAjax()) {
            $data['quality_id'] = $this->request->post('quality_id');
            db('project')->where(['id' => $ids])->update($data);
            $this->success();
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


     //项目检查
    public function qualitycheck($ids)
    {

        $result = db('check_msg')->where('project_id', $ids)->where('status', 2)->find();//判断该项目是否以指派人员
        $res = db('check_msg')->where('project_id', $ids)->where('c_status', 3)->where('status', 2)->find();//判断该项目是否以指派人员
        if ($res) {
            return "<span style='color:red;'>站长已发起检查并指派人员</span>";
        }
        if ($result) {
            return "<span style='color:red;'>已发起检查并指派人员</span>";
        }
        
        $quality_id = db('project')->where('id', $ids)->find();
         //查出除了主责的质监员 12
        $quality = db('admin admin')
            ->where('admin.id', 'neq', $quality_id['quality_id'])
            ->field('admin.id,admin.nickname name')
            ->join('auth_group_access a', 'a.uid=admin.id and a.group_id=12')
            ->select();

        $this->assign('quality', $quality);

        if ($this->request->isPost()) {
            $data = input('post.');
            $result = db('check_msg')->where('project_id', $ids)->where('c_status', 1)->find();
            if ($result == null) {//直接发起检查，并指派监督人员
                $row['c_supervisor'] = implode(",", $data['qulity_name']);
                $row['task'] = $data['task'];
                $row['open_time'] = Date("Y-m-d");
                $row['c_status'] = 2;
                $row['status'] = 2;
                $row['project_id'] = $ids;
                $row['quality_id'] = $quality_id['quality_id'];
                $data = db('check_msg')->insert($row);
                if ($data == 1) {
                    return '发起成功！';
                }
                return '发起失败！';
            } else {//著指责发起检查，副站指派监督人员
                $row['c_supervisor'] = implode(",", $data['qulity_name']);
                $row['task'] = $data['task'];
                $row['c_status'] = 2;
                $row['status'] = 2;
                $data = db('check_msg')->where('id', $result['id'])->update($row);
                if ($data == 1) {
                    return '发起成功！';
                }
                return '发起失败！';
            }
        }
        return $this->fetch();
    }

    //登记告知书
    public function quality($ids)
    {
        quality_inform($ids);
    }



}