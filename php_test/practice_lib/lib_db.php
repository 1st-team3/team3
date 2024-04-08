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
        ."  team3 "
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
        ."  ,board_title "
        ."  ,created_at "
        ." FROM "
        ."  team3 "
        ." WHERE "
        ."  deleted_at IS NULL "
        ." ORDER BY  "
        ."  no DESC "
        ." LIMIT :list_cnt OFFSET :offset "
    ;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();
    
    return $result;
}

function db_insert_boards(&$conn, &$array_param) {
    
    $sql = 
        " INSERT INTO team3( "
        ."  board_title "
        ."  ,board_content "
        ."  ,board_img "
        ." ) "
        ."  VALUES( "
        ."  :board_title "
        ."  ,:board_content "
        ."  ,:board_img "
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
        ."  ,board_img "
        ." FROM      "
        ."  team3 "
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
        " UPDATE team3"
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
    $add_file_path = "";
    if(isset($array_param["board_img"])) {
        $add_file_path = " ,board_img = :board_img ";
    }


    $sql = 
        " UPDATE team3"
        ." SET "
        ."  board_title = :board_title "
        ."  ,board_content = :board_content "
        ."  ,updated_at = now() "
        .$add_file_path
        ." WHERE "
        ."  board_no = :board_no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}
