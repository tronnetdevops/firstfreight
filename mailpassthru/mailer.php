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
		
		/**
		 * Go through all users in this particular conversation, and put on reply list
		 */
		
		$statement = $db->prepare('SELECT `user_id` FROM `messages` WHERE `conversation_id`=? GROUP BY `user_id`');
		$statement->execute(array($data['conversation_id']));

		$messages = $statement->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
		$conversationParticipents = array_keys($messages);
		
		foreach ($conversationParticipents as $participentID){
			$email->addTo($users[ $participentID ]['email']);
		}
		
		
		/**
		 * Get the subject stored with the original message.
		 */
		$statement = $db->prepare('SELECT `subject` FROM `messages` WHERE `conversation_id`=? LIMIT 1');
		$statement->execute(array($data['conversation_id']));

		$messages = $statement->fetchAll(PDO::FETCH_ASSOC);
		$primaryMessage = reset($messages);
		
		$email
			->setReplyTo($guid.'.'.$nonce.'@sbdevops.com')
		    ->setFrom( $users[ $data['user_id'] ]['email'] )
		    ->setSubject('RE: '.$primaryMessage['subject'])
		    ->setText($data['message'])
		    ->setHtml($data['message'])
		;
		
		$sendgrid->send($email);
	}
	
	header('Location: http://firstfreight.sbdevops.com/mailpassthru/convo.php?m=Sent!');
?>