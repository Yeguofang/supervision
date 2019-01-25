<?php

/**
 * Created by Visual Studio code.
 * User:  Yeguofang
 * Date: 2018/12/15
 * Time: 12:35
 */
namespace app\admin\controller\safety;

use app\common\controller\Backend;
use think\Session;

//安监主责上传项目图片功能
class Voucher extends Backend
{

    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('projectVoucher');

    }


    public function index()
    {

        //管理员id
        $adminId = Session::get('admin')['id'];
        //查出自己负责的项目
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $map['project_voucher.security_id'] = $adminId;

            $field = "project_voucher.id,project_voucher.project_images,project_voucher.project_desc,project_voucher.push_time,project_voucher.situation,project_voucher.kind,project_voucher.schedule,project_voucher.edit_status,project_voucher.del_status,i.project_name `i.project_name`,i.build_dept `i.build_dept`";
            $total = $this->model
                ->alias("project_voucher")
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('project i', 'project_voucher.project_id=i.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project_voucher", '')
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('project i', 'project_voucher.project_id=i.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            // dump($list);exit;
            return json($result);
        }
        return $this->view->fetch();

    }


    //编辑上传过后的项目图片
    public function edit($ids = null)
    {
        $row = db('project_voucher')->where(['id' => $ids])->find();
        $this->assign('row', $row);

        if ($this->request->isAjax()) {
            $data = $this->request->post('row/a');
            $data['push_time'] = date('Y-m-d H:i:s', time());
            $data['edit_status'] = 0;

            $res = db('project_voucher')->where(['id' => $ids])->update($data);
            if ($res) {
                return $this->success();
            }

        }
        return $this->fetch();

    }

    //主责申请修改项目图片
    public function statusEdit($ids)
    {
        if ($this->request->isAjax()) {
            $data['edit_status'] = 1;
            db('project_voucher')->where(['id' => $ids])->update($data);
            return $this->success("已发出申请！");
        }
    }

    //主责申请删除项目图片
    public function statusDel($ids)
    {
        if ($this->request->isAjax()) {
            $data['del_status'] = 1;
            db('project_voucher')->where(['id' => $ids])->update($data);
            return $this->success("已发出申请！");
        }
    }
}
