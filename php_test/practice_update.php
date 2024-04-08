<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/practice_config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB);

try {
  // DB Connect
  $conn = my_db_conn(); // PDO 인스턴스 생성

  if(REQUEST_METHOD === "GET") {

    // 게시글 데이터 조회
    // 파라미터
    $no = isset($_GET["no"]) ? $_GET["no"] : ""; // no 획득
    $page = isset($_GET["page"]) ? $_GET["page"] : ""; // page 획득
    
    // 파라미터 예외처리
    $arr_err_param = [];

    if($no === "") {
      $arr_err_param[] = "no";
    }
    
    if($page === "") {
      $arr_err_param[] = "page";
    }
    
    if(count($arr_err_param) > 0) {
      throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
    }

    // 게시글 정보 획득
    $arr_param = [
      "no" => $no
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
    $no = isset($_POST["no"]) ? $_POST["no"] : ""; // no 획득
    $page = isset($_POST["page"]) ? $_POST["page"] : ""; // page 획득
    $title = isset($_POST["title"]) ? $_POST["title"] : ""; // title 획득
    $content = isset($_POST["content"]) ? $_POST["content"] : ""; // content 획득
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
      $arr_err_param[] = "no";
    }
    
    if($page === "") {
      $arr_err_param[] = "page";
    }

    if($title === "") {
      $arr_err_param[] = "title";
    }

    if($content === "") {
      $arr_err_param[] = "content";
    }
    
    if(count($arr_err_param) > 0) {
      throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
    }

    // Transaction 시작
    $conn->beginTransaction();

    // 게시글 수정 처리
    $arr_param = [
      "no" => $no
      ,"title" => $title
      ,"content" => $content
    ];
    if($targetFilePath !== "") {
      $arr_param["file_path"] = $targetFilePath;
    }

    $result = db_update_boards_no($conn, $arr_param);
    

    // 수정 예외 처리
    if($result !== 1) {
      throw new Exception("Update Boards no count");
    }

    // commit
    $conn->commit();

    // 상세 페이지 이동
    header("Location: practice_detail.php?no={$no}&page={$page}");
    
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../practice_css/practice_common.css">
</head>
<body>
    <main>
        <form action="./practice_update.php" method="post"  enctype="multipart/form-data">
          <input type="hidden" name="no" value="<?php echo $no; ?>">
          <input type="hidden" name="page" value="<?php echo $page; ?>">
          <div class="main-middle">
            <div class="line-item">
              <label for="title" class="line-title">
                <div>제목</div>
              </label>
              <div class="line-title-content">
                <input type="text" name="title" id="title" size = "10" value="<?php echo $item["title"]; ?>">
              </div>
            </div>
            <div class="inset-line">
                <div class="list-num"><?php echo $item["no"]; ?></div>
                <label for="file">
                    <div class="btn-upload">이미지 파일</div>
                </label>
            </div>
            <input type="file" accept="img/*" name="file" id="file" onchange="readURL(this)">
            <div class="line-item">
              <label for="content" class="line-title">
                <div class="line-title center">내용</div>
              </label>
              <div class="line-content">
                <img id="preview" />
                <?php if (!empty($item["file_path"])){ ?>
                <img src="<?php echo $item["file_path"]; ?>" id="existing_image">
                <?php } ?>
                <textarea name="content" id="content" cols="30" rows="10"><?php echo $item["content"]; ?></textarea>
              </div>
            </div>
          </div>
          <div class="main-bottom">
            <button type="submit" class="button-submit">완료</button>
            <a href="./practice_detail.php?no=<?php echo $no; ?>&page=<?php echo $page; ?>" class="button-submit">취소</a>
          </div>
        </form>
      </main>
</body>
<script src="./js/img.js"></script>
</html>