<?php

/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/28
 * Time: 23:54
 */
namespace app\api\controller;

use app\common\controller\Api;
use think\Session;
use app\common\model\Area;
use app\common\model\Version;
use fast\Random;
use think\Config;


class Project extends Api
{
    protected $noNeedRight = ['*'];
    // protected $noNeedLogin = ['*'];
   

    //判断身份
    public function index()
    {
        $user = $this->auth->getUser();

        if ($user['identity'] == 0) {
            //质监部门
            if ($user['identity_type'] == 1) {
                //副站长只能看到quality_assistant是自己
                $data =$this->quality();
                return $this->success('', $data);
            } elseif ($user['identity_type'] == 2) {
                //副站长只能看到quality_id是自己
                $data = $this->quality();
                return $this->success('', $data);
            }

        } else if ($user['identity'] == 1) {
            //安部门
            if ($user['identity_type'] == 1) {
                //副站长只能看到quality_assistant是自己
                $data =$this->safetiy();
                return $this->success('', $data);
            } elseif ($user['identity_type'] == 2) {
                //副站长只能看到quality_id是自己
                $data = $this->safetiy();
                return $this->success('', $data);
            }

        }
    }



    //项目列表
    public function quality()
    {

        $user = $this->auth->getUser();
        $page = $this->request->param('page', 1);
        $num = $this->request->param('num', 2);
        $map = [];

        if ($user['identity_type'] == 1) {
            //副站长只能看到quality_assistant是自己
            $map['quality_assistant'] = $user['admin_id'];
        } elseif ($user['identity_type'] == 2) {
            //副站长只能看到quality_id是自己
            $map['quality_id'] = $user['admin_id'];
        }
        //差竣工联系人
        $field = 'p.id,p.build_dept,p.project_name,p.address,p.push_time,p.begin_time,p.finish_time,p.check_time,l.supervision_person,i.status';
        $data = db('project p')
            ->field($field)
            ->join('quality_info i', 'p.quality_info=i.id')
            ->join('licence l', 'p.licence_id=l.id')
            ->where($map)
            ->page($page, $num)
            ->select();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['begin_time'] = convertTime($data[$i]['begin_time']);
            $data[$i]['push_time'] = convertTime($data[$i]['push_time']);
            $data[$i]['finish_time'] = convertTime($data[$i]['finish_time']);
            $data[$i]['check_time'] = convertTime($data[$i]['check_time']);
        }
        // if($data == null){
        //     return 0;
        // }
        return $data;
    }


     //项目列表
    public function safetity()
    {

        $user = $this->auth->getUser();
        $page = $this->request->param('page', 1);
        $num = $this->request->param('num', 2);
        $map = [];

        if ($user['identity_type'] == 1) {
             //副站长只能看到quality_assistant是自己
            $map['supervisor_assistant'] = $user['admin_id'];
        } elseif ($user['identity_type'] == 2) {
             //副站长只能看到quality_id是自己
            $map['security_id'] = $user['admin_id'];
        }
         //差竣工联系人
        $field = 'p.id,p.build_dept,p.project_name,p.address,p.push_time,p.begin_time,p.finish_time,p.check_time,l.supervision_person,i.status';
        $data = db('project p')
            ->field($field)
            ->join('safety_info i', 'p.safety_info=i.id')
            ->join('licence l', 'p.licence_id=l.id')
            ->where($map)
            ->page($page, $num)
            ->select();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['begin_time'] = convertTime($data[$i]['begin_time']);
            $data[$i]['push_time'] = convertTime($data[$i]['push_time']);
            $data[$i]['finish_time'] = convertTime($data[$i]['finish_time']);
            $data[$i]['check_time'] = convertTime($data[$i]['check_time']);
        }
         
        //  if($data == null){
        //     return 0;
        // }


        return $data;
    }







}