<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config_khs.php");
require_once(FILE_LIB_DB); 

try {
    $conn = my_db_conn();

    $current_url = $_SERVER['REQUEST_URI'];   

    $result_memo = db_select_memos_all($conn);
} catch (\Throwable $e) {
    echo $e->getMessage();
    exit;
} finally {
    if (!empty($conn)) {
        $conn = null;
    }
}
?>

<div class="memo">
    <h2>MEMO</h2>
    <div class="memo-board">
        <div class="memo-textarea">
            <?php foreach ($result_memo as $item2){ ?>
                <div class="memo_main"><div class="memo_con"><?php echo $item2["memo_content"] ?></div>
                <form action="memo_delete_update.php" method="post">
                <input type="hidden" name="memo_no" value="<?php echo $item2["memo_no"]; ?>">
                <button type="submit" class ="divbutton">x</button></form></div>
            <?php } ?>
        </div>

        <div class="text-button">
            <form action="./memo_insert_update.php" method="post" >
                <input type="text" class="memo-text" autocomplete="off" name="memo_content">
                <button class="sudal-button" type="submit" ><img class="sudal-head" src="../image/otter_face_end.png"></button>
            </form>
        </div>
    </div>
</div>