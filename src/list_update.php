<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB); 

try{
    $conn = my_db_conn();
   
     $board_no = isset($_POST["board_no"]) ? $_POST["board_no"] : "";
     $page = isset($_POST["page"]) ? $_POST["page"] : ""; 
     $year = isset($_POST["year"]) ? $_POST["year"] : ""; 
     $month = isset($_POST["month"]) ? $_POST["month"] : ""; 
     $date = isset($_POST["date"]) ? $_POST["date"] : ""; 


    $arr_err_param = [];

    if($board_no === "") {
         $arr_err_param[] = "board_no";
    }
    if($page === "") {
        $arr_err_param[] = "page";
    }
    if($year === "") {
        $arr_err_param[] = "year";
    }
    if($month === "") {
        $arr_err_param[] = "month";
    }
    if($date === "") {
        $arr_err_param[] = "date";
    }
    if(count($arr_err_param) > 0) {
        throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
    }

    
    $arr_param = [
        "board_no" => $board_no
    ];
    $conn->beginTransaction();
    $result = db_list_update_no($conn, $arr_param);    
    $conn->commit();

    header("Location: otter_list.php?year=".$year."&month=".$month."&date=".$date);
} catch(\Throwable $e){
    if(!empty($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo $e ->getMessage();
    exit;
} finally {

    if(!empty($conn)){
        $conn = null;
    }
}
?>