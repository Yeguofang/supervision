<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/25
 * Time: 19:53
 */

namespace app\admin\controller\administration;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\Session;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_IOFactory;

//行政管理
class Project extends Backend{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index(){
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->alias("project")
                ->where($where)
                ->join('licence l','s_project.licence_id=l.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project")
                ->where($where)
                ->join('licence l','s_project.licence_id=l.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //新建项目档案
    public function add()
    {
        $adminId = Session::get('admin')['id'];
        $dept = db('project')->field('build_dept')->select();
        $this->assign("dept",$dept);
        if ($this->request->isAjax())
        {
            //新增一个空的licence和quality_info和safety_info把id放在新增的project上
            $row = $this->request->post('row/a');
            Db::startTrans();
            try{
                $fresh['create_time'] =time();
                db('licence')->insert($fresh);
                db('quality_info')->insert($fresh);
                db('safety_info')->insert($fresh);
                
                $row['licence_id']=db('licence')->getLastInsID();
                $row['quality_info']=db('quality_info')->getLastInsID();
                $row['safety_info']=db('safety_info')->getLastInsID();

                $row['admin_id']=$adminId;
                db('project')->insert($row);

                //TODO 通知质监站站长 还有安监站站长

                Db::commit();
                $this->success();
            }catch (Exception $e){
                Db::rollback();
                $this->error("新建失败");
            }
        }

        return $this->view->fetch();
    }

    //修改项目档案
    public function edit($ids = NULL)
    {
       
        $row = db('project')
            ->field('build_dept,project_name,address,licence_id,project_type,special_one,special_two,supervisor_one,supervisor_two')
            ->where(['id'=>$ids])->find();
            // dump($row);exit;
        $row['project_type'] =explode(',', $row['project_type']);//工程项目

        $licence = db('licence')
            ->field('qr_code,licence_code,area,cost,survey_company,design_company,construction_company,supervision_company,survey_person,design_person,construction_person,supervision_person,begin_time,end_time')
            ->where(['id'=>$row['licence_id']])
            ->find();
        if($licence['begin_time']!=null){
            $licence['begin_time']=date('Y-m-d', $licence['begin_time']);
        }
        if($licence['end_time']!=null){
            $licence['end_time']=date('Y-m-d', $licence['end_time']);
        }

        //调用五大责任关联查询方法
        $five =$this->Five();
        // dump($five['build_dept'][0]['name']);exit;
        $this->assign('row',$row);
        $this->assign('licence',$licence);
        $this->assign('five',$five);
        
        if ($this->request->isAjax())
        {
           $project = $this->request->post('row/a');
           
           //工程项目
           $project['project_type'] = $this->request->post('project_type/a');
           $project['project_type'] = implode(",",$project['project_type']);

           $licence = $this->request->post('licence/a');
            if($licence['begin_time']==''){
                $licence['begin_time']=null;
            }else{
                $licence['begin_time']=strtotime($licence['begin_time']);
            }
            if($licence['end_time']==''){
                $licence['end_time']=null;
            }else{
                $licence['end_time']=strtotime($licence['end_time']);
            }
            db('project')->where(['id'=>$ids])->update($project);
            db('licence')->where(['id'=>$row['licence_id']])->update($licence);
            
            $licence['build_dept'] = $project['build_dept'];
            $this->five_insert($licence);
            
            $this->success();
        }
        return $this->view->fetch();
    }


    //修改项目的时候，五大责任输入框的关联查询输出，
    public function Five()
    {
        $build_dept = db('five_duty')->where('type',1)->select();
        $design_company = db('five_duty')->where('type',2)->select();
        $design_person = db('five_duty')->where('type',3)->select();
        $survey_company = db('five_duty')->where('type',4)->select();
        $survey_person = db('five_duty')->where('type',5)->select();
        $construction_company = db('five_duty')->where('type',6)->select();
        $construction_person = db('five_duty')->where('type',7)->select();
        $supervision_company = db('five_duty')->where('type',8)->select();
        $supervision_person = db('five_duty')->where('type',9)->select();

        $five =array(
            'build_dept' => $build_dept,
            'design_company' => $design_company,
            'design_person'  => $design_person,
            'survey_company' => $survey_company,
            'survey_person' => $survey_person,
            'construction_company' => $construction_company,
            'construction_person' => $construction_person,
            'supervision_company' => $supervision_company,
            'supervision_person' => $supervision_person,
        );
        return $five;
    }
    
    // 行政管理修改过项目的五大责任体系后，把修改过的信息插入five_duty表，用于修改的时候关联查询
    public function five_insert($licence){
        $this->five_duty($licence['build_dept'],1);
        $this->five_duty($licence['design_company'],2);
        $this->five_duty($licence['design_person'],3);
        $this->five_duty($licence['survey_company'],4);
        $this->five_duty($licence['survey_person'],5);
        $this->five_duty($licence['construction_company'],6);
        $this->five_duty($licence['construction_person'],7);
        $this->five_duty($licence['supervision_company'],8);
        $this->five_duty($licence['supervision_person'],9);
        return;
    }

    public function five_duty($name,$type)
    {
        if($name != null){
            $result = db('five_duty')->where('name',$name)->where('type',$type)->find();
            if($result == null){
                $data = [
                    'name' =>$name,
                    'type' => $type
                ];
                db('five_duty')->insert($data);
            }
        }
        return;

    }

   


    // 项目检查记录详情
    public function Checkinfo($ids){
        $project = db('project_voucher')->where('id',$ids)->find();
        $image = explode(",",$project['project_images']);
        for($i=0;$i<count($image);$i++){
            $image[$i] = "http://security.dreamwintime.com/supervision/public".$image[$i];
        }

        $w ="";
        $h= "";
        if($project['coordinate'] != null){
            $coordinate = explode(",",$project['coordinate']);
            $w = $coordinate[0];
            $h = $coordinate[1];
        }
        $this->assign('project',$project);
        $this->assign('image',$image);
        $this->assign('count',count($image));
        $this->assign('w',$w);
        $this->assign('h',$h);
        return $this->fetch();
    }

      //建管验收excel表导出
      public function export()
      {
          if ($this->request->isPost()) {
              set_time_limit(0);
              $search = $this->request->post('search');
              $ids = $this->request->post('ids');
              $filter = $this->request->post('filter');
              $op = $this->request->post('op');
  
              $excel = new PHPExcel();
  
              $excel->getProperties()
                  ->setCreator("FastAdmin")
                  ->setLastModifiedBy("FastAdmin")
                  ->setTitle("标题")
                  ->setSubject("Subject");
              $excel->getDefaultStyle()->getFont()->setName('Microsoft Yahei');
              $excel->getDefaultStyle()->getFont()->setSize(12);
  
              $this->sharedStyle = new PHPExcel_Style();
              $this->sharedStyle->applyFromArray(
                  array(
                      'fill' => array(
                          'type' => PHPExcel_Style_Fill::FILL_SOLID,
                          'color' => array('rgb' => '000000')
                      ),
                      'font' => array(
                          'color' => array('rgb' => "000000"),
                      ),
                      'alignment' => array(
                          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                          'indent' => 1
                      ),
                      'borders' => array(
                          'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                      )
                  )
              );
  
              $worksheet = $excel->setActiveSheetIndex(0);
              $worksheet->setTitle('标题');
  
              $whereIds = $ids == 'all' ? '1=1' : ['p.id' => ['in', explode(',', $ids)]];
              $this->request->get(['search' => $search, 'ids' => $ids, 'filter' => $filter, 'op' => $op]);
              list($where, $sort, $order, $offset, $limit) = $this->buildparams();
              $line = 1;
              $columns = "p.id,p.build_dept,p.project_name,p.address,p.licence_id,l.licence_code,l.area,l.cost,l.survey_company,l.design_company,l.construction_company,l.supervision_company,l.survey_person,l.design_person,l.construction_person,l.supervision_person,l.begin_time,l.end_time";
            
              $count = $this->model
                  ->alias("p")
                  ->where($where)
                  ->where($whereIds)
                  ->join('licence l','p.licence_id =l.id')
                  ->count();
  
              $this->model
                  ->alias("p")
                  ->field($columns)
                  ->where($where)
                  ->where($whereIds)
                  ->join('licence l','p.licence_id =l.id')
                  ->paginate($count)
                  ->each(function ($items, $index) use (&$line, &$worksheet) {
                      $items = json_decode($items);
                      $items->begin_time =date('Y-m-d', $items->begin_time);
                      $items->end_time =date('Y-m-d', $items->end_time);
                      $items->area = $items->area."平方米";
                      $items->cost = $items->cost."万";
                      $line++;
                      $col = 0;
                      foreach ($items as $index => $value) {
                          $worksheet->setCellValueByColumnAndRow($col, $line, $value);
                          $worksheet->getStyleByColumnAndRow($col, $line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                          $col++;
                      }
                  });
               
              $kv = [
                  'p.id' => 'ID',
                  'p.build_dept' => '建设单位',
                  'p.project_name' => '工程名称',
                  'p.address' => '建设地址',
                  'l.licence_code' => '编号',
                  'l.area' => '建设规模',
                  'l.cost' => '合同价格',
                  'l.survey_company' => '勘察单位',
                  'l.design_company' => '设计单位',
                  'l.construction_company' => '施工单位',
                  'l.supervision_company' => '监理单位',
                  'l.survey_person' => '勘察负责人',
                  'l.design_person' => '设计负责人',
                  'l.construction_person' => '施工负责人',
                  'l.supervision_person' => '总监工程师',
                  'l.begin_time' => '合同开始日期',
                  'l.end_time' => '合同结束日期',
                  'p.licence_id' => '',
                 
              ];

              $columnsArr = explode(',', $columns);
              foreach ($columnsArr as $index => $item) {
                  $worksheet->setCellValueByColumnAndRow($index, 1, __($kv[$item]));
              }
  
              $excel->createSheet();
              // Redirect output to a client’s web browser (Excel2007)
              $title = date("YmdHis");
              ob_end_clean();
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment;filename="行政建档' . $title . '.xls"');
              header('Cache-Control: max-age=0');
              // If you're serving to IE 9, then the following may be needed
              header('Cache-Control: max-age=1');
  
              // If you're serving to IE over SSL, then the following may be needed
              header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
              header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
              header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
              header('Pragma: public'); // HTTP/1.0
  
              $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
              $objWriter->save('php://output');
              return;
          }
      }
  

}