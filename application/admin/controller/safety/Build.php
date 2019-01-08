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


//施工安全抽查记录
class Build extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
    }


    //项目管理
    public function index($ids = null)
    {
        //把当前项目的id存在session，
        Session::set('build', $ids);
        return $this->view->fetch();
    }

    public function list()
    {

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $id = Session::get('build');
            $total = db('safety_books')
                ->where('project_id', $id)
                ->where($where)
                ->where('type',2)//查出施工安全监督抽查记录
                ->count();

            $list = db('safety_books')
                ->where('project_id', $id)
                ->where($where)
                ->where('type',2)//查出施工安全监督抽查记录
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

    }
   
     //下发整改书
     public function add($ids=null)
     {
         if($this->request->isAjax()){
             $row = $this->request->post('row/a');
             $ids = Session::get('build');
             $name = db('project')->field('project_name')->where('id',$ids)->find();
            
             $data['project_id'] = $ids;
             $data['project_name'] = $name['project_name'];
             $data['desc'] = $row['desc'];
             $data['images'] =$row['images'];
             $data['number'] =$row['number'];
             $data['now_time'] = $row['now_time'];
             $data['expire_time'] = $row['expire_time'];
             $data['type'] = 2;//施工安全监督抽查记录
             $res = db('safety_books')->insert($data);
             if($res){
                 return $this->success('添加成功');
             }
             return $this->error('添加失败');
         }
         return $this->view->fetch();
     }
    

    //详情
    public function detail($ids)
    {
        $data = db('safety_books')->where('id', $ids)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }



}