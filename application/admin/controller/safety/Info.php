<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\safety;

use app\common\controller\Backend;

//资料录入员
class Info extends Backend{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    public function index(){
        //查出所有的项目
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $field="project.id,project.build_dept,project.project_name,project.address,l.construction_company `l.construction_company`,l.supervision_company `l.supervision_company`
            ,s.safety_code `s.safety_code`";
            $total = $this->model
                ->alias("project")
                ->join('licence l','project.licence_id=l.id')
                ->join('safety_info s','project.safety_info=s.id')
                ->field($field)
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project",'')
                ->join('licence l','project.licence_id=l.id')
                ->join('safety_info s','project.safety_info=s.id')
                ->field($field)
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $row=db('project')->where(['id'=>$ids])->find();
        $infoId=$row['safety_info'];
        $info=db('safety_info')->where(['id'=>$infoId])->find();
        //转时间
        if($info['down_time']!=null){
            $info['down_time']=date('Y-m-d', $info['down_time']);
        }
        if($info['stop_time']!=null){
            $info['stop_time']=date('Y-m-d', $info['stop_time']);
        }
        if($info['assess_time']!=null){
            $info['assess_time']=date('Y-m-d', $info['assess_time']);
        }
        if($info['argument_time']!=null){
            $info['argument_time']=date('Y-m-d', $info['argument_time']);
        }
        if($info['reply_time']!=null){
            $info['reply_time']=date('Y-m-d', $info['reply_time']);
        }
        if($info['inspect_time']!=null){
            $info['inspect_time']=date('Y-m-d', $info['inspect_time']);
        }
        if($row['check_time']!=null){
            $row['check_time']=date('Y-m-d', $row['check_time']);
        }
        if($row['end_time']!=null){
            $row['end_time']=date('Y-m-d', $row['end_time']);
        }
        if($row['supervise_time']!=null){
            $row['supervise_time']=date('Y-m-d', $row['supervise_time']);
        }
        $this->assign('row',$row);
        $this->assign('info',$info);
        if ($this->request->isAjax())
        {
            $row = $this->request->post('row/a');
            $info = $this->request->post('info/a');
            //转时间
            if($info['down_time']==''){
                $info['down_time']=null;
            }else{
                $info['down_time']=strtotime($info['down_time']);
            }
            if($info['stop_time']==''){
                $info['stop_time']=null;
            }else{
                $info['stop_time']=strtotime($info['stop_time']);
            }
            if($info['assess_time']==''){
                $info['assess_time']=null;
            }else{
                $info['assess_time']=strtotime($info['assess_time']);
            }
            if($info['argument_time']==''){
                $info['argument_time']=null;
            }else{
                $info['argument_time']=strtotime($info['argument_time']);
            }
            if($info['reply_time']==''){
                $info['reply_time']=null;
            }else{
                $info['reply_time']=strtotime($info['reply_time']);
            }
            if($info['inspect_time']==''){
                $info['inspect_time']=null;
            }else{
                $info['inspect_time']=strtotime($info['inspect_time']);
            }
            if($row['check_time']==''){
                $row['check_time']=null;
            }else{
                $row['check_time']=strtotime($row['check_time']);
            }
            if($row['end_time']==''){
                $row['end_time']=null;
            }else{
                $row['end_time']=strtotime($row['end_time']);
            }
            if($row['supervise_time']==''){
                $row['supervise_time']=null;
            }else{
                $row['supervise_time']=strtotime($row['supervise_time']);
            }
            db('project')->where(['id'=>$ids])->update($row);
            db('safety_info')->where(['id'=>$infoId])->update($info);
            $this->success();
        }
        return $this->view->fetch();
    }
}