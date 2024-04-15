<?php 
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB);
 

// REQUEST_METHOD(요청방식)이 POST 일 경우 처리

try {
   // 현재 날짜 표시하기
        $now_year = date("Y"); // 현재 연도
        $now_month = date("n"); // 현재 월
        $now_day = date("d"); // 현재 일

    if (REQUEST_METHOD === "GET") {

        // GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        // GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');

        $first_date = "$year-$month-01"; // 현재 날짜의 1일
        $time = strtotime($first_date); // 현재 날짜의 타임스탬프
        $start_week = date('w', $time); // 1. 시작 요일
        $total_day = date('t', $time); // 2. 현재 달의 총 날짜
        $total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차 (현재 요일부터 요일수를 구한뒤 7로 나눔 ($start_week = 일 = 0 월 = 1 ... 토 = 6))
     
        $is_now_month = ($year == $now_year && $month == $now_month); // 현재 년도와 달이 맞는지 확인

    }    
    else if (REQUEST_METHOD === "POST") {
        // title과 content 파라미터 획득
        $title = isset($_POST["board_title"]) ? trim($_POST["board_title"]) : ""; // title 획득
        $content = isset($_POST["board_content"]) ? trim($_POST["board_content"]) : ""; // content 획득  

        $targetFilePath = null;
        $img_file = "upload_img/"; // 서버 이미지 폴더 경로

        // 파라미터 에러 체크
        $arr_err_param = [];
        if($title === "") {
            $arr_err_param[] = "board_title";
        }
        if($content === "") {
            $arr_err_param[] = "board_content";
        }
        if(count($arr_err_param) > 0) {
            // 예외 처리
            throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
        }
        
        // 파일이 이미지인지 확인
        // strtolower(pathinfo() : 파일경로를 가져오는 내장함수
        // $_FILES["file"]["name"] : 슈퍼 글로벌 변수 $_FILES에 있는 file모든정보를 가져오는 file 안에 name을 써서 이름만 가져옴
        //  PATHINFO_EXTENSION 파일의 확장자 명을 가져옴
        if(!empty($_FILES["file"]["name"]))  { 
            $imageFileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
      
            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                throw new Exception("Only JPG, JPEG, and PNG files are allowed.");
            }
      
            // 업로드된 파일을 디렉토리에 저장
            //  $targetFilePath = $img_file . $_FILES["file"]["name"]; : 파일의 경로와 이름을 변수에 담음 
            $targetFilePath = $img_file . $_FILES["file"]["name"];
      
            // 이미지 파일을 디렉토리에 저장
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
              // 파일 업로드 성공
            }
            
            else {
              throw new Exception("Sorry, there was an error uploading your file.");
            }
        }

    
        // DB Connect
        $conn = my_db_conn(); // PDO 인스턴스

        // Transaction 시작
        $conn->beginTransaction();
        
        // 게시글 작성 처리
        $arr_param = [
            "board_title" => $title,
            "board_content" => $content,
            "board_img" => $targetFilePath
        ];
        $result = db_insert_boards($conn, $arr_param);

        // 글 작성 예외 처리
        if($result !== 1) {
            throw new Exception("Insert Boards count");
        }

        // 커밋
        $conn->commit();

        //리스트 페이지로 이동
        header("Location: otter_list.php?year=$now_year&month=$now_month&date=$now_day"); 
        // 위의 입력 처리를 한 후에 list.php에서 추가된 데이터를 포함해서 새로 리스트를 만들고 사용자에게 출력해줌
}
}
catch (\Throwable $e) {
    if(!empty($conn)) {
        $conn->rollBack();
    }
    echo $e->getMessage();
    exit;
}

finally {
    if(!empty($conn)) {
    $conn = null;
    }
}


?>

<!DOCTYPE html>
<html lang="KO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTTER OS</title>
    <link rel="stylesheet" href="./css/otter_insert.css">
    <link rel="icon" href="./image/otter_face_end.png">
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
                    <div class="folder_title_x"><a href="./otter_main.php" class="X_btn">X</a></div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn"><a href="./otter_list.php?year=<?php echo $now_year ?>&month=<?php echo $now_month ?>&date=<?php echo $now_day ?>" class="back_btn">◁</a></div>
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
                            <a class="calendar-day" href="./otter_insert.php?year=<?php echo $year-1 ?>&month=12"><img src="./image/left.png" alt=""></a>
                        <?php }
                            
                        else{ ?>
                            <!-- 이번 년 이전 월 -->
                            <a class="calendar-day" href="./otter_insert.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="./image/left.png" alt=""></a>
                        <?php }; ?>

                        <div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>

                        <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                        <?php if ($month == 12){ ?>
                            <!-- 내년 1월 -->
                            <a class="calendar-day" href="./otter_insert.php?year=<?php echo $year+1 ?>&month=1"><img src="./image/right.png" alt=""></a>
                        <?php }

                         else{ ?>
                            <!-- 이번 년 다음 월 -->
                            <a class="calendar-day" href="./otter_insert.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="./image/right.png" alt=""></a>
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
                                        if ($is_now_month && $n == $now_day) { // GET으로 가져온 달이 다르면 (출력된 년도나 달이 다르면) false가 돼서 else로 넘어감
                                            // 현재 날짜에 해당하는 경우
                                            echo '<div class="today">' . $n++ . '</div>';
                                        }
                                        else {
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

                <?php require_once(ROOT."/memo_list.php"); ?>

                <div class="insert-list">
                    <form action="./otter_insert.php" method="post" enctype="multipart/form-data">
                        <div class="insert-main">
                            <div class="insert-header">
                                <input type="text" name="board_title" id="title" class="title-text" required placeholder="제목을 입력하세요" autocomplete="off">
                            </div>
                            <div class="insert-middle">
                                <label for="file">
                                    <div class="btn-upload">이미지 파일</div>
                                </label>
                                <input type="file" accept="img/*" name="file" id="file" onchange="readURL(this)">
                            </div>
                            <div class="insert-text">
                                <img id="preview" />
                                <textarea name="board_content" id="content" cols="30" rows="10" required placeholder="내용을 입력하세요"></textarea>
                            </div>
                        </div>
                        <div class="insert-footer">
                            <button type="submit" class="button-submit" >작성</button>
                            <a href="./otter_list.php?year=<?php echo $now_year ?>&month=<?php echo $now_month ?>&date=<?php echo $now_day ?> " class="button-submit">취소</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>


<script src="./js/img.js"></script>


</html>