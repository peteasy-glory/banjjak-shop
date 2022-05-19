<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //호출한 uri을 가져옴 
$uri = explode('/', $uri);

$bottomPattern = explode('_', $uri[1]);

/* url 이 많아서 컨트롤 하기 어렵기 때문에 방식을 변경
$shopBottomArray = Array (
	"home" => Array(
		"home_main",
	),
	"reserve" => Array(
		"reserve_advice_list_1",
		"reserve_main_month",
	),
	"customer" => Array(
		"customer_all_inquiry1",
		"customer_pet_new",
		"customer_grade",
	),
	"shop_main" => Array(
		"shop_gate_picture_list",
		"shop_management",
		"shop_portfolio_list",
		"shop_review_list",
		"shop_blog_list",
	),
	"set_main" => Array(
		"",
		"",
		"",
	)
);

$bottomActived = Array (
	(in_array($uri[1],$shopBottomArray["home"]) ? "actived":"" ),
	(in_array($uri[1],$shopBottomArray["reserve"]) ? "actived":"" ),
	(in_array($uri[1],$shopBottomArray["customer"]) ? "actived":"" ),
	(in_array($uri[1],$shopBottomArray["shop_main"]) ? "actived":"" ),
	(in_array($uri[1],$shopBottomArray["set_main"]) ? "actived":"" )
);
 */

 
$bottomActived = Array (
	(($bottomPattern[0] == "home") ? "actived":"" ),
	(($bottomPattern[0] == "reserve") ? "actived":"" ),
	(($bottomPattern[0] == "customer") ? "actived":"" ),
	(($bottomPattern[0] == "shop") ? "actived":"" ),
	(($bottomPattern[0] == "set") ? "actived":"" )
);

$userBottomActived = Array (
	(($bottomPattern[0] == "") ? "actived":"" ),
	(($bottomPattern[0] == "reserve") ? "actived":"" ),
	(($bottomPattern[0] == "customer") ? "actived":"" ),
	(($bottomPattern[0] == "shop") ? "actived":"" ),
	(($bottomPattern[0] == "mypage") ? "actived":"" )
);

$menuId = getMenuId($uri[1]);

function getMenuId($url) {
	$subUrl = Array(
		"set_beauty_management_time" => "set_beauty_management",
		"reserve_main_month" => "hiddenReserve",
		"reserve_accept_input" => "reserve_accept",
		"customer_inquiry" => "hiddenCustomer",
		"shop_main" => "hiddenShop",
		"set_main" => "hiddenSet",
        "#" => "hiddenMyMenu"
	);
	return (array_key_exists($url,$subUrl)) ? $subUrl[$url]: $url;
}
?>