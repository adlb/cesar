<?php

/** PHPExcel root directory */
require_once dirname(__FILE__) . '/PHPExcel.php';

function ExportToExcel($fileName, $fileContent) {
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Malte-Liban")
                                 ->setLastModifiedBy("Malte-Liban");

    $objPHPExcel->setActiveSheetIndex(0);
    
    $row = 1;
    $title = true;
    
    foreach($fileContent as $line) {
        if ($title) {
            $col = 0;
            foreach(array_keys($line) as $key) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key);
                $col++;
            }
            $title = false;
            $row++;
        }
        $col = 0;
        foreach(array_values($line) as $value) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
            $col++;
        }
        $row++;
    }
    
    $objPHPExcel->getActiveSheet()->setTitle($fileName);

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
?>