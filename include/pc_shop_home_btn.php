<style>
    .header-home-btn {
        margin-top: 11px;
    }
</style>

<?php
$is_pc = $_SESSION['is_pc'];
if (!$is_pc) {
    ?>
    <div class="header-home-btn"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/main_logo.png" height="25px"></a></div>
<?php
}
?>