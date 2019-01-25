<?php
/**
 * Created by Visual Studio code.
 * User:  Yeguofang
 * Date: 2018/12/17
 * Time: 3:45
 */
 
namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Area;
use app\common\model\Version;
use fast\Random;
use think\Config;
use think\File;

// 项目图片处理
class Quality extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
  
    //项目详情
    public function detail()
    {
        $id = $this->request->param('id');
        $field = 'p.id,p.build_dept,p.project_name,p.address,l.area,l.cost,i.floor,p.push_time,p.supervise_time,p.begin_time bTime,i.schedule,l.begin_time,l.end_time,p.finish_time,p.check_time,l.construction_person,l.supervision_person,i.status,i.project_kind,i.situation';
        $data = db('project p')
            ->field($field)
            ->join('quality_info i', 'p.quality_info=i.id')
            ->join('licence l', 'p.licence_id=l.id')
            ->where(['p.id' => $id])
            ->find();
        if ($data == null) {
            return $this->success('无数据', $data);
        }
        $floor = explode(',', $data['floor']);
        if (count($floor) == 1) {
            $data['floor'] = '暂无';
        } else {
            $data['floor'] = '地上：' . $floor[0] . ' 地下：' . $floor[1];
        }
        if ($data['begin_time'] != null && $data['end_time'] != null) {
            $data['permit'] = date("Y-m-d", $data['begin_time']) . "~" . date("Y-m-d", $data['end_time']);
        } else {
            $data['permit'] = '暂无';
        }

        $data['begin_time'] = convertTime($data['begin_time']);
        $data['finish_time'] = convertTime($data['finish_time']);
        $data['check_time'] = convertTime($data['check_time']);
        $data['push_time'] = convertTime($data['push_time']);
        $data['supervise_time'] = convertTime($data['supervise_time']);

        $this->success('', $data);
    }

    
    //项目图片列表
    public function imageList()
    {
        $user = $this->auth->getUser();
        $id = $this->request->param('id');
        $num = $this->request->param('num', 2);
        if ($id == null) {
            return $this->error('项目id不能为空', 3);
        }
        
        if($user['identity'] == 0){//质监身份
            if($user['identity_type'] == 0){
                $map['dept_type'] = 1; //质监站长
            }else if($user['identity_type'] == 1){
                $map['quality_assistant'] = $user['admin_id'];//质监副站长
            }else{
                $map['quality_id'] = $user['admin_id'];//质监员
            }
        }else {//安监身份
            if($user['identity_type'] == 0){
                 $map['dept_type'] = 2; //质监站长
            }else if($user['identity_type'] == 1){
                $map['supervisor_assistant'] = $user['admin_id'];//质监副站长
            }else{
                $map['security_id'] = $user['admin_id'];//质监员
            }
        }

        $data = db('project_voucher')
            ->where('project_id', $id)
            ->where($map)
            ->order("push_time desc")
            ->paginate($num)->each(function ($item, $index) {
                $item['project_images'] = explode(",", $item['project_images']);
                for($i=0;$i<count($item['project_images']);$i++){
                    $item['project_images'][$i] = 'https://security.dreamwintime.com/supervision/public'.$item['project_images'][$i];//图片地址拼接
                }
                $item['push_time'] = substr($item['push_time'], 0, 16);
                return $item;
            });
        //查出项目，是否竣工
        $project = db('project')
            ->field('finish_time')
            ->where('id',$id)
            ->find();
        if($project['finish_time'] == null){ //项目无竣工日期
            if ($user['identity_type'] == 0) {//站长不可操作
                $type = 1;
            }else if($user['identity_type'] == 1){//副站长不可操作
                $type = 1;
            }else if($user['identity_type'] == 2){//只有主责才能有操作
                $type = 0;
            }
        }else{
            $type = 1;//项目有竣工日期，不可操作
        }
        $result = array('result' => $data->items(),'type' => $type);

        $this->success('', $result);
    }

       //单条图片记录详情
    public function imageInfo()
    {
        $user = $this->auth->getUser();
        $id = $this->request->param('id');
        if ($id == null) {
            return $this->error('图片id不能为空', 3);
        }

        $data = db('project_voucher')
            ->where('id', $id)
            ->find();
        if ($data == null) {
            return $this->error('无数据', 0);
        }
        $data['project_images'] = explode(",", $data['project_images']);

        //图片地址拼接
        for($i=0;$i<count($data['project_images']);$i++){
            $data['new_images'][$i] = $data['project_images'][$i];//无域名图片
            $data['project_images'][$i] = 'https://security.dreamwintime.com/supervision/public'.$data['project_images'][$i];//有域名图片
            
        }

        //查出项目，是否竣工
        $project = db('project')
            ->field('finish_time')
            ->where('id',$data['project_id'])
            ->find();
        if($project['finish_time'] == null){
            //项目无竣工日期
            if ($user['identity_type'] == 2) {//只有主责才能有操作
                $data['type'] = 0;
            }else{
                $data['type'] = 1;//站长。副站长不可操作
            }
        }else{
            $data['type'] = 1;//项目有竣工日期，不可操作
        }

        $this->success('', $data);
    }


    /**
     * @Description 保存已上传图片的url跟图片说明
     * @author YGF
     */
    public function addImage()
    {
        

        $data['project_images'] = $this->request->param('image');//图片url
        $data['project_desc'] = $this->request->param('desc');//检查情况
        $data['project_id'] = $this->request->param('id');//项目id
        $data['coordinate'] = $this->request->param('coordinate');//坐标
        
        if ($data['project_images'] == null) {
            return $this->error('图片不能为空', 1);
        }
        if ($data['project_desc'] == null) {
            return $this->error('说明不能空', 2);
        }
        if ($data['project_id'] == null) {
            return $this->error('项目id不能为空', 3);
        }
        if($data['coordinate'] == null){
            return $this->error('当前位置获取失败', 5);
        }

        $kind = db('quality_info')->where('id', $data['project_id'])->find();//获取当前记录时的工程概况
        if($kind['project_kind'] == 1 && $kind['situation'] == 1){
            $schedule = $kind['schedule'];//当工程类别为房建，工程概况为主体阶段的时候，才有进度 ，例如：3/5
        }else{
            $schedule = "";
        }

        //获取用户信息
        $user = $this->auth->getUser();
        if ($user['identity'] == 0) {
              //查出上传项目的状态，是否符合上传条件
            $msg = db('check_msg')->where('project_id',$data['project_id'])->where('status',2)->find();
            if($msg == null){
                return $this->error('项目还未指派协助检查人员，不能上传。',2);
            }
            //质监部门
            $qua = db('project')->where('id', $data['project_id'])->find();
            $data['dept_type'] = 1;
            $data['quality_id'] = $msg['quality_id'];//主责id
            $data['task'] = $msg['task'];//检查任务
            $data['supervisor'] =$msg['c_supervisor'];//协助检查人员
            $data['push_time'] =Date("Y-m-d,H:i:s");//检查时间
            $data['situation'] = $kind['situation'];//工程概况
            $data['kind'] = $kind['project_kind'];//工程类别
            $data['schedule'] =$schedule;
            $data['quality_assistant'] =  $qua['quality_assistant'];
            $res = db('project_voucher')->insert($data);
            if($res){
                db('check_msg')->where('id',$msg['id'])->delete();//删除check_msg表关联项目id的发起信息内容
                return $this->success('上传成功');
            }
        } else if ($user['identity'] == 1) {
            //安监部门
             $security = db('project')->where('id', $data['project_id'])->find();
            $data['dept_type'] = 2;
            $data['push_time'] =Date("Y-m-d,H:i:s");//检查时间
            $data['situation'] = $kind['situation'];//工程概况
            $data['kind'] = $kind['project_kind'];//工程类别
            $data['schedule'] =$schedule;//工程进度
            $data['security_id'] = $user['admin_id'];
            $data['supervisor_assistant'] = $security['supervisor_assistant'];
        }
        $res = db('project_voucher')->insert($data);
        if ($res ==1 ) {
            return $this->success('上传成功', 0);
        }
        return $this->error('上传失败', $data);
    }

    /**
     * @Description  修改图片
     * @author YGF
     */
    public function editImage()
    {
        $id = $this->request->param('id');
        $data['project_images'] = $this->request->param('image');//图片url
        $data['project_desc'] = $this->request->param('desc');//说明
        $data['coordinate'] = $this->request->param('coordinate');//坐标

        if ($data['project_images'] == null) {
            return $this->error('图片不能为空', 1);
        }
        if ($data['project_desc'] == null) {
            return $this->error('说明不能空', 2);
        }
        if ($id == null) {
            return $this->error('图片id不能为空', 3);
        }
        if($data['coordinate'] == null){
            return $this->error('当前位置获取失败', 5);
        }

        $data['push_time'] = date('Y-m-d H:i:s', time());
        $data['edit_status'] = 0;
        $res = db('project_voucher')->where(['id' => $id])->update($data);
        if ($res == 1 ) {
            return $this->success('修改成功', 0);
        } else {
            return $this->error('修改失败', 4);
        }

    }

     /**
     * @Description  申请修改
     * @author YGF
     */
    public function applyEdit()
    {
        $id = $this->request->param('id');
        if ($id == null) {
            return $this->error('项目id不能为空', 3);
        }

        $data['edit_status'] = 1;
        $res = db('project_voucher')->where(['id' => $id])->update($data);
        if ($res == 1) {
            return $this->success('申请成功', 0);
        }
        return $this->error('申请失败', 4);
    }

     //申请删除项目图片
    public function applyDel()
    {
        $id = $this->request->param('id');
        if ($id == null) {
            return $this->error('项目id不能为空', 1);
        }

        $data['del_status'] = 1;
        $res = db('project_voucher')->where(['id' => $id])->update($data);
        if ($res == 1) {
            return $this->success('申请成功', 0);
        }
        return $this->error('申请失败', 2);
    }

    
     /**
     * @Description  删除图片记录
     * @author YGF
     */
    public function imageDel()
    {
        $id = $this->request->param('id');
        if ($id == null) {
            return $this->error('项目id不能为空', 1);
        }
        $res = db('project_voucher')->where(['id' => $id])->delete();
        if ($res ==1 ) {
            return $this->success('删除成功', 0);
        }
        return $this->error('删除失败', 2);
    }


      /**
     * @Description  修改项目工程状态
     * @author YGF
     */
    public function statusEdit()
    {
        $id = $this->request->param('id');
        $data['status'] = $this->request->param('status');//工程状态
        $data['schedule'] = $this->request->param('schedule');//工程进度
        $data['situation'] = $this->request->param('situation');//工程概况

        if ($id == null) {
            return $this->error('项目id不能为空', 1);
        }
        if ($data['status'] == null) {
            return $this->error('项目状态不能为空', 1);
        }

        $res = db('quality_info')->where(['id' => $id])->update($data);

        if ($res ==1 ) {
            return $this->success('修改成功', 0);
        }
        return $this->error('修改失败', 2);
    }

     /**
     * @Description  上传图片
     * @author YGF
     */
    public function upload(){
        $base64 = $this->request->request('file');
        if ($base64 != null) {
            $base64 = substr(strstr($base64,','),1);
            $base64_body2 = base64_decode($base64);
            
            $dir = ROOT_PATH ."public/uploads/".Date("Ymd");
            if(!is_readable($dir))
                {
                    is_file($dir) or mkdir($dir,0777);
                }

            $url = "/uploads/".Date("Ymd")."/".time().".jpg";
            $path = ROOT_PATH ."public".$url;
            $res = file_put_contents($path, $base64_body2);
            if($res){
                $this->success(__('上传成功了base64'),$url,1);
            }
        }else{
            $this->error(__('没有上传文件'),0,2);
        }

    }


    public function uploadsaa()
    {
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
        if ($upload['mimetype'] !== '*' && (!in_array($suffix, $mimetypeArr)
            || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr))))) {
            $this->error(__('Uploaded file format is limited'));
        }
        $replaceArr = [
            '{year}' => date("Y"),
            '{mon}' => date("m"),
            '{day}' => date("d"),
            '{hour}' => date("H"),
            '{min}' => date("i"),
            '{sec}' => date("s"),
            '{random}' => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}' => $suffix,
            '{.suffix}' => $suffix ? '.' . $suffix : '',
            '{filemd5}' => md5_file($fileInfo['tmp_name']),
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
                'admin_id' => 0,
                'user_id' => (int)$this->auth->id,
                'filesize' => $fileInfo['size'],
                'imagewidth' => $imagewidth,
                'imageheight' => $imageheight,
                'imagetype' => $suffix,
                'imageframes' => 0,
                'mimetype' => $fileInfo['type'],
                'url' => $uploadDir . $splInfo->getSaveName(),
                'uploadtime' => time(),
                'storage' => 'local',
                'sha1' => $sha1,
            );
            $attachment = model("attachment");
            $attachment->data(array_filter($params));
            $attachment->save();
            \think\Hook::listen("upload_after", $attachment);
            $url = $uploadDir . $splInfo->getSaveName();
            $this->success('上传成功', $url);
        } else {
                // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }


 

}