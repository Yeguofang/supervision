<?php
/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/15
 * Time: 23:07
 */
namespace app\admin\controller\check;

use app\common\controller\Backend;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_IOFactory;

//建管验收
class Build extends Backend{
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
            $order = "build_check asc";
            $field="l.licence_code `licence_code`,project.id,project.build_dept,project.build_check,project.project_name,project.address,project.supervisor_progress,project.quality_progress,i.project_kind `i.project_kind`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->field($field)
                ->where($where)
                ->join('licence l','project.licence_id=l.id')
                ->join('quality_info i','project.quality_info=i.id')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project",'')
                ->field($field)
                ->where($where)
                ->join('licence l','project.licence_id=l.id')
                ->join('quality_info i','project.quality_info=i.id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //监督报告
    public function report($ids){
        $id = db('project')
            ->field('supervisory_report')
            ->where(['id'=>$ids])
            ->find()['supervisory_report'];
            
        $this->assign('id',$id);
        if ($this->request->isAjax())
        {
            //1未提交，2已提交
            $status =  $this->request->post('status');
            if($status){
                $data['supervisory_report']=$status;
            }
            db('project')->where(['id'=>$ids])->update($data);
            $this->success();
        }
        return $this->view->fetch();
    }
    //建管同意验收
    public function deal($ids){
        if ($this->request->isAjax())
        {
			//查询项目，并判断项目是否有提交监督报告
            $report =  db('project')->where('id',$ids)->field("supervisory_report")->find();
            if($report['supervisory_report'] == 1){
                $this->error('监督报告未提交，不能验收');
            }
            $data['build_check']=1;//同意
            $data['quality_progress'] = 5;//建管同意
            db('project')->where(['id'=>$ids])->update($data);
            $this->success('已同意验收');
        }
        return $this->view->fetch();
    }
     //建管b不同意验收
     public function nodeal($ids){
        if ($this->request->isAjax())
        {
            $data['build_check']=2;//不同意
            $data['quality_progress'] = 4;//建管不同意
            db('project')->where(['id'=>$ids])->update($data);
            db('quality_check')->where('project_id',$ids)->delete();//建管不同意，删除主责申请的验收人员名单。
            $this->success('不同意验收，已驳回');   
        }
        return $this->view->fetch();
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
            $columns = $this->request->post('columns');

            $columnsArr = explode(',', $columns);

            for ($i = 0; $i < count($columnsArr); $i++) {
                if ($columnsArr[$i] == 'id') {
                    $columnsArr[$i] = 'p.id';
                }
                if ($columnsArr[$i] == 'a.nickname') {
                    $columnsArr[$i] = 'a.nickname';
                }
                if ($columnsArr[$i] == 'z.nickname') {
                    $columnsArr[$i] = 'z.nickname';
                }
            }

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
            $count = $this->model
                ->alias("p")
                ->where($where)
                ->where($whereIds)
                ->join('quality_info i','p.quality_info=i.id')
                ->count();

            $this->model
                ->alias("p")
                ->field(implode(',', $columnsArr))
                ->where($where)
                ->where($whereIds)
                ->join('quality_info i','p.quality_info=i.id')
                ->paginate($count)
                ->each(function ($items, $index) use (&$line, &$worksheet) {
                    $items = json_decode($items);
                    if ($items->status == '0') {
                        $items->status = "未开工";
                    } else if ($items->status == '1') {
                        $items->status = "在建";
                    } else if ($items->status == '2') {
                        $items->status = "质量停工";
                    } else if ($items->status == '3') {
                        $items->status = "安全停工";
                    } else if ($items->status == '4') {
                        $items->status = "局停工";
                    } else if ($items->status == '5') {
                        $items->status = "自停工";
                    }
                    if ($items->quality_progress == '0') {
                        $items->quality_progress = "未处理";
                    } else if ($items->quality_progress == '1') {
                        $items->quality_progress = "已申请竣工并已通知副站";
                    } else if ($items->quality_progress == '2') {
                        $items->quality_progress = "已通知站长";
                    } else if ($items->quality_progress == '3') {
                        $items->quality_progress = "同意竣工";
                    }
                    if ($items->supervisor_progress == '0') {
                        $items->supervisor_progress = "未处理";
                    } else if ($items->supervisor_progress == '1') {
                        $items->supervisor_progress = "已申请竣工并已通知副站";
                    } else if ($items->supervisor_progress == '2') {
                        $items->supervisor_progress = "已通知站长";
                    } else if ($items->supervisor_progress == '3') {
                        $items->supervisor_progress = "同意竣工";
                    }
                    if ($items->project_kind == '0') {
                        $items->project_kind = '市政建设';
                    } else if ($items->project_kind == '1') {
                        $items->project_kind = '房建';
                    }
                    if($items->build_check == '0'){
                        $items->build_check = "未处理";
                    }else if($items->build_check =='1'){
                        $items->build_check = "已处理";
                    }

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
                'build_dept' => '建设单位',
                'project_name' => '工程名称',
                'address' => '建设地址',
                'i.project_kind' => '工程类别',
                'i.status' => '工程状态',
                'quality_progress' => '安监进度',
                'supervisor_progress' => '安监进度',
                'build_check' => '安监进度',
            ];
            foreach ($columnsArr as $index => $item) {
                $worksheet->setCellValueByColumnAndRow($index, 1, __($kv[$item]));
            }

            $excel->createSheet();
            // Redirect output to a client’s web browser (Excel2007)
            $title = date("YmdHis");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="质监站长负责项目' . $title . '.xls"');
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