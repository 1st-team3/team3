<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    // DB Connect
    $conn = my_db_conn(); // PDO 인스터스 생성

    // Method
    if(REQUEST_METHOD === "GET") {
    // 게시글 데이터 조회
    // 파라미터 획득
  $no = isset($_GET["memo_no"]) ? $_GET["memo_no"] : ""; // no 획득


  $arr_err_param = [];
  if($no === ""){
      $arr_err_param[] = "memo_no";
  }
  if(count($arr_err_param) > 0) {
    throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
  }

  // 게시글 정보 획득
  $arr_param = [
      "memo_no" => $no
  ];
  $result_memo = db_select_memo_no($conn, $arr_param);
  if(count($result_memo) !== 1){
    throw new Exception("Select memo no count");
  }
  
  // 아이템 셋팅
  $item = $result_memo[0];
    }
 

    else if(REQUEST_METHOD === "POST") {
        
    // 파라미터 획득
    $no = isset($_POST["memo_no"]) ? $_POST["memo_no"] : "";

    $arr_err_param = [];
    if($no === ""){
    $arr_err_param[] = "memo_no";
}
    if(count($arr_err_param) > 0) {
        throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
    }
}
    // Transaction 시작
    $conn->beginTransaction();

    // 게시글 정보 삭제
    $arr_param = [
        "memo_no" => $no
    ];
    $result_memo = db_memo_delete($conn, $arr_param);

    $previous_page = $_SERVER['HTTP_REFERER'];

    // 삭제 예외 처리
    if($result_memo !== 1) {
        throw new Exception("Delete memos no count");
    }

    // commit
    $conn->commit();
    header("Location: $previous_page");

}   catch (\Throwable $e) {
    if(!empty($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo $e->getMessage();
    exit;
}   finally {
    // PDO파기
    if(!empty($conn)) {
        $conn = null;
    }
}


?>