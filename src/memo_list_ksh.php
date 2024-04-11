<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config3.php");
require_once(FILE_LIB_DB); 

try {
    $conn = my_db_conn();



    $result_board = db_select_boards_all($conn);
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
            <form action="./memo_delete_sbw.php" method="post">
            <?php foreach ($result_memo as $item){ ?>
                <div class="memo_main">
                    <div class="memo_con"><?php echo $item["memo_content"] ?></div>
                    <input type="hidden" name="memo_no" value="<?php echo $item["memo_no"]; ?>">
                    <button type="submit" class ="divbutton">x</button>
                </div>
            <?php } ?>
            <?php foreach ($result_board as $item){ ?>
                <div class="memo_main">
                    <input type="hidden" name="memo_no" value="<?php echo $item["board_no"]; ?>">
                </div>
            <?php } ?>

            </form>
        </div>

        <div class="text-button">
            <form action="./memo_insert_ksh.php" method="post" >
                <input type="text" class="memo-text" autocomplete="off" name="memo_content">
                                    <input type="hidden" name="memo_no" value="<?php echo $item["board_no"]; ?>">
                <button class="sudal-button" type="submit" ><img class="sudal-head" src="../image/otter_face_end.png"></button>
            </form>
        </div>
    </div>
</div>