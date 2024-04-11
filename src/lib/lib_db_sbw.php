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
function db_boards_select_delete_list($conn) {
  
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
function db_boards_restore(&$conn, &$array_param) {
    $sql = 
        " UPDATE boards"
        ." SET "
        ."  deleted_at = NULL "
        ." WHERE "
        ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();

}


// 삭제
function db_boards_delete(&$conn, &$array_param) {
    $sql =
	    " DELETE FROM "
	    ."  boards "
	    ." WHERE "
	    ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}


// 전체 삭제
function db_boards_all_delete($conn) {
    $sql =
	    " DELETE FROM "
	    ."  boards "
	    ." WHERE "
	    ."  deleted_at IS NOT NULL "
    ;

    $stmt = $conn->query($sql);

    return $stmt->rowCount();
}








// list 관련 작업 04/11


function db_select_boards_all($conn) {
    
    $sql =
        " SELECT "
        ."  board_no "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
    ;
    
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();

    return $result;
}

function a(&$conn, &$array_param) {

    $sql =
        " SELECT "
        ."  board_no "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
        ."  AND board_no = :board_no "
    ;

// Query 실행
$stmt = $conn-> prepare($sql);
$stmt->execute($array_param);
$result = $stmt->fetchAll();

// 리턴
return $result;   

}
