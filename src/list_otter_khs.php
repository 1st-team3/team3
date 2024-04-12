<?php
    require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); 
    require_once(FILE_LIB_DB); 
    $list_cnt = 8; 
    $page_num = 1; 

    try{
        $conn = my_db_conn();

        if(REQUEST_METHOD === "GET") {
            $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;

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
            $now_year = date("Y"); // 현재 연도
            $now_month = date("n"); // 현재 월
            $now_day = date("d"); // 현재 일
            $is_now_month = ($year == $now_year && $month == $now_month); // 현재 년도와 달이 맞는지 확인

            $date = isset($_GET["date"]) ? $_GET["date"] : $date;
            $month = str_pad($month, 2, '0', STR_PAD_LEFT); // 한 자리 숫자를 왼쪽에 0을 추가하여 두 자리 숫자로 만듦
            $date = str_pad($date, 2, '0', STR_PAD_LEFT); // 한 자리 숫자를 왼쪽에 0을 추가하여 두 자리 숫자로 만듦
            $days = "$year-$month-$date";
            $start_date = "";
            $end_date = "";

            if(isset($_GET["date"])) {
                $start_date = $year.$month.$date."000000";
                $end_date = $year.$month.$date."235959";
            }

            $arr_param = [
                "start" => $start_date,
                "end" => $end_date
            ];
            
            
            $result_board_cnt = db_select_boards_cnt($conn, $arr_param);
            
            $max_page_num = ceil($result_board_cnt / $list_cnt);   
            $offset = $list_cnt * ($page_num - 1); 
            $prev_page_num = ($page_num -1) < 1 ? 1 : ($page_num - 1) ; 
            $next_page_num = ($page_num + 1) > $max_page_num  ? $max_page_num : ($page_num + 1); // 다음 버튼 페이지 번호
        
            $arr_param = [
                "start" => $start_date
                ,"end" => $end_date
                ,"list_cnt" => $list_cnt
                ,"offset" => $offset
            ];  
                
            $result = db_select_boards_title($conn, $arr_param);

    }

    if (REQUEST_METHOD === "POST") {

    // GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
    $post_year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    // GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
    $post_month = isset($_POST['month']) ? $_POST['month'] : date('m');
    $post_date = isset($_POST['date']) ? $_POST['date'] : date('n');

    $board_no = isset($_POST["board_no"]) ? $_POST["board_no"] : "";
    $page = isset($_POST["page"]) ? $_POST["page"] : ""; 

   
    $arr_err_param = [];

    if($board_no === ""){
        $arr_err_param[] = "board_no";
    }
    if($page === "") {
    $arr_err_param[] = "page";
    }
    if(count($arr_err_param) > 0) {
    throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
    }

   
   $arr_param = [
       "board_no" => $board_no
   ];
   $conn->beginTransaction();

   $result = db_list_update_no($conn, $arr_param);    
   
   $conn->commit();

   header("Location: list_otter_khs.php?year=" . $post_year . "&month=" . $post_month . "&date=" . $post_date . "&page=" . $page . "#list" . $board_no);

    }
}   catch(\Throwable $e){
    echo $e ->getMessage();
    exit;
}   finally {
    if(!empty($conn)){
        $conn = null;
    }
}

?>
    <!DOCTYPE html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>문서</title>
        <link rel="stylesheet" href="./css/otter_list.css">
    </head>
    <body>
        <div class="container">
            <div class="side">
                <img class="icon-delete" src="./image/delete_otter.png" alt="">
                <br>
                <img class="icon" src="./image/209_2-1.png" alt="">
            </div>
            <div class="folder">
                <div class="folder_1">
                    <div class="folder_title">
                        <div class="folder_title_circle"></div>
                        <div class="folder_title_x"><a href="./main_otter.html" class="X_btn">X</a></div>
                    </div>
                    <div class="folder_back">
                        <div class="folder_back_btn"><a href="" class="back_btn">◁</a></div>
                        <div class="folder_back_square"></div>
                    </div>
                </div>
                <div class="folder_main">
                    <form action="./list_otter_khs.php/" method="GET">
                        <div class="calendar">
                            <div class="calendar-header"></div>
                            <div class="calendar-body">
                                <!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
                                <?php if ($month == 1){ ?>
                                    <!-- 작년 12월 -->
                                    <a class="calendar-day" href="./list_otter_khs.php?year=<?php echo $year-1 ?>&month=12"><img src="./image/left.png" alt=""></a>
                                <?php }else{ ?>
                                    <!-- 이번 년 이전 월 -->
                                    <a class="calendar-day" href="./list_otter_khs.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="./image/left.png" alt=""></a>
                                <?php }; ?>
                                <div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>
                                <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                                <?php if ($month == 12){ ?>
                                    <!-- 내년 1월 -->
                                    <a class="calendar-day" href="./list_otter_khs.php?year=<?php echo $year+1 ?>&month=1"><img src="./image/right.png" alt=""></a>
                                <?php }else{ ?>
                                    <!-- 이번 년 다음 월 -->
                                    <a class="calendar-day" href="./list_otter_khs.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="./image/right.png" alt=""></a>
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
                                                        $button_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $n)); // 날짜를 YYYY-MM-DD 형식으로 변환
                                                        if ($is_now_month && $n == $now_day) { // GET으로 가져온 달이 다르면 (출력된 년도나 달이 다르면) false가 돼서 else로 넘어감
                                                            // 현재 날짜에 해당하는 경우
                                                            echo '<a href="./list_otter_khs.php?year=' . $year . '&month=' . $month . '&date=' . $n . '" class="today">' . $n++ . '</a>';
                                                        } else {
                                                            // 다른 날짜에 해당하는 경우
                                                            echo '<a href="./list_otter_khs.php?year=' . $year . '&month=' . $month . '&date=' . $n . '" class="day-button' . (($date == $n) ? ' selected' : '') . '">' . $n++ . '</a>';

                                                        }
                                                        echo '<input type="hidden" name="year" value="' . $year . '">';
                                                        echo '<input type="hidden" name="month" value="' . $month . '">';
                                                        echo '<input type="hidden" name="date" value="' . $n . '">';
                                                    }?>
                                                </td>
                                            <?php }; ?>
                                        </tr>
                                    <?php }; ?>
                                </table>
                            </div>
                        </div>
                    </form>
                    
                    <?php require_once(ROOT."/memo_list.php"); ?>

                    <div class="insert-list">
                            <div class="header">
                                <div class="todaylist">오늘 할 일</div>
                                <!-- <button type="submit">작성하기</button> -->
                                <a href="./insert_otter.php">작성하기</a>
                            </div>

                        <?php foreach ($result as $item){ ?>  
                            <div class="list" id="list<?php echo $item["board_no"]; ?>">
                                <form action="./list_otter_khs.php" method="post">
                                    <input class="input_list" type="hidden" name="board_no" value="<?php  echo $item["board_no"]; ?>">
                                    <input type="hidden" name="page" value=<?php echo $page_num; ?>>
                                    <button type="submit" class="btn-update" id="input_listt<?php echo $item["board_no"];?>"></button>
                                    <label class="input_label" for="input_listt<?php echo $item["board_no"]; ?>"><?php echo $item["board_chkbox"] === 1 ? "<span>✔</span>": "" ?></label>
                                    <input class="text_box <?php echo $item["board_chkbox"] === 1 ? "strikethrough" : "" ?>" type="text" id="text_box_<?php echo $item["board_no"]; ?>" value="<?php echo $item["board_title"]; ?>" required> 
                                    <?php 
                                        echo '<input type="hidden" name="year" value="' . $year . '">';
                                        echo '<input type="hidden" name="month" value="' . $month . '">';
                                        echo '<input type="hidden" name="date" value="' . $date . '">'; 
                                    ?>
                                </form>
                            </div>
                        <?php } ?>
                        <div class="main-bottom">
                        <a href="./list_otter_khs.php?year=<?php echo $year ?>&month=<?php echo $month ?>&date=<?php echo $date ?>&page=<?php echo $prev_page_num ?>" class ="number_button">이전</a>
                        <?php
                        for($num = 1; $num <= $max_page_num; $num++){
                            ?>
                        <a href="./list_otter_khs.php?year=<?php echo $year ?>&month=<?php echo $month ?>&date=<?php echo $date ?>&page=<?php echo $num ?>" class ="number_button"><?php echo $num ?></a>
                        <?php
                        }
                        ?>
                        <a href="./list_otter_khs.php?year=<?php echo $year ?>&month=<?php echo $month ?>&date=<?php echo $date ?>&page=<?php echo $next_page_num?>" class ="number_button">다음</a> 
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>