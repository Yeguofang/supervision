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
        $ret = judge_identity($adminId,1);
        $this->assign('ret', $ret);
        $map=[];
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
    public function detail($ids){
        $data = db('project p')->where(['p.id'=>$ids])
            ->join('s_licence l','l.id=p.licence_id')
            ->find();
            
        $this->assign("data",$data);
        return $this->view->fetch();
    }

    //通知
    public function notice($ids, $ret)
    {
        if ($this->request->isAjax()) {
            if ($ret == 0) {
                //TODO 通知副站
                $data['quality_progress'] = 1;
            } elseif ($ret == 2) {
                //TODO 通知主站
                $data['quality_progress'] = 2;
            }elseif ($ret == 1) {
                //TODO 通知建管
                $data['quality_progress'] = 3;
            }
            db('project')->where(['id' => $ids])->update($data);
            $this->success();
        }
        return $this->view->fetch();
    }

}