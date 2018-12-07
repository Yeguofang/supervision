<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/15
 * Time: 16:18
 */
namespace app\admin\controller\dept;

use app\common\controller\Backend;
use fast\Random;

class Administration extends Backend{
    protected $noNeedRight = ['*'];
    public function _initialize()
    {
        parent::_initialize();

        $this->model=model('Admin');
    }
    public function index()
    {
        if ($this->request->isAjax())
        {
            $filed="admin.id,admin.worker_code,admin.nickname,admin.mobile,admin.username";
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total =$this->model->alias('admin')
                ->join('auth_group_access g','g.uid=admin.id and g.group_id=8')
                ->field($filed)
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list =$this->model->alias('admin')
                ->join('auth_group_access g','g.uid=admin.id and g.group_id=8')
                ->field($filed)
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5(123456) . $params['salt']);
                $params['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。
                $result = $this->model->validate('Admin.add')->save($params);
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                //行政 8
                $data['group_id']=8;
                $data['uid']=$this->model->getLastInsID();
                db('AuthGroupAccess')->insert($data);
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }
    

}