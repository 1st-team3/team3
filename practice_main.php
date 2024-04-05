<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/practice_config.php"); // 설정 파일 호출
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
    $max_cnt_num = 5; // 한 화면에 표시하고 싶은 페이지
    
    $min_page_cnt = ceil($page_num / $max_cnt_num ) * $max_cnt_num - ($max_cnt_num - 1); // 페이지 시작 숫자
    $max_page_cnt = $min_page_cnt + ($max_cnt_num - 1); // 페이지 마지막 숫자
    $max_page_cnt = $max_page_cnt > $max_page_num ? $max_page_num :$max_page_cnt;

    $prev_page_num = ($page_num - 1 ) < 1 ? 1 : $page_num - 5; // 이전 버튼 페이지 번호 (5페이지씩 이동)
    $prev_page_num = 1 < $prev_page_num ? $prev_page_num : 1; // 처음 페이지를 넘어갈 경우 이전페이지에 처음 페이지 번호를 넣음
    $next_page_num = ($page_num + 1 ) > $max_page_num ? $max_page_num : $page_num + 5; // 다음 버튼 페이지 번호 (5페이지씩 이동)
    $next_page_num = $max_page_num < $next_page_num ? $max_page_num : $next_page_num; // 마지막 페이지를 넘어갈 경우 다음페이지에 마지막페이지 번호를 넣음
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
    <link rel="stylesheet" href="../practice_css/practice_common.css">
</head>
<body>
    <main>
        <h1>Bread Recipe</h1>
        <div class="main-top">
            <a href="./practice_insert.php" class="a-insert">글 작성</a>
        </div>
        <div class="main-middle">
            <div class="item list-head">
                <div class="board-no">게시글 번호</div>
                <div class="board-title">게시글 제목</div>
            </div>
            <?php 
            foreach($result as $item) {
            ?>
                <div class="item">
                    <div class="item-no"><?php echo $item["no"] ?></div>
                    <div class="item-title"><a href="./practice_detail.php?no=<?php echo $item["no"] ?>&page=<?php echo $page_num ?>"><?php echo $item["title"] ?></a></div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="main-bottom">
            <a href="./practice_main.php?page=<?php echo $prev_page_num ?>" class="a-button small-button">이전</a>
            <?php
            for($num = $min_page_cnt; $num <= $max_page_cnt; $num++){
            ?>
            <a href="./practice_main.php?page=<?php echo $num?>" class="a-button small-button"><?php echo $num?></a>
            <?php 
            }
            ?>
            <a href="./practice_main.php?page=<?php echo $next_page_num ?>" class="a-button small-button">다음</a>
        </div> 
    </main>
</body>
</html>


<!-- $max_cnt_num = 5; // 한 화면에 표시하고 싶은 페이지

    $num_cnt = ($max_page_num / $max_cnt_num); // 페이지 그룹
    $min_page_cnt = (($num_cnt - 1) * $max_cnt_num + 1); // 페이지 시작 숫자
    $max_page_cnt = ($num_cnt * $max_cnt_num); // 페이지 마지막 숫자 -->