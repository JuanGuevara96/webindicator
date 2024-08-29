<?php 

$filepath = 'CORPORATIVO/Div-COR-21.xlsm';

    if(file_exists($filepath)) {
        ob_end_clean(); // this is solution
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($filepath) . "\"");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($filepath);
        die();
    } else {
        http_response_code(404);
        die();
    }
 ?>