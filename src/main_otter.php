<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config_sbw.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/main_otter.css">
</head>
<body>
    <div class="container">
        <div class="side">
            <div class="icon_1"><a href="./delete_otter.php"></a></div>
            <div class="icon_2"><a href="./list_otter_sbw.php"></a></div>
        </div>
</body>
</html>