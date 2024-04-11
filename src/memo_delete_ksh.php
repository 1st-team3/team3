<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config3.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    // DB Connect
    $conn = my_db_conn(); // PDO 인스터스 생성

    // Method
    if(REQUEST_METHOD === "GET") {
    // 게시글 데이터 조회
    // 파라미터 획득
  $no = isset($_GET["memo_no"]) ? $_GET["memo_no"] : ""; // no 획득
  $board_no = isset($_GET["board_no"]) ? $_GET["board_no"] : ""; // board_no 획득
  $page = isset($_GET["page"]) ? $_GET["page"] : ""; // page 획득

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

// 게시글 정보 획득
$arr_param_board = [
    "board_no" => $board_no
];
$result_board = db_select_boards_no($conn, $arr_param_board);
if(count($result_board) !== 1) {
    throw new Exception("Select Boards board_no count");
}
  
  // 아이템 셋팅
  $item = $result_memo[0];
  $board_item = $result_board[0];
    }
 

    else if(REQUEST_METHOD === "POST") {
        
    // 파라미터 획득
    $no = isset($_POST["memo_no"]) ? $_POST["memo_no"] : "";
    $board_no = isset($_POST["board_no"]) ? $_POST["board_no"] : "";
    $page = isset($_POST["page"]) ? $_POST["page"] : "";

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

    // 삭제 예외 처리
    if($result_memo !== 1) {
        throw new Exception("Delete memos no count");
    }

    // 이전페이지
    if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
        $previous_page = $_SERVER['HTTP_REFERER'];
    }

    // commit
    $conn->commit();
    
    header("Location: " . $previous_page);

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