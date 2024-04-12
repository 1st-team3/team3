<?php 
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB);


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
	

try {
  // DB Connect
  $conn = my_db_conn(); // PDO 인스턴스 생성
      
  if(REQUEST_METHOD === "GET") {
      
    // 게시글 데이터 조회
    // 파라미터
    $no = isset($_GET["board_no"]) ? $_GET["board_no"] : ""; // no 획득
    $page = isset($_GET["page"]) ? $_GET["page"] : ""; // page 획득
          
    // 파라미터 예외처리
    $arr_err_param = [];
      
    if($no === "") {
      $arr_err_param[] = "board_no";
    }
          
    if($page === "") {
      $arr_err_param[] = "page";
    }
          
    if(count($arr_err_param) > 0) {
      throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
    }
      
    // 게시글 정보 획득
    $arr_param = [
      "board_no" => $no
    ];
    $result = db_select_boards_no($conn, $arr_param);
        
    if(count($result) !== 1) {
      throw new Exception("Select Boards no count");
    }
        
    // 아이템 셋팅
    $item = $result[0];
  }
      
  else if(REQUEST_METHOD === "POST") {
    // 게시글 데이터 조회
    // 파라미터
    $no = isset($_POST["board_no"]) ? $_POST["board_no"] : ""; // no 획득
    $page = isset($_POST["page"]) ? $_POST["page"] : ""; // page 획득
    $title = isset($_POST["board_title"]) ? $_POST["board_title"] : ""; // title 획득
    $content = isset($_POST["board_content"]) ? $_POST["board_content"] : ""; // content 획득
    $targetFilePath = "";

    $img_file = "upload_img/";
      
    if($_FILES["file"]["name"] !== "")  { 
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
      
    // 파라미터 예외처리
    $arr_err_param = [];
      
    if($no === "") {
      $arr_err_param[] = "board_no";
    }
          
    if($page === "") {
      $arr_err_param[] = "page";
    }
      
    if($title === "") {
      $arr_err_param[] = "board_title";
    }
      
    if($content === "") {
      $arr_err_param[] = "board_content";
    }
          
    if(count($arr_err_param) > 0) {
      throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
    }
      
    // Transaction 시작
    $conn->beginTransaction();
      
    // 게시글 수정 처리
    $arr_param = [
      "board_no" => $no
      ,"board_title" => $title
      ,"board_content" => $content
    ];

    if($targetFilePath !== "") {
      $arr_param["board_img"] = $targetFilePath;
    }
      
    $result = db_update_boards_no($conn, $arr_param);
          
      
    // 수정 예외 처리
    if($result !== 1) {
      throw new Exception("Update Boards no count");
    }
      
    // commit
    $conn->commit();
      
    // 상세 페이지 이동
    header("Location: otter_detail.php?board_no={$no}&page={$page}");
          
  }
}

catch (\Throwable $e) {
  // inTransaction : 트랜젝션이 시작된상태면 true 아니면 false 를 반환
  if(!empty($conn) && $conn->inTransaction()) {
  $conn->rollBack();
  }
      
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
<html lang="KO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTTER OS</title>
    <link rel="stylesheet" href="./css/otter_update.css">
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
                <div class="folder_back_btn"><a href="./otter_detail.php?board_no=<?php echo $no ?>&page=<?php echo $page ?>" class="back_btn">◁</a></div>
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
                            <a class="calendar-day" href="./otter_update.php?board_no=<?php echo $no ?>&page=<?php echo $page ?>&year=<?php echo $year-1 ?>&month=12"><img src="./image/left.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 이전 월 -->
                            <a class="calendar-day" href="./otter_update.php?board_no=<?php echo $no ?>&page=<?php echo $page ?>&year=<?php echo $year ?>&month=<?php echo $month-1 ?>"><img src="./image/left.png" alt=""></a>
                        <?php }; ?>
                        <div class="calendar-year"><?php echo "$year 년 $month 월" ?></div>
                        <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                        <?php if ($month == 12){ ?>
                            <!-- 내년 1월 -->
                            <a class="calendar-day" href="./otter_update.php?board_no=<?php echo $no ?>&page=<?php echo $page ?>&year=<?php echo $year+1 ?>&month=1"><img src="./image/right.png" alt=""></a>
                        <?php }else{ ?>
                            <!-- 이번 년 다음 월 -->
                            <a class="calendar-day" href="./otter_update.php?board_no=<?php echo $no ?>&page=<?php echo $page ?>&year=<?php echo $year ?>&month=<?php echo $month+1 ?>"><img src="./image/right.png" alt=""></a>
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

                <?php require_once(ROOT."/memo_list.php"); ?>

                <div class="insert-list">
                    <form action="./otter_update.php" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="board_no" value="<?php echo $no; ?>">
                    <input type="hidden" name="page" value="<?php echo $page; ?>">
                        <div class="insert-main">
                            <div class="insert-header">
                                <input type="text" name="board_title" id="title" class="title-text" required placeholder="제목을 입력하세요" value="<?php echo $item["board_title"]; ?>">
                            </div>
                            <div class="insert-middle">
                                <label for="file">
                                    <div class="btn-upload">이미지 파일</div>
                                </label>
                                <input type="file" accept="img/*" name="file" id="file" onchange="readURL(this)">
                            </div>
                            <div class="insert-text">
                                <img id="preview" />
                                <?php if (!empty($item["board_img"])){ ?>
                                <img src="<?php echo $item["board_img"]; ?>" id="existing_image">
                                <?php } ?>
                                <textarea name="board_content" id="content" cols="30" rows="10" required placeholder="내용을 입력하세요"><?php echo $item["board_content"]; ?></textarea>
                            </div>
                        </div>
                        <div class="insert-footer">
                            <button type="submit" class="button-submit" >수정 완료</button>
                            <a href="./otter_detail.php?board_no=<?php echo $no ?>&page=<?php echo $page ?>" class="button-submit">취소</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="./js/img.js"></script>
</html>