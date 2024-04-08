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
        ." ,board_title "
        ." ,created_at "
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
        ."  title "
        ."  ,content "
        ." ) "
        ."  VALUES( "
        ."  :title "
        ."  ,:content "
        ." ) "
    ;

        $stmt = $conn->prepare($sql);
        $stmt->execute($array_param);

        return $stmt->rowCount();
}

function db_select_boards_no(&$conn, &$array_param) {
    $sql =
        " SELECT "
        ."  no  "
        ."  ,title "
        ."  ,content "
        ."  ,created_at  "
        ." FROM      "
        ."  team3 "
        ." WHERE "
        ."  no = :no "
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
        ."  no = :no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}

function db_update_boards_no(&$conn, &$array_param) {
   
    $sql = 
        " UPDATE team3"
        ." SET "
        ."  title = :title "
        ."  ,content = :content "
        ."  ,updated_at = now() "
        ." WHERE "
        ."  no = :no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}
