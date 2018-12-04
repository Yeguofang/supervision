<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\quality;

use app\common\controller\Backend;
use think\Session;

//副站长项目管理
class Assistant extends Backend{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //副站长项目管理列表
    public function index(){
        $adminId = Session::get('admin')['id'];
        //查出自己被指派的项目，指派主责
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            //自己指派的项目quality_assistant
            $map['quality_assistant']=$adminId;
            $field="project.id,project.build_dept,project.project_name,project.address,project.quality_code,i.project_kind `i.project_kind`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->join('quality_info i','project.quality_info=i.id')
                ->field($field)
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project")
                ->join('quality_info i','project.quality_info=i.id')
                ->field($field)
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //选择主责
    public function select($ids){
        //查出质检员 12
        $assistant['assistant'] = db('admin admin')->field('admin.id,admin.nickname name,admin.mobile')->join('auth_group_access a','a.uid=admin.id and a.group_id=12')->select();
        //查出当前质检员
        $assistant['now'] = db('project')->field('quality_id')->where(['id'=>$ids])->find()['quality_id'];
        $this->assign('assistant',$assistant);
        if ($this->request->isAjax())
        {
           $data['quality_id'] = $this->request->post('quality_id');
           db('project')->where(['id'=>$ids])->update($data);
           $this->success();
        }
        return $this->view->fetch();
    }

    //详细信息
    public function detail($ids){
        $row=db('project')->where(['id'=>$ids])->find();
        $infoId=$row['quality_info'];
        $info=db('quality_info')->where(['id'=>$infoId])->find();
        $info['floor_up']=explode(",",$info['floor'])[0];
        $info['floor_down']=explode(",",$info['floor'])[1];
        $extra = explode(",",$info['status_extra']);
        if(count($extra)==2){
            $info['extra_type']=$extra[0];
            $info['extra_floor']=$extra[1];
        }
        //施工联系人 监理联系人
        $licence = db('licence')->field('supervision_person,construction_person')->where(['id'=>$row['licence_id']])->find();
        $this->assign('licence',$licence);
        $this->assign('row',$row);
        $this->assign('info',$info);
        return $this->view->fetch();
    }

    //登记告知书
    public function quality($ids){
        quality_inform($ids);
    }
}