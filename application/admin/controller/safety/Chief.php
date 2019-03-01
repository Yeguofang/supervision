<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\safety;

use app\common\controller\Backend;
use think\Db;
use think\Session;


//主责安监员的项目管理
class Chief extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目管理
    public function index()
    {
        $adminId = Session::get('admin')['id'];

        //查出自己被指派的项目，指派主责
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            //自己指派的项目security_id
            $map['security_id'] = $adminId;
            $field = "l.licence_code `licence_code`,project.id,project.build_dept,project.project_name,project.address,project.supervisor_code,i.project_kind `i.project_kind`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('quality_info i', 'project.quality_info=i.id')
                ->join('licence l','project.licence_id=l.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project", '')
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('quality_info i', 'project.quality_info=i.id')
                ->join('licence l','project.licence_id=l.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
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
        $this->assign('row',$row);
        $this->assign('quality',$info);

        $infoId=$row['safety_info'];
        $info=db('safety_info')->where(['id'=>$infoId])->find();
        $this->assign('info',$info);

        $licence=db('licence')->where(['id'=>$row['licence_id']])->find();
        $this->assign('licence',$licence);
        return $this->view->fetch();
    }


    //下发告知书
    public function deal($ids){
        $adminId = Session::get('admin')['id'];
        //查出排除自己的安监员 16
        $map['admin.id'] = ['neq', $adminId];
        $assistant['assistant'] = db('admin admin')->where($map)->field('admin.id,admin.nickname name,admin.mobile')->join('auth_group_access a', 'a.uid=admin.id and a.group_id=16')->select();
        $security = [];
        foreach ($assistant['assistant'] as $v) {
            $security[$v['id']] = "姓名" . $v['name'] . " 手机号" . $v['mobile'];
        }
        $this->assign('security', $security);
        if ($this->request->isAjax())
        {
                //通过获取编号
                $up['supervisor_code'] = $this->request->post("code");
            $up['supervisor_time'] = strtotime($this->request->post("time"));
                //质检人员
                $person = $this->request->post('security/a');
                if($person[0]==""){
                    $this->error("失败！通过请选择成员");
                }
                $list = [];
                foreach ($person as $v){
                    $d['id'] =guid();
                    $d['admin_id'] =$v;
                    $d['project_id']=$ids;
                    $d['create_time']=time();
                    $d['type']=2;
                    array_push($list,$d);
                }
                db('person_project')->insertAll($list);
            db('project')->where(['id'=>$ids])->update($up);
            $this->success();

        }
        return $this->view->fetch();
    }


    //选择安监员
    public function select($ids)
    {
        $adminId = Session::get('admin')['id'];
        //查出排除自己的安监员 16
        $map['admin.id'] = ['neq', $adminId];
        $assistant['assistant'] = db('admin admin')->where($map)->field('admin.id,admin.nickname name,admin.mobile')->join('auth_group_access a', 'a.uid=admin.id and a.group_id=16')->select();
        $security = [];
        foreach ($assistant['assistant'] as $v) {
            $security[$v['id']] = "姓名" . $v['name'] . " 手机号" . $v['mobile'];
        }
        $this->assign('security', $security);
        //查出当前质检员  项目id，type为安监员
        $map['p.project_id'] = $ids;
        $map['p.type'] = 2;
        $assistant['now'] = db('person_project p')->join('admin admin', 'admin.id=p.admin_id')->where($map)->select();
        $now = [];
        $before = '';
        foreach ($assistant['now'] as $value) {
            $now[] = $value['admin_id'];
            $before = $before . $value['admin_id'] . ',';
        }
        $before = rtrim($before, ',');
        $this->assign('now', $now);
        if ($this->request->isAjax()) {
            //新增的质检人员
            $person = $this->request->post('security/a');
            if ($person[0] == "") {
                $this->error("不能为空！通过请选择成员");
            }
            $list = [];
            $after = "";
            foreach ($person as $v) {
                $after = $after . $v . ',';
                $d['id'] = guid();
                $d['admin_id'] = $v;
                $d['project_id'] = $ids;
                $d['create_time'] = time();
                $d['type'] = 2;
                array_push($list, $d);
            }
            $after = rtrim($after, ',');

            //做记录
            Db::startTrans();
            try {

                //更改表
                $change['id'] = guid();
                $change['project_id'] = $ids;
                $change['extra_id'] = $adminId;
                $change['person_type'] = 4;
                $change['before_person'] = $before;
                $change['create_time'] = time();
                $change['after_person'] = $after;
                //插入修改记录
                db('change_person_log')->insert($change);
                //删除原来的人员
                db('person_project')->where(['project_id' => $ids])->delete();
                //新增新的人员
                db('person_project')->insertAll($list);
                Db::commit();
                $this->success();
            } catch (Exception $e) {
                Db::rollback();
                $this->error("操作失败");
            }
        }
        return $this->view->fetch();
    }


    //施工安全监督告知书
    public function safety($ids){
        safety_inform($ids);
    }

 
}