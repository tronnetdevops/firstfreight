<?php

	abstract class SendgridConnector{
		
		static private $_sendgrid;
		
		static public function GetHandle(){
			if (!self::$_sendgrid){

				$sendgridConfig = json_decode(file_get_contents('./../data/sendgrid.json'), true);
				$sgc = $sendgridConfig['sendgrid'];
								
				self::$_sendgrid = new SendGrid($sgc['key']);
				
			}

			return self::$_sendgrid;
		}
	}