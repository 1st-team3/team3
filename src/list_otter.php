<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/doc/config.php"); 
require_once(FILE_LIB_DB); 
$list_cnt = 8; 
$page_num = 1; 

try{
    $conn = my_db_conn();

        $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;

    $result_board_cnt = db_select_todo_cnt($conn);

     $max_page_num = ceil($result_board_cnt / $list_cnt);   
     $offset = $list_cnt * ($page_num - 1); 
     $prev_page_num= ($page_num -1) < 1 ? 1 : ($page_num - 1) ; 
     $next_page_num= ($page_num + 1) > $max_page_num  ? $max_page_num : ($page_num + 1);
    $arr_param = [
        "list_cnt" => $list_cnt
        ,"offset" => $offset
    ];
    $result = db_select_todo_paging($conn, $arr_param);
    
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
    <title>Document</title>
    <link rel="stylesheet" href="./list_otter.css">
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
                    <div class="folder_title_x"><a href="./main_otter.html" class="X_btn">X</a>
                    </div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn"><a href="" class="back_btn">◁</a></div>
                    <div class="folder_back_square"></div>
                </div>
            </div>
            <div class="folder_main">
                <div class="calendar">
                    
                </div>
                <div class="memo">
                    <h2>MEMO</h2>
                    <div class="memo-board">
                        <div class="memo-textarea">

                        </div>
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
                </form>
                </div> 
            <?php foreach ($result as $item){
    ?>  
                <div class="list" <?php echo $item["no"];?>>
                    <form action="./list_otter.php" method="get">
                    <input class="input_list" type="checkbox" id="input_list"  value="<?php echo $item["no"]; ?>">
                    <label class="input_label" for="input_list"></label>
                    <input class="text_box" type="text" id="text_box" value ="<?php echo $item["title"]; ?>">
                    <label for="text_box"></label>
                </div>
            </form>
            </div>
            <?php
}
?>
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