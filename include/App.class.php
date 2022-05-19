<?php
class App {
	public $app = 0;

	function is_app () {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->app = 0;
		if (user_agent) {
			$and_idx = strpos(strtolower($user_agent), "app_gopet_partner_and");
            //$and_one_idx = strpos(strtolower($user_agent), "app_gopet_partner_and_one_store");
            $ios_idx = strpos(strtolower($user_agent), "app_gopet_partner_ios");

		        if ($and_idx > -1) {
        		        $this->app = 1;
	        	}else if ($ios_idx > -1) {
					$this->app = 2;
				}
		}
		return $this->app;
	}
}
?>
