<?php

	abstract class MemcachedConnector{
		
		static private $_memcache;
		
		static public function GetHandle(){
			if (!self::$_memcache){
				self::$_memcache = new Memcached;
				$cacheAvailable = self::$_memcache->addServer('localhost', 11211);
			}

			return self::$_memcache;
		}
	}