<?php
/*
	if( ! class_exists('XenoPostToForm') ){
		class XenoPostToForm {
			public static function check() {
				return !isset($_COOKIE['PHPSESSID']) && count($_POST) && ((isset($_SERVER['HTTP_REFERER']) && !preg_match('~^https://'.preg_quote($_SERVER['HTTP_HOST'], '~').'/~', $_SERVER['HTTP_REFERER']) || ! isset($_SERVER['HTTP_REFERER']) ));
			}

			public static function submit($posts) {
				echo '<html><head><meta charset="UTF-8"></head><body>';
				echo '<form id="f" name="f" method="post">';
				echo self::makeInputArray($posts);
				echo '</form>';
				echo '<script>';
						echo 'document.f.submit();';
						echo '</script></body></html>';
				exit;
			}

			public static function makeInputArray($posts) {
				$res = array();
				foreach($posts as $k => $v) {
					$res[] = self::makeInputArray_($k, $v);
				}
				return implode('', $res);
			}

			private static function makeInputArray_($k, $v) {
				if(is_array($v)) {
					$res = array();
					foreach($v as $i => $j) {
						$res[] = self::makeInputArray_($k.'['.htmlspecialchars($i).']', $j);
					}
					return implode('', $res);
				}
				return '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'" />';
			}
		}
	}

	if( !function_exists('shop_check_is_pay_page') ){
		function shop_check_is_pay_page(){

			// PG 결제사의 리턴페이지 목록들
			$pg_checks_pages = array(
				$mainpage_directory.'/INI_return.php',
				$mainpage_directory.'/INI_return_pc.php'
			);

			$server_script_name = str_replace('', '/', $_SERVER['SCRIPT_NAME']);

			// PG 결제사의 리턴페이지이면
			foreach( $pg_checks_pages as $pg_page ){
				if( preg_match('~'.preg_quote($pg_page).'$~i', $server_script_name) ){
					return true;
				}
			}

			return false;
		}
	}

	if(XenoPostToForm::check()) {
		if ( shop_check_is_pay_page() ){	// PG 결제 리턴페이지에서만 사용
			XenoPostToForm::submit($_POST); // session_start(); 하기 전에
		}
	}

	if(!function_exists('session_start_samesite')) {
		function session_start_samesite($options = array()) {
			$res = @session_start($options);

			// IE 브라우저 또는 엣지브라우저 일때는 secure; SameSite=None 을 설정하지 않습니다.
			if( preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT']) ){
				return $res;
			}

			$headers = headers_list();
			krsort($headers);
			foreach ($headers as $header) {
				if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
				$header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; SameSite=None; secure';
				header($header, false);
				break;
			}
			return $res;
		}
	}

	if(!function_exists('session_start_samesite')) {
		function session_start_modify_cookie()
		{
			$headers = headers_list();
			krsort($headers);
			foreach ($headers as $header) {
				if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
				$header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None';
				header($header, false);
				break;
			}
		}

		function session_start_samesite($options = [])
		{
			$res = session_start($options);
			session_start_modify_cookie();
			return $res;
		}

		function session_regenerate_id_samesite($delete_old_session = false)
		{
			$res = session_regenerate_id($delete_old_session);
			session_start_modify_cookie();
			return $res;
		}
	}
*/
	//session_start_samesite();
	//$sessionid = session_id();
?>
<?php
	if(!function_exists('session_start_samesite')) {
		function session_start_modify_cookie()
		{
			$headers = headers_list();
			krsort($headers);
			foreach ($headers as $header) {
				if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
				$header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None';
				header($header, false);
				break;
			}
		}

		function session_start_samesite($options = [])
		{
			//$res = session_start($options);
			//session_start_modify_cookie();
			//return $res;
		}

		function session_regenerate_id_samesite($delete_old_session = false)
		{
			$res = session_regenerate_id($delete_old_session);
			session_start_modify_cookie();
			return $res;
		}
	}

	session_start_samesite();
	$sessionid = session_id();
?>
