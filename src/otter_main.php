<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config_sbw.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리
    
try {
    
    $conn = my_db_conn(); // PDO 인스턴스 생성
    
    // 게시글 데이터 조회
    // 파라미터 획득
    $no = isset($_GET["board_no"]) ? $_GET["board_no"] : ""; // no 획득

    // 파라미터 예외 처리
    $arr_err_param = [];
    if($no === "") {
        $arr_err_param[] = "board_no";
    }
    if(count($arr_err_param) > 0) {
        throw new Exception("parameter Error : ".implode(", ", $arr_err_param));
    }

    // 게시글 정보 획득
    $arr_param = [
        ":board_no" => $no
    ];
    $result = db_select_boards_no($conn, $arr_param);
    if(count($result) !== 1) {
        throw new Exception("Select Boards board_no count");
    }


    // 아이템 세팅
    $item = $result[0];
}catch (\Throwable $e) {
    if(!empty($conn) && $conn->inTransaction()) {
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/otter_main.css">
</head>
<body>
    <div class="container">
        <div class="side">
            <div class="icon_1"><a href="./otter_delete_otter.php"></a></div>
            
            <?php $created_at = $item["created_at"]; // 예시 데이터베이스에서 날짜 정보를 가져온다고 가정
                    // 가져온 날짜 정보를 이용하여 연도와 월을 추출합니다.
                    $day = date('j', strtotime($created_at));
                    $mon = date('n', strtotime($created_at));
                    // BACK_BTN의 링크에 GET 파라미터를 추가합니다.
                    $back_btn_link = "./list_otter.php?year=$year&month=$mon&date=$day";
                    ?>

            <div class="icon_2"><a href="<?php echo $back_btn_link; ?>"></a></div>
        </div>
</body>
</html>