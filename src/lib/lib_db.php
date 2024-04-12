<?php

function my_db_conn() {
   
    $option = [
        PDO::ATTR_EMULATE_PREPARES      => FALSE
        ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
        ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
    ];

    return new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD, $option);
}

function db_select_boards_cnt(&$conn, &$array_param) {
    $add_date = "";
    if(isset($array_param["start"] ) && isset($array_param["end"])) {
        $add_date = " and created_at between :start and :end ";
    }

    $sql =
        " SELECT "
        ."  COUNT(board_no) as cnt "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
        .$add_date
        ;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();
    
    return (int)$result[0]["cnt"];
}

function db_select_boards_paging(&$conn, &$array_param) {
  
    $sql =
        " SELECT "
        ."  board_no "
        ." ,board_title "
        ." ,created_at "
        ." ,board_chkbox "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
        ." ORDER BY  "
        ."  board_no DESC "
        ." LIMIT :list_cnt OFFSET :offset "
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
    $add_file_path = "";
    if(isset($array_param["board_img"])) {
        $add_file_path = " ,board_img = :board_img ";
    }


    $sql = 
        " UPDATE boards"
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

function db_list_update_no(&$conn, &$array_param) {
    $sql = 
        " UPDATE boards"
        ." SET "
        ."  board_chkbox = CASE WHEN board_chkbox = '0' THEN '1' ELSE '0' END "
        ." WHERE "
        ."  board_no = :board_no "
    ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    // return 
    return $stmt->rowCount();
}

function db_select_boards_title(&$conn, &$array_param) {
    $add_date = "";
    if(isset($array_param["start"] ) && isset($array_param["end"])) {
        $add_date = " and created_at between :start and :end ";
    }
    $sql =
        " SELECT "
        ." board_no "
        ." ,board_title "
        ." ,created_at "
        ." ,board_chkbox "
        ." FROM "
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
        .$add_date
        ." ORDER BY  "
        ."  board_no DESC "
        ." LIMIT :list_cnt OFFSET :offset "
    ;
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();
    return $result;
}

function db_select_memos_cnt($conn) {
   
    $sql =
        " SELECT "
        ."  COUNT(memo_no) as cnt "
        ." FROM "
        ."  memos "
        ." WHERE "
        ."  deleted_at IS NULL "
        ;
    
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();
    
    return (int)$result[0]["cnt"];
}


    function db_select_memo_no(&$conn, &$array_param) {
        $sql =
        " SELECT "
        ."  memo_no  "
        ."  ,memo_content "
        ."  ,created_at  "
        ."  , memo_chkbox "
        ."FROM      "
        ."  memos "
        ."WHERE "
        ."  memo_no = :memo_no "
    ;

    // Query 실행
    $stmt = $conn-> prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    // 리턴
    return $result;   

    }

    function db_select_memos_all(&$conn) {
    
        $sql =
            " SELECT "
            ."  memo_no "
            ." ,memo_content "
            ." ,memo_chkbox "
            ." ,created_at "
            ." FROM "
            ."  memos "
            ." WHERE "
            ."  deleted_at IS NULL "
            ." ORDER BY  "
            ."  memo_no DESC "
        ;
        
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll();
    
        return $result;
    }


function db_memo_insert(&$conn, &$array_param) {
    $sql = 
        " INSERT INTO memos( "
        ." memo_content "
        ." )"
        ." VALUES( "
        ." :memo_content "
        ." ) "
        ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);


    // 리턴
    return $stmt->rowCount();

}

function db_memo_delete(&$conn, &$array_param) {
    // SQL
$sql =
    " UPDATE memos"
    ." SET "
    ."  deleted_at = NOW() "
    ." WHERE "
    ."  memo_no = :memo_no "
;

// Query 실행
$stmt = $conn->prepare($sql);
$stmt->execute($array_param);

// return 
return $stmt->rowCount();

}




// delete 페이지 lib
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

// 초기 화면 오늘날짜 표현
function db_boards_select_created_at(&$conn, &$array_param) {

    $sql =
        " SELECT "
        ."  created_at  "
        ." FROM      "
        ."  boards "
        ." WHERE DATE(created_at) = :today"
        ."  AND deleted_at IS NULL";

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    return $result;
}


// detail 페이지 lib

// 페이지 이전글 다음글
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