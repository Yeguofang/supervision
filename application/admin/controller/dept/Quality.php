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
use think\Db;
use think\Session;

class Quality extends Backend
{
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();

        $this->model = model('Admin');
    }

    public function index()
    {
        $adminId = Session::get('admin')['id'];
        $admin = db('auth_group_access')->where(['uid' => $adminId])->find();
        if ($this->request->isAjax()) {

            $filed = "id,worker_code,nickname,mobile,supervisor_card,admin_code,is_law,username,g.group_id";
            if ($adminId == 1) {
                $map = 'group_id=10 or group_id=11 or group_id=12 or group_id=13';
            } else {
                if ($admin['group_id'] == 10 || $adminId == 1) {
                    //如果是站长
                    $map = 'group_id=11 or group_id=12 or group_id=13';
                } elseif ($admin['group_id'] == 11) {
                    //如果是副站长
                    $map = 'group_id=12 or group_id=13';
                }
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->alias('admin')
                ->join('auth_group_access g', 'g.uid=admin.id ')
                ->field($filed)
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->count();

            $list = $this->model->alias('admin')
                ->join('auth_group_access g', 'g.uid=admin.id')
                ->field($filed)
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

    public function add()
    {
        $adminId = Session::get('admin')['id'];
        $admin = db('auth_group_access')->where(['uid' => $adminId])->find();
        $this->assign('level', $admin['group_id']);
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $level = $this->request->post("level");
            if ($params) {
                Db::startTrans();
                try {
                    if ($level == 0) {
                        //站长 先判断是否已经有站长
                        if (db('auth_group_access')->where(['group_id' => 10])->find()) {
                            $this->error("已经有一个站长了");
                        }
                        $data['group_id'] = 10;
                    } elseif ($level == 1) {
                        //副站 先判断是否已经有两个副站了
                        if (db('auth_group_access')->where(['group_id' => 11])->count() == 2) {
                            $this->error("已经有两个副站了");
                        }
                        $data['group_id'] = 11;
                    } elseif ($level == 2) {
                        $data['group_id'] = 12;
                    } elseif ($level == 3) {
                        $level=2;
                        $data['group_id'] = 13;
                    }
                    $params['salt'] = Random::alnum();
                    $params['password'] = md5(md5(123456) . $params['salt']);
                    $params['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。
                    $result = $this->model->validate('Admin.add')->save($params);
                    if ($result === false) {
                        $this->error($this->model->getError());
                    }
                    $data['uid'] = $this->model->getLastInsID();
                    db('AuthGroupAccess')->insert($data);
                    //质监前段登录账号
                    $user['username'] = $params['username'];
                    $user['password'] = md5(md5(123456) . $params['salt']);
                    $user['nickname'] = $params['nickname'];
                    $user['mobile'] = $params['mobile'];
                    $user['salt'] = $params['salt'];
                    $user['avatar'] = '/assets/img/avatar.png';
                    $user['status'] = 'normal';
                    $user['identity'] = 0;
                    $user['identity_type'] = $level;
                    $user['admin_id'] = $data['uid'];
                    db('user')->insert($user);
                    Db::commit();

                    $this->success();
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
            }
            $this->error();
        }
        return $this->view->fetch();
    }
}