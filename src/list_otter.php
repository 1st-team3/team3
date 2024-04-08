<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); 
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 8; //한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화

try{
    $conn = my_db_conn();
    // Query 실행
     // 파라미터에서 page 획득
      


        $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;
        

              
     // 게시글 수 조회
     $result_board_cnt = db_select_todo_cnt($conn);

     $max_page_num = ceil($result_board_cnt / $list_cnt);   // 최대 페이지 수
     $offset = $list_cnt * ($page_num - 1); // OFFSET
     $prev_page_num= ($page_num -1) < 1 ? 1 : ($page_num - 1) ; // 이전 버튼 페이지 번호
     $next_page_num= ($page_num + 1) > $max_page_num  ? $max_page_num : ($page_num + 1); // 다음 버튼 페이지 번호
    // 게시글 리스트 조회
    $arr_param = [
        "list_cnt" => $list_cnt
        ,"offset" => $offset
    ];
    $result = db_select_todo_paging($conn, $arr_param);
    // var_dump($result);
    // exit;
    
}   catch(\Throwable $e){
    echo $e ->getMessage();
    exit;
}   finally {
    // PDO 파기
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
    <link rel="stylesheet" href="../list_otter.css\">
</head>
<body>
    <div class="container">
        <div class="side">
            <div class="icon">a</div>
            <div class="icon">a</div>
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
                <div class="calendar">
                    <div class="calendar-header"></div>
                    <div class="calendar-body">
                        <?php if ($month == 1){ ?>
                            <a class="calendar-day" href="./test.php?year=<?php echo $year-1 ?>&month=12"><img src="./img/left.png" alt=""></a>
                        <?php }else{ ?>
                            <a class="calendar-day" href="./test.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="./img/left.png" alt=""></a>
                        <?php }; ?>
                        <div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>
                        <?php if ($month == 12){ ?>
                            <a class="calendar-day" href="./test.php?year=<?php echo $year+1 ?>&month=1"><img src="./img/right.png" alt=""></a>
                        <?php }else{ ?>
                            <a class="calendar-day" href="./test.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="./img/right.png" alt=""></a>
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
                            <?php for ($n = 1, $i = 0; $i < $total_week; $i++){ ?> 
                                <tr> 
                                    <?php for ($k = 0; $k < 7; $k++){ ?> 
                                        <td> 
                                            <?php if ( ($n > 1 || $k >= $start_week) && ($total_day >= $n) ){ ?>
                                                <a href="" class="day-button"><?php echo $n++ ?></a>
                                            <?php }?>
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
                        <div class="memo-textarea"></div>
                        <div class="text-button">
                            <form action="">
                                <input type="text" name="memo-text" class="memo-text">
                                <button class="sudal-button" type="submit" name="memo-text"><img class="sudal-head" src="../image/otter_face_end.png"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="insert-list">
                    <form action="./insert_otter.php">
                        <div class="header">
                            <div class="todaylist">오늘 할 일</div>
                            <button type="submit">작성하기</button>
                        </div>
                    </form>
                    <?php foreach ($result as $item){ ?>  
                        <div class="list" <?php echo $item["no"];?>>
                            <form action="./list_otter.php" method="get">
                                <input class="input_list" type="checkbox" id="input_list"  value="<?php echo $item["no"]; ?>">
                                <label class="input_label" for="input_list"></label>
                                <input class="text_box" type="text" id="text_box" value ="<?php echo $item["title"]; ?>">
                                <label for="text_box"></label>
                            </form>
                        </div>
                    <?php } ?>
                    <div class="main-bottom">
                    <a href="./list_otter.php?page=<?php echo $prev_page_num ?>" class ="number_button">이전</a>
                    <?php
                    for($num = 1; $num <= $max_page_num; $num++){
                        ?>
                    <a href="./list_otter.php?page=<?php echo $num ?>" class ="number_button"></a>
                    <?php
                    }
                    ?>
                    <a href="./list_otter.php?page=<?php echo $next_page_num?>" class ="number_button">다음</a>
                </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>