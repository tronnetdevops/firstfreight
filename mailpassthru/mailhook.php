<?php
	/**
	 * Mock users with IDs (doing this to avoid having to make DB assoc and records)
	 */
	require 'data/fakeusers.php';
		
	require 'connectors/MemcachedConnector.php';
	$memcache = MemcachedConnector::GetHandle();
	
	$data = $_POST;
	
	error_log("Got a Web Hook Reply!!");
	error_log(var_export($data, true));
	if (isset($data['envelope'])){
		$contacts = json_decode($data['envelope'], true);
		
		error_log("Contacts...");
		error_log(var_export($contacts, true));
		
		$cid = explode('@', $contacts['to'][0]);
		$creds = explode('.', $cid[0]);
		
		
		error_log("Creds guid and nonce");
		error_log(var_export($creds, true));
		
		
		$guid = $creds[0];
		$nonce = $creds[1];
		
		$package = $memcache->get( $guid );
		
		error_log("checking nonces match: " .$nonce."   =   " . $package['nonce']);
		if ($package['nonce'] == $nonce){
			/**
			 * Now...store the message in the DB, as this was a reply...
			 */ 
			require 'connectors/MySQLConnector.php';
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
