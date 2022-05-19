<!doctype html>
<?php session_start(); ?>

<?php include "configure.php"; ?>

<?php include "db_connection.php"; ?>

<?php include "php_function.php"; ?>

<html lang="ko">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui" />

		<title>Go Beauty - 뷰티가 찾아갑니다</title>

		<link type="text/css" rel="stylesheet" href="<?=$root_directory?>/css/swiper.min.css" />
		<link type="text/css" rel="stylesheet" href="<?=$root_directory?>/css/messagebox.css" />

		<script type="text/javascript" src="<?=$root_directory?>/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="<?=$root_directory?>/js/swiper.4.1.6.min.js"></script>
		<script type="text/javascript" src="<?=$root_directory?>/js/messagebox.min.js"></script>
	</head>
	<body>
