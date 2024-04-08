<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB); 

try{
    $conn = my_db_conn();
   
     $board_no = isset($_POST["board_no"]) ? $_POST["board_no"] : "";
     $page = isset($_POST["page"]) ? $_POST["page"] : ""; 
    
     $arr_err_param = [];

     if($board_no === ""){
         $arr_err_param[] = "board_no";
     }
     if($page === "") {
     $arr_err_param[] = "page";
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

    header("Location: list_otter.php?&page=".$page."#list".$board_no);
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