<?php
// 전체 삭제 PHP



// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    // DB Connect
    $conn = my_db_conn(); // connection 함수 호출

    if (REQUEST_METHOD === "POST") {

        //Transaction 시작
        $conn->beginTransaction();
  
        // 게시글 정보 전체 삭제

        $result = db_boards_all_delete($conn);
        
  
        // 복구 예외 처리
        if($result === 0 ){
          throw new Exception("삭제할 파일이 없습니다.");
        }
  
        // commmit
        $conn->commit();
        header("Location: otter_delete.php");
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