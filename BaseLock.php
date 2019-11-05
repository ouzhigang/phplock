<?php

namespace phplock;

abstract class BaseLock {
	
	public static abstract function lock($k);
	public static abstract function unlock($k, $v);
	
	protected static function randStr($type, $length) {
		$str1 = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
		$str2 = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		$str3 = "0,1,2,3,4,5,6,7,8,9";
	
		if ($type == 1) {
			$content = $str1;
		}
		else if ($type == 2) {
			$content = $str2;
		}
		else if ($type == 3) {
			$content = $str3;
		}
		else if ($type == 4) {
			$content = $str1 . "," . $str2;
		}
		else if ($type == 5) {
			$content = $str2 . "," . $str3;
		}
		else if ($type == 6) {
			$content = $str1 . "," . $str3;
		}
		else if ($type == 7) {
			$content = $str1 . "," . $str2 . "," . $str3;
		}
	
		$strs = explode(",", $content);
		$output = "";
		for($i = 0; $i < $length; $i++) { 
			do {
				$r = rand(0, strlen($content));
			}
			while(empty($strs[$r]));
			$output .= $strs[$r];
		}
	
		return $output;
	}
	
	protected static function msectime() {
    	list($msec, $sec) = explode(' ', microtime());
    	$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    	return $msectime;
    }
    
}
