<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/practice_config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB);

// REQUEST_METHOD(요청방식)이 POST 일 경우 처리
if (REQUEST_METHOD === "POST") {
  try {
    // title과 content 파라미터 획득
    $title = isset($_POST["title"]) ? trim($_POST["title"]) : ""; // title 획득
    $content = isset($_POST["content"]) ? trim($_POST["content"]) : ""; // content 획득  
    $img_file = "upload_img/";
    // 파라미터 에러 체크
    $arr_err_param = [];
    if($title === "") {
        $arr_err_param[] = "title";
    }
    if($content === "") {
        $arr_err_param[] = "content";
    }
    if(count($arr_err_param) > 0) {
        // 예외 처리
        throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
    }
    
    // 파일이 이미지인지 확인
    // strtolower(pathinfo() : 파일경로를 가져오는 내장함수
    // $_FILES["file"]["name"] : 슈퍼 글로벌 변수 $_FILES에 있는 file모든정보를 가져오는 file 안에 name을 써서 이름만 가져옴
    //  PATHINFO_EXTENSION 파일의 확장자 명을 가져옴
    if(isset($_FILES["file"]["name"]))  { 
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
        "title" => $title,
        "content" => $content,
        "file_path" => $targetFilePath
    ];
    $result = db_insert_boards($conn, $arr_param);

    // 글 작성 예외 처리
    if($result !== 1) {
        throw new Exception("Insert Boards count");
    }

    // 커밋
    $conn->commit();

    //리스트 페이지로 이동
    header("Location: practice_main.php"); 
    // 위의 입력 처리를 한 후에 list.php에서 추가된 데이터를 포함해서 새로 리스트를 만들고 사용자에게 출력해줌
  }

  catch (\Throwable $e) {
      if(!empty($conn)) {
          $conn->rollBack();
      }
      echo $e->getMessage();
      exit;
  }

  finally {
      if(!empty($conn))
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
        <form action="./practice_insert.php" method="post" enctype="multipart/form-data">
          <div class="main-middle">
            <div class="line-item">
              <label for="title" class="line-title">
                <div>제목</div>
              </label>
              <div class="line-title-content">
                <input type="text" name="title" id="title">
              </div>
            </div>
            <div class="input-img">
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
                <textarea name="content" id="content" cols="30" rows="10"></textarea>
              </div>
            </div>
          </div>
          <div class="main-bottom">
            <button type="submit" class="button-submit" >작성</button>
              <a href="./practice_main.php" class="button-submit">취소</a>
          </div>
        </form>
      </main>
</body>
<script src="./js/img.js"></script>
</html>