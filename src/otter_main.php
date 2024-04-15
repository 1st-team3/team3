<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
require_once(FILE_LIB_DB);

try {
    $conn = my_db_conn();

    // 파라미터 획득
    $today = date("Y-m-d");
    // $today = isset($_GET["today"]) ? $_GET["today"] : date("Y-m-d");


    $arr_param =[
        "today" => $today
    ];
    $result = db_boards_select_created_at($conn, $arr_param);

    if (!empty($result)) { // 오늘 생성된 데이터가 있는경우
        $created_at = $result[0]["created_at"];

        // 추출한 날짜에서 연, 월, 일을 추출합니다.
        $year = date("Y", strtotime($created_at));
        $month = date("m", strtotime($created_at));
        $day = date("d", strtotime($created_at));
        
        $list_btn_today = "./otter_list.php?year=".$year."&month=".$month."&date=".$day;

    } else { // 오늘 생성된 데이터가 없을 경우
        $list_btn_today = "./otter_list.php";
    }
} catch (\Throwable $err) {
    echo $err->getMessage();
    exit;
} finally {
    // PDO 파기
    if (!empty($conn)) {
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTTER OS</title>
    <link rel="stylesheet" href="./css/otter_main.css">
    <link rel="icon" href="./image/otter_face_end.png">
</head>
<body>
<div class="container">
    <div class="side">
        <div class="icon_1"><a href="./otter_delete.php"></a></div>
        <?php // if(isset($list_btn_today)): ?>
            <div class="icon_2"><a href="<?php echo $list_btn_today; ?>"></a></div>
        <?php // endif; ?>
    </div>
</div>
</body>
</html>