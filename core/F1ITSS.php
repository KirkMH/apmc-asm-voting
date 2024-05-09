<?php
class F1ITSS {
	public static function crop_email($email) {
		$first = substr($email, 0, 2);
		$domain = strchr($email, "@");
		$mid_c = substr($email, 2, strlen($email) - strlen($domain) - 2);
		$last = substr($mid_c, strlen($mid_c) - 2) . $domain;
		$mid = str_repeat("*", strlen($mid_c) - 2);
		return $first.$mid.$last;
	}

    public static function randomGen($count){
        $randomCode = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $charArray = str_split($chars);
        for($index = 0; $index < $count; $index++){
            $randChar = array_rand($charArray);
            $randomCode .= $charArray[$randChar];
        }
        return $randomCode;
    }

    public static function randomNum($count){
        $randomCode = "";
        $chars = "0123456789";
        $charArray = str_split($chars);
        for($index = 0; $index < $count; $index++){
            $randChar = array_rand($charArray);
            if ($index > 0)
                $randomCode .= " ";
            $randomCode .= $charArray[$randChar];
        }
        return $randomCode;
    }
}
?>