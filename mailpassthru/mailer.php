<?php
	/**
	 * Mock users with IDs (doing this to avoid having to make DB assoc and records)
	 */
	require 'fakeusers.php';

	require 'vendor/autoload.php';
		
	require 'MemcachedConnector.php';
	$memcache = MemcachedConnector::GetHandle();
	
	$data = $_POST;
	if (isset($data['conversation_id']) && isset($data['user_id'])){
		/**
		 * Create a GUID as a memcached key for pertinent, dynamic information
		 */ 
		$guid = md5($data['conversation_id'].$data['user_id'].$data['message']);
		
		/**
		 * Nonce for security...
		 */ 
		$nonce = uniqid();
		$package = array(
			'nonce' => $nonce,
			'user_id' => $data['user_id'],
			'conversation_id' => $data['conversation_id'],
			'message' => $data['message']
		);
		
		/**
		 * Store in memcached to be accessed by the web hook on reply to email from user
		 */ 
		$memcache->set( $guid, $package);
		
		/**
		 * Now...store the message in the DB, as this was a reply...
		 */ 
		require 'MySQLConnector.php';
		$db = MySQLConnector::GetHandle();
		
		$statement = $db->prepare('INSERT INTO `messages` (`user_id`, `conversation_id`, `message`, `data`) VALUES (?,?,?,?)');
		
		$statement->execute(array(
			$data['user_id'], $data['conversation_id'], $data['message'], json_encode($package)
		));
			
			
		/**
		 * Lastly, send the email!
		 */ 
		require 'SendgridConnector.php';
		$sendgrid = SendgridConnector::GetHandle();
		
		$email = new SendGrid\Email();
		$email
		    ->addTo($users[ $data['user_id'] ]['email'])
			->setReplyTo($guid.'.'.$nonce.'@sbdevops.com')
		    ->setFrom('reply@firstfreight.com')
		    ->setSubject('A reply to a conversation!')
		    ->setText($data['message'])
		    ->setHtml($data['message'])
		;
		
		$sendgrid->send($email);
	}
	
	header('Location: http://firstfreight.sbdevops.com/mailpassthru/convo.php?m=Sent!');
?>