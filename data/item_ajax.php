<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$data = array();
$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
$mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

if($mode){
    // 카테고리 리스트 가져오기
    if($mode == "get_last_category"){
        $category = ($_POST["category"] && $_POST["category"] != "")? $_POST["category"] : "";

        if($category){
            $sql = "
                SELECT * FROM (
                    SELECT a.*, 
                            (SELECT COUNT(*) FROM tb_item_list WHERE FIND_IN_SET(a.ic_seq, ic_seq)
                            AND is_shop = '1' AND is_delete = '1') cnt
                    FROM tb_item_category a WHERE a.parent_seq = '".$category."'
                ) main
                WHERE main.cnt > 0
            ";
            $array = sql_fetch_array($sql);
            $data[] = $array;
            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "000008", "data" => "error");
        }

    // 카테고리별 상품리스트 가져오기
    }else if($mode == 'get_item_list') {
        $category = ($_POST["category"] && $_POST["category"] != "") ? $_POST["category"] : "";
        $limit = ($_POST["limit"] && $_POST["limit"] != "") ? $_POST["limit"] : 0;

        if ($category) {
            // 총 개수 구하기
            $cnt_sql = "
                SELECT * FROM tb_item_list WHERE FIND_IN_SET(" . $category . ", ic_seq)
                AND is_shop = '1' AND is_delete = '1'
            ";
            $cnt_result = mysqli_query($connection, $cnt_sql);
            $item_cnt = mysqli_num_rows($cnt_result);

            // 리스트 구하기
            $sql = "
                SELECT * FROM tb_item_list a
                LEFT JOIN (
                    SELECT product_no p_no, AVG(rating) rating_avg, COUNT(product_no) AS rating_cnt 
                    FROM tb_item_review WHERE rating IS NOT NULL AND is_delete = '2' GROUP BY product_no
                ) b ON b.p_no = a.product_no
                WHERE FIND_IN_SET(" . $category . ", a.ic_seq)
                AND a.is_shop = '1' AND a.is_delete = '1' ORDER BY a.is_soldout, a.reg_dt DESC
                LIMIT " . $limit . ", 20
            ";
            $array = sql_fetch_array($sql);

            foreach ($array as $rs) {
                // 메인사진 구하기
                $file_sql = "SELECT * FROM tb_file WHERE is_delete = '1' AND f_seq IN ({$rs['product_img']}) LIMIT 1";
                $file_result = sql_query($file_sql);
                $file_row = sql_fetch($file_result);
                $photo = ($file_row['file_path']) ? "https://image.banjjakpet.com" . img_link_change($file_row['file_path']) : $rs['goodsRepImage'];
                $data['photo'][] = $photo;
            }
            $data['item'] = $array;
            $return_data = array("code" => "000000", "data" => $data, "cnt" => $item_cnt);
        } else {
            $return_data = array("code" => "000008", "data" => "error");
        }

    // 이벤트페이지 상품가져오기
    }else if($mode == "get_event_item"){
        $r_product_in = ($_POST["product_in"] && $_POST["product_in"] != "")? $_POST["product_in"] : "";

        if($r_product_in != ""){
            $sql = "
                SELECT * FROM tb_item_list a
                LEFT JOIN tb_file b ON b.f_seq = a.product_img
                LEFT JOIN (
                    SELECT product_no p_no, AVG(rating) rating_avg, COUNT(product_no) AS rating_cnt 
                    FROM tb_item_review WHERE rating IS NOT NULL AND is_delete = '2' GROUP BY product_no
                ) c ON c.p_no = a.product_no
                WHERE a.product_no IN (".$r_product_in.")
                AND a.is_delete = '1' AND a.is_view = '1'
                ORDER BY FIELD(a.product_no,".$r_product_in.")
            ";
            $array = sql_fetch_array($sql);
            $data[] = $array;

            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "000701", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 일반옵션 상품 옵션 가져오기
    }else if($mode == "get_item_option"){
        $r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
        $r_io_seq		   = ($_POST["io_seq"] && $_POST["io_seq"] != "")? $_POST["io_seq"] : "";
        $where_qy = "";

        if($r_il_seq != ""){
            $where_qy .= " AND il_seq = '".$r_il_seq."' ";
        }
        if($r_io_seq != ""){
            $where_qy .= " AND io_seq = '".$r_io_seq."' ";
        }

        if($where_qy != ""){
            $sql = "
					SELECT *
					FROM tb_item_option
					WHERE is_delete != '2'
						".$where_qy."
				";
            $array = sql_fetch_array($sql);
            $data[] = $array;

            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "000701", "data" => "중요 데이터가 누락되었습니다.");
        }
    // 그룹옵션 상품 옵션 가져오기
    }else if($mode == "get_item_group_option_list"){
        $r_igo_seq = ($_POST["igo_seq"] && $_POST["igo_seq"] != "")? $_POST["igo_seq"] : "";
        $r_product_no = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
        $where_qy = "";

        if($r_product_no != ""){
            $where_qy .= " AND product_no = '".$r_product_no."' ";
        }
        if($r_igo_seq != ""){
            $where_qy .= " AND igo_seq = '".$r_igo_seq."' ";
        }

        if($where_qy != ""){
            $sql = "
					SELECT *
					FROM tb_item_group_option
					WHERE is_delete = '2'
						".$where_qy."
				";
            $array = sql_fetch_array($sql);

            foreach($array as $rs) {
                // select option
                $detail_sql = "SELECT *
					FROM tb_item_group_option_detail
					WHERE is_delete = '2'
					AND igo_seq = '".$rs['igo_seq']."'
				";
                $detail_array = sql_fetch_array($detail_sql);
                $data['detail'][] = $detail_array;
            }
            $data['group'] = $array;

            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "006301", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 그룹옵션 리스트 구하기
    }else if($mode == "get_item_group_option_detail_list"){
        $r_igod_seq = ($_POST["igod_seq"] && $_POST["igod_seq"] != "")? $_POST["igod_seq"] : "";
        $r_igo_seq = ($_POST["igo_seq"] && $_POST["igo_seq"] != "")? $_POST["igo_seq"] : "";
        $r_product_no = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
        $where_qy = "";

        if($r_product_no != ""){
            $where_qy .= " AND product_no = '".$r_product_no."' ";
        }
        if($r_igo_seq != ""){
            $where_qy .= " AND igo_seq = '".$r_igo_seq."' ";
        }
        if($r_igod_seq != ""){
            $where_qy .= " AND igod_seq = '".$r_igod_seq."' ";
        }

        if($where_qy != ""){
            $sql = "
					SELECT *
					FROM tb_item_group_option_detail
					WHERE is_delete = '2'
						".$where_qy."
				";
            $array = sql_fetch_array($sql);
            $data[] = $array;

            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "006701", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 장바구니 개수 가져오기
    }else if($mode == "get_cart_cnt"){
        $r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

        if($r_customer_id != ''){
            $where_qy = "AND customer_id = '".$r_customer_id."' ";
        }else{
            $where_qy = "AND session_id = '".$sessionid."' ";
        }

        $sql = "
					SELECT *
					FROM tb_item_cart
					WHERE is_delete = '1'
					AND is_shop = '1'
                    ".$where_qy."
                    AND direct_chk = 0
				";
        $result = mysqli_query($connection, $sql);
        $cnt = mysqli_num_rows($result);

        $return_data = array("code" => "000000", "data" => $cnt);

    // 장바구니 리스트 가져오기
    }else if($mode == "get_cart"){
        $r_no			   = ($_POST["no"] && $_POST["no"] != "")? $_POST["no"] : "";
        $r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
        $r_is_session	   = ($_POST["is_session"] && $_POST["is_session"] != "")? $_POST["is_session"] : "";
        $r_direct_chk	   = ($_POST["direct_chk"] && $_POST["direct_chk"] != "")? $_POST["direct_chk"] : "0";
        $where_qy = "";

        if($r_no != ""){
            $where_qy .= " AND order_num = '".$r_no."' ";
        }
//        if($r_is_session != "" && $sessionid != "" && $r_customer_id == ""){
//            $where_qy .= " AND session_id = '".$sessionid."' ";
//        }
        // 가입자는 아이디 기준으로 장바구니 계속 살리기
        if($r_customer_id != ""){
            $where_qy .= " AND (customer_id = '".$r_customer_id."' OR session_id = '".$sessionid."')";
        }else{
            $where_qy .= " AND session_id = '".$sessionid."' ";
        }
        if($r_direct_chk != ""){
            $where_qy .= " AND direct_chk = '".$r_direct_chk."' ";
        }

        if($where_qy != ""){
            $sql = "
					SELECT *
					FROM tb_item_cart
					WHERE is_delete = '1'
					AND is_shop = '1'
						".$where_qy."
				";
            $array = sql_fetch_array($sql);
            $data[] = $array;

            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "001701", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 상품정보 가져오기
    }else if($mode == "get_item"){
        $r_il_seq		   = ($_POST["il_seq"] && $_POST["il_seq"] != "")? $_POST["il_seq"] : "";
        $r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
        $where_qy = "";

        if($r_il_seq != "" || $r_product_no != ""){
            if($r_il_seq != ""){
                $where_qy .= " AND il_seq = '".$r_il_seq."' ";
            }
            if($r_product_no != ""){
                $where_qy .= " AND product_no = '".$r_product_no."' ";
            }

            $sql = "
					SELECT *
					FROM tb_item_list
					WHERE is_delete != '2' ".$where_qy."
				";
            $array = sql_fetch_array($sql);

            foreach($array as $rs) {
                // 메인사진 구하기
                $file_sql = "SELECT * FROM tb_file WHERE is_delete = '1' AND f_seq IN ({$rs['product_img']}) LIMIT 1";
                $file_result = sql_query($file_sql);
                $file_row = sql_fetch($file_result);
                $photo = ($file_row['file_path']) ? "https://image.banjjakpet.com" . img_link_change($file_row['file_path']) : $rs['goodsRepImage'];
                $data['photo'][] = $photo;
            }
            $data['item'] = $array;

            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "000201", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 장바구니 상품 삭제
    }else if($mode == "set_delete_item_cart"){
        $r_ic_seq		   = ($_POST["ic_seq"] && $_POST["ic_seq"] != "")? $_POST["ic_seq"] : "";
        $r_order_num	   = (isset($_POST["order_num"]) && $_POST["order_num"] &&  $_POST["order_num"] != "")? $_POST["order_num"] : "";
        $r_user_id		   = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "비회원";
        $r_delete_txt	   = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";
        $where_qy = "";

        if($r_ic_seq != "" || $r_order_num != ""){
            if($r_ic_seq != ""){
                $where_qy = " AND ic_seq = '".$r_ic_seq."' ";
            }
            if($r_order_num != ""){
                $where_qy = " AND order_num = '".$r_order_num."' ";
            }

            $sql = "
					UPDATE tb_item_cart SET
						`is_delete` = '2', 
						`delete_txt` = '".$r_user_id."|".$r_delete_txt."', 
						`delete_dt` = NOW()
					WHERE is_delete = '1' ".$where_qy."
				";
            $result = mysqli_query($connection,$sql);

            if($result){
                $return_data = array("code" => "000000", "data" => "OK");
            }else{
                $return_data = array("code" => "001601", "data" => "상품 카트 삭제에 실패했습니다.");
            }
        }else{
            $return_data = array("code" => "001602", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 장바구니에 새 제품 등록
    }else if($mode == "set_insert_cart"){
        $r_total_price	   = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : 0;
        $r_user_id 		   = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
        $r_cart_data	   = ($_POST["cart_data"] && $_POST["cart_data"] != "")? $_POST["cart_data"] : "";
        $r_product_no	   = ($_POST["product_no"] && $_POST["product_no"] != "")? $_POST["product_no"] : "";
        $r_is_shop		   = (isset($_POST["is_shop"]) && $_POST["is_shop"] && $_POST["is_shop"] != "")? $_POST["is_shop"] : "1"; // 전문몰상품여부(1-전문몰,2-반짝몰)
        $r_is_supply	   = ($_POST["is_supply"] && $_POST["is_supply"] != "")? $_POST["is_supply"] : "2"; // 외주판매여부(1-외주,2-자체)
        $r_ip_seq		   = ($_POST["ip_seq"] && $_POST["ip_seq"] != "")? $_POST["ip_seq"] : "";
        $r_supplier		   = ($_POST["supplier"] && $_POST["supplier"] != "")? $_POST["supplier"] : "";
        $r_direct_chk      = ($_POST["direct_chk"] && $_POST["direct_chk"] != "")? $_POST["direct_chk"] : "0";
        $order_num = "I".STRTOTIME(DATE("Y-m-d H:i:s")).str_pad(rand(0,99),"2","0",STR_PAD_LEFT);

        $sql = "
				INSERT INTO tb_item_cart (
					`session_id`, `product_no`, `order_num`, `customer_id`, `cart_price`, 
					`cart_data`, `is_shop`, `is_supply`, `ip_seq`, `supplier`, `direct_chk`, 
					`reg_dt`
				) VALUES (
					'".$sessionid."', '".$r_product_no."', '".$order_num."', '".$r_user_id."', ".$r_total_price.", 
					'".addslashes($r_cart_data)."', '".$r_is_shop."', '".$r_is_supply ."', '".$r_ip_seq."', '".$r_supplier."', '".$r_direct_chk."',
					NOW()
				)
			";
        $result = mysqli_query($connection,$sql);

        if($result){
            $return_data = array("code" => "000000", "data" => $order_num);
        }else{
            $return_data = array("code" => "001501", "data" => $sql);
        }

    // 장바구니에 담겨있는 제품 수량 추가하기
    }else if($mode == "set_update_cart") {
        $r_ic_seq = ($_POST["ic_seq"] && $_POST["ic_seq"] != "") ? $_POST["ic_seq"] : "";
        $r_is_session = ($_POST["is_session"] && $_POST["is_session"] != "") ? $_POST["is_session"] : "";
        $r_customer_id = (isset($_POST["customer_id"]) && $_POST["customer_id"] && $_POST["customer_id"] != "") ? $_POST["customer_id"] : "";
        $r_cart_data = ($_POST["cart_data"] && $_POST["cart_data"] != "") ? $_POST["cart_data"] : "";
        $r_cart_price = ($_POST["cart_price"] && $_POST["cart_price"] != "") ? $_POST["cart_price"] : "";
        $r_order_num = (isset($_POST["order_num"]) && $_POST["order_num"] && $_POST["order_num"] != "") ? $_POST["order_num"] : "";
        $r_cart_update = ($_POST["cart_update"] && $_POST["cart_update"] != "") ? $_POST["cart_update"] : "";
        $where_qy = "";
        $update_qy = "";

        if ($r_ic_seq != "") {
            $where_qy .= " AND ic_seq = '" . $r_ic_seq . "' ";
        }
//        if($r_is_session != "" && $sessionid != ""){
//            $where_qy .= " AND session_id = '".$sessionid."' ";
//        }


        if ($r_cart_data != "") {
            $update_qy .= " cart_data = '" . addslashes($r_cart_data) . "', ";
        }
        if ($r_cart_price != "") {
            $update_qy .= " cart_price = '" . $r_cart_price . "', ";
        }
        if ($r_order_num != "") {
            $update_qy .= " order_num = '" . $r_order_num . "', ";
        }
        if ($r_cart_update != "" && $r_ic_seq != "" && $r_is_session != "" && $r_customer_id != "") { // 세션값 기준으로 회원 업데이트처리
            $update_qy .= " customer_id = '" . $r_customer_id . "', ";
            $where_qy .= " AND customer_id = '' ";
        } else {
            if ($r_customer_id != "") {
                $where_qy .= " AND (customer_id = '" . $r_customer_id . "' OR session_id = '" . $sessionid . "')";
            } else {
                $where_qy .= " AND session_id = '" . $sessionid . "' ";
            }
        }

        if ($where_qy != "" && $update_qy != "") {
            $sql = "
					UPDATE tb_item_cart SET
						" . $update_qy . "
						update_dt = NOW()
					WHERE is_delete = '1'
						" . $where_qy . "
						
				";
            $result = mysqli_query($connection, $sql);
            if ($result) {
                $return_data = array("code" => "000000", "data" => $sql);
            } else {
                $return_data = array("code" => "005102", "data" => "상품 카트 수정에 실패했습니다.");
            }
        } else {
            $return_data = array("code" => "005101", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 장바구니 상품정보 수정시 리스트 가져오기
    }else if($mode == "get_modify_list"){
        $r_ic_seq = ($_POST["ic_seq"] && $_POST["ic_seq"] != "")? $_POST["ic_seq"] : "";

        $sql = "
            SELECT * FROM tb_item_cart a
            LEFT JOIN tb_item_list b ON b.product_no = a.product_no
            WHERE a.ic_seq = '".$r_ic_seq."' AND b.is_delete = '1'
        ";
        $result = sql_query($sql);
        $row = sql_fetch($result);
        $return_data = array("code" => "000000", "data" => $row);

    // 위탁업체 리스트 가져오기
    }else if($mode == "get_partner_list") {

        $sql = "
            SELECT * FROM tb_item_partner WHERE is_delete = '1' and not ip_seq = '3'
        ";
        $array = sql_fetch_array($sql);
        $data[] = $array;
        $return_data = array("code" => "000000", "data" => $data);

    // 첫결제 여부 판단하기
    }else if($mode == "get_first_payment"){
        $r_customer_id = (isset($_POST["customer_id"]) && $_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

        if($r_customer_id != ""){
            $sql = "
                SELECT *
					FROM tb_item_payment_log
					WHERE 1 = 1
					AND (customer_id = '".$r_customer_id."' and pay_type = '1' AND receipt_id IS NOT NULL AND pay_status IN ('2', '3', '4', '5', '6') AND is_delete = '1')
					OR (customer_id = '".$r_customer_id."' and pay_type = '2' AND is_cancel = '1' AND is_return = '1' AND is_delete = '1')
					AND reg_dt > '2021-01-01 00:00:00'
				";
            $result = mysqli_query($connection,$sql);
            $cnt = mysqli_num_rows($result);

            $return_data = array("code" => "000000", "data" => array("cnt" => $cnt));
        }else{
            $return_data = array("code" => "005501", "data" => "비회원");
        }

    // 배송지 목록 가져오기
    }else if($mode == "get_customer_addr_list"){
        $r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

        $sql = "
				SELECT *
				FROM tb_customer_addr
				WHERE is_delete != '2'
					AND customer_id = '".$r_customer_id."'
			";
        $result = mysqli_query($connection,$sql);
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        $return_data = array("code" => "000000", "data" => $data);

    // 선택한 배송지 정보 가져오기
    }else if($mode == "get_customer_addr"){
        $r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
        $r_ca_seq		   = ($_POST["ca_seq"] && $_POST["ca_seq"] != "")? $_POST["ca_seq"] : "";

        $sql = "
				SELECT *
				FROM tb_customer_addr
				WHERE is_delete != '2'
					AND customer_id = '".$r_customer_id."'
					AND ca_seq = '".$r_ca_seq."'
			";
        $result = mysqli_query($connection,$sql);
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        $return_data = array("code" => "000000", "data" => $data);

    // 배송지 추가하기
    }else if($mode == "set_insert_customer_addr"){
        $r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
        $r_addr_name	   = ($_POST["addr_name"] && $_POST["addr_name"] != "")? $_POST["addr_name"] : "";
        $r_road_addr	   = ($_POST["road_addr"] && $_POST["road_addr"] != "")? $_POST["road_addr"] : "";
        $r_jibun_addr	   = ($_POST["jibun_addr"] && $_POST["jibun_addr"] != "")? $_POST["jibun_addr"] : "";
        $r_extra_addr	   = ($_POST["extra_addr"] && $_POST["extra_addr"] != "")? $_POST["extra_addr"] : "";
        $r_detail_addr	   = ($_POST["detail_addr"] && $_POST["detail_addr"] != "")? $_POST["detail_addr"] : "";
        $r_zipcode		   = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : "1";

        $sql = "
				INSERT INTO tb_customer_addr (
					`customer_id`, `addr_name`, `zipcode`, `road_addr`, `jibun_addr`, 
					`extra_addr`, `detail_addr`, reg_dt
				) VALUES (
					'".$r_customer_id."', '".addslashes($r_addr_name)."', '".$r_zipcode."', '".addslashes($r_road_addr)."', '".addslashes($r_jibun_addr)."', 
					'".addslashes($r_extra_addr)."', '".addslashes($r_detail_addr)."', NOW()
				)
			";
        $result = mysqli_query($connection,$sql);

        if($result){
            $customer_addr_seq = mysqli_insert_id();
            $return_data = array("code" => "000000", "data" => $customer_addr_seq);
        }else{
            $return_data = array("code" => "020301", "data" => "배송지 생성에 실패했습니다.");
        }

    // 보유 포인트 가져오기
    }else if($mode == "get_customer_point"){
//        include "../include/Point.class.php"; // global에 있음
        $r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

        if($r_customer_id != ""){
            $point = new Point;
            $result = $point->select_point($r_customer_id);
            if ($result == true) {
                $data = $point->get_point();
            } else {
                $data = "0";
            }
            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "020601", "data" => "중요 데이터가 누락되었습니다.");
        }

    // 결제 상세내역 product 가져오기
    }else if($mode == "get_item_payment_log_product"){
        $r_order_num	   = (isset($_POST["order_num"]) && $_POST["order_num"] &&  $_POST["order_num"] != "")? $_POST["order_num"] : "";
        $r_iplp_seq		   = (isset($_POST["iplp_seq"]) && $_POST["iplp_seq"] && $_POST["iplp_seq"] != "")? $_POST["iplp_seq"] : "";
        $where_qy = "";

        if($r_order_num != ""){	// 주문번호
            $where_qy .= " AND a.order_num = '".$r_order_num."' ";
        }

        if($r_iplp_seq != ""){	// pk
            $where_qy .= " AND a.iplp_seq = '".$r_iplp_seq."' ";
        }

        if($where_qy != ""){	// is_delete - 삭제여부(1-미삭제, 2-삭제)
            $sql = "
                    SELECT a.*, b.product_name, c.file_path, b.goodsRepImage FROM tb_item_payment_log_product a
                    LEFT JOIN tb_item_list b ON b.product_no = a.product_no
                    LEFT JOIN tb_file c ON c.f_seq = b.product_img
					WHERE a.is_delete = '1'
						".$where_qy."
				";
            $result = mysqli_query($connection,$sql);
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }

            $return_data = array("code" => "000000", "data" => $data, "sql" => $sql);
        }else{
            $return_data = array("code" => "007102", "data" => "중요 데이터가 누락되었습니다.");
        }
    }else{
        $return_data = array("code" => "000007", "data" => "중요 데이터 누락".$mode);
    }

}else{
    $return_data = array("code" => "000009", "data" => "중요 데이터 누락");
}
echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>
