<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");

$emoji = new TEmoji();
$user_id = $_SESSION['gobeauty_user_id'];

$working_years = $_POST["artist_working_years"];
$self_intro = $emoji->emojiStrToDB($_POST["artist_self_intro"]);

$career = $emoji->emojiStrToDB($_POST["artist_career"]);

$region = $_POST["artist_region"];
$license = $_POST["artist_license"];
$award = $_POST["artist_award"];

// sns
$kakao_channel = ($_POST["kakao_channel"] && $_POST["kakao_channel"] != "")? $_POST["kakao_channel"] : "";
$instagram = ($_POST["instagram"] && $_POST["instagram"] != "")? $_POST["instagram"] : "";
$kakao_id = ($_POST["kakao_id"] && $_POST["kakao_id"] != "")? $_POST["kakao_id"] : "";
$enable = ($kakao_channel == "" && $instagram == "" && $kakao_id == "")? "0" : "1";
$sns_cnt = ($_POST["sns_cnt"] && $_POST["sns_cnt"] != "")? $_POST["sns_cnt"] : "";


$sql = "UPDATE tb_shop
SET working_years = " . $working_years . ",
self_introduction = '" . $self_intro . "',
career = '" . $career . "',
region_and_cost = '" . $region . "',
update_time = NOW()
WHERE customer_id = '" . $user_id . "'";
// error_log('----- $sql : '.$sql);
$result = mysqli_query($connection, $sql);

//sns
if($sns_cnt){
    $sns_sql = "
		UPDATE tb_shop_sns SET
			kakao_channel = '".$kakao_channel."',
			instagram = 'https://www.instagram.com/".$instagram."',
			kakao_id = '".$kakao_id."',
			enable_flag = '".$enable."',
			update_time = NOW()
		WHERE artist_id = '".$user_id."'
	";

    $sns_result = mysqli_query($connection, $sns_sql);

}else{
    $sns_sql = "
		INSERT INTO tb_shop_sns
		(artist_id, kakao_channel, instagram, kakao_id, update_time)
		VALUES
		('".$user_id."', '".$kakao_channel."', 'https://www.instagram.com/".$instagram."', '".$kakao_id."', NOW())
	";

    $result = mysqli_query($connection, $sns_sql);
}
?>

<script language="javascript">
    <? if (!$result) { ?>
    location.href = "../shop_main";
    <? } else { ?>
    location.href = "../shop_main";
    <? } ?>
</script>
