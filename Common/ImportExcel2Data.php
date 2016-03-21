<?php

//require 'PHPExcel.php';
require 'PHPExcel/IOFactory.php';
require 'PHPExcel/Reader/Excel5.php';
require 'PHPExcel/Reader/Excel2007.php';

class ImportExcel2Data {

    public static function excel2array($filename, $file_type = 'xls') {
//        if (str_equals($file_type, 'xls')) {
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
//        } elseif (str_equals($file_type, 'xlsx')) {
//            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
//        }
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }

}
