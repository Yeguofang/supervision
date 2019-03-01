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
class System extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目管理
    public function index()
    {

        list($where, $sort, $order, $offset, $limit) = $this->buildparams();

        $field = "l.licence_code `licence_code`,project.id,project.build_dept,project.project_name,project.address,project.supervisor_code,supervisor_progress,i.project_kind `i.project_kind`,i.status `i.status`,i.situation `i.situation`,a.nickname `a.nickname`,s.nickname `s.nickname`";
        $total = $this->model
            ->alias("project")
            ->field($field)
            ->where($where)
            ->join('quality_info i', 'project.quality_info=i.id')
            ->join('admin a', 'project.security_id=a.id', 'LEFT')
            ->join('admin s', 'project.supervisor_assistant=s.id', 'LEFT')
            ->join('licence l','project.licence_id=l.id')
            ->order($sort, $order)
            ->count();

        $list = $this->model
            ->alias("project", '')
            ->field($field)
            ->where($where)
            ->join('quality_info i', 'project.quality_info=i.id')
            ->join('admin a', 'project.security_id=a.id', 'LEFT')
            ->join('admin s', 'project.supervisor_assistant=s.id', 'LEFT')
            ->join('licence l','project.licence_id=l.id')
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
        $result = array("total" => $total, "rows" => $list);
        return $this->view->fetch();
    }


    public function message()
    {
        $adminId = session::get('admin')['id'];
        $group = db('auth_group_access')->where('uid', $adminId)->find();//查出账号所属的分组

        if ($group['group_id'] == 14 || $group['group_id'] == 15 || $group['group_id'] == 16 || $group['group_id'] == 17) {//判断账号是否为安监部门

            $time = Date("Y-m-d");
            $term_ids = array();
            $build_ids = array();
            $suspend_ids = array();
            $stop_ids = array();
            $device_ids = array();

            $term = db('safety_books')->where('status', 0)->where('type', 1)->select(); //1 .限期整改通知书，
            $build = db('safety_books')->where('status', 0)->where('type', 2)->select(); //2.施工安全抽查记录，
            $suspend = db('safety_books')->where('status', 0)->where('type', 3)->select(); //3.暂停施工通知书，
            $stop = db('safety_books')->where('status', 0)->where('type', 4)->select(); //4.停工整改通知书
            $device = db('device')->select();

            for ($i = 0; $i < count($term); $i++) {
                //对两个时间差进行差运算
                if ((strtotime($time. "+15 day") - strtotime($term[$i]['expire_time']) > 0)) {
                    array_push($term_ids, $term[$i]['id']);
                }
            }

            for ($i = 0; $i < count($build); $i++) {
                if ((strtotime($time. "+15 day") - strtotime($build[$i]['expire_time']) > 0)) {
                    array_push($build_ids, $build[$i]['id']);
                }
            }

            for ($i = 0; $i < count($suspend); $i++) {
                if ((strtotime($time. "+15 day") - strtotime($suspend[$i]['expire_time']) > 0)) {
                    array_push($suspend_ids, $suspend[$i]['id']);
                }
            }

            for ($i = 0; $i < count($stop); $i++) {
                if ((strtotime($time. "+15 day") - strtotime($stop[$i]['expire_time']) > 0)) {
                    array_push($stop_ids, $stop[$i]['id']);
                }
            }
            for ($i = 0; $i < count($device); $i++) {
                if ((strtotime($time . "+15 day") - strtotime($device[$i]['test_end_time']) > 0)) {
                    array_push($device_ids, $device[$i]['id']);
                }
            }
            if (empty($term_ids) && empty($build_ids) && empty($suspend_ids) && empty($stop_ids) && empty($device_ids)) {//全部都还没到期的记录，就不提醒
                $arr = array('status' => 0);
                return array($arr);
            } else {
                $arr = array('status' => 1);
                return array($arr);
            }
            $terms = db('safety_books')->where('id', 'in', $term_ids)->select();
            $builds = db('safety_books')->where('id', 'in', $build_ids)->select();
            $suspends = db('safety_books')->where('id', 'in', $suspend_ids)->select();
            $stops = db('safety_books')->where('id', 'in', $stop_ids)->select();
            $devices = db('device')->where('id', 'in', $device_ids)->select();
        } else {//不是安监部门则不提醒
            $arr = array('status' => 0);
            return array($arr);
        }
    }

    public function notice()
    {


        $time = Date("Y-m-d");
        $term_ids = array();
        $build_ids = array();
        $suspend_ids = array();
        $stop_ids = array();
        $device_ids = array();

        $term = db('safety_books')->where('status', 0)->where('type', 1)->select(); //1 .限期整改通知书，
        $build = db('safety_books')->where('status', 0)->where('type', 2)->select(); //2.施工安全抽查记录，
        $suspend = db('safety_books')->where('status', 0)->where('type', 3)->select(); //3.暂停施工通知书，
        $stop = db('safety_books')->where('status', 0)->where('type', 4)->select(); //4.停工整改通知书
        $device = db('device')->select();

        for ($i = 0; $i < count($term); $i++) {
            //对两个时间差进行差运算
            if ((strtotime($time. "+15 day") - strtotime($term[$i]['expire_time']) > 0)) {
                array_push($term_ids, $term[$i]['id']);
            }
        }

        for ($i = 0; $i < count($build); $i++) {
            if ((strtotime($time. "+15 day") - strtotime($build[$i]['expire_time']) > 0)) {
                array_push($build_ids, $build[$i]['id']);
            }
        }

        for ($i = 0; $i < count($suspend); $i++) {
            if ((strtotime($time. "+15 day") - strtotime($suspend[$i]['expire_time']) > 0)) {
                array_push($suspend_ids, $suspend[$i]['id']);
            }
        }

        for ($i = 0; $i < count($stop); $i++) {
            if ((strtotime($time. "+15 day") - strtotime($stop[$i]['expire_time']) > 0)) {
                array_push($stop_ids, $stop[$i]['id']);
            }
        }
        for ($i = 0; $i < count($device); $i++) {
            if ((strtotime($time . "+15 day") - strtotime($device[$i]['test_end_time']) > 0)) {
                array_push($device_ids, $device[$i]['id']);
            }
        }

        $terms = db('safety_books')->where('id', 'in', $term_ids)->select();
        $builds = db('safety_books')->where('id', 'in', $build_ids)->select();
        $suspends = db('safety_books')->where('id', 'in', $suspend_ids)->select();
        $stops = db('safety_books')->where('id', 'in', $stop_ids)->select();
        $devices = db('device')->where('id', 'in', $device_ids)->select();

        for ($i = 0; $i < count($terms); $i++) {
            $terms_second = strtotime($terms[$i]['expire_time']) - strtotime($time);
            $terms_day = floor($terms_second / (3600 * 24));//计算到期剩余的天数
            $terms[$i]['day'] = $terms_day;
        }
        for ($i = 0; $i < count($builds); $i++) {
            $builds_second = strtotime($builds[$i]['expire_time']) - strtotime($time);
            $builds_day = floor($builds_second / (3600 * 24));//计算到期剩余的天数
            $builds[$i]['day'] = $builds_day;
        }
        for ($i = 0; $i < count($suspends); $i++) {
            $suspends_second = strtotime($suspends[$i]['expire_time']) - strtotime($time);
            $suspends_day = floor($suspends_second / (3600 * 24));//计算到期剩余的天数
            $suspends[$i]['day'] = $suspends_day;
        }
        for ($i = 0; $i < count($stops); $i++) {
            $stops_second = strtotime($stops[$i]['expire_time']) - strtotime($time);
            $stops_day = floor($stops_second / (3600 * 24));//计算到期剩余的天数
            $stops[$i]['day'] = $stops_day;
        }
        for ($i = 0; $i < count($devices); $i++) {
            $devices_second = strtotime($devices[$i]['test_end_time']) - strtotime($time);
            $devices_day = floor($devices_second / (3600 * 24));//计算到期剩余的天数
            $devices[$i]['day'] = $devices_day;
        }

        $all = array(
            'terms' => $terms,
            'builds' => $builds,
            'suspends' => $suspends,
            'stops' => $stops,
            'devices' => $devices
        );
        $this->assign('all', $all);
        return $this->fetch();
    }



   //改变消息状态，已处理
    public function status($ids)
    {
        if ($this->request->isAjax()) {
            $data['status'] = 1;
            $res = db('safety_books')->where('id', $ids)->update($data);
            if ($res) {
                return 1;
            }
            return 0;
        }
    }








}