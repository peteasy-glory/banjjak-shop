<?php    
	$client_id = 'com.gopet.banjjak';
	// 20210524 이전 - valid_client 에러 발생
	//$client_secret = 'eyJraWQiOiIzVlpLVTNTNDJVIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiI3U01TUEo2RDhQIiwiaWF0IjoxNjAzMzMzNjY0LCJleHAiOjE2MTg4ODU2NjQsImF1ZCI6Imh0dHBzOi8vYXBwbGVpZC5hcHBsZS5jb20iLCJzdWIiOiJjb20uZ29wZXQuYmFuamphayJ9.UaY4hkWwh9tliHhlLp2pbPHT5EU1gss-3skjUAG7SOi-tJT6j4KuPhXB_XP0yhuzq_0xm7CXp-_dt9lSDJD1Cw';
	
	// 20210525 by migo
//	$client_secret = 'eyJraWQiOiIzVlpLVTNTNDJVIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiI3U01TUEo2RDhQIiwiaWF0IjoxNjIxOTA4MzcxLCJleHAiOjE2Mzc0NjAzNzEsImF1ZCI6Imh0dHBzOi8vYXBwbGVpZC5hcHBsZS5jb20iLCJzdWIiOiJjb20uZ29wZXQuYmFuamphayJ9.bXehtr2ys9Vr96uaT9yWbViSTFrcuPtjYgF7SlSktz1V_nKFXf1w8IbdJCR9wJYR2jkvn09MMoiyT14h6qysRQ';

    $client_secret = 'eyJraWQiOiI5MzRKQTVBNlIzIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiI3U01TUEo2RDhQIiwiaWF0IjoxNjM5NjI3MzAzLCJleHAiOjE2NTUxNzkzMDMsImF1ZCI6Imh0dHBzOi8vYXBwbGVpZC5hcHBsZS5jb20iLCJzdWIiOiJjb20uZ29wZXQuYmFuamphayJ9.NRGB5QvPPSOZ8JShzc1b_QNaO1tbXTxQzO9Shjbd1YZb9cj3X3n1ZzfOcmr1GsIky5CoeulttN7tRuycENIU3Q';

	$redirect_uri = 'https://partner.banjjakpet.com/login/apple_callback';
	//echo $_REQUEST['code'];
	//print_r($_REQUEST);
	//exit;
	//echo "<br/>";
	if(isset($_REQUEST['code'])){
		$response = http('https://appleid.apple.com/auth/token', [
						 'grant_type' => 'authorization_code',
						 'code' => $_REQUEST['code'],
						 'redirect_uri' => $redirect_uri,
						 'client_id' => $client_id,
						 'client_secret' => $client_secret,
						 ]);
		/*
		$response = new stdClass;
		$response->access_token = "a4d1f6103a01b481e957e54f6f114982b.0.rxwx.uImHPYIzxcNxjlUNB4USXw";
		$response->token_type = "Bearer";
		$response->expires_in = "3600";
		$response->refresh_token = "rf68032d10b464831ae6b87408ec4f3d2.0.rxwx.iSgIapYx5pkHnlkY-jQgDg";
		$response->id_token = "eyJraWQiOiJlWGF1bm1MIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiY29tLmdvcGV0LmJhbmpqYWsiLCJleHAiOjE2MDM0Mjg0NzUsImlhdCI6MTYwMzM0MjA3NSwic3ViIjoiMDAwNzY3Ljk5NjM1YmIwYzJhYzQ3YmNiODgyNDc2MmNkZjM4OTZmLjA0MjciLCJhdF9oYXNoIjoiM3lZMXRJcmJ6QXl5MkIzZ2VsN3hXdyIsImVtYWlsIjoieGhoamR6Zmg1MkBwcml2YXRlcmVsYXkuYXBwbGVpZC5jb20iLCJlbWFpbF92ZXJpZmllZCI6InRydWUiLCJpc19wcml2YXRlX2VtYWlsIjoidHJ1ZSIsImF1dGhfdGltZSI6MTYwMzM0MjA3Mywibm9uY2Vfc3VwcG9ydGVkIjp0cnVlfQ.gxfFBxFtHPm2-OitD-ZQNPMHyp_oGgri-UxORBdfyZI2PrgPhvKUHiysW1ZRgFAsABm6kyAAjZO-mSaIW9zHl76REsDu0HDANBFREX3iNNoUiFSk9AFimMZt4MUy4d7tGOdBynAtH9bb1cbUCO2vvfW8lq3h12_DhLMv8lqEb0TBDD1IAKBSmv0RYzcKEDrTjsIyXjxUrbIIE7TLkE3KsPaEQQNqZHsxiakZcb8bzn__pnvVV9f9YBHCasmMmyDij60DZFmEGjruK_YNB8iObx_UQdeyuJtGGR_6IAIU6bdn0N0DPYLT0VaOApkG7fU9vYI9s3poeZyyaK-9Ulx-9g";
		*/
		
		//print_r($response);
		//echo "<br/>";
	
		
		// 20210524 by migo - 
		// errorstdClass Object ( [error] => invalid_client )
		/*
		if($_SERVER['REMOTE_ADDR'] == "128.134.102.49") {
			echo "error";
		//	print_r($_POST);
			//echo $response->access_token;
			//print($response['access_token']);
			print_r($response);
			exit;
		}
		*/

		if(!isset($response->access_token)) {
			echo '문제 발생! 다시 시도해주세요!!';
			header("LOCATION: ?status=fail");
			die();
		}

		$claims = explode('.', $response->id_token)[1];
		$claims = json_decode(base64_decode($claims));

		//print_r($claims);

//		header("LOCATION: https://partner.banjjakpet.com/login/apple_cellphone?status=success&email={$claims->email}&sub={$claims->sub}");
        header("LOCATION: https://partner.banjjakpet.com/login/apple_cellphone?status=success&email=pettester@peteasy.kr&sub={$claims->sub}");


	}else{

		echo '문제 발생! 다시 시도해주세요!';
		header("LOCATION: fail");
		die();
	}
    
    function http($url, $params=false) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($params)
			//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);	
            //
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: application/json',
                    //'Accept: application/x-www-form-urlencoded',
					'User-Agent: curl', # Apple requires a user agent header at the token endpoint
                    ]);
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		$response = curl_exec($ch);
        return json_decode($response);
    }

?>
