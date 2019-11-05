<?php

namespace phplock;

require_once(dirname(__FILE__) . "/BaseLock.php");

class RedisLock extends BaseLock {
	
	//配置
	public static $redis_cfg = [
		"host" => "127.0.0.1",
		"port" => 6379,
		//"password" => "111111",
		"key_timeout" => 30,
	];
	
	private static $rds_instance = NULL;
	private static function getInstance() {
		if(is_null(self::$rds_instance)) {
			self::$rds_instance = new \Redis();
			self::$rds_instance->pconnect((isset(self::$redis_cfg['host']) ? self::$redis_cfg['host'] : "127.0.0.1"), (isset(self::$redis_cfg['port']) ? self::$redis_cfg['port'] : 6379));
			if(isset(self::$redis_cfg['password'])) {
				self::$rds_instance->auth(self::$redis_cfg['password']);
			}
		}
		return self::$rds_instance;
	}
	
	//锁逻辑
	public static function lock($k) {
		$v = self::msectime() . "_" . self::randStr(7, 32);
		$now = time();
		
		$redis = self::getInstance();
		
		$set_ok = $redis->setNx($k, $v);
		if($set_ok) {
			$redis->expireAt($k, $now + (isset(self::$redis_cfg['key_timeout']) ? self::$redis_cfg['key_timeout'] : 30));
			return $v;
		}
		return false;
	}
	
	//解锁
	public static function unlock($k, $v) {
		$redis = self::getInstance();
		
		$vv = $redis->get($k);
		
		if($vv && $vv === $v) {
			//兼容新版php-redis
			if(method_exists($redis, 'del')) {
				$redis->del($k);
			}
			else {
				$redis->delete($k);
			}
			return true;
		}
		return false;
	}
	
}
