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
       
        $row = db('project')
            ->field('build_dept,project_name,address,licence_id')
            ->where(['id'=>$ids])->find();
        $licence = db('licence')
            ->field('qr_code,licence_code,area,cost,survey_company,design_company,construction_company,supervision_company,survey_person,design_person,construction_person,supervision_person,begin_time,end_time')
            ->where(['id'=>$row['licence_id']])
            ->find();
        if($licence['begin_time']!=null){
            $licence['begin_time']=date('Y-m-d', $licence['begin_time']);
        }
        if($licence['end_time']!=null){
            $licence['end_time']=date('Y-m-d', $licence['end_time']);
        }

        //调用五大责任关联查询方法
        $five =$this->Five();
        // dump($five['build_dept'][0]['name']);exit;
        $this->assign('row',$row);
        $this->assign('licence',$licence);
        $this->assign('five',$five);
        
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
            
            $licence['build_dept'] = $project['build_dept'];
            $this->five_insert($licence);
            
            $this->success();
        }
        return $this->view->fetch();
    }


    //修改项目的时候，五大责任输入框的关联查询输出，
    public function Five()
    {
        $build_dept = db('five_duty')->where('type',1)->select();
        $design_company = db('five_duty')->where('type',2)->select();
        $design_person = db('five_duty')->where('type',3)->select();
        $survey_company = db('five_duty')->where('type',4)->select();
        $survey_person = db('five_duty')->where('type',5)->select();
        $construction_company = db('five_duty')->where('type',6)->select();
        $construction_person = db('five_duty')->where('type',7)->select();
        $supervision_company = db('five_duty')->where('type',8)->select();
        $supervision_person = db('five_duty')->where('type',9)->select();

        $five =array(
            'build_dept' => $build_dept,
            'design_company' => $design_company,
            'design_person'  => $design_person,
            'survey_company' => $survey_company,
            'survey_person' => $survey_person,
            'construction_company' => $construction_company,
            'construction_person' => $construction_person,
            'supervision_company' => $supervision_company,
            'supervision_person' => $supervision_person,
        );
        return $five;
    }
    
    // 行政管理修改过项目的五大责任体系后，把修改过的信息插入five_duty表，用于修改的时候关联查询
    public function five_insert($licence){
        $this->five_duty($licence['build_dept'],1);
        $this->five_duty($licence['design_company'],2);
        $this->five_duty($licence['design_person'],3);
        $this->five_duty($licence['survey_company'],4);
        $this->five_duty($licence['survey_person'],5);
        $this->five_duty($licence['construction_company'],6);
        $this->five_duty($licence['construction_person'],7);
        $this->five_duty($licence['supervision_company'],8);
        $this->five_duty($licence['supervision_person'],9);
        return;
    }

    public function five_duty($name,$type)
    {
        if($name != null){
            $result = db('five_duty')->where('name',$name)->where('type',$type)->find();
            if($result == null){
                $data = [
                    'name' =>$name,
                    'type' => $type
                ];
                db('five_duty')->insert($data);
            }
        }
        return;

    }

}