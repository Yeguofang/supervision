<?php

/**
 * Created by Visual Studio code.
 * User:  Yeguofang
 * Date: 2018/12/14
 * Time: 1:35
 */
namespace app\admin\controller\quality;

use app\common\controller\Backend;
use think\Session;
/**
 * 
 *
 * @icon fa fa-circle-o
 */
//质监主责上传项目图片功能
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

        $adminId = Session::get('admin')['id'];
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $map['project_voucher.quality_id'] = $adminId;

            $field = "l.licence_code `licence_code`,project_voucher.id,project_voucher.project_images,project_voucher.project_desc,project_voucher.push_time,project_voucher.situation,project_voucher.kind,project_voucher.schedule,project_voucher.edit_status,project_voucher.del_status,i.project_name `i.project_name`,i.build_dept `i.build_dept`";
            $total = $this->model
                ->alias("project_voucher")
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('project i', 'project_voucher.project_id=i.id')
                ->join('licence l','i.licence_id=l.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project_voucher", '')
                ->field($field)
                ->where($where)
                ->where($map)
                ->join('project i', 'project_voucher.project_id=i.id')
                ->join('licence l','i.licence_id=l.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            // dump($list);exit;
            return json($result);
        }
        return $this->view->fetch();

    }


    //修改项目图片
    public function edit($ids = null)
    {
        $row = db('project_voucher')->where(['id' => $ids])->find();
        $this->assign('row', $row);

        if ($this->request->isAjax()) {
            $data = $this->request->post('row/a');

            $images = explode(",", $data['project_images']);
            //判断图片上传数量是否为3~9张
            if (count($images) > 2 && count($images) < 10) {
                $data['push_time'] = date('Y-m-d H:i:s', time());
                $data['edit_status'] = 0;
                $res = db('project_voucher')->where(['id' => $ids])->update($data);
                if ($res) {
                    return $this->success();
                }
            }
            return $this->error('图片数量必须为3~9张');
        }
        return $this->fetch();

    }

    //申请修改项目图片
    public function statusEdit($ids)
    {
        if ($this->request->isAjax()) {
            $data['edit_status'] = 1;
            db('project_voucher')->where(['id' => $ids])->update($data);
            return $this->success("已发出申请！");
        }
    }

    //申请删除项目图片
    public function statusDel($ids)
    {
        if ($this->request->isAjax()) {
            $data['del_status'] = 1;
            db('project_voucher')->where(['id' => $ids])->update($data);
            return $this->success("已发出申请！");
        }
    }

    
}
