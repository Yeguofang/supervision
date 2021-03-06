<?php

/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/28
 * Time: 23:54
 */
namespace app\api\controller;

use app\common\controller\Api;
use think\Session;
use app\common\model\Area;
use app\common\model\Version;
use fast\Random;
use think\Config;


class Quality extends Api
{
    // protected $noNeedRight = ['*'];
    protected $noNeedLogin = ['*'];
    /**
     * 质监站
     *
     */




    //项目列表
    public function listing()
    {
        $user = $this->auth->getUser();
        $page = $this->request->param('page', 1);
        $num = $this->request->param('num', 2);
        $map = [];
        if($user['identity'] ==1 ){

        }else if($user['identity'] == 0){
            
        }
        if ($user['identity_type'] == 1) {
            //副站长只能看到quality_assistant是自己
            $map['quality_assistant'] = $user['admin_id'];
        } elseif ($user['identity_type'] == 2) {
            //副站长只能看到quality_id是自己
            $map['quality_id'] = $user['admin_id'];
        }
        //差竣工联系人
        $field = 'p.id,p.build_dept,p.project_name,p.address,p.push_time,p.begin_time,p.finish_time,p.check_time,l.supervision_person,i.status';
        $data = db('project p')
            ->field($field)
            ->join('quality_info i', 'p.quality_info=i.id')
            ->join('licence l', 'p.licence_id=l.id')
            ->where($map)
            ->page($page, $num)
            ->select();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['begin_time'] = convertTime($data[$i]['begin_time']);
            $data[$i]['push_time'] = convertTime($data[$i]['push_time']);
            $data[$i]['finish_time'] = convertTime($data[$i]['finish_time']);
            $data[$i]['check_time'] = convertTime($data[$i]['check_time']);
        }

        $this->success('', $data);
    }
    //项目详情
    public function detail()
    {
        $id = $this->request->param('id');
        $field = 'p.id,p.build_dept,p.project_name,p.address,l.area,l.cost,i.floor,p.push_time,p.supervise_time,p.begin_time bTime,l.begin_time,l.end_time,p.finish_time,p.check_time,l.construction_person,l.supervision_person,i.status';
        $data = db('project p')
            ->field($field)
            ->join('quality_info i', 'p.quality_info=i.id')
            ->join('licence l', 'p.licence_id=l.id')
            ->where(['p.id' => $id])
            ->find();
        $floor = explode(',', $data['floor']);
        if (count($floor) == 1) {
            $data['floor'] = '暂无';
        } else {
            $data['floor'] = '地上：' . $floor[0] . ' 地下：' . $floor[1];
        }

        if ($data['begin_time'] != null && $data['end_time'] != null) {
            $data['permit'] = date("Y-m-d", $data['begin_time']) . "-" . date("Y-m-d", $data['end_time']);
        } else {
            $data['permit'] = '暂无';
        }

        $data['begin_time'] = convertTime($data['begin_time']);
        $data['finish_time'] = convertTime($data['finish_time']);
        $data['check_time'] = convertTime($data['check_time']);
        $data['push_time'] = convertTime($data['push_time']);
        $data['supervise_time'] = convertTime($data['supervise_time']);

        if($data == null){
            return $this->error('无数据', 1);
        }

        return $this->success('',$data);
    }
    
    //项目图片列表
    public function imageList()
    {
        $id = $this->request->param('id');
        $num = $this->request->param('num', 2);

        if ($id == null) {
            return $this->error('项目id不能为空', 3);
        }

        $data = db('project_voucher')
            ->where('project_id', $id)
            ->paginate($num)->each(function($item, $index){
                $item['project_images'] = explode(",",$item['project_images']);
                $item['push_time'] =  substr($item['push_time'],0,16);
                return $item;
            });
        if($data == null){
                return $this->error('无数据', 1);
        }
        
        $this->success('', $data->items());
    }

    //单条图片记录详情
    public function imageInfo(){
        $id = $this->request->param('id');
        $num = $this->request->param('num', 2);

        if ($id == null) {
            return $this->error('图片id不能为空', 3);
        }

        $data = db('project_voucher')
            ->where('id', $id)
            ->find();
        
        // if($data){
        //         $this->error('无数据', $data);
        //     }
        $data['project_images'] = explode(",",$data['project_images']);

        $this->success('', $data);
    }


    //上传图片
    public function addImage()
    {

        $data['project_images'] = $this->request->param('image');
        $data['project_desc'] = $this->request->param('desc');
        $data['project_id'] = $this->request->param('id');

        if ($data['project_images'] == null) {
            return $this->error('图片不能为空', 1);
        }
        if ($data['project_desc'] == null) {
            return $this->error('说明不能空', 2);
        }
        if ($data['project_id'] == null) {
            return $this->error('项目id不能为空', 3);
        }

         //查出选中项目的副站长
        $assistant = db('project')->where('id', $data['project_id'])->find();
        $data['push_time'] = date('Y-m-d H:i:s', time());
        $data['quality_id'] = $assistant['quality_id'];
        $data['quality_assistant'] = $assistant['quality_assistant'];

        $res = db('project_voucher')->insert($data);
        if ($res) {
            return $this->success('上传成功', 0);
        } else {
            return $this->error('上传失败', 4);
        }
    }


    //修改图片
    public function editImage()
    {

        $id = $this->request->param('id');
        $data['project_images'] = $this->request->param('image');
        $data['project_desc'] = $this->request->param('desc');

        if ($data['project_images'] == null) {
            return $this->error('图片不能为空', 1);
        }
        if ($data['project_desc'] == null) {
            return $this->error('说明不能空', 2);
        }
        if ($id == null) {
            return $this->error('项目id不能为空', 3);
        }

        $data['push_time'] = date('Y-m-d H:i:s', time());
        $data['edit_status'] = 0;
        $res = db('project_voucher')->where(['id' => $id])->update($data);
        if ($res) {
            return $this->success('上传成功', 0);
        } else {
            return $this->error('上传失败', 4);
        }

    }

    public function Del(){
        $id = $this->request->param('id');
        if ($id == null) {
            return $this->error('项目id不能为空', 1);
        }

        $res = db('project_voucher')->where(['id' => $id])->delete();
        if ($res) {
            return $this->success('申请成功', 0);
        }
        return $this->error('申请失败', 2);
    }

    //申请修改
    public function appalyEdit()
    {
        $id = $this->request->param('id');
        if ($id == null) {
            return $this->error('项目id不能为空', 1);
        }

        $data['edit_status'] = 1;
        $res = db('project_voucher')->where(['id' => $id])->update($data);
        if ($res) {
            return $this->success('申请成功', 0);
        }
        return $this->error('申请失败', 2);
    }

    //申请删除项目图片
    public function appalyDel()
    {
        $id = $this->request->param('id');
        if ($id == null) {
            return $this->error('项目id不能为空', 1);
        }

        $data['del_status'] = 1;
        $res = db('project_voucher')->where(['id' => $id])->update($data);
        if ($res) {
            return $this->success('申请成功', 0);
        }
        return $this->error('申请失败', 2);
    }

    //修改项目工程状态
    public function statusEdit(){
       
        $id = $this->request->param('id');
        $data['status'] = $this->request->param('status');

        if ($id == null) {
            return $this->error('项目id不能为空', 1);
        }
        if ($data['status'] == null) {
            return $this->error('项目状态不能为空', 1);
        }
        
        $res = db('quality_info')->where(['id' => $id])->update($data);
        if ($res) {
            return $this->success('修改成功', 0);
        }
        return $this->error('修改失败', 2);
    }

    
    public function upload(){
        $file = $this->request->file('file');
            if (empty($file)) {
                $this->error(__('No file upload or server upload limit exceeded'));
            }

            //判断是否已经存在附件
            $sha1 = $file->hash();

            $upload = Config::get('upload');

            preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
            $type = strtolower($matches[2]);
            $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
            $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
            $fileInfo = $file->getInfo();
            $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
            $suffix = $suffix ? $suffix : 'file';

            $mimetypeArr = explode(',', strtolower($upload['mimetype']));
            $typeArr = explode('/', $fileInfo['type']);

            //验证文件后缀
            if ($upload['mimetype'] !== '*' &&
                (
                    !in_array($suffix, $mimetypeArr)
                    || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)))
                )
            ) {
                $this->error(__('Uploaded file format is limited'));
            }
            $replaceArr = [
                '{year}'     => date("Y"),
                '{mon}'      => date("m"),
                '{day}'      => date("d"),
                '{hour}'     => date("H"),
                '{min}'      => date("i"),
                '{sec}'      => date("s"),
                '{random}'   => Random::alnum(16),
                '{random32}' => Random::alnum(32),
                '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
                '{suffix}'   => $suffix,
                '{.suffix}'  => $suffix ? '.' . $suffix : '',
                '{filemd5}'  => md5_file($fileInfo['tmp_name']),
            ];
            $savekey = $upload['savekey'];
            $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

            $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
            $fileName = substr($savekey, strripos($savekey, '/') + 1);
            //
            $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
            if ($splInfo) {
                $imagewidth = $imageheight = 0;
                if (in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'])) {
                    $imgInfo = getimagesize($splInfo->getPathname());
                    $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
                    $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
                }
                $params = array(
                    'admin_id'    => 0,
                    'user_id'     => (int)$this->auth->id,
                    'filesize'    => $fileInfo['size'],
                    'imagewidth'  => $imagewidth,
                    'imageheight' => $imageheight,
                    'imagetype'   => $suffix,
                    'imageframes' => 0,
                    'mimetype'    => $fileInfo['type'],
                    'url'         => $uploadDir . $splInfo->getSaveName(),
                    'uploadtime'  => time(),
                    'storage'     => 'local',
                    'sha1'        => $sha1,
                );
                $attachment = model("attachment");
                $attachment->data(array_filter($params));
                $attachment->save();
                \think\Hook::listen("upload_after", $attachment);
                
                $url = 'http://192.168.50.182/'.$uploadDir . $splInfo->getSaveName();

                // $result = array('msg' => '上传成功','url' => $url);

                // return json($result);
                $this->success('上传成功',$url);
            } else {
                // 上传失败获取错误信息
                $this->error($file->getError());
            }
    }

   

}