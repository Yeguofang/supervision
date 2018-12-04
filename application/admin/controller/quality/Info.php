<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\quality;

use app\common\controller\Backend;

//项目资料录入员
class Info extends Backend{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index(){
        //查出所有的项目
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $field="project.id,project.build_dept,project.project_name,project.address,i.project_kind `i.project_kind`,i.situation `i.situation`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->join('quality_info i','project.quality_info=i.id')
                ->field($field)
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project",'')
                ->join('quality_info i','project.quality_info=i.id')
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

    //修改项目信息
   public function edit($ids = NULL)
   {
       $row=db('project')->where(['id'=>$ids])->find();
       $infoId=$row['quality_info'];
       $info=db('quality_info')->where(['id'=>$infoId])->find();
       $info['floor_up']=explode(",",$info['floor'])[0];
       $info['floor_down']=explode(",",$info['floor'])[1];
       
       //转时间
       $row['begin_time']=DataTiem($row['begin_time']);
       $row['finish_time']=DataTiem($row['finish_time']);
       $row['push_time']=DataTiem($row['push_time']);
       $row['register_time']=DataTiem($row['register_time']);
       $row['permit_time']=DataTiem($row['permit_time']);
       
       $this->assign('row',$row);
       $this->assign('info',$info);
       if ($this->request->isAjax())
       {
           $row = $this->request->post('row/a');
           //转时间
           $row['begin_time'] = StrtoTime($row['begin_time']);
           $row['finish_time'] = StrtoTime($row['finish_time']);
           $row['push_time'] = StrtoTime($row['push_time']);
           $row['register_time'] = StrtoTime($row['register_time']);
           $row['permit_time'] = StrtoTime($row['begin_time']);
          
           $info = $this->request->post('info/a');
           $floor = $this->request->post('floor/a');
           $info['floor']=$floor[0].','.$floor[1];
           db('project')->where(['id'=>$ids])->update($row);
           db('quality_info')->where(['id'=>$infoId])->update($info);
           $this->success();
       }
       return $this->view->fetch();
   }
   public function situation(){
        $data = db('project p')->field('p.quality_info,situation,status_extra')->join('quality_info i','i.id=p.quality_info')->find();
        if($data['situation']==1){
            //主体阶段
            $extra = explode(",",$data['status_extra']);
            if(count($extra)==1){
                $extra[0]='';
                $extra[1]='';
            }
           $info['type'] = $extra[0];
            $info['floor'] = $extra[1];
        }else{
            $info['type'] = $data['status_extra'];
        }
       $info['situation']=$data['situation'];
        $this->assign('info',$info);
       if ($this->request->isAjax())
       {
            $post = $this->request->post('info/a');
            if($post['situation']==1){
                //主体阶段
                $up['status_extra']=$post['type'].','.$post['floor'];
            }else{
                $up['status_extra']=$post['type'];
            }
            db('quality_info')->where(['id'=>$data['quality_info']])->update($up);
            $this->success();
       }
       return $this->view->fetch();
   }

}