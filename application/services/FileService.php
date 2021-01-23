<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 * Date: 2019/4/12
 * Time: 15:42
 */

namespace app\services;


use app\exception\CommonException;
use think\Db;
use think\facade\Env;

class FileService
{
    private static $instance;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }

    public function upload($file = '')
    {
        if(empty($file))
            $file = request()->file('file');
        if($file)
        {
            if(is_array($file)){
                $data = [];
                foreach ($file as $item) {
                    // 移动到框架应用根目录/uploads/ 目录下
                    $info = $item->move( Env::get('root_path').'/public/uploads');
                    if($info){
                        // 成功上传后 获取上传信息
                        // 输出 jpg
                        $file_info = $item->getInfo();
                        if ($file_info['error'] != 0)
                            throw new CommonException($file_info['error']);
                        $save_name = $info->getSaveName();
                        $save_name = str_replace('\\','/',$save_name);
                        $name = $file_info['name'];
                        $size = $file_info['size'];
                        list($width, $height, $type) = getimagesize(Env::get('root_path').'/public/uploads/'.$save_name);
                        $cell = [
                            'save_name' => $save_name,
                            'name' => $name,
                            'width'=>$width,
                            'height'=>$height,
                            'size'=>$size
                        ];
                        array_push($data,$cell);
                    }
                    return $data;
                }
            }else{
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->move( Env::get('root_path').'/public/uploads');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    $file_info = $file->getInfo();
                    if ($file_info['error'] != 0)
                        throw new CommonException($file_info['error']);
                    $save_name = $info->getSaveName();
                    $save_name = str_replace('\\','/',$save_name);
                    $name = $file_info['name'];
                    $size = $file_info['size'];
                    list($width, $height, $type) = getimagesize(Env::get('root_path').'/public/uploads/'.$save_name);
                    $data = [
                        'save_name' => $save_name,
                        'name' => $name,
                        'width'=>$width,
                        'height'=>$height,
                        'size'=>$size
                    ];
                    return $data;
                }else{
                    throw new CommonException($file->getError());
                }
            }

        }else
            throw new CommonException('文件不存在');
    }

    public function delPic($pic)
    {
        if(!empty($pic))
        {
            $file = Env::get('root_path').'/public/uploads/'.$pic;
            if(file_exists($file))
                unlink($file);
        }
    }

    public function excelImport($file)
    {
        // 判断文件是什么格式
        $info = $file->move(Env::get('root_path') . 'public' . DS . 'uploads/excel');
        $path = '/public/uploads/excel/'.$info->getSaveName();
        $type = pathinfo($path);
        $type = strtolower($type["extension"]);
//        $type=$type==='csv' ? $type : 'Excel5';
        // 判断使用哪种格式
//        $objReader = \PHPExcel_IOFactory::createReader($type);
        if ($type =='xlsx')
            $objReader = new \PHPExcel_Reader_Excel2007();
        else if ($type =='xls')
            $objReader = new \PHPExcel_Reader_Excel5();
        else if ($type=='csv') {
            $objReader = new \PHPExcel_Reader_CSV();
            //默认输入字符集
            $objReader->setInputEncoding('GBK');
            //默认的分隔符
            $objReader->setDelimiter(',');
        } else
            throw new CommonException('文件格式错误');
        $objExcel = $objReader->load('.'.$path);
        $sheet = $objExcel->getSheet(0);
        // 取得总行数
        $highestRow = $sheet->getHighestRow();
        // 取得总列数
        $highestColumn = $sheet->getHighestColumn();
        //循环读取excel文件,读取一条,插入一条
        //从第一行开始读取数据
        $data = [];
        for($j=2;$j<=$highestRow;$j++){
            //从A列读取数据
            $cell = [];
            for($k='A';$k<=$highestColumn;$k++){
//                print_r($k);
                array_push($cell,$objExcel->getActiveSheet()->getCell("$k$j")->getValue());
            }
            array_push($data,$cell);
        }
        return $data;
    }

    public function exportExcel($fileName, $data, $title, $fun){
        $fileName .= ".xls";
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();
        $objActSheet = $objPHPExcel->getActiveSheet();
        $this->wrapTitle($objActSheet,$title);// 写入标题
        $this->$fun($data,$objActSheet,$title);// 写入数据
        $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
        $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$fileName");
        header('Cache-Control: max-age=0');
        $objWriter=new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    private function wrapTitle($objActSheet,$title)
    {
        // 写入第一行标题
        $span = ord("A");
        $span2 = ord("@");
        foreach ($title as $v) { // 列写入
            if($span>ord("Z")){
                $span2 += 1;
                $span = ord("A");
                $colum = chr($span2).chr($span);
            }else{
                if($span2>=ord("A")){
                    $colum = chr($span2).chr($span);//超过26个字母时才会启用
                }else{
                    $colum = chr($span);
                }
            }
            $objActSheet->setCellValue($colum . 1, $v);
            $span++;
        }
    }

    /**
     * 订单数据写入表格
     * @param $data
     * @param $objActSheet
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function wrapOrderData($data,$objActSheet)
    {
        $column = 2;
        foreach ($data as $v) { // 行写入
            $objActSheet->setCellValue('A' . $column, $v['shop_name'].'/'.$v['shop_phone']);
            $objActSheet->setCellValue('B' . $column, $v['order_no']);
            $objActSheet->setCellValue('C' . $column, $v['name']);
            $objActSheet->setCellValue('D' . $column, $v['phone']);
            $objActSheet->setCellValue('E' . $column, $v['p_name'].$v['c_name'].$v['a_name'].$v['address_detail']);


            $objActSheet->setCellValue('K' . $column, round($v['total_money']/100,2));
            $objActSheet->setCellValue('L' . $column, round($v['express_money']/100,2));
            $objActSheet->setCellValue('M' . $column, round($v['discount_money']/100,2));
            $objActSheet->setCellValue('N' . $column, round($v['cost']/100,2));//cost
            $objActSheet->setCellValue('O' . $column, round($v['real_money']/100,2));
            $objActSheet->setCellValue('P' . $column, round($v['refund_money']/100,2));
            $objActSheet->setCellValue('Q' . $column, orderPayMethod($v['pay_method']));
            $objActSheet->setCellValue('R' . $column, orderStatusToName($v['status']));
            $objActSheet->setCellValue('S' . $column, orderAfterStatus($v['after_status']));
            $objActSheet->setCellValue('T' . $column, orderPayStatus($v['pay_status']));
            $objActSheet->setCellValue('U' . $column, $v['third_order_no']);
            $objActSheet->setCellValue('V' . $column, parseTime($v['create_at']));
            $objActSheet->setCellValue('W' . $column, parseTime($v['pay_at']));
            $objActSheet->setCellValue('X' . $column, parseTime($v['send_at']));
            $column += 1;
            $goods = Db::name('order_goods')->where('o_id',$v['o_id'])->field('art_no,sku_no,goods_name,goods_info,total_price,num')->select();
            foreach ($goods as $good) {
                $objActSheet->setCellValue('F' . $column, $good['art_no']);
                $objActSheet->setCellValue('G' . $column, $good['sku_no']);
                $objActSheet->setCellValue('H' . $column, $good['goods_name']);
                $objActSheet->setCellValue('I' . $column, $good['goods_info']);
                $objActSheet->setCellValue('J' . $column, $good['num']);
                $objActSheet->setCellValue('K' . $column,  round($good['total_price']/100,2));
                $column += 1;
            }
        }
    }

    /**
     * 结算数据写入表格
     * @param $data
     * @param $objActSheet
     */
    private function wrapSettlementData($data,$objActSheet)
    {
        $column = 2;
        foreach ($data as $v) { // 行写入
            $objActSheet->setCellValue('A' . $column, $v['shop_name']);
            $objActSheet->setCellValue('B' . $column, $v['name']);
            $objActSheet->setCellValue('C' . $column, $v['phone']);
            $objActSheet->setCellValue('D' . $column, $v['order_no']);
            $objActSheet->setCellValue('E' . $column, round($v['money']/100,2));
            $objActSheet->setCellValue('F' . $column, isset($v['create_at'])?parseTime($v['create_at']):'');
            $column++;
        }
    }

}