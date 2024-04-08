<?php

function my_db_conn() {
   
    $option = [
        PDO::ATTR_EMULATE_PREPARES      => FALSE
        ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
        ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
    ];

    return new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD, $option);
}

function db_select_boards_cnt($conn) {
   
    $sql =
        " SELECT "
        ."  COUNT(board_no) as cnt "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
        ;
    
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();
    
    return (int)$result[0]["cnt"];
}
function db_select_boards_paging(&$conn, &$array_param) {
  
    $sql =
        " SELECT "
        ."  board_no "
        ." ,board_title "
        ." ,created_at "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
        ." ORDER BY  "
        ."  Board_no DESC "
        ." LIMIT :list OFFSET :offset "
    ;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();
    
    return $result;
}

function db_insert_boards(&$conn, &$array_param) {
    
    $sql = 
        " INSERT INTO boards( "
        ."  board_title "
        ."  ,board_content "
        ." ) "
        ."  VALUES( "
        ."  :board_title "
        ."  ,:board_content "
        ." ) "
    ;

        $stmt = $conn->prepare($sql);
        $stmt->execute($array_param);

        return $stmt->rowCount();
}

function db_select_boards_no(&$conn, &$array_param) {
    $sql =
        " SELECT "
        ."  board_no  "
        ."  ,board_title "
        ."  ,board_content "
        ."  ,created_at  "
        ."  ,board_img  "

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

function db_delete_boards_no(&$conn, &$array_param) {
    
    $sql = 
        " UPDATE boards"
        ." SET "
        ."  deleted_at = NOW() "
        ." WHERE "
        ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}

function db_update_boards_no(&$conn, &$array_param) {
   
    $sql = 
        " UPDATE boards"
        ." SET "
        ."  board_title = :board_title "
        ."  ,board_content = :board_content "
        ."  ,updated_at = now() "
        ." WHERE "
        ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}

function next_btn(&$conn, &$array_param){
    $sql =
        " SELECT "
        ." board_no "
        ." FROM "
        ." boards "
        ." WHERE "
        ." board_no > :board_no "
        ." AND deleted_at IS NULL "
        ." ORDER BY board_no ASC "
        ." LIMIT 1 "
    ;
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchColumn();
    return $result ? $result : null;
}
function prev_btn(&$conn, &$array_param){
    $sql=
        " SELECT "
        ." board_no"
        ." FROM "
        ." boards "
        ." WHERE "
        ." board_no < :board_no "
        ." AND "
        ." deleted_at IS NULL "
        ." ORDER BY board_no DESC "
        ." LIMIT 1 "
    ;
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchColumn();
    return $result ? $result : null;
}
function max_no_sql(&$conn){
    $sql =
        " SELECT "
        ." MAX(board_no) board_no "
        ." FROM boards "
        ." WHERE "
        ." deleted_at IS NULL "
    ;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result[0]["board_no"];
}
function min_no_sql(&$conn){
    $sql =
        " SELECT "
        ." MIN(board_no) board_no "
        ." FROM "
        ." boards "
        ." WHERE "
        ." deleted_at IS NULL "
    ;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result[0]["board_no"];
}