<?php
	/**
	 * Mock users with IDs (doing this to avoid having to make DB assoc and records)
	 */
	$users = array(
		'1' => array(
			"name" => "Bob Schwarts",
			"email" => "smurraysb@gmail.com"
		),
		'2' => array(
			"name" => "Joe Guy",
			"email" => "smurray@tronnet.me"
		)
	);
	
	$usersByEmail = array(
		'smurraysb@gmail.com' => array(
			"name" => "Bob Schwarts",
			"id" => '1'
		),
		'smurray@tronnet.me' => array(
			"name" => "Joe Guy",
			"id" => '2'
		)
	);
