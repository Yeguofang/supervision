<?php

use app\common\model\Category;
use fast\Form;
use fast\Tree;
use PhpOffice\PhpWord\TemplateProcessor;
use think\Db;

if (!function_exists('getJson')) {
    function getJson($status, $msg="", $data=array()){
    $result = array(
        'status'  =>  $status,
        'msg'   =>  $msg,
        'data'  =>  $data
    );
    return json($result);
}

}

//时间戳转换Y-m-d时间
if(!function_exists('DataTiem')){
    function DataTiem($time)
    {
        if($time!=null){
            $time=date('Y-m-d', $time);
            return $time;
        }
    }
}

//时间Y-m-d转换时间戳
if(!function_exists('StrtoTime')){
    function StrtoTime($time){
        if($time == ''){
            $time = null;
        }else{
            $time=strtotime($time);
        }
        return $time;
    }
}




if (!function_exists('safety_inform')) {
    function safety_inform($ids) {

        $templateProcessor = new TemplateProcessor("./doc/safety.docx");
        dump("aa");
        $data =  db('project p')->field('build_dept,project_name,supervisor_time,supervisor_code,c.nickname quality,c.mobile quality_mobile')
            ->where(['p.id'=>$ids])
            ->join('admin c','c.id=p.security_id')
            ->find();

        $templateProcessor->setValue('year', date("Y",$data['supervisor_time']));
        $templateProcessor->setValue('month', date("m",$data['supervisor_time']));
        $templateProcessor->setValue('day', date("d",$data['supervisor_time']));
        $templateProcessor->setValue('build_dept', $data['build_dept']);
        $templateProcessor->setValue('project_name', $data['project_name']);
        $templateProcessor->setValue('supervisor_code', $data['supervisor_code']);
        //查监督组
        $person = db('person_project p')
            ->field('a.nickname,a.mobile')
            ->where(['p.project_id'=>$ids,'p.type'=>2])->join('admin a','a.id=p.admin_id')->select();
        $people = $data['quality'].',';
        $phone = $data['quality_mobile'].',';
        foreach ($person as $v){
            $people=$people.$v['nickname'].',';
            $phone=$phone.$v['mobile'].',';
        }
        $people= rtrim($people, ',');
        $phone=rtrim($phone, ',');
        $templateProcessor->setValue('people', $people);
        $templateProcessor->setValue('phone', $phone);
        //文档保存路径
        $filePath=get_rand_number_char(6).".docx";
        $templateProcessor->saveAs($filePath);
        ob_clean();
        $fp=fopen($filePath,"r");
        $filesize=filesize($filePath);
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Accept-Length:".$filesize);
        header("Content-Disposition: attachment; filename="."施工安全监督告知书.docx");
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


if (!function_exists('quality_inform')) {
    function quality_inform($ids) {
        $templateProcessor = new TemplateProcessor("./doc/quality.docx");
        //查询质监，安监，质监站长，安监站长等人员信息
        $data =  db('project p')->field('p.quality_time,build_dept,project_name,quality_code,c.nickname quality,c.mobile quality_mobile,
        a.nickname assistant,a.mobile assistant_mobile,m.nickname master,m.mobile master_mobile')
            ->where(['p.id'=>$ids])
            ->join('admin c','c.id=p.quality_id')
            ->join('admin a','a.id=p.quality_assistant')
            ->join('auth_group_access g','g.group_id = 10')
            ->join('admin m','m.id=g.uid')
            ->find();
//        $master =db('admin a')->field('nickname,mobile')->join('auth_group_access g','g.uid=a.id and g.')
        
        //赋值quality.doc模板文件中的变量      
        $templateProcessor->setValue('year', date("Y",$data['quality_time']));
        $templateProcessor->setValue('month', date("m",$data['quality_time']));
        $templateProcessor->setValue('day', date("d",$data['quality_time']));
        $templateProcessor->setValue('build_dept', $data['build_dept']);
        $templateProcessor->setValue('project_name', $data['project_name']);
        $templateProcessor->setValue('quality_code', $data['quality_code']);
        $templateProcessor->setValue('quality', $data['quality']);
        $templateProcessor->setValue('quality_phone', $data['quality_mobile']);
        $templateProcessor->setValue('assistant', $data['assistant']);
        $templateProcessor->setValue('assistant_phone', $data['assistant_mobile']);
        $templateProcessor->setValue('master', $data['master']);
        $templateProcessor->setValue('master_phone', $data['master_mobile']);
        //查监督组
        $person = db('person_project p')
                ->field('a.nickname,a.mobile')
                ->where(['p.project_id'=>$ids,'p.type'=>1])
                ->join('admin a','a.id=p.admin_id')
                ->select();

        $str = '';
        foreach ($person as $v){
            $str=$str.$v['nickname'].'(监督员):'.$v['mobile'].',';
        }
        $str=rtrim($str, ',');

        $templateProcessor->setValue('people', $str);
        
        $filePath=get_rand_number_char(6).".docx";

        $templateProcessor->saveAs($filePath);
        ob_clean();
        $fp=fopen($filePath,"r");
        $filesize=filesize($filePath);
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Accept-Length:".$filesize);
        header("Content-Disposition: attachment; filename="."建设工程质量监督登记告知书.docx");
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

if (!function_exists('get_rand_number_char')) {
    function get_rand_number_char($len = 30,$type=1) {
        if($type==1){
            $chars = '0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
        }else{
            $chars = 'QWERTYUIOPLKJHGFDSAZXCVBNM';
        }

        $CheckCode = "";
        while (strlen($CheckCode) < $len)
            $CheckCode.=substr($chars, (mt_rand() % strlen($chars)), 1);
        return $CheckCode;
    }

}
if (!function_exists('judge_identity')) {

    function judge_identity($adminId,$type)
    {
        //type=1是质监，type=2是安监
       $group = db('auth_group_access')->where(['uid'=>$adminId])->find()['group_id'];
       $ret = 0;
       if($type==1){
           if($group==10){
               $ret = 1;
           }else if($group==11){
               $ret = 2;
           }
       }else{
           if($group==14){
               $ret = 1;
           }else if($group==15){
               $ret = 2;
           }
       }
       return $ret;

    }


}

if(!function_exists("guid")){
    function guid() {
        if (function_exists('com_create_guid')) {
            return strtolower(str_replace(array('-','{','}'),'',com_create_guid()));
        } else {
            mt_srand((double)microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = chr(123)
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125);
            return strtolower(str_replace(array('-', '{', '}'), '', $uuid));
        }
    }
}

if (!function_exists('build_select')) {

    /**
     * 生成下拉列表
     * @param string $name
     * @param mixed $options
     * @param mixed $selected
     * @param mixed $attr
     * @return string
     */
    function build_select($name, $options, $selected = [], $attr = [])
    {
        $options = is_array($options) ? $options : explode(',', $options);
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        return Form::select($name, $options, $selected, $attr);
    }
}

if (!function_exists('build_radios')) {

    /**
     * 生成单选按钮组
     * @param string $name
     * @param array $list
     * @param mixed $selected
     * @return string
     */
    function build_radios($name, $list = [], $selected = null)
    {
        $html = [];
        $selected = is_null($selected) ? key($list) : $selected;
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        foreach ($list as $k => $v) {
            $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::radio($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}"]));
        }
        return '<div class="radio">' . implode(' ', $html) . '</div>';
    }
}

if (!function_exists('build_checkboxs')) {

    /**
     * 生成复选按钮组
     * @param string $name
     * @param array $list
     * @param mixed $selected
     * @return string
     */
    function build_checkboxs($name, $list = [], $selected = null)
    {
        $html = [];
        $selected = is_null($selected) ? [] : $selected;
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        foreach ($list as $k => $v) {
            $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::checkbox($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}"]));
        }
        return '<div class="checkbox">' . implode(' ', $html) . '</div>';
    }
}


if (!function_exists('build_category_select')) {

    /**
     * 生成分类下拉列表框
     * @param string $name
     * @param string $type
     * @param mixed $selected
     * @param array $attr
     * @return string
     */
    function build_category_select($name, $type, $selected = null, $attr = [], $header = [])
    {
        $tree = Tree::instance();
        $tree->init(Category::getCategoryArray($type), 'pid');
        $categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = $header ? $header : [];
        foreach ($categorylist as $k => $v) {
            $categorydata[$v['id']] = $v['name'];
        }
        $attr = array_merge(['id' => "c-{$name}", 'class' => 'form-control selectpicker'], $attr);
        return build_select($name, $categorydata, $selected, $attr);
    }
}

if (!function_exists('build_toolbar')) {

    /**
     * 生成表格操作按钮栏
     * @param array $btns 按钮组
     * @param array $attr 按钮属性值
     * @return string
     */
    function build_toolbar($btns = NULL, $attr = [])
    {
        $auth = \app\admin\library\Auth::instance();
        $controller = str_replace('.', '/', strtolower(think\Request::instance()->controller()));
        $btns = $btns ? $btns : ['refresh', 'add', 'edit', 'del', 'import'];
        $btns = is_array($btns) ? $btns : explode(',', $btns);
        $index = array_search('delete', $btns);
        if ($index !== FALSE) {
            $btns[$index] = 'del';
        }
        $btnAttr = [
            'refresh' => ['javascript:;', 'btn btn-primary btn-refresh', 'fa fa-refresh', '', __('Refresh')],
            'add'     => ['javascript:;', 'btn btn-success btn-add', 'fa fa-plus', __('Add'), __('Add')],
            'edit'    => ['javascript:;', 'btn btn-success btn-edit btn-disabled disabled', 'fa fa-pencil', __('Edit'), __('Edit')],
            'del'     => ['javascript:;', 'btn btn-danger btn-del btn-disabled disabled', 'fa fa-trash', __('Delete'), __('Delete')],
            'import'  => ['javascript:;', 'btn btn-danger btn-import', 'fa fa-upload', __('Import'), __('Import')],
        ];
        $btnAttr = array_merge($btnAttr, $attr);
        $html = [];
        foreach ($btns as $k => $v) {
            //如果未定义或没有权限
//            if (!isset($btnAttr[$v]) || ($v !== 'refresh' && !$auth->check("{$controller}/{$v}"))) {
//                continue;
//            }
            list($href, $class, $icon, $text, $title) = $btnAttr[$v];
            $extend = $v == 'import' ? 'id="btn-import-file" data-url="ajax/upload" data-mimetype="csv,xls,xlsx" data-multiple="false"' : '';
            $html[] = '<a href="' . $href . '" class="' . $class . '" title="' . $title . '" ' . $extend . '><i class="' . $icon . '"></i> ' . $text . '</a>';
        }
        return implode(' ', $html);
    }
}

if (!function_exists('build_heading')) {

    /**
     * 生成页面Heading
     *
     * @param string $path 指定的path
     * @return string
     */
    function build_heading($path = NULL, $container = TRUE)
    {
        $title = $content = '';
        if (is_null($path)) {
            $action = request()->action();
            $controller = str_replace('.', '/', request()->controller());
            $path = strtolower($controller . ($action && $action != 'index' ? '/' . $action : ''));
        }
        // 根据当前的URI自动匹配父节点的标题和备注
        $data = Db::name('auth_rule')->where('name', $path)->field('title,remark')->find();
        if ($data) {
            $title = __($data['title']);
            $content = __($data['remark']);
        }
        if (!$content)
            return '';
        $result = '<div class="panel-lead"><em>' . $title . '</em>' . $content . '</div>';
        if ($container) {
            $result = '<div class="panel-heading">' . $result . '</div>';
        }
        return $result;
    }
}
