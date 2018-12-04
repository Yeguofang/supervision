<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/15
 * Time: 23:07
 */
namespace app\admin\controller\check;

use app\common\controller\Backend;

class Build extends Backend{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    public function index(){
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $order = "build_check asc";
            $field="project.id,project.build_dept,project.build_check,project.project_name,project.address,project.supervisor_progress,project.quality_progress,i.project_kind `i.project_kind`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->field($field)
                ->where($where)
                ->join('quality_info i','project.quality_info=i.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project",'')
                ->field($field)
                ->where($where)
                ->join('quality_info i','project.quality_info=i.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //监督报告
    public function report($ids){
        $id = db('project')->field('supervisory_report')->where(['id'=>$ids])->find()['supervisory_report'];
        $this->assign('id',$id);
        if ($this->request->isAjax())
        {
            //1未提交，2已提交
            $status =  $this->request->post('status');
            if($status){
                $data['supervisory_report']=$status;
            }
            db('project')->where(['id'=>$ids])->update($data);
            $this->success();
        }
        return $this->view->fetch();
    }
    //建管处理
    public function deal($ids){
        if ($this->request->isAjax())
        {
            $data['build_check']=1;
            db('project')->where(['id'=>$ids])->update($data);
            $this->success();
        }
        return $this->view->fetch();
    }
}