<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 5; // 한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화

try {
    // DB Connect
    $conn = my_db_conn();

    // 파라미터에서 page 획득
    // 값이 있으면 $_GET["page"]에 담긴 값을 없으면 기존 $page_num의 값인 1을 대입
    $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;

    // 게시글 수 조회
    $result_board_cnt = db_select_boards_cnt($conn);

    // 페이지 관련 설정 셋팅
    $max_page_num = ceil( $result_board_cnt / $list_cnt); // 최대 페이지 수
    $offset = $list_cnt * ($page_num -1); // OFFSET
    $prev_page_num = ($page_num -1 ) < 1 ? 1 : $page_num - 1; // 이전 버튼 페이지 번호
    $next_page_num = ($page_num +1 ) > $max_page_num ? $max_page_num : ($page_num + 1); // 다음 버튼 페이지 번호

    // 게시글 리스트 조회
    $arr_param = [
        "list_cnt" => $list_cnt,
        "offset" => $offset
    ];

    $result = db_select_boards_paging($conn, $arr_param);

}

catch (\Throwable $e) {
    echo $e->getMessage();
    exit;
}

finally {
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
    <link rel="stylesheet" href="./css/common.css">
</head>
<body>
    <!-- 헤더 호출 -->
    <?php require_once(FILE_HEADER); ?>
    <main>
        <div class="main-top">
            <a href="./insert.php" class="a-button">글 작성</a>
        </div>
        <div class="main-middle">
            <div class="item list-head">
                <div class="board-no">게시글 번호</div>
                <div class="board-title">게시글 제목</div>
                <div class="board-createdat">작성일자</div>
            </div>
            <?php 
            foreach($result as $item) {
            ?>
                <div class="item">
                    <div class="item-no"><?php echo $item["no"] ?></div>
                    <div class="item-title"><a href="./detail.php?no=<?php echo $item["no"] ?>&page=<?php echo $page_num ?>"><?php echo $item["title"] ?></a></div>
                    <div class="item-createdat"><?php echo $item["created_at"] ?></div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="main-bottom">
            <a href="./list.php?page=<?php echo $prev_page_num ?>" class="a-button small-button">이전</a>
            <?php
            for($num = 1; $num <= $max_page_num; $num++){

            ?>
            <a href="./list.php?page=<?php echo $num ?>" class="a-button small-button"><?php echo $num?></a>
            <?php 
            }
            ?>
            <a href="./list.php?page=<?php echo $next_page_num ?>" class="a-button small-button">다음</a>
        </div>       
    </main>
</body>
</html>