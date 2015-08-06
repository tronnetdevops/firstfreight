<?php
	/**
	 * Mock users with IDs (doing this to avoid having to make DB assoc and records)
	 */
	require 'fakeusers.php';
		
	require 'MemcachedConnector.php';
	$memcache = MemcachedConnector::GetHandle();
	
	$data = $_POST;
	if (isset($data['envelope'])){
		$contacts = json_decode($data['envelope'], true);
		
		$creds = explode('.', $contacts['to'][0]);
		
		$guid = $creds[0]
		$nonce = $creds[1];
		
		$package = $memcache->get( $guid );
		
		if ($package['nonce'] == $nonce){
			/**
			 * Now...store the message in the DB, as this was a reply...
			 */ 
			require 'MySQLConnector.php';
			$db = MySQLConnector::GetHandle();
		
			$statement = $db->prepare('INSERT INTO `messages` (`user_id`, `conversation_id`, `message`, `data`) VALUES (?,?,?,?)');
		
			$statement->execute(array(
				$usersByEmail[ $contacts['from'] ]['id'], $package['conversation_id'], $data['text'], json_encode($data)
			));
			
		} else {
			error_log("THE NONCE FOR THE EMAIL REPLY CHAIN DIDN'T MATCH!!");
		}
	}
	
	echo "OK";	
