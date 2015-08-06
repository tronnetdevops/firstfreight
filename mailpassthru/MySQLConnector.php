<?php

	abstract class MySQLConnector{
		
		static private $_dbh;
		
		static public function GetHandle(){
			if (!self::$_dbh){
				self::$_dbh = new PDO(
					'mysql:host=localhost;dbname=firstfreight', 
					'ffagent', 
					'ff1234'
				);
			}

			return self::$_dbh;
		}
	}