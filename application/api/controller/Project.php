<?php
/**
 * Created by Visual Studio code.
 * User:  Yeguofang
 * Date: 2018/12/17
 * Time: 1:35
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
                $data = $this->quality();
                if($data == null){
                    return $this->error('无数据', 0);
                }
                return $this->success('', $data);
        } else if ($user['identity'] == 1) {
                //安监部门
                $data = $this->safetily();
                if($data == null){
                    return $this->error('无数据', 0);
                }
                return $this->success('', $data);
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
            //副站长只能看到；quality_assistant是自己
            $map['quality_assistant'] = $user['admin_id'];
        } elseif ($user['identity_type'] == 2) {
            //质监员只能看到；quality_id是自己
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

        return $data;
    }


     //项目列表
    public function safetily()
    {

        $user = $this->auth->getUser();
        $page = $this->request->param('page', 1);
        $num = $this->request->param('num', 2);
        $map = [];

        if ($user['identity_type'] == 1) {
             //副站长只能看到supervisor_assistant是自己
            $map['supervisor_assistant'] = $user['admin_id'];
        } elseif ($user['identity_type'] == 2) {
             //安监员只能看到security_id是自己
            // $map['security_id'] = $user['admin_id'];
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