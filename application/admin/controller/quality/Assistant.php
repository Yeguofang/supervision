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
  
        $result = db('check_msg')
            ->alias('m')
            ->field('m.open_time,m.task,m.c_supervisor,p.project_name `name`,a.nickname `nickname`')
            ->join('project p','m.project_id=p.id')
            ->join('admin a','m.quality_id=a.id')
            ->where('m.quality_assistant', $adminId)
            ->where('m.c_status', 1)
            ->select();
        $this->assign('result', $result);//著指责发起检擦，待带指派监督人员的项目
        $this->assign('count',count($result));//项目总数


        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            //自己指派的项目quality_assistant
            $map['quality_assistant'] = $adminId;
          
            $field = "l.licence_code `licence_code`,project.id,project.build_dept,project.project_name,project.quality_code,project.address,quality_progress,i.schedule `i.schedule`,i.project_kind `i.project_kind`,i.status `i.status`,i.situation `i.situation`,a.nickname `a.nickname`";
            $total = $this->model
                ->alias("project")
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('quality_info i', 'project.quality_info=i.id','LEFT')
                ->join('admin a', 'project.quality_id=a.id', 'LEFT')
                ->join('licence l','project.licence_id=l.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->count();

            $list = $this->model
                ->alias("project", '')
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('quality_info i', 'project.quality_info=i.id','LEFT')
                ->join('admin a', 'project.quality_id=a.id', 'LEFT')
                ->join('licence l','project.licence_id=l.id')
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
        $row['project_type'] =explode(',', $row['project_type']);//工程项目
        $infoId = $row['quality_info'];
        $info = db('quality_info')->where(['id' => $infoId])->find();
        $info['floor_up'] = explode(",", $info['floor'])[0];
        $info['floor_down'] = explode(",", $info['floor'])[1];
        $extra = explode(",", $info['status_extra']);
        if (count($extra) == 2) {
            $info['extra_type'] = $extra[0];
            $info['extra_floor'] = $extra[1];
        }else{
            $info['extra_type'] = '';
            $info['extra_floor'] = '';
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

        $result = db('check_msg')->where('project_id', $ids)->where('c_status', 2)->where('status', 2)->find();//判断是否副站指派人员
        $res = db('check_msg')->where('project_id', $ids)->where('c_status', 3)->where('status', 2)->find();//判断是否站长指派人员
        
        if ($res) {
            return "<span style='color:red;'>站长已发起检查：<br/>人员名单：".$res['c_supervisor']."<br/>检查任务：".$res['task']."</span>";
        }
        if ($result) {
            return "<span style='color:red;'>你已发起检查<br/>人员名单：".$result['c_supervisor']."<br/>检查任务：".$result['task']."</span>";
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
                    return '发起成功';
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