<?php

namespace app\admin\controller;

use app\admin\model\AdminLog;
use app\common\controller\Backend;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_IOFactory;

/**
 * 后台首页
 * @internal
 */
class Excel extends Backend
{

    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('project');
    }

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
                ->join('quality_info i', 'p.quality_info=i.id')
                ->join('admin a', 'p.quality_id=a.id', 'LEFT')
                ->join('admin z', 'p.quality_assistant=z.id', 'LEFT')
                ->count();

            $this->model
                ->alias("p")
                ->field(implode(',', $columnsArr))
                ->where($where)
                ->where($whereIds)
                ->join('quality_info i', 'p.quality_info=i.id')
                ->join('admin a', 'p.quality_id=a.id')
                ->join('admin z', 'p.quality_assistant=z.id')
                ->paginate($count)
                ->each(function ($items,$index) use (&$line, &$worksheet) {
                    $items = json_decode($items);
                    
                    dump($items);exit;
                        if($items->status == '0'){
                            $items->status ="未开工";
                        }else if($items->status == '1'){
                            $items->status =  "在建";
                        }else if($items->status == '2'){
                            $items->status = "质量停工";
                        }else if($items->status == '3'){
                            $items->status = "安全停工";
                        }else if($items->status == '4'){
                            $items->status = "局停工";
                        }else if($items->status == '5'){
                            $items->status = "自停工";
                        }
                        if($items->quality_progress == '0'){
                            $items->quality_progress = "未处理";
                        }else if($items->quality_progress == '1'){
                            $items->quality_progress = "已申请竣工并已通知副站";
                        }else if($items->quality_progress == '2'){
                            $items->quality_progress = "已通知站长";
                        }else if($items->quality_progress == '3'){
                            $items->quality_progress = "同意竣工";
                        }
                        if($items->project_kind == '0'){
                            $items->project_kind = '市政建设';
                            if($items->situation == '0'){
                                $items->situation = "路基处理";
                            }else if($items->situation == '1'){
                                $items->situation = "路面工程";
                            }else if($items->situation == '2'){
                                $items->situation = "排水系统";
                            }else if($items->situation == '3'){
                                $items->situation = "绿化照明";
                            }else if($items->situation == '4'){
                                $items->situation = "标识标线";
                            }else if($items->situation == '5'){
                                $items->situation = "完成";
                            }else if($items->situation == '6'){
                                $items->situation = "竣工验收";
                            }
                        }else if($items->project_kind == '1'){
                            $items->project_kind = '房建';
                            if($items->situation == '0'){
                                $items->situation = "基础阶段";
                            }else if($items->situation == '1'){
                                $items->situation = "主体阶段";
                            }else if($items->situation == '2'){
                                $items->situation = "装饰阶段";
                            }else if($items->situation == '3'){
                                $items->situation = "收尾";
                            }else if($items->situation == '4'){
                                $items->situation = "完工";
                            }else if($items->situationue == '5'){
                                $items->situation = "竣工验收";
                            }
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
                'a.nickname' => '质监员',
                'z.nickname' => '质监副站长',
                'i.project_kind' => '工程类别',
                'i.situation' => '工程概况',
                'i.status' => '工程状态',
                'quality_progress' => '工程进度',
            ];
            foreach ($columnsArr as $index => $item) {
                $worksheet->setCellValueByColumnAndRow($index, 1, __($kv[$item]));
            }

            $excel->createSheet();
            // Redirect output to a client’s web browser (Excel2007)
            $title = date("YmdHis");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="质监站长负责项目' . $title . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $objWriter->save('php://output');
            return;
        }
    }


   

}