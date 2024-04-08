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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./insert_otter_test.css">
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
                    <div class="folder_title_x"><a href="./main_otter.html" class="X_btn">X</a>
                    </div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn"><a href="" class="back_btn">◁</a></div>
                    <div class="folder_back_square"></div>
                </div>
            </div>
            <img class="otter-face" src="./image/otter_face_end.png" alt="">
            <div class="folder_main">
                <div class="calendar">
                    <div class="calendar-header"></div>
                    <div class="calendar-body">
                        <!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
                        <?php if ($month == 1){ ?>
                            <!-- 작년 12월 -->
                            <a class="calendar-day" href="./insert_otter_test.php?year=<?php echo $year-1 ?>&month=12"><img src="./image/left.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 이전 월 -->
                            <a class="calendar-day" href="./insert_otter_test.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="./image/left.png" alt=""></a>
                        <?php }; ?>
                        <div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>
                        <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                        <?php if ($month == 12){ ?>
                            <!-- 내년 1월 -->
                            <a class="calendar-day" href="./insert_otter_test.php?year=<?php echo $year+1 ?>&month=1"><img src="./image/right.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 다음 월 -->
                            <a class="calendar-day" href="./insert_otter_test.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="./image/right.png" alt=""></a>
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
                                <input type="text" name="memo-text" class="memo-text" required>
                                <button class="sudal-button" type="submit" name="memo-text"><img class="sudal-head" src="./image/otter_face_end.png"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="insert-list">
                    <form action="">
                        <div class="insert-main">
                            <div class="insert-header">
                                <input type="text" name="title" id="title" class="title-text" required placeholder="제목을 입력하세요">
                            </div>
                            <div class="insert-middle">
                                <label for="file">
                                    <div class="btn-upload">이미지 파일</div>
                                </label>
                                <input type="file" accept="img/*" name="file" id="file" onchange="readURL(this)">
                            </div>
                            <div class="insert-text">
                                <img id="preview" />
                                <textarea name="content" id="content" cols="30" rows="10" required placeholder="내용을 입력하세요"></textarea>
                            </div>
                        </div>
                        <div class="insert-footer">
                            <button type="submit" class="button-submit" >작성</button>
                            <a href="./detail_otter.html/" class="button-submit">취소</a>
                        </div>
                    </form>
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