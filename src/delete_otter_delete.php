<?php
// 삭제 PHP



// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config_sbw.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    // DB Connect
    $conn = my_db_conn(); // connection 함수 호출

    if (REQUEST_METHOD === "POST") {
        // 파라미터 획득
        $board_no = isset($_POST["board_no"]) ? $_POST["board_no"] : "";
        
        $arr_err_param = [];
        if($board_no === ""){
           $arr_err_param[] = "board_no";
        }
        if(count($arr_err_param) > 0) {
            throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
        }

        //Transaction 시작
        $conn->beginTransaction();
  
        // 게시글 정보 삭제
        $arr_param = [
            "board_no" => $board_no
        ];
        $result = db_boards_delete($conn, $arr_param);
  
        // 삭제 예외 처리
        if($result !== 1 ){
          throw new Exception("삭제된 파일이 없습니다.");
        }
  
        // commmit
        $conn->commit();
        header("Location: delete_otter.php");
        exit;
      }
  
  } catch (\Throwable $err) {
      if(!empty($conn)) {
          $conn->rollBack();
      }
      echo $err->getMessage();
      exit;
  } finally {
      // PDO 파기
      if(!empty($conn)) {
          $conn = null;
      }
  }


?>