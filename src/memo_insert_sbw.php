<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB); 
$page_num = 1;  

try{
  $conn = my_db_conn(); 

  $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;


  if(REQUEST_METHOD === "POST") {
    
    $board_no = isset($_POST["board_no"]) ? trim($_POST["board_no"]) : "";
    $content = isset($_POST["memo_content"]) ? trim($_POST["memo_content"]) : ""; 












    
    $arr_err_param = [];
    if($content === ""){
      $arr_err_param[] ="memo_content";
    }
    if(count($arr_err_param) > 0 ){
      throw new Exception("메모를 입력해주세요.");
    }
    
    $conn->beginTransaction();
    
    $arr_param = [
      "memo_content" => $content
    ];
    $result_memo = db_memo_insert($conn, $arr_param);

    if($result_memo !== 1) {
      throw new Exception("Insert memos count");
    }
    
    $conn->commit();

    
    // header("Location: detail_otter.php?year=".$yser."&month=".$month."&board_no=".$board_no."&page=".$page);
    header("Location: detail_otter.php?year=2024&month=6&board_no=".$board_no."&page=1");
    exit;
  } 
}catch (\Throwable $e) {
  if(!empty($conn)){
    $conn->rollBack();
  }
  echo $e->getMessage();
  exit;

} finally {
  if(!empty($conn)) {
    $conn = null;
  }
}




?>