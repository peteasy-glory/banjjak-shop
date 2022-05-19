<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$idch = $_POST['id'];

if ($idch == '') {
    ?>
    {"result": "error", "msg": "아이디를 입력하세요."}
    <?php
    } else {
        $sql = "select * from tb_customer where id = '" . $idch . "'";
        $result = mysqli_query($connection, $sql);
        if ($result_datas = mysqli_fetch_object($result)) {
            ?>
        {"result": "error", "msg": "아이디가 존재합니다."}
    <?php
        } else {
            ?>
        {"result": "success", "msg": "사용가능한 아이디입니다."}
<?php
    }
}

?>