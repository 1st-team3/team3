<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB); 

if(REQUEST_METHOD === "POST") {
  try{
    
    $content = isset($_POST["memo_content"]) ? trim($_POST["memo_content"]) : ""; 
    
    $arr_err_param = [];
    if($content === ""){
      $arr_err_param[] ="memo_content";
    }
    if(count($arr_err_param) > 0 ){

      throw new Exception("errrrrrr");
    }

   
    $conn = my_db_conn(); 

    
    $conn->beginTransaction();
    
    
    $arr_param = [
      "memo_content" => $content
    ];
    $result_memo = db_memo_insert($conn, $arr_param);

    
    if($result_memo !== 1) {
      throw new Exception("Insert memos count");
    }
    
    // 이전페이지
    if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
      $previous_page = $_SERVER['HTTP_REFERER'];
    }

    $conn->commit();

    
    header("Location: " . $previous_page);
   

  } catch (\Throwable $e) {
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
}




?>