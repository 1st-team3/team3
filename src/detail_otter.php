<?php 
    require_once($_SERVER["DOCUMENT_ROOT"]."/config3.php"); 
    require_once(FILE_LIB_DB); 
    
    

    try {
        // DB connect
        $conn = my_db_conn(); // PDO 인스턴스 생성
    
        // 게시글 데이터 조회
        // 파라미터 획득
        $no = isset($_GET["board_no"]) ? $_GET["board_no"] : ""; // no 획득
        $page = isset($_GET["page"]) ? $_GET["page"] : ""; // page 획득
    
        // 파라미터 예외 처리
        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "board_no";
        }
        if($page === "") {
            $arr_err_param[] = "page";
        }
        
        if(count($arr_err_param) > 0) {
            throw new Exception("parameter Error : ".implode(", ", $arr_err_param));
        }
    
        // 게시글 정보 획득
        $arr_param = [
            "board_no" => $no
        ];
        $result = db_select_boards_no($conn, $arr_param);
        if(count($result) !== 1) {
            throw new Exception("Select boards board_no count");
        }
    
        // 아이템 세팅
        $item = $result[0];
    
    } catch (\Throwable $e) {
        echo $e->getMessage();
        exit;
    } finally {
        // PDO 파기
        if(!empty($conn)) {
            $conn = null;
        }
    }
?>

<?php

try {
    // DB connect
    $conn = my_db_conn(); // PDO 인스턴스 생성

    // 게시글 데이터 삭제
    $no = isset($_POST["board_no"]) ? $_POST["board_no"] : ""; // no 획득
    
    // 삭제할 레코드의 board_no 값이 존재하는지 확인
    if($no !== "") {
        // 데이터베이스에서 해당 레코드 삭제
        $arr_param = [
            "board_no" => $no
        ];
        $result = db_delete_board($conn, $arr_param);

        if($result) {
            // 삭제 성공
            echo "게시글이 성공적으로 삭제되었습니다.";
            // 삭제 후 다시 해당 페이지로 리다이렉트할 수 있음
            // header("Location: 현재페이지URL");
            exit;
        } else {
            // 삭제 실패
            echo "게시글 삭제에 실패했습니다.";
        }
    } else {
        echo "삭제할 게시글 번호를 찾을 수 없습니다.";
    }
} catch (\Throwable $e) {
    echo $e->getMessage();
} finally {
    // PDO 파기
    if(!empty($conn)) {
        $conn = null;
    }
}

?>


<?php
	// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
	// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
	$month = isset($_GET['month']) ? $_GET['month'] : date('m');

	$date = "$year-$month-01"; // 현재 날짜의 1일
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$total_day = date('t', $time); // 2. 현재 달의 총 날짜
	$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차 (현재 요일부터 요일수를 구한뒤 7로 나눔 ($start_week = 일 = 0 월 = 1 ... 토 = 6))

    // 현재 날짜 표시하기
    $current_timestamp = time();
    $current_year = date("Y", $current_timestamp); // 현재 연도
    $current_month = date("n", $current_timestamp); // 현재 월
    $current_day = date("d", $current_timestamp); // 현재 일
    $is_current_month = ($year == $current_year && $month == $current_month); // 현재 날짜가 속한 년도와 월을 확인하여 현재 달인지를 판단합니다.
	
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail page</title>
    <link rel="stylesheet" href="./css/detail_otter.css">
</head>
<body>
    <div class="container">
        <div class="side">
            <div class="gbicon"></div>
            <div class="icon"></div>
        </div>
        <div class="folder">
            <div class="folder_1">
                <div class="folder_title">
                    <div class="folder_title_circle"></div>
                    <div class="folder_title_x"><a href="./main_otter.html" class="X_btn">X</a>
                    </div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn"><a href="" class="back_btn">◁</a></div>
                    <div class="folder_back_square"></div>
                </div>
            </div>
            <button class="sudalbutton" type="submit"><img class="sudal-head" src="../image/otter_face_end.png"></button>
            <div class="folder_main">
            <div class="calendar">
                    <div class="calendar-header"></div>
                    <div class="calendar-body">
                        <!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
                        <?php if ($month == 1){ ?>
                            <!-- 작년 12월 -->
                            <a class="calendar-day" href="./detail_otter.php?year=<?php echo $year-1 ?>&month=12"><img src="./image/left.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 이전 월 -->
                            <a class="calendar-day" href="./detail_otter.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="./image/left.png" alt=""></a>
                        <?php }; ?>
                        <div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>
                        <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                        <?php if ($month == 12){ ?>
                            <!-- 내년 1월 -->
                            <a class="calendar-day" href="./detail_otter.php?year=<?php echo $year+1 ?>&month=1"><img src="./image/right.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 다음 월 -->
                            <a class="calendar-day" href="./detail_otter.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="./image/right.png" alt=""></a>
                        <?php }; ?>


                        <table>
                            <tr>
                                <th>일</th> 
                                <th>월</th> 
                                <th>화</th> 
                                <th>수</th> 
                                <th>목</th> 
                                <th>금</th> 
                                <th>토</th>
                            </tr> 

                            <!-- 총 주차를 반복합니다. -->
                            <?php for ($n = 1, $i = 0; $i < $total_week; $i++){ ?> 
                                <tr> 
                                    <!-- 1일부터 7일 (한 주) -->
                                    <?php for ($k = 0; $k < 7; $k++){ ?> 
                                        <td> 
                                            <!-- 시작 요일부터 마지막 날짜까지만 날짜를 보여주도록 -->
                                            <?php if ( ($n > 1 || $k >= $start_week) && ($total_day >= $n) ){
                                                if ($is_current_month && $n == $current_day) {
                                                    // 현재 날짜에 해당하는 경우
                                                    echo '<div class="today">' . $n++ . '</div>';
                                                } else {
                                                    // 다른 날짜에 해당하는 경우
                                                    echo '<div class="day-button">' . $n++ . '</div>';
                                                }
                                                
                                             }?>
                                        </td> 
                                    <?php }; ?> 
                                </tr> 
                            <?php }; ?> 
                        </table>
                    </div>
                </div>
                <div class="memo">
                    <h2>MEMO</h2>
                    <div class="memo-board">
                        <div class="memo-textarea">
                        </div>
                        <div class="text-button">
                            <form action="">
                                <input type="text" name="memo-text" class="memo-text" placeholder="일정 추가하기">
                                <button class="sudal-button" type="submit" name="memo-text"><img class="sudal-head" src="../image/otter_face_end.png"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="insert-list">
                    <div class="insert-main">
                        <div class="insert-header">
                        <div class="line-content"><?php echo $item["board_no"] ?></div>
                        </div>
                        <div class="insert-middle">

                        </div>
                        <div class="insert-text">
                        <div class="line-content"><?php echo $item["board_content"] ?></div>
                        </div>
                    </div>
                    <div class="insert-footer">
                        
                        <a href="./detail_otter.php?page=<?php echo $prev_page_num ?>" class="prevbtn">◁</a>
                        
                        <a href="./update_otter.php?no=<?php echo $no ?>&page=<?php echo $page ?>" class="updatebtn">수정</a>

                        <form action="./delete_otter.php" method="post">
                        <input type="hidden" name="board_no" value="<?php echo $no ?>">
                        <input type="hidden" name="board_content" value="<?php echo $page ?>">
                        <button type="submit" class="deletebtn" name="deletebtn">삭제</button>
                        </form>

                        <a href="./detail_otter.php?page=<?php echo $next_page_num ?>" class="nextbtn">▷</a>
                    </div>
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