<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\safety;

use app\common\controller\Backend;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_IOFactory;


//安监站长的项目管理
class Master extends Backend{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index(){
        //查出所有的项目，指派副站长
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $field="l.licence_code `licence_code`,project.id,project.build_dept,project.project_name,project.address,project.supervisor_code,supervisor_progress,i.schedule `i.schedule`,i.project_kind `i.project_kind`,i.status `i.status`,i.situation `i.situation`,a.nickname `a.nickname`,s.nickname `s.nickname`";
            $total = $this->model
                ->alias("project")
                ->field($field)
                ->where($where)
                ->join('quality_info i','project.quality_info=i.id')
                ->join('admin a','project.security_id=a.id','LEFT')
                ->join('admin s','project.supervisor_assistant=s.id','LEFT')
                ->join('licence l','project.licence_id=l.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project",'')
                ->field($field)
                ->where($where)
                ->join('quality_info i','project.quality_info=i.id')
                ->join('admin a','project.security_id=a.id','LEFT')
                ->join('admin s','project.supervisor_assistant=s.id','LEFT')
                ->join('licence l','project.licence_id=l.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
    
    //选择副站长
    public function select($ids){
        //查出安监副站长 15
        $assistant['assistant'] = db('admin admin')->field('admin.id,admin.nickname name,admin.mobile')->join('auth_group_access a','a.uid=admin.id and a.group_id=15')->select();
        //查出当前副站长
        $assistant['now'] = db('project')->field('supervisor_assistant')->where(['id'=>$ids])->find()['supervisor_assistant'];
        $this->assign('assistant',$assistant);
        if ($this->request->isAjax())
        {
           $data['supervisor_assistant'] = $this->request->post('supervisor_assistant');
           //TODO 如果是修改做变更记录
           db('project')->where(['id'=>$ids])->update($data);
           $this->success();
        }
        return $this->view->fetch();
    }

    //详细信息
    public function detail($ids){
        $row=db('project')->where(['id'=>$ids])->find();
        $infoId=$row['quality_info'];
        $info=db('quality_info')->where(['id'=>$infoId])->find();
        $info['floor_up']=explode(",",$info['floor'])[0];
        $info['floor_down']=explode(",",$info['floor'])[1];
        $this->assign('row',$row);
        $this->assign('quality',$info);

        $infoId=$row['safety_info'];
        $info=db('safety_info')->where(['id'=>$infoId])->find();
        $this->assign('info',$info);

        $licence=db('licence')->where(['id'=>$row['licence_id']])->find();
        $this->assign('licence',$licence);
        return $this->view->fetch();
    }

     //项目检查
     public function qualitycheck($ids){

        $res = db('check_project')
            ->where('project_id',$ids)
            ->where('status',2)
            ->where('quality_id','exp','is null')
            ->find();
        $result = db('check_project')
            ->where('project_id',$ids)
            ->where('quality_id','exp','is null')
            ->where('status',1)
            ->find();
        if($result){
            return "<h2>你已经发起过检查了</h2>";
        }
        if($res){
            return "<h2>副站长已经发起过检查了</h2>";
        }
        
         //查出质监员 12
         $safety= db('admin admin')
                ->field('admin.id,admin.nickname name')
                ->join('auth_group_access a','a.uid=admin.id and a.group_id=16')
                ->select();
        $this->assign('safety',$safety);

        if($this->request->isPost()){
            $qid = input('post.safety_id/a');
            $row['safety_id'] = implode(",",$qid) ;
            $row['open_time'] = Date("Y-m-d,H:i:s");
            $row['status'] = 1;
            $row['project_id'] = $ids;
            $data = db('check_project')->insert($row);
            if($data == 1){
              return '发起成功！';
            }
            return '发起失败！';
        }
        return $this->fetch();
    }


    //施工安全监督告知书
    public function safety($ids){
        safety_inform($ids);
    }

    // TODO 时间未明确时间用哪个时间
   public function stop($ids){
       $templateProcessor = new TemplateProcessor("./doc/safety.docx");
       $data =  db('project p')->field('build_dept,project_name,quality_code,c.nickname quality,c.mobile quality_mobile')
           ->where(['p.id'=>$ids])
           ->join('admin c','c.id=p.security_id')
           ->select();

       $templateProcessor->setValue('build_dept', $data['build_dept']);
       $templateProcessor->setValue('project_name', $data['project_name']);
       $templateProcessor->setValue('supervisor_code', $data['supervisor_code']);
       $filePath="./runtime/".get_rand_number_char(6).".docx";
       $templateProcessor->saveAs($filePath);
       ob_clean();
       $fp=fopen($filePath,"r");
       $filesize=filesize($filePath);
       header("Content-type:application/octet-stream");
       header("Accept-Ranges:bytes");
       header("Accept-Length:".$filesize);
       header("Content-Disposition: attachment; filename="."终止施工安全监督告知书.docx");
       $buffer=1024;
       $buffer_count=0;
       while(!feof($fp)&&$filesize-$buffer_count>0){
           $data=fread($fp,$buffer);
           $buffer_count+=$buffer;
           echo $data;
       }
       fclose($fp);
       unlink($filePath);
   }

 
}