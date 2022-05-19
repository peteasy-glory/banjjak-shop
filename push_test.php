<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
$message = "푸시테스트입니다..";
$path = "https://partner.banjjakpet.com/set_notice";
$image = "";
//app_push("dTBPP2qlikCsn6MvDuk30p:APA91bHEy0xJhyzcS7Ix_bRaKdxH_IB_jGrOX-b-DrYO13bbQNVyGf5gmcircKKqPMXL7UHIkqiH7xh8nJ1-wYb77jodmb-QMXgrMQC4Mv_ZIJgpyyG3XRH2DQvTTE5tKxuNtEf4LqUq", "반짝,푸시테스트", $message, $path, $image);
app_push("cc-hqExYwk6PhPD7LntBV2:APA91bFbv4lZE153MR2VtOKVImTuNEL8TrkMy6jr0c_Hu14uNM9osdzqeKAL9Tzay7JzFX9BbRP6Usjy2_mRsQziz3ei86HE3o5P3RckLFFmo_obO5D0ciYyyVDi3PpX3QkQ-7DTJtIA", "반짝, 푸시테스트", $message, $path, $image);
echo "test";
?>
