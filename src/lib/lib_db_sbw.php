<?php

function my_db_conn() {
   
    $option = [
        PDO::ATTR_EMULATE_PREPARES      => FALSE
        ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
        ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
    ];

    return new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD, $option);
}


// 삭제된 파일 정보 불러오기
function db_select_delete_boards_list($conn) {
  
    $sql =
        " SELECT "
        ."  board_no "
        ." ,board_title "
        ." ,board_content "
        ." ,deleted_at "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NOT NULL "
        ." ORDER BY  "
        ."  deleted_at DESC, board_no DESC"
    ;
    
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();
    
    return $result;
}


// 복구
function db_restore_boards(&$conn, &$array_param) {
    $sql = 
        " UPDATE boards"
        ." SET "
        ."  deleted_at = NULL "
        ." WHERE "
        ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    return $stmt();
}


// 삭제
function db_delete_boards(&$conn, &$array_param) {
    $sql =
	    " DELETE FROM "
	    ."  boards "
	    ." WHERE "
	    ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    return $stmt();
}














function db_select_boards_no(&$conn, &$array_param) {
    $sql =
        " SELECT "
        ."  board_no  "
        ."  ,board_title "
        ."  ,board_content "
        ."  ,created_at  "
        ." FROM      "
        ."  boards "
        ." WHERE "
        ."  board_no = :board_no "
;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    return $result;
}






function db_update_boards_no(&$conn, &$array_param) {
   
    $sql = 
        " UPDATE boards"
        ." SET "
        ."  board_title = :title "
        ."  ,board_content = :content "
        ."  ,updated_at = now() "
        ." WHERE "
        ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}
