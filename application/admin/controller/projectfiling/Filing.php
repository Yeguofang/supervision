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
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index()
    {
        if ($this->request->isAjax()) {

            $field = "l.licence_code `licence_code`,project.id,project.build_dept,project.project_name,project.address,project.push_time,project.supervise_time,project.begin_time,project.finish_time,project.check_time,project.recode_status,project.record_time";

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->alias('project')
                ->field($field)
                ->where($where)
                ->join('licence l','project.licence_id=l.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                 ->alias('project')
                 ->field($field)
                ->where($where)
                ->join('licence l','project.licence_id=l.id')
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

    //工程备案
    public function recodeStatus($ids)
    {
        if ($this->request->isAjax()) {
            $data['recode_status'] = 1;//状态：1.已备案，0.未备案
            $data['record_time'] = strtotime(date('Y-m-d H:i:s', time()));
            $res = db('project')->where('id', $ids)->update($data);

            if ($res == 1) {
                return $this->success("备案成功");
            }
        }
    }

    public function message()
    {
        $adminId = session::get('admin')['id'];//账号id
        $group = db('auth_group_access')->where('uid', $adminId)->find();//查出账号所属的分组
        if ($group['group_id'] == 9) {//判断账号是否为建管部门

            $time = Date("Y-m-d");
            $project_id = array();
            $check_time = db('project')->where('check_time', 'not null')->where('recode_status',0)->select(); //查询有备案时间的。还没备案
            for ($i = 0; $i < count($check_time); $i++) {
                $terms_second =  strtotime($time) -$check_time[$i]['check_time']."+15 day";
                $terms_day = floor($terms_second / (3600 * 24));//计算到期剩余的天数
                if($terms_day >= 15){
                    array_push($project_id, $check_time[$i]['id']);
                }
            }
            if (empty($project_id)) {//全部都还没到期的记录，就不提醒
                $arr = array('status' => 0);
                return array($arr);
            } else {
                $arr = array('status' => 1);
                return array($arr);
            }
        } else {//不是安监部门则不提醒
            $arr = array('status' => 0);
            return array($arr);
        }
    }

    public function notice()
    {
        $time = Date("Y-m-d");
        $project_id = array();
        $check_time = db('project')->where('check_time', 'not null')->where('recode_status',0)->select(); //查询有备案时间的。还没备案
        for ($i = 0; $i < count($check_time); $i++) {
            $terms_second =  strtotime($time) -$check_time[$i]['check_time']."+15 day";
            $terms_day = floor($terms_second / (3600 * 24));//计算到期剩余的天数
            if($terms_day >= 15){
                array_push($project_id, $check_time[$i]['id']);
            }
        }
        $project = db('project')->where('id', 'in', $project_id)->select();
		  for($i=0;$i<count($project);$i++){
            $project[$i]['check_time'] = DataTiem($project[$i]['check_time']);
        }
        $this->assign('project', $project);
        return $this->fetch();
    }


}