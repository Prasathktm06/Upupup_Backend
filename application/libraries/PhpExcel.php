<?php 
/*if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/libraries/Excel.php"; 
 

class ExcelPhp {

function parse_file($p_Filepath) 
{
$file = $p_Filepath;
//
//load the excel library

//read file from path
$objPHPExcel = PHPExcel_IOFactory::load($file);
//echo "<pre>";print_r($objPHPExcel);exit();
//get only the Cell Collection
$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

//extract to a PHP readable array format
foreach ($cell_collection as $cell) {
   $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
   $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
   $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
   //header will/should be in row 1 only. of course this can be modified to suit your need.

   if ($row == 1) {
       $header[$row][$column] = $data_value;
   } else {
       $arr_data[$row][$column] = $data_value;
   }

}

//send the data in an array format
$data['header'] = $header;
$data['values'] = $arr_data;
}

}*/
/////////////////////////NEW//////////////////
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH."/libraries/Excel.php"; 
 

class Excelphp {

function parse_file($p_Filepath) 
{
$file = $p_Filepath;
//
//load the excel library

//read file from path
$objPHPExcel = PHPExcel_IOFactory::load($file);
//echo "<pre>";print_r($objPHPExcel);exit();
//get only the Cell Collection
$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
$maxCell = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
$datas = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
//$cell_collection->setIterateOnlyExistingCells(false);
//echo "<pre>";print_r($datas);exit;
//extract to a PHP readable array format
/*foreach ($cell_collection as $cell) {
   $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
   $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
   $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
   //header will/should be in row 1 only. of course this can be modified to suit your need.
  //echo "<pre>";print_r($column);exit;
   if ($row == 1) {
       $header[$row][$column] = $data_value;
   } else {
       $arr_data[$row][$column] = $data_value;
   }

}*/

//send the data in an array format
/*if ($row == 1) {
       $header[0][0] = $data_value;
   } else {
       $arr_data[$row][$column] = $data_value;
   }*/
$data['header'] = $datas[0];
//echo "<pre>";print_r($data);exit();
//$count=count($arr_data);
/*foreach ($arr_data[2] as $key => $value) {

	$data['values'][$count][$header[1][$key]] = $value;
}*/
//echo "<pre>";print_r($data);exit();
foreach ($datas as $key => $value) {
  if ($key!=0) {
    foreach ($data['header'] as $key2 => $value2) {
      $data['values'][$key][$value2] = $value[$key2];
    }
  }
    
}
//echo "<pre>";print_r($data);exit;
return $data;
}

}













 ?>











