<?php

/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/15
 * Time: 23:07
 */

namespace app\admin\controller\check;

use app\common\controller\Backend;
use think\Session;
//质监站验收
class Quality extends Backend
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
        $adminId = Session::get('admin')['id'];
        $ret = judge_identity($adminId, 1);
        $this->assign('ret', $ret);
        $map = [];
        if ($ret == 1) {
            //站长
//            $map['supervisor_progress']=0;
        } elseif ($ret == 2) {
            //副站长
//            $map['supervisor_progress']=1;
            $map['quality_assistant'] = $adminId;
        } else {
            //质检员
//            $map['supervisor_progress']=2;
            $map['quality_id'] = $adminId;
        }
        if ($this->request->isAjax()) {
            // 查出下发了告知书的  如果已经申请了竣工的没有竣工按钮
            $filed = "id,project_name,build_dept,address,begin_time,finish_time,check_time,finish_time,quality_progress,build_check";
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->alias('project')
                ->field($filed)
                ->where($where)
                ->whereNotNull('quality_code')
                ->where($map)
                ->order($sort, $order)
                ->count();

            $list = $this->model->alias('project')
                ->field($filed)
                ->where($where)
                ->whereNotNull('quality_code')
                ->where($map)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['begin_time'] = DataTiem($list[$i]['begin_time']);
                $list[$i]['finish_time'] = DataTiem($list[$i]['finish_time']);
                $list[$i]['check_time'] = DataTiem($list[$i]['check_time']);
            }
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //五大责任主体
    public function detail($ids)
    {
        $data = db('project p')->where(['p.id' => $ids])
            ->join('s_licence l', 'l.id=p.licence_id')
            ->find();
        $this->assign("data", $data);
        return $this->view->fetch();
    }

    //通知
    public function notice($ids, $ret)
    {


        $quality_id = db('project')->where('id', $ids)->find();
        //查出除了主责的质监员 12
        if ($ret == 0) {//如果是没有申请竣工，主责选择参与验收人员
            $quality = db('admin admin')
                ->where('admin.id', 'neq', $quality_id['quality_id'])
                ->field('admin.id,admin.nickname name')
                ->join('auth_group_access a', 'a.uid=admin.id and a.group_id=12')
                ->select();
            $name = array();
        } else {//如果主责已经申请，则显示参与验收人员
            $quality = array();
            $result = db('quality_check')->where('project_id', $ids)->find();//查出该项目参与的验收与人员
            $name = explode(',', $result['quality_check_name']);
        }
        $this->assign('quality', $quality);//开始申请被选参与人员
        $this->assign('name', $name);//已经申请并有参与人员，用作显示，

        if ($this->request->isAjax()) {
            if ($ret == 0 || $ret == 4) {//主责操作
                $name = input('post.check_name/a');
                if ($name == null) {
                    $this->error('参与验收人员不能为空');
                }
                $row['quality_check_name'] = implode(',', $name);
                $row['project_id'] = $ids;
                //TODO 通知副站
                $data['quality_progress'] = 1;
                db('quality_check')->insert($row);//添加参与验收人员
            } elseif ($ret == 2) {//副站操作
                //TODO 通知主站
                $data['quality_progress'] = 2;
            } elseif ($ret == 1) {//主站操作
                //TODO 通知建管
                $data['quality_progress'] = 3;
                $data['build_check'] = 0;//把建管不同意的状态初始化
            }
            db('project')->where(['id' => $ids])->update($data);
            $this->success();
        }
        return $this->view->fetch();
    }

}