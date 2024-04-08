<?php
// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리









?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../src/css/delete_otter.css">
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
                    <div class="folder_title_x"><a href="./main_otter.html" class="X_btn">X</a>
                    </div>
                </div>
                <div class="folder_back">
                    <div class="folder_back_btn"><a href="./main_otter.html" class="back_btn">◁</a></div>
                    <div class="folder_back_square"></div>
                </div>
            </div>
            <div class="folder_main">
                <div class="item_icon">


                    <div class="item_icon_can">
                            <div class="deleted_title">
                                삭제한 파일입니다
                            </div>
                            <div class="deleted_created_at">
                                2024-04-05
                            </div>
                            <div class="deleted_deleted_at">
                                2024-04-05
                            </div>
                        <div class="form_btn">
                            <form action="" method="">
                                <button type="submit">복구</button>
                            </form>
                            <form action="" method="">
                                <ul>
                                    <button type="submit">삭제</button>
                                    <li><strong style="color: red;">※주의!!</strong><br>삭제하면 영원히 복구할 수 없습니다.</li>
                                </ul>
                            </form>
                            </form>
                        </div>
                    </div>
                    <div class="item_icon_can">
                            <div class="deleted_title">
                                삭제한 파일입니다
                            </div>
                            <div class="deleted_created_at">
                                2024-04-05
                            </div>
                            <div class="deleted_deleted_at">
                                2024-04-05
                            </div>
                        <div class="form_btn">
                            <form action="" method="">
                                <button type="submit">복구</button>
                            </form>
                            <form action="" method="">
                                <ul>
                                    <button type="submit">삭제</button>
                                    <li><strong style="color: red;">※주의!!</strong><br>삭제하면 영원히 복구할 수 없습니다.</li>
                                </ul>
                            </form>
                            </form>
                        </div>
                    </div>
                    <div class="item_icon_can">
                            <div class="deleted_title">
                                삭제한 파일입니다
                            </div>
                            <div class="deleted_created_at">
                                2024-04-05
                            </div>
                            <div class="deleted_deleted_at">
                                2024-04-05
                            </div>
                        <div class="form_btn">
                            <form action="" method="">
                                <button type="submit">복구</button>
                            </form>
                            <form action="" method="">
                                <ul>
                                    <button type="submit">삭제</button>
                                    <li><strong style="color: red;">※주의!!</strong><br>삭제하면 영원히 복구할 수 없습니다.</li>
                                </ul>
                            </form>
                            </form>
                        </div>
                    </div>






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