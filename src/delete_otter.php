<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config_sbw.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리

// $page_num = 1; // 페이지 번호 초기화

try {
    // DB Connect
    $conn = my_db_conn(); // connection 함수 호출

    if(REQUEST_METHOD === "GET") {
    // 파라미터에서 page 획득
    // $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;
    
    // 게시글 수 조회
    $result_board_cnt = db_select_delete_boards_cnt($conn);
    

    $result = db_select_delete_boards_list($conn, $arr_param);

    } else if (REQUEST_METHOD === "POST") {
        // 파라미터 획득
          $board_no = isset($_POST["board_no"]) ? $_POST["board_no"] : "";
          $arr_err_param = [];
          if($no === ""){
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
          $result = db_restore_boards($conn, $arr_param);
  
          // 삭제 예외 처리
          if($result !== 1 ){
              throw new Exception("Delete Boards no count");
          }
  
          // commmit
          $conn->commit();
          header("Location: 1list.php");
          exit;
      }



} catch (\Throwable $err) {
    echo $err->getMessage();
    exit;
} finally {
    // PDO 파기
    if(!empty($conn)) {
        $conn = null;
    }
}
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/delete_otter.css">
</head>
<body>
    <div class="container">
        <div class="side">
            <div class="icon_1"></div>
            <div class="icon_2"></div>
        </div>
        <div class="folder">
            <div class="folder_1">
                <div class="folder_title">
                    <div class="folder_title_circle"></div>
                    <div class="folder_title_x"><a href="./main_otter.php" class="X_btn">X</a>
                    </div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn">◁</div>
                    <div class="folder_back_square"></div>
                </div>
            </div>
            <!-- TODO : 주의 메세지 위치 수정 필요 -->
            <!-- <form action="" method="">
                <ul class="ul_1">
                    <button type="submit">전체 삭제</button>
                    <li class="li_1"><strong style="color: red;">※주의!!</strong><br>삭제하면 영원히 복구할 수 없습니다.</li>
                </ul>
            </form> -->
            <div class="folder_main">
                <div class="item_icon">
                    
                    <?php
                    foreach($result as $item) {
                    ?>  

                    <div class="li_main_card">
                        <div class="item_icon_can">


                            <div class="li_card_item">
                                <!-- <div class="li_card_no">no</div> -->
                                <div class="li_card_no2"><?php echo $item["board_title"] ?> </div>
                            </div>
                            <div class="limited-text">
                                <!-- <div class="li_card_name">title</div> -->
                                <div class="item_card_name" ><?php echo $item["board_content"] ?></div>
                            </div>
                            <div class="li_card_item">
                                <!-- <div class="li_card_created_at">created</div> -->
                                <div class="li_card_created_at2"><?php echo $item["deleted_at"] ?></div>
                            </div>



                            <div class="form_btn">
                                <form action="" method="">
                                    <button type="submit">복구</button>
                                </form>
                                <form action="" method="">
                                    <ul>
                                        <button type="submit">삭제</button>
                                        <li><strong style="color: red;">※주의!!</strong><br>삭제하면 영원히 복구할 수 없습니다.</li>
                                    </ul>
                                </form>
                            </div>

                        </div>
                    </div>

                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <!-- <div class="folder_header">
        <div class="folder_header_otter4"></div>
        <div class="folder_header_otter4"></div>
        <div class="folder_header_otter4"></div>
        <div class="folder_header_otter4"></div>
    </div> -->
</body>
</html>