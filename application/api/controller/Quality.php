<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/28
 * Time: 23:54
 */
namespace app\api\controller;

use app\common\controller\Api;
class Quality extends Api
{
    protected $noNeedRight = ['*'];

    /**
     * 质监站
     *
     */
    //项目列表
    public function listing()
    {
        $user = $this->auth->getUser();
        $page= $this->request->param('page',1);
        $num= $this->request->param('num',2);
        $map=[];
        if($user['identity_type']==1){
            //副站长只能看到quality_assistant是自己
            $map['quality_assistant']=$user['admin_id'];
        }elseif ($user['identity_type']==2){
            //副站长只能看到quality_id是自己
            $map['quality_id']=$user['admin_id'];
        }
        //差竣工联系人
        $data=db('project p')->field('p.id,p.build_dept,p.project_name,p.address,p.push_time,
        p.begin_time,p.finish_time,p.check_time,l.supervision_person,i.status')
            ->join('quality_info i','p.quality_info=i.id')
            ->join('licence l','p.licence_id=l.id')
            ->where($map)->page($page,$num)->select();
        for ($i = 0; $i < count($data); $i++) {
            $time = $data[$i]['begin_time'];
            if($time!=null){
                $data[$i]['begin_time'] =date("Y-m-d",$time);
            }else{
                $data[$i]['begin_time'] ='暂无';
            }
            $time = $data[$i]['push_time'];
            if($time!=null){
                $data[$i]['push_time'] =date("Y-m-d",$time);
            }else{
                $data[$i]['push_time'] ='暂无';
            }
            $time = $data[$i]['finish_time'];
            if($time!=null){
                $data[$i]['finish_time'] =date("Y-m-d",$time);
            }else{
                $data[$i]['finish_time'] ='暂无';
            }
            $time = $data[$i]['check_time'];
            if($time!=null){
                $data[$i]['check_time'] =date("Y-m-d",$time);
            }else{
                $data[$i]['check_time'] ='暂无';
            }
        }
        $this->success('',$data);
    }
    //项目详情
    public function detail(){
        $id= $this->request->param('id');
        $field='p.id,p.build_dept,p.project_name,p.address,l.area,l.cost,i.floor,p.push_time,p.supervise_time,p.begin_time bTime,l.begin_time,l.end_time,p.finish_time,p.check_time,l.construction_person,l.supervision_person,i.status';
        $data=db('project p')->field($field)->join('quality_info i','p.quality_info=i.id')
         ->join('licence l','p.licence_id=l.id')->where(['p.id'=>$id])
            ->find();
        $floor = explode(',', $data['floor']);
        if(count($floor)==1){
            $data['floor']= '暂无';
        }else{
            $data['floor']='地上：'.$floor[0].' 地下：'.$floor[1];
        }
        if($data['push_time']!=null){
            $data['push_time'] = date("Y-m-d",$data['push_time']);
        }else{
            $data['push_time'] = '暂无';
        }
        if($data['supervise_time']!=null){
            $data['supervise_time'] =date("Y-m-d",$data['supervise_time']);
        }else{
            $data['supervise_time'] = '暂无';
        }
        if($data['begin_time']!=null&&$data['end_time']!=null){
            $data['permit'] = date("Y-m-d",$data['begin_time']) . "-" . date("Y-m-d",$data['end_time']);
        }else{
            $data['permit'] ='暂无';
        }
        if($data['begin_time']!=null){
            $data['begin_time'] =date("Y-m-d",$data['bTime']);
        }else{
            $data['begin_time']='暂无';
        }
        if($data['finish_time']!=null){
            $data['finish_time'] =date("Y-m-d",$data['finish_time']);
        }else{
            $data['finish_time'] = '暂无';
        }
        if($data['check_time']!=null){
            $data['check_time'] =date("Y-m-d",$data['check_time']);
        }else{
            $data['check_time'] = '暂无';
        }
        $this->success('', $data);
    }

}