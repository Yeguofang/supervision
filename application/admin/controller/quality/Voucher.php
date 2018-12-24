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
        //查出自己被指派的项目
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $map['project_voucher.quality_id'] = $adminId;

            $field = "project_voucher.id,project_voucher.project_images,project_voucher.project_desc,project_voucher.push_time,project_voucher.edit_status,project_voucher.del_status,i.project_name `i.project_name`,i.build_dept `i.build_dept`";
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

    //上传项目图片
    public function add()
    {
        //查出自己负责的项目，上传时选中项目
        $quality_id = Session::get('admin')['id'];
        $project = db('project')
                ->field('id,project_name')
                ->where('quality_id', $quality_id)
                ->where('finish_time','exp','is null')
                ->select();
        $this->assign('project', $project);

        if ($this->request->isAjax()) {
            $data = $this->request->post('row/a');
            
            $images = explode(",", $data['project_images']);
            //判断图片上传数量是否为3~9张
            if (count($images) > 2 && count($images) < 10) {
                //查出选中项目的副站长
                $assistant = db('project')->where('id', $data['project_id'])->find();
                $data['push_time'] = date('Y-m-d H:i:s', time());
                $data['quality_id'] = $quality_id;
                $data['dept_type'] = 1;
                $data['quality_assistant'] = $assistant['quality_assistant'];
                $res = db('project_voucher')->insert($data);
                if ($res) {
                    return $this->success();
                }
            }
            return $this->error('图片数量必须为3~9张');

        }
        return $this->fetch();
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
