<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config_sbw.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    // DB Connect
    $conn = my_db_conn(); // connection 함수 호출

    if(REQUEST_METHOD === "GET") {
    // 파라미터 획득
    $board_no = isset($_GET["board_no"]) ? $_GET["board_no"] : ""; // no 획득
    var_dump($board_no);
    // 삭제된 파일 정보 불러오기
    $result = db_select_delete_boards_list($conn);
    } 
} catch (\Throwable $err) {
    echo $err->getMessage();
    exit;
} finally {
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
    <link rel="stylesheet" href="./css/delete_otter.css">
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
                    <div class="folder_title_x"><a href="./main_otter.php" class="X_btn">X</a>
                    </div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn">◁</div>
                    <div class="folder_back_square"></div>
                </div>
            </div>

            <div class="folder_main">
                <div class="folder_icon">
                    
                    <?php
                    foreach($result as $item) {
                    ?>  

                    <div class="icon_item">
                        <div class="icon_item_card">


                            <div class="icon_item_title">
                                <div class="icon_item_title1">title</div>
                                <div class="icon_item_title2"><?php echo $item["board_no"] ?> </div>
                            </div>
                            <div class="icon_item_content">
                                <div class="icon_item_content1">content</div>
                                <div class="icon_item_content2" ><?php echo $item["board_content"] ?></div>
                            </div>
                            <div class="icon_item_deleted">
                                <div class="icon_item_deleted1">deleted</div>
                                <div class="icon_item_deleted2"><?php echo $item["deleted_at"] ?></div>
                            </div>



                            <div class="form_btn">
                                <form action="./delete_otter2.php" method="POST">
                                    <input type="hidden" name="board_no" value="<?php echo $_GET["board_no"]; ?>">
                                    <button type="submit">복구</button>
                                </form>
                                <form action="" method="POST">
                                    <ul>
                                        <button type="submit">삭제</button>
                                        <input type="hidden" name="" value="">
                                        <li><strong style="color: red;">※주의!!</strong><br>삭제하면 영원히 복구할 수 없습니다.</li>
                                    </ul>
                                </form>
                            </div>

                        </div>
                    </div>

                    <?php
                    }
                    ?>

                </div>
            </div>
            전체삭제 버튼
            <!-- TODO : 주의 메세지 위치 수정 필요 -->
            <!-- <form action="" method="POST">
                <ul class="ul_1">
                    <button type="submit">전체 삭제</button>
                    <li class="li_1"><strong style="color: red;">※주의!!</strong><br>삭제하면 영원히 복구할 수 없습니다.</li>
                </ul>
            </form> -->
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