<?php

/**
 * Created by Visual Studio code.
 * User:  Yeguofang
 * Date: 2018/12/14
 * Time: 3:05
 */
namespace app\admin\controller\safety;

use app\common\controller\Backend;
use think\Session;


//安监站长审批主责申请修改或删除项目图片功能
class Vouchermaster extends Backend
{

    protected $noNeedRight = ['*'];
    protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('projectVoucher');

    }


    public function list()
    {
        //取出项目id,查出当前项目的所有主责上传的图片
        $ids = Session::get('ids');
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $map['project_voucher.project_id'] = $ids;
             $map['project_voucher.dept_type'] = 2;
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

            return json($result);
        }
        return $this->view->fetch();

    }
    //项目图片页面
    public function index($ids = null)
    {
        //把当前项目的id存在session，
        Session::set('ids', $ids);
        return $this->view->fetch();

    }
    //副站长同意主责修改项目图片
    public function statusEdit($ids)
    {
        if ($this->request->isAjax()) {
            $data['edit_status'] = 3;
            db('project_voucher')->where(['id' => $ids])->update($data);
            return $this->success("已同意！");
        }
    }
    //副站长同意主责删除项目图片
    public function statusDel($ids)
    {
        if ($this->request->isAjax()) {
            $data['del_status'] = 3;
            db('project_voucher')->where(['id' => $ids])->update($data);
            return $this->success("已同意！");
        }
    }
}
