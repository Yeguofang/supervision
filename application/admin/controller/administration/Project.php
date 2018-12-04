<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/25
 * Time: 19:53
 */

namespace app\admin\controller\administration;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\Session;

//行政管理
class Project extends Backend{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index(){
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->alias("project")
                ->where($where)
                ->join('licence l','s_project.licence_id=l.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project")
                ->where($where)
                ->join('licence l','s_project.licence_id=l.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //新建项目档案
    public function add()
    {
        $adminId = Session::get('admin')['id'];
        $dept = db('project')->field('build_dept')->select();
        $this->assign("dept",$dept);
        if ($this->request->isAjax())
        {
            //新增一个空的licence和quality_info和safety_info把id放在新增的project上
            $row = $this->request->post('row/a');
            Db::startTrans();
            try{
                $fresh['create_time'] =time();
                db('licence')->insert($fresh);
                db('quality_info')->insert($fresh);
                db('safety_info')->insert($fresh);
                $row['licence_id']=db('licence')->getLastInsID();
                $row['quality_info']=db('quality_info')->getLastInsID();
                $row['safety_info']=db('safety_info')->getLastInsID();
                $row['admin_id']=$adminId;
                db('project')->insert($row);

                //TODO 通知质监站站长 还有安监站站长

                Db::commit();
                $this->success();
            }catch (Exception $e){
                Db::rollback();
                $this->error("新建失败");
            }
        }

        return $this->view->fetch();
    }

    //修改项目档案
    public function edit($ids = NULL)
    {
        $row = db('project')->field('build_dept,project_name,address,licence_id')->where(['id'=>$ids])->find();
        $licence = db('licence')->field('qr_code,licence_code,area,cost,survey_company,design_company,construction_company,supervision_company,survey_person,design_person,construction_person,supervision_person,begin_time,end_time')->where(['id'=>$row['licence_id']])->find();
        if($licence['begin_time']!=null){
            $licence['begin_time']=date('Y-m-d', $licence['begin_time']);
        }
        if($licence['end_time']!=null){
            $licence['end_time']=date('Y-m-d', $licence['end_time']);
        }
        $this->assign('row',$row);
        $this->assign('licence',$licence);
        if ($this->request->isAjax())
        {
           $project = $this->request->post('row/a');
           $licence = $this->request->post('licence/a');
            if($licence['begin_time']==''){
                $licence['begin_time']=null;
            }else{
                $licence['begin_time']=strtotime($licence['begin_time']);
            }
            if($licence['end_time']==''){
                $licence['end_time']=null;
            }else{
                $licence['end_time']=strtotime($licence['end_time']);
            }
            db('project')->where(['id'=>$ids])->update($project);
            db('licence')->where(['id'=>$row['licence_id']])->update($licence);
           $this->success();
        }
        return $this->view->fetch();
    }

}