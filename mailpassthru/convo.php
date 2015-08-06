<?php

	/**
	 * Mock users with IDs (doing this to avoid having to make DB assoc and records)
	 */
	require 'fakeusers.php';
	
	/**
	 * Get messages from database and sort them by conversation IDs.
	 */
	require 'MySQLConnector.php';
	$db = MySQLConnector::GetHandle();
	
	$statement = $db->prepare('SELECT * FROM `messages`');
	$statement->execute();

	$messages = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($messages as $message){
		$cid = $message['conversation_id'];
		if (!isset($conversations[ $cid ]['subject'])){
			$conversations[ $cid ]['subject'] = $message['subject'];
		}
		
		$conversations[ $cid ]['messages'][] = $message;
	}
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Example Details Lane</title>

    <!-- Bootstrap -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
		body{
			background: whitesmoke;
			margin-top: 30px;
		}
		
		.convo{
			background: #e9e9e9;
			cursor: pointer;
		}
	</style>
  </head>
  <body>
	
	<div class="container">
		
		<?php foreach($conversations as $id=>$convo) : ?>
		<div class="row convo" data-toggle="collapse" href="#convo-<?php echo $id; ?>" aria-expanded="false" aria-controls="convo-<?php echo $id; ?>">
			<div class="col-xs-7 col-md-10">
				<h1><?php echo $convo['subject']; ?></h1>
			</div>
		</div>
		
		<div class="row collapse" id="convo-<?php echo $id; ?>">
			
			<?php foreach ($convo['messages'] as $message) : ?>
			<div class="col-xs-12">
				<h1><?php echo $users[ $message['user_id'] ]['name']; ?>, said</h1>
				<p><?php echo $message['message']; ?></p>
			</div>
			<hr/>
			
			<?php endforeach; ?>
		
			<form action="mailer.php" method="POST">
				<div class="form-group">
					<label for="exampleInputPassword1">Reply</label>
					<textarea type="message" name="message" class="form-control" id="message" placeholder="Reply to message..."></textarea>
				</div>
			    <button type="submit" class="btn btn-default">Submit</button>
				<input type="hidden" name="conversation_id" value="<?php echo $id; ?>"/>
				<input type="hidden" name="user_id" value="1"/>
			</form>
		</div>
		  
		
		<?php endforeach; ?>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>