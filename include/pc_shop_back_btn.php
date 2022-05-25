<?php
include "mobile_check.php";

$obj = new module();
$is_pc = $_SESSION['is_pc'];
$artist_flag = $_SESSION['artist_flag'];

if ($window_type == "close") {
    ?>
    <div class="header-back-btn"><a href="javascript:window.close();"><img src="<?= $image_directory ?>/close_ico.png" width="35px"></a></div>
<?php
} else if ($from == "main") {
    ?>
    <div class="header-back-btn"><a href="<?= $mainpage_directory?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<?php
} else if ($backurl) {
    ?>
    <div class="header-back-btn"><a href="<?= $backurl ?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<?php
} else if ($obj->mobileConcertCheck() != "mobile") {
    ?>
    <div class="header-back-btn"><a href="<?= $shop_directory ?>/index_pc.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<?php
} else if ($artist_flag === true) {
    ?>
    <div class="header-back-btn"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<?php
} else {
    ?>
    <div class="header-back-btn"><a href="<?= $shop_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<?php } ?>