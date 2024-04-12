<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
require_once(FILE_LIB_DB);

try {
    $conn = my_db_conn();
    $today = date('Y-m-d');
    // $today = isset($_GET["today"]) ? $_GET["today"] : date('Y-m-d');

    $arr_param =[
        "today" => $today
    ];
    $result = db_boards_select_created_at($conn, $arr_param);
    var_dump($result);
    // 쿼리 결과가 비어 있는지 확인하고, 비어 있지 않은 경우에만 변수를 설정합니다.
    if (!empty($result)) {
        // 쿼리 결과에서 날짜를 추출합니다.
        $created_at = $result[0]['created_at'];
        // 첫 번째 결과의 'created_at' 값을 사용합니다.
        
        // 추출한 날짜에서 연, 월, 일을 추출합니다.
        $year = date('Y', strtotime($created_at));
        $day = date('j', strtotime($created_at));
        $mon = date('n', strtotime($created_at));
        
        // 버튼 링크를 생성합니다.
        $back_btn_link = "./otter_list.php?year=$year&month=$mon&date=$day";
    } else {
        // 오늘 생성된 데이터가 없을 경우, 기본 링크를 생성합니다.
        $back_btn_link = "./otter_list.php"; // 기본 링크는 필요에 따라 변경할 수 있습니다.
    }
} catch (\Throwable $e) {
    echo $e->getMessage();
    exit;
} finally {
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
    <title>Document</title>
    <link rel="stylesheet" href="./css/otter_main.css">
</head>
<body>
<div class="container">
    <div class="side">
        <div class="icon_1"><a href="./otter_delete.php"></a></div>
        <!-- 수정된 버튼 링크를 사용합니다. -->
        <?php if(isset($back_btn_link)): ?>
            <div class="icon_2"><a href="<?php echo $back_btn_link; ?>"></a></div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>