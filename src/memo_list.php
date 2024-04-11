<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
require_once(FILE_LIB_DB); 

try {
    $conn = my_db_conn();

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
            <?php foreach ($result_memo as $item){ ?>
            <div class="memo_main"><div class="memo_con"><?php echo $item["memo_content"] ?></div>
            <form action="memo_delete.php" method="post">
                <input type="hidden" name="memo_no" value="<?php echo $item["memo_no"]; ?>">
                <button type="submit" class ="divbutton">x</button>
            </form>
            <?php } ?>
        </div>

        <div class="text-button">
            <form action="./memo_insert.php" method="post" >
                <input type="hidden" name="memo_no" value="<?php echo $item["memo_no"]; ?>">
                <input type="text" class="memo-text" autocomplete="off" name="memo_content">
                <button class="sudal-button" type="submit" ><img class="sudal-head" src="../image/otter_face_end.png"></button>
            </form>
        </div>
    </div>
</div>