<?php

/**
 * Created by PhpStorm.
 * User: xiong
 * Date: 2018/11/26
 * Time: 10:49
 */

namespace app\admin\controller\quality;

use app\common\controller\Backend;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_IOFactory;
//项目资料录入员
class Info extends Backend
{
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

    //项目列表
    public function index()
    {
        //查出所有的项目
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $field = "project.id,project.build_dept,project.project_name,project.address,i.project_kind `i.project_kind`,i.situation `i.situation`,i.status `i.status`";
            $total = $this->model
                ->alias("project")
                ->join('quality_info i', 'project.quality_info=i.id')
                ->field($field)
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("project", '')
                ->join('quality_info i', 'project.quality_info=i.id')
                ->field($field)
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //修改项目信息
    public function edit($ids = null)
    {
        $row = db('project')->where(['id' => $ids])->find();
       
    //    //检查项目是否下发告知书
    //    if($row['quality_code'] == "" ){
    //        return "<h1>项目还未下发告知书，暂时无法编辑！</h1>";
    //    }

        $infoId = $row['quality_info'];
        $info = db('quality_info')->where(['id' => $infoId])->find();
        $info['floor_up'] = explode(",", $info['floor'])[0];
        $info['floor_down'] = explode(",", $info['floor'])[1];
       
       //转时间
        $row['begin_time'] = DataTiem($row['begin_time']);
        $row['finish_time'] = DataTiem($row['finish_time']);
        $row['push_time'] = DataTiem($row['push_time']);
        $row['register_time'] = DataTiem($row['register_time']);
        $row['permit_time'] = DataTiem($row['permit_time']);
        
        $this->assign('row', $row);
        $this->assign('info', $info);
        if ($this->request->isAjax()) {
            $row = $this->request->post('row/a');
           //转时间
            $row['begin_time'] = StrtoTime($row['begin_time']);
            $row['finish_time'] = StrtoTime($row['finish_time']);
            $row['push_time'] = StrtoTime($row['push_time']);
            $row['register_time'] = StrtoTime($row['register_time']);
            $row['permit_time'] = StrtoTime($row['permit_time']);

            $info = $this->request->post('info/a');
            $floor = $this->request->post('floor/a');
            $info['floor'] = $floor[0] . ',' . $floor[1];
            db('project')->where(['id' => $ids])->update($row);
            db('quality_info')->where(['id' => $infoId])->update($info);
            $this->success();
        }
        return $this->view->fetch();
    }

   //工程状况额外状态
    public function situation($ids)
    {
        $data = db('project p')
            ->field('p.quality_info,situation,status_extra')
            ->where('p.id',$ids)
            ->join('quality_info i', 'i.id=p.quality_info')
            ->find();
        if ($data['situation'] == 1) {
            //主体阶段
            $extra = explode(",", $data['status_extra']);
            if (count($extra) == 1) {
                $extra[0] = '';
                $extra[1] = '';
            }
            $info['type'] = $extra[0];
            $info['floor'] = $extra[1];
        } else {
            $info['type'] = $data['status_extra'];
        }
        $info['situation'] = $data['situation'];
        $this->assign('info', $info);
        if ($this->request->isAjax()) {
            $post = $this->request->post('info/a');
            if ($post['situation'] == 1) {
                //主体阶段
                $up['status_extra'] = $post['type'] . ',' . $post['floor'];
            } else {
                $up['status_extra'] = $post['type'];
            }
            db('quality_info')->where(['id' => $data['quality_info']])->update($up);
            $this->success();
        }
        return $this->view->fetch();
    }

    //质监资料录入员excel表导出
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
            $line = 2;
            $columns = "p.id,p.build_dept,l.survey_company,l.design_company,l.supervision_company,l.construction_company,i.check_company,i.picture_company,p.project_name,i.project_kind,i.energy,p.address,l.area,i.extend,l.cost,i.structure,i.floor,l.licence_code,i.schedule,p.supervise_time,p.register_time,p.permit_time,p.begin_time,p.finish_time,p.push_time,p.begin_time,p.check_time,p.record_time,a.nickname,i.build_person,survey_person,l.design_person,l.supervision_company";

            $count = $this->model
                ->alias("p")
                ->where($where)
                ->where($whereIds)
                ->join('quality_info i', 'p.quality_info=i.id')
                ->join('licence l', 'p.licence_id=l.id')
                ->count();

            $this->model
                ->alias("p")
                ->field($columns)
                ->where($where)
                ->where($whereIds)
                ->join('admin a', 'p.quality_id=a.id')
                ->join('quality_info i', 'p.quality_info=i.id')
                ->join('licence l', 'p.licence_id=l.id')
                ->paginate($count)
                ->each(function ($items, $index) use (&$line, &$worksheet) {
                    $items = json_decode($items);
                    $items->supervise_time = date('Y-m-d', $items->supervise_time);
                    $items->begin_time = date('Y-m-d', $items->begin_time);
                    $items->finish_time = date('Y-m-d', $items->finish_time);
                    $items->push_time = date('Y-m-d', $items->push_time);
                    $items->register_time = date('Y-m-d', $items->register_time);
                    $items->permit_time = date('Y-m-d', $items->permit_time);
                    $items->check_time = date('Y-m-d', $items->check_time);
                    $items->record_time = date('Y-m-d', $items->record_time);

                    if ($items->project_kind == '0') {
                        $items->project_kind = '市政建设';
                    } else if ($items->project_kind == '1') {
                        $items->project_kind = '房建';
                    }

                    $styleArray = array(
                        'font' => array(
                            'color' => array('rgb' => '000000'),
                            'size' => 12,
                            'name' => 'Verdana'
                        ),
                        'width' => '70',
                    );

                    $line++;
                    $col = 0;
                    foreach ($items as $index => $value) {
                        $worksheet->setCellValueByColumnAndRow($col, $line, $value);
                        $worksheet->getStyleByColumnAndRow($col, $line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $worksheet->getCellByColumnAndRow($col, $line)->getStyle()->applyFromArray($styleArray);

                        $col++;
                    }
                });

            $kv = [
                'p.id' => 'ID',
                'p.build_dept' => '建设单位',
                'l.survey_company' => '勘察单位',
                'l.design_company' => '设计单位',
                'l.supervision_company' => '监理单位',
                'l.construction_company' => '施工单位',
                'i.check_company' => '检测单位',
                'i.picture_company' => '图审机构',
                'p.project_name' => '工程名称',
                'i.project_kind' => '工程类别',
                'i.energy' => '节能',
                'p.address' => '建设地址',
                'l.area' => '面积(m²)',
                'i.extend' => '道路工程延长米',
                'l.cost' => '造价（万元）',
                'i.structure' => '结构形式',
                'i.floor' => '层数（地上，地下）',
                'l.licence_code' => '施工许可证号',
                'i.schedule' => '工程进度',
                'p.supervise_time' => '报监日期',
                'p.register_time' => '监督注册表审批',
                'p.permit_time' => '施工许可审批',
                'p.begin_time' => '开工时间',
                'p.finish_time' => '竣工日期',
                'p.push_time' => '报建日期',
                'p.begin_time' => '开工日期',
                'p.check_time' => '验收日期',
                'p.record_time' => '备案日期',
                'a.nickname' => '主责质监员',
                'i.build_person' => '建设单位项目负责人',
                'survey_person' => '勘察单位项目负责人',
                'l.design_person' => '设计单位项目负责人',
                'l.supervision_company' => '总监',
            ];

            $columnsArr = explode(',', $columns);
            foreach ($columnsArr as $index => $item) {
                $worksheet->setCellValueByColumnAndRow($index, 2, __($kv[$item]));
            }
            $worksheet->setCellValueByColumnAndRow(0, 1, "质量监督");

            $excel->createSheet();
            // Redirect output to a client’s web browser (Excel2007)
            $title = date("YmdHis");
            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $title . '.xls"');
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
    //质监检查信息导出
    public function checkExport()
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

            $whereIds = $ids == 'all' ? '1=1' : ['c.project_id' => ['in', explode(',', $ids)]];
            $this->request->get(['search' => $search, 'ids' => $ids, 'filter' => $filter, 'op' => $op]);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $line = 2;

            $columns = "c.id,c.push_time,p.project_name,p.build_dept,l.construction_company,l.supervision_company,p.address,c.task,a.nickname,c.supervisor,c.project_desc";

            $count = model('project_voucher')
                ->alias("c")
                ->where($where)
                ->where($whereIds)
                ->join('admin a', 'c.quality_id=a.id')
                ->join('project p', 'p.id=c.project_id')
                ->join('licence l', 'p.licence_id=l.id')
                ->count();

            model('project_voucher')
                ->field($columns)
                ->alias("c")
                ->where($where)
                ->where($whereIds)
                ->join('admin a', 'c.quality_id=a.id')
                ->join('project p', 'p.id=c.project_id')
                ->join('licence l', 'p.licence_id=l.id')
                ->paginate($count)
                ->each(function ($items, $index) use (&$line, &$worksheet) {
                    $items = json_decode($items);
                    $styleArray = array(
                        'font' => array(
                            'color' => array('rgb' => '000000'),
                            'size' => 12,
                            'name' => 'Verdana'
                        ),
                        'width' => '70',
                    );

                    $line++;
                    $col = 0;
                    foreach ($items as $index => $value) {
                        $worksheet->setCellValueByColumnAndRow($col, $line, $value);
                        $worksheet->getStyleByColumnAndRow($col, $line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        $worksheet->getCellByColumnAndRow($col, $line)->getStyle()->applyFromArray($styleArray);

                        $col++;
                    }
                });
            $kv = [
                'c.id' => '序号',
                'c.push_time' => '检查时间',
                'p.project_name' => '工程名称',
                'p.build_dept' => '建设单位',
                'l.construction_company' => '施工单位',
                'l.supervision_company' => '监理单位',
                'p.address' => '工程地点',
                'c.task' => '检查任务内容',
                'a.nickname' => '主监督员',
                'c.supervisor' => '参与监督人员',
                'c.project_desc' => '查处情况',
            ];

            $columnsArr = explode(',', $columns);
            foreach ($columnsArr as $index => $item) {
                $worksheet->setCellValueByColumnAndRow($index, 2, __($kv[$item]));
            }
            $worksheet->setCellValueByColumnAndRow(0, 1, "质量工程检查");

            $excel->createSheet();
            // Redirect output to a client’s web browser (Excel2007)
            $title = date("YmdHis");
            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $title . '.xls"');
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