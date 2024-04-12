<?php 
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); 
    require_once(FILE_LIB_DB); 
    
    try {
        
        $conn = my_db_conn(); // PDO 인스턴스 생성

        // GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        // GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');

        $previous_page = $_SERVER['HTTP_REFERER'];

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
            ":board_no" => $no
        ];
        $result = db_select_boards_no($conn, $arr_param);
        if(count($result) !== 1) {
            throw new Exception("Select Boards board_no count");
        }
    
        // 페이지 이전글 다음글
        $max_board_no = max_no_sql($conn);
        $min_board_no = min_no_sql($conn);
        $prev_btn_result = prev_btn($conn, $arr_param);
        $next_btn_result = next_btn($conn, $arr_param);
    
        // 아이템 세팅
        $item = $result[0];
    
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $no = isset($_POST["board_no"]) ? $_POST["board_no"] : "";
            $year = isset($_GET['year']) ? $_GET['year'] : date('Y');


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
        

            $arr_param = [
                ":board_no" => $no
            ];
            $result = db_select_boards_no($conn, $arr_param);
            if(count($result) !== 1) {
                throw new Exception("Select Boards board_no count");
            }

            $item = $result[0];



            $arr_err_param = [];
            if($no === "") {
                $arr_err_param[] = "board_no";
            }
            if(count($arr_err_param) > 0) {
                throw new Exception("parameter Error : ".implode(", ", $arr_err_param));
            }
    
            $conn->beginTransaction();
    
            $arr_param = [
                ":board_no" => $no
            ];
            $result = db_delete_boards_no($conn, $arr_param);
    
            if($result !== 1) {
                throw new Exception("Delete Boards board_no count");
            }
    
            $conn->commit();
    
            header("Location: $back_btn_link");
            exit;
        }
    } catch (\Throwable $e) {
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

    if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
        $previous_page = $_SERVER['HTTP_REFERER'];
    }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>디테일 페이지</title>
    <link rel="stylesheet" href="./css/otter_detail.css">
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
                    <div class="folder_title_x"><a href="./otter_main.php" class="X_btn">X</a>
                    </div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn"><a href="<?php echo $previous_page; ?>" class="back_btn">◁</a></div>
                    <div class="folder_back_square"></div>
                </div>
            </div>
            <div class="sudaldiv"><img class="sudal-head" src="../image/otter_face_end.png"></div>
            <div class="folder_main">
            <div class="calendar">
                    <div class="calendar-header"></div>
                    <div class="calendar-body">
                        <!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
                        <?php if ($month == 1){ ?>
                            <!-- 작년 12월 -->
                            <a class="calendar-day" href="./otter_detail.php?year=<?php echo $year-1 ?>&month=12&board_no=<?php echo $no ?>&page=<?php echo $page ?>"><img src="./image/left.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 이전 월 -->
                            <a class="calendar-day" href="./otter_detail.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>&board_no=<?php echo $no ?>&page=<?php echo $page ?>"><img src="./image/left.png" alt=""></a>
                            
                        <?php }; ?>
                        <div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>
                        <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                        <?php if ($month == 12){ ?>
                            <!-- 내년 1월 -->
                            <a class="calendar-day" href="./otter_detail.php?year=<?php echo $year+1 ?>&month=1&board_no=<?php echo $no ?>&page=<?php echo $page ?>"><img src="./image/right.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 다음 월 -->
                            <a class="calendar-day" href="./otter_detail.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>&board_no=<?php echo $no ?>&page=<?php echo $page ?>"><img src="./image/right.png" alt=""></a>
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
                <!-- <div class="memo">
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
                </div> -->

                <?php require_once(ROOT."/memo_list.php"); ?>
                <?php foreach ($result as $item){ ?>  
                
                <div class="insert-list">
                    <div class="insert-main">
                        <div class="insert-header">
                        <div class="line-content"><?php echo $item["board_title"] ?></div>
                        <div class=""><?php echo $item["created_at"] ?></div>
                        </div>
                        <div class="insert-middle">

                        </div>
                        <div class="insert-text">
 
                                <!-- 이미지 출력 -->
                                <?php echo '<img src="' . $item["board_img"] . '"><br>' . $item["board_content"]; ?>
                                
                                
                        </div>
                    </div>
                    <div class="insert-footer">
                        
                       
                        
                        <a href="./otter_detail.php?year=<?php echo $year ?>&month=<?php echo $month ?>&board_no=<?php if($prev_btn_result !== null){ echo $prev_btn_result; } if($no == $min_board_no){ echo $min_board_no; }?>&page=<?php echo $page ?>" class="prevbtn">◁</a>

                        <a href="./otter_update.php?board_no=<?php echo $no ?>&page=<?php echo $page ?>" class="updatebtn">수정</a>

                        <form method="POST">
                        <input type="hidden" name="board_no" value="<?php echo $no ?>">
                        <button type="submit" class="deletebtn" name="deletebtn">삭제</button>
                        </form>

                    
                        <a href="./otter_detail.php?year=<?php echo $year ?>&month=<?php echo $month ?>&board_no=<?php if($next_btn_result !== null){ echo $next_btn_result; } if($no == $max_board_no){ echo $max_board_no; } ?>&page=<?php echo $page ?>" class="nextbtn">▷</a>
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- <div class="folder_header">
        <div class="folder_header_otter4"></div>
        <div class="folder_header_otter4"></div>
        <div class="folder_header_otter4"></div>
        <div class="folder_header_otter4"></div>
    </div> -->
</body>
</html>