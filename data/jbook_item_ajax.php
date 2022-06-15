<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$data = array();
$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
$headers = array('Authorization: RittQ1EwLzJtb2pKWUtwUXR2VE9lMHVoZDdRbU1NMEY2ajF6Z24ycDQ2Q2s3dzVNVU9USlNaNUd4UlVxaldyeUNkMWhPcVNZZGdsL1FYYWhZMHRXeXVZcndsKzFPMEpYcU55WEI2QVpPcmFncWM5M3VDRU12S0NhOHJYa2dWb3M');
$err_code = array(
    "001" => "필수 파라미터 오류",
    "002" => "파라미터 형식 오류",
    "101" => "구매 및 결제확인 동의하지 않음 으로인한 주문접수 불가",
    "102" => "주문상품중 중복으로 주문한 상품이 있음",
    "103" => "구매 불가 상품 주문",
    "104" => "주문 상품 없음",
    "105" => "중복주문 오류",
    "201" => "주문상품 찾을수 없음",
    "202" => "상품옵션 오류",
    "203" => "묶음주문 수량 오류",
    "204" => "최소 구매 수량 오류",
    "205" => "최대 구매 수량 오류",
    "206" => "주문상품 품절",
    "207" => "주문상품 재고부족",
    "208" => "공급사 요청으로 인한 구매제한 상품 주문",
);

$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

if($r_mode){
    if($r_mode == "get_item_list"){
        $r_menu = ($_POST["menu"] && $_POST["menu"] != "")? $_POST["menu"] : "";
        $r_option = ($_POST["option"] && $_POST["option"] != "")? $_POST["option"] : "";

        $return_data = get_jb($headers, $r_menu, $r_option);
    }else if($r_mode == "get_item_category"){
        $r_menu = "category";
        $r_option = ($_POST["option"] && $_POST["option"] != "")? $_POST["option"] : "";

        $return_data = get_jb($headers, $r_menu, $r_option);
    }else if($r_mode == "get_member"){
        $r_menu = "member";
        $r_option = ($_POST["option"] && $_POST["option"] != "")? $_POST["option"] : "";

        $return_data = get_jb($headers, $r_menu, $r_option);
    }else if($r_mode == "get_item_invoice"){
        $r_menu = "invoice";
        $r_option = ($_POST["option"] && $_POST["option"] != "")? $_POST["option"] : "";

        $return_data = get_jb($headers, $r_menu, $r_option);
    }else if($r_mode == "get_item_order"){
        $r_menu = "order";
        $r_option = ($_POST["option"] && $_POST["option"] != "")? $_POST["option"] : "";

        $return_data = get_jb($headers, $r_menu, $r_option);
    }else if($r_mode == "set_insert_item_order"){
        $r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : ""; // "I161043984737";
        $r_orderItem = ($_POST["orderItem"] && $_POST["orderItem"] != "")? $_POST["orderItem"] : ""; // "[{a: 1, b: 2}]";

        if($r_order_num != "" && $r_orderItem != ""){
            $sql = "
					INSERT INTO tb_item_payment_log_jbook (
						`order_num`, `orderItem`
					) VALUES (
						'".$r_order_num."', '".json_encode($r_orderItem)."'
					)
				";
            $result = mysqli_query($connection, $sql);
            if($result){
                $return_data = array("code" => "000000", "data" => "OK");
            }else{
                $return_data = array("code" => "000001", "data" => "결제내역 상품 등록에 실패했습니다.");
            }
        }else{
            $return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
        }
    }else if($r_mode == "set_update_item_order"){
        $r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : ""; // "I161043984737";
        $r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : ""; // "01086604588";
        $r_customer_name = ($_POST["customer_name"] && $_POST["customer_name"] != "")? $_POST["customer_name"] : ""; // "전영도";
        $r_zipcode = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : ""; // "12345";
        $r_address = ($_POST["address"] && $_POST["address"] != "")? $_POST["address"] : ""; // "서울시 종로구 종로6 광화문우체국";
        $r_address2 = ($_POST["address2"] && $_POST["address2"] != "")? $_POST["address2"] : ""; // "5층";
        $r_memo = ($_POST["memo"] && $_POST["memo"] != "")? $_POST["memo"] : ""; // "문앞에 놓고 가세요";
        $r_orderItem = ($_POST["orderItem"] && $_POST["orderItem"] != "")? $_POST["orderItem"] : ""; // "문앞에 놓고 가세요";

        $post_data = array(
            "oaType" => "api",
            "oaOrderNo" => $r_order_num,
            "phoneReceiver" => add_hyphen($r_cellphone), //수령자 전화번호1
            "mobileReceiver" => add_hyphen($r_cellphone), //수령자 전화번호2
            "nameReceiver" => $r_customer_name, //수령자: 2글자 이상의 수령자 이름
            "zipCode" => $r_zipcode, //(구/신)우편번호: 5자리 이상의 구 우편번호 또는 신 우편번호
            "address" => $r_address, //(지번/도로명)주소: 주소정재 API를 이용하기 때문에 가급적 신주소로 요청하시기 바랍니다.
            "address2" => $r_address2, //나머지 주소: 아파트명, 동, 호수 등 나머지 주소
            "settleKind" => "s", //결제방식: "a" => (무통장->주문접수만), "s" => (캐쉬결제->주문접수+캐쉬결제)
            "bankSender" => $r_customer_name, //입금자명: settleKind 파라미터가 "a" (무통장)인경우 필수
            "doubleCheck" => 1, //구매동의여부(1-동의, 0-미동의) * 미동의시 진행안됨
            "memo" => $r_memo, //배송메세지: 최대 100글자
            "orderItem" => json_decode($r_orderItem, 1) // 주문상품 정보
        );

        $data = post_jb($headers, 'data='.json_encode($post_data));

        if($data["orderResult"]["success"] == "1"){ // success
            $order_step = ($r_order_num == $data["orderResult"]["oaApiOrdno"])? "2" : "9"; // 9-거래번호 다름(정글북에 문의)

            $sql = "
					UPDATE tb_item_payment_log_jbook SET
						orderResult = '".$data["orderResult"]["success"]."',
						ordNo = '".$data["orderResult"]["ordNo"]."',
						oaType = '".$data["orderResult"]["oaType"]."',
						oaApiOrdno = '".$data["orderResult"]["oaApiOrdno"]."',
						nameReceiver = '".$data["orderResult"]["nameReceiver"]."',
						zipCode = '".$data["orderResult"]["zipCode"]."',
						address = '".$data["orderResult"]["address"]."',
						orderGoods = '".$data["orderResult"]["orderGoods"]."',
						settleKind = '".$data["orderResult"]["settleKind"]."',
						settlePrice = '".$data["orderResult"]["settlePrice"]."',
						totalGoodsPrice = '".$data["orderResult"]["totalGoodsPrice"]."',
						delivery = '".$data["orderResult"]["delivery"]."',
						memo = '".$data["orderResult"]["memo"]."',
						payResult = '".$data["payResult"]["success"]."',
						payResultMsg = '".$data["payResult"]["payResultMsg"]."',
						cashBalance = '".$data["orderResult"]["cashBalance"]."',
						payAmount = '".$data["orderResult"]["payAmount"]."',
						order_step = '".$order_step."',
						update_dt = NOW()
					WHERE is_delete = '2'
						AND order_num = '".$r_order_num."'
				";
            $result = mysqli_query($connection, $sql);
            if($result){
                $sql = "
						UPDATE tb_item_payment_log SET
							jbOrdNo = '".$data["orderResult"]["ordNo"]."',
							update_dt = NOW()
						WHERE order_num = '".$data["orderResult"]["oaApiOrdno"]."'
					";
                $result = mysqli_query($connection, $sql);
                if($result){
                    $return_data = array("code" => "000000", "data" => "OK");
                }else{
                    $return_data = array("code" => "000002", "data" => "결제내역 반영에 실패했습니다.");
                }
            }else{
                $return_data = array("code" => "000003", "data" => "결제내역 상품 변경에 실패했습니다.");
            }
        }else{ // error
            $sql = "
					UPDATE tb_item_payment_log_jbook SET
						errCode = '".$data["orderResult"]["errCode"]."',
						parameter = '".json_encode($data["orderResult"]["parameter"])."',
						errMsg = '".$err_code[$data["orderResult"]["errCode"]]."',
						payResult = '".$data["payResult"]["success"]."',
						payResultMsg = '".$data["payResult"]["payResultMsg"]."',
						cashBalance = '".$data["payResult"]["cashBalance"]."',
						payAmount = '".$data["payResult"]["payAmount"]."',
						order_step = '2',
						update_dt = NOW()
					WHERE is_delete = '2'
						AND order_num = '".$r_order_num."'
				";
            $result = mysqli_query($connection, $sql);
            if($result){
                $return_data = array("code" => "000101", "data" => $err_code[$data["orderResult"]["errCode"]]."(".$data["orderResult"]["errCode"].", ".print_r($data["orderResult"]["parameter"], 1).")");
            }else{
                $return_data = array("code" => "000102", "data" => $err_code[$data["orderResult"]["errCode"]]."(".$data["orderResult"]["errCode"].", ".print_r($data["orderResult"]["parameter"], 1).")");
            }
        }
    }else if($r_mode == "set_update_item_order_call"){
        $r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "";

        if($r_order_num != ""){
            $sql = "
					SELECT *
					FROM tb_item_payment_log
					WHERE order_num = '".$r_order_num."'
				";
            $result = mysqli_query($connection, $sql);
            $row = mysqli_fetch_assoc($result);
            $payment_cnt = mysqli_num_rows($result);

            if($payment_cnt == 1){
                // 정글북 여부(데이터 있으면 처리)
                $sql = "
						SELECT *
						FROM tb_item_payment_log_jbook
						WHERE is_delete = '2'
							AND order_num = '".$row["order_num"]."'
					";
                $result2 = mysqli_query($connection, $sql);
                $cnt = mysqli_num_rows($result2);
                if($cnt > 0){
                    $row2 = mysqli_fetch_assoc($result2);

                    $shipping_name = $row["shipping_name"];
                    if($shipping_name == ""){
                        $guest_name = ($row["guest_info"] != "" && strpos($row["guest_info"], ',') !== false)? explode(',', $row["guest_info"]) : array($row["cellphone"]);
                        $shipping_name = $guest_name[0];
                    }
                    if($row["shipping_zipcode"] != ""){
                        $shipping_zipcode = $row["shipping_zipcode"];
                        $shipping_addr = $row["shipping_addr"];
                        $shipping_addr2 = $row["shipping_addr2"];
                    }else{
                        $shipping_addr_list = ($row["shipping_addr"] != "" && strpos($row["shipping_addr"], '|') !== false)? explode('|', $row["shipping_addr"]) : "";
                        $shipping_zipcode = $shipping_addr_list[0];
                        $shipping_addr = $shipping_addr_list[1];
                        $shipping_addr2 = "1";
                    }

                    $post_data = array(
                        "oaType" => "api",
                        "oaOrderNo" => $row["order_num"],
                        "phoneReceiver" => add_hyphen($row["cellphone"]), //수령자 전화번호1
                        "mobileReceiver" => add_hyphen($row["cellphone"]), //수령자 전화번호2
                        "nameReceiver" => $shipping_name, //수령자: 2글자 이상의 수령자 이름
                        "zipCode" => $shipping_zipcode, //(구/신)우편번호: 5자리 이상의 구 우편번호 또는 신 우편번호
                        "address" => $shipping_addr, //(지번/도로명)주소: 주소정재 API를 이용하기 때문에 가급적 신주소로 요청하시기 바랍니다.
                        "address2" => $shipping_addr2, //나머지 주소: 아파트명, 동, 호수 등 나머지 주소
                        "settleKind" => "s", //결제방식: "a" => (무통장->주문접수만), "s" => (캐쉬결제->주문접수+캐쉬결제)
                        "bankSender" => $shipping_name, //입금자명: settleKind 파라미터가 "a" (무통장)인경우 필수
                        "doubleCheck" => 1, //구매동의여부(1-동의, 0-미동의) * 미동의시 진행안됨
                        "memo" => $row["shipping_memo"], //배송메세지: 최대 100글자
                        "orderItem" => json_decode($row2["orderItem"], 1) // 주문상품 정보
                    );

                    $data = post_jb($headers, 'data='.json_encode($post_data));

                    if($data["orderResult"]["success"] == "1"){ // success
                        $order_step = ($row["order_num"] == $data["orderResult"]["oaApiOrdno"])? "2" : "9"; // 9-거래번호 다름(정글북에 문의)
                        $cash_balance = ($data["orderResult"]["cashBalance"] != "")? $data["orderResult"]["cashBalance"] : "0";
                        $payAmount = ($data["orderResult"]["payAmount"] != "")? $data["orderResult"]["payAmount"] : "0";

                        $sql = "
								UPDATE tb_item_payment_log_jbook SET
									orderResult = '".$data["orderResult"]["success"]."',
									ordNo = '".$data["orderResult"]["ordNo"]."',
									oaType = '".$data["orderResult"]["oaType"]."',
									oaApiOrdno = '".$data["orderResult"]["oaApiOrdno"]."',
									nameReceiver = '".$data["orderResult"]["nameReceiver"]."',
									zipCode = '".$data["orderResult"]["zipCode"]."',
									address = '".$data["orderResult"]["address"]."',
									orderGoods = '".$data["orderResult"]["orderGoods"]."',
									settleKind = '".$data["orderResult"]["settleKind"]."',
									settlePrice = '".$data["orderResult"]["settlePrice"]."',
									totalGoodsPrice = '".$data["orderResult"]["totalGoodsPrice"]."',
									delivery = '".$data["orderResult"]["delivery"]."',
									memo = '".$data["orderResult"]["memo"]."',
									payResult = '".$data["payResult"]["success"]."',
									payResultMsg = '".$data["payResult"]["payResultMsg"]."',
									cashBalance = '".$cash_balance."',
									payAmount = '".$payAmount."',
									order_step = '".$order_step."',
									update_dt = NOW()
								WHERE is_delete = '2'
									AND order_num = '".$row["order_num"]."'
							";

                        $result3 = mysqli_query($connection, $sql);
                        if($result3){
                            $sql = "
									UPDATE tb_item_payment_log SET
										jbOrdNo = '".$data["orderResult"]["ordNo"]."',
										update_dt = NOW()
									WHERE order_num = '".$data["orderResult"]["oaApiOrdno"]."'
								";
                            $result3 = mysqli_query($connection, $sql);
                            if($result3){
                                $return_data = array("code" => "000000", "data" => $data["orderResult"]["ordNo"]);
                            }else{
                                $return_data = array("code" => "000005", "data" => "등록성공 - 주문한 상품에 정글북 거래번호 등록에 실패했습니다.");
                            }
                        }else{
                            $return_data = array("code" => "000004", "data" => "등록성공 - 정글북 데이터 변경에 실패했습니다.");
                        }
                    }else{
                        $cash_balance = ($data["payResult"]["cashBalance"] != "")? $data["payResult"]["cashBalance"] : "0";
                        $payAmount = ($data["payResult"]["payAmount"] != "")? $data["payResult"]["payAmount"] : "0";

                        $sql = "
								UPDATE tb_item_payment_log_jbook SET
									orderResult = '".$data["orderResult"]["success"]."',
									errCode = '".$data["orderResult"]["errCode"]."',
									parameter = '".json_encode($data["orderResult"]["parameter"])."',
									errMsg = '".$err_code[$data["orderResult"]["errCode"]]."',
									payResult = '".$data["payResult"]["success"]."',
									payResultMsg = '".$data["payResult"]["payResultMsg"]."',
									cashBalance = '".$cash_balance."',
									payAmount = '".$payAmount."',
									order_step = '2',
									update_dt = NOW()
								WHERE is_delete = '2'
									AND order_num = '".$row["order_num"]."'
							";
                        $result3 = mysqli_query($connection, $sql);
                        if($result3){
                            $return_data = array("code" => "000000", "data" => ""); // 등록실패 - 주문번호 없음
                        }else{
                            $return_data = array("code" => "000005", "data" => "등록실패 - 정글북 데이터 변경에 실패했습니다.");
                        }
                    }
                }else{
                    $return_data = array("code" => "000003", "data" => "주문한 상품에 정글북 상품이 없습니다.");
                }
            }else{
                $return_data = array("code" => "000002", "data" => "상품 주문이 없습니다.");
            }
        }else{
            $return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
        }
    }else{
        $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
    }
}else{
    $return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
}

echo json_encode($return_data, JSON_UNESCAPED_UNICODE);

function get_jb($headers, $menu, $option = null){
    $P_REQ_URL  = "http://api.junglebook.co.kr/".$menu;
    $P_REQ_URL .= ($option === null || $option == "")? "" : "/".$option;
    $tmp_arr = "";
    $tmp_err = "";

    $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

    curl_setopt($ch, CURLOPT_URL, $P_REQ_URL);			// URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		// 결과를 노출(0-print, 1-변수저장)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https ssl 인증서 확인 하지 않도록 함
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		// header 정보 전달

    $response = curl_exec($ch);

    $tmp_arr = json_decode($response, true);			//결과값 확인하기

    $tmp_err  = curl_errno($ch);						//마지막 에러 번호 출력
    $tmp_err .= ($tmp_err != 0)? curl_error($ch) : "";	//현재 세션의 마지막 에러 출력
    $tmp_err .= ($tmp_err != 0)? print_r(curl_getinfo($ch), 1) : ""; //마지막 http 전송 정보 출력
    curl_close($ch);

    if($tmp_err == 0){
        return array("code" => "000000", "data" => $tmp_arr);
    }else{
        return array("code" => "000001", "data" => $tmp_err);
    }
}

function post_jb($headers, $post_data){
    $P_REQ_URL = "http://api.junglebook.co.kr/order";		// live
    //$P_REQ_URL = "http://api.junglebook.co.kr/order/test";	// test

    $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
    curl_setopt($ch, CURLOPT_URL, $P_REQ_URL);			// URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		// 결과를 노출(0-print, 1-변수저장)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https ssl 인증서 확인 하지 않도록 함
    curl_setopt($ch, CURLOPT_SSLVERSION,3);				// 주소가 https가 아니라면 지울것
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		// header 정보 전달

    curl_setopt($ch, CURLOPT_POST, 1);					// 0이 default 값이며 POST 통신을 위해 1로 설정해야 함
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);	// POST로 보낼 데이터 지정하기

    $response = curl_exec($ch);
    $tmp_arr = json_decode($response, true); //결과값 확인하기

    $tmp_err = curl_errno($ch);//마지막 에러 번호 출력
    $tmp_err .= curl_error($ch);//현재 세션의 마지막 에러 출력
    curl_close($ch);

    return ($tmp_err == 0)? $tmp_arr : $tmp_err;
}

?>