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

//整改通知书管理
class Rectify extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }
  //整改通知书列表

    //项目列表
    public function index()
    {
        $adminId = Session::get('admin')['id'];
        $ret = judge_identity($adminId, 1);
        $this->assign('ret', $ret);
        $map = [];
       if ($ret == 2) {
            //副站长只能看到自己分管的项目。
            $map['quality_assistant'] = $adminId;
        }
        //站长跟质监员可以看到全部项目
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                $field = "project.id,project.build_dept,project.project_name,project.quality_code,project.address,project.rectify_status,quality_progress,a.nickname `a.nickname`,z.nickname `z.nickname`";
                $total = $this->model
                    ->alias("project")
                    ->field($field)
                    ->where($where)
                    ->where($map)
                    ->join('admin a', 'project.quality_id=a.id', 'LEFT')
                    ->join('admin z', 'project.quality_assistant=z.id', 'LEFT')
                    ->order($sort, $order)
                    ->count();
    
                $list = $this->model
                    ->alias("project", '')
                    ->field($field)
                    ->where($where)
                    ->where($map)
                    ->join('admin a', 'project.quality_id=a.id', 'LEFT')
                    ->join('admin z', 'project.quality_assistant=z.id', 'LEFT')
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

         
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //申请下发
    public function rectifyApply($ids,$identy)
    {
        if($identy == 0){//安监员申请
            $result = db('project')->where('id',$ids)->find();
            if($result['finish_time'] != null){
                return $this->error('该项目已竣工');
            }
        $data['rectify_status'] = 1;
        $res = db('project')->where('id',$ids)->update($data);
        return $this->success('已发出申请');
        }else if($identy == 1){//站长同意下发
            $data['rectify_status'] = 3;
            $res = db('project')->where('id',$ids)->update($data);
            return $this->success('已同意');
        }else if($identy == 2){//副站长同意下发
            $data['rectify_status'] = 2;
            $res = db('project')->where('id',$ids)->update($data);
            return $this->success('已同意');
        }
    }
    //下发整改书
    public function add($ids=null)
    {
        if($this->request->isAjax()){
            $row = $this->request->post('row/a');

            $data['project_id'] = $ids;
            $data['desc'] = $row['desc'];
            $data['images'] =$row['images'];
            $data['number'] =$row['number'];
            $data['time'] = date('Y-m-d');

            $res = db('quality_rectify')->insert($data);
            if($res){
                $project['rectify_status'] = 0;//恢复可申请状态
                $res = db('project')->where('id',$ids)->update($project);
                return $this->success('下发成功');
            }
            return $this->error('下发失败');
        }
        return $this->view->fetch();
    }


}
