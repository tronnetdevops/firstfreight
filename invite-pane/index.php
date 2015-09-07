<?php

	$data = $_POST;
	
	if ($data['send']){
		
		$templateID = 'ebb752d3-cc95-4627-8aca-d9dfd5eb44d4';
		
		require 'vendor/autoload.php';
		require 'connectors/SendgridConnector.php';
		$sendgrid = SendgridConnector::GetHandle();
	
		$inviter = array(
			'name' => 'Van Halen',
			'company' => 'LTD Company'
		);
	
		$opportunity = array(
			'name' => 'Ground Shipping Lane',
			'company' => 'ABC Company',
			'lanes' => array(
				'1' => array(
					'service' => 'Air',
					'to' => 'SSA, Deputado',
					'from' => 'LAX, Los Angeles',
					'weight' => '24,000 Tons',
					'revenue' => '$240,000 USD'
				),
				'2' => array(
					'service' => 'Ground',
					'to' => 'SSA, Deputado',
					'from' => 'LAX, Los Angeles',
					'weight' => '4,000 Tons',
					'revenue' => '$40,000 USD'
				),
				'3' => array(
					'service' => 'Freight',
					'to' => 'SSA, Deputado',
					'from' => 'LAX, Los Angeles',
					'weight' => '11,000 Tons',
					'revenue' => '$110,000 USD'
				)
			)
		);
		
		$messageHTML=<<<EOT
<table class="row" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica','Arial',sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
		<p style="color: #222222; font-family: 'Helvetica','Arial',sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
			By <a href="#" style="color: #2ba6cb; text-decoration: none;">{$inviter['name']}</a> with <a href="#" style="color: #2ba6cb; text-decoration: none;">{$inviter['company']}</a> to collaborate on <a href="#" style="color: #2ba6cb; text-decoration: none;">{$opportunity['name']}</a>, an Opportunity with <a href="#" style="color: #2ba6cb; text-decoration: none;">{$opportunity['company']}</a>.
		</p>
		<br/>
		<p style="color: #222222; font-family: 'Helvetica','Arial',sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
			{$data['message']}
		</p>
	</td>
</tr></table><h6 style="color: #222222; font-family: 'Helvetica','Arial',sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 20px; margin: 0; padding: 0;" align="left">Opportunity Lanes</h6>

	<table class="row" id="opp-lanes" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;">
EOT;
		
		foreach($opportunity['lanes'] as $lane){
				$messageHTML .= <<<EOT
		<tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper panel" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica','Arial',sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; background: #f2f2f2; margin: 0; padding: 10px; border: 1px solid #d9d9d9;" align="left" bgcolor="#f2f2f2" valign="top">
				<p style="color: #222222; font-family: 'Helvetica','Arial',sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
					<a href="#" style="color: #2ba6cb; text-decoration: none;">Air</a> service from <em><u>{$lane['from']}</u></em>
					to <em><u>{$lane['from']}</u></em> transporting <em>{$lane['weight']}</em>
					and generating <em><strong>{$lane['revenue']}</strong></em> revenue.
				</p>
			</td>
EOT;
		}

			
		
		$messageHTML .= '</tr></table>';

		$email = new SendGrid\Email();

		$messageText = 'You have been invited by '.$inviter['name'].' with '.$inviter['company'].' to collaborate on '.$opportunity['name'].', an Opportunity with '.$opportunity['company'].'! \n\r\n\t'.$data['message'];

		$email
			->addTo( $data['to'] )
			->setReplyTo($guid.'.'.$nonce.'@firstfreight.com')
		    ->setFrom("invite@firstfreight.com")
		    ->setSubject("You've Been Invited!")
		    ->setText($messageText)
		    ->setHtml($messageHTML)
		    ->addSubstitution(':company', array($opportunity['company']) )
			->setTemplateId( $templateID );
			
		;
		
		$response = $sendgrid->send( $email );
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
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
		body{
			background: rgba(0,0,0,0.8);
			margin-top: 30px;
		}

		.container{
			margin-top: 2em;
			max-width: 60em;
			border: 1px #9e9e9e;
			background: white;
			
			padding-bottom: 1em;
			
			border-radius:5px;
			
		}
		
		#content-container{
			
		}
		
		textarea{
			height: 12em !important;
		}
		
		.step-number{
		    border-radius: 60px;
		    padding: .3em .5em;
		}
		
		.step-number.one{
			background: #e9e9e9;
		}
		
		.step-number.two{
			background: #e5e5e5;
		}
		
		.step-number.three{
			background: #e1e1e1;
		}
		
		.or-sep{
		    border-top: 1px solid #9e9e9e;
		    margin-top: 30px;
		    margin-bottom: 15px;
		    color: #5e5e5e;
		}
		
		.or-sep-text{
			background: whitesmoke;
		    margin: -13px auto;
		    width: 3em;
		    text-align: center;
		    border: 1px solid #9e9e9e;
		    border-radius: 60px;
		    padding: .3em .5em;
		}
	</style>
  </head>
  <body>
	
	<div class="container round">
		<div class="row">
			<div class="col-xs-12">
				<div class="navbar row navbar-default">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">Invite To Opportunity</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<h3>How it works</h3>
						<p>Invite potential collaborators to this opportunity!</p>
						
						<div class="well well-sm">
							<div class="row">
								<div class="col-xs-4">
									<div class="row">
										<div class="col-xs-4 text-center">
											<h1 class="step-number one">1.</h1>
										</div>
										<div class="col-xs-8" style="padding-top: 1.5em;">
											<sub style="line-height: 2em;">Either <em>enter an email or contact name</em> to invite!</sub>
										</div>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="row">
										<div class="col-xs-4 text-center">
											<h1 class="step-number two">2.</h1>
										</div>
										<div class="col-xs-8" style="padding-top: 1.5em;">
											<sub style="line-height: 2em;">Write a custom message, or we'll put something in for you.</sub>
										</div>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="row">
										<div class="col-xs-4 text-center">
											<h1 class="step-number three">3.</h1>
										</div>
										<div class="col-xs-8" style="padding-top: 1.5em;">
											<sub style="line-height: 2em;">We'll let you know when they accept Start collaborating!.</sub>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="content-container">
					<div class="col-xs-4">
						<form method="post">
						<div class="row">
							<div class="col-lg-12">
								<label>Email</label>
								<div class="input-group">
									<span class="input-group-addon" id="sizing-addon1">
										<span class="glyphicon glyphicon-envelope"></span>
									</span>
									<input class="form-control" name="to" placeholder="user@email.com"/>							
								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->
						</div>
						
						<div class="or-sep">
							<h5 class="or-sep-text">OR</h5>
						</div>
										
						<div class="row">
							<div class="col-lg-12">
								<label>Contact</label>
								<div class="input-group">
									<span class="input-group-addon" id="sizing-addon1">
										<span class="glyphicon glyphicon-user"></span>
									</span>
									<select class="js-example-basic-multiple" multiple="multiple" name="to" style="width: 100%">
									  <option value="smurray@tronnet.me">Dave Mustaine</option>
									  <option value="smurray@tronnet.me">Ozzy Osbourne</option>
									</select>							
								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->
						</div>
						

					</div>
					
					<div class="col-xs-4" style="padding-left: 2em">
						
						<div class="row" style="border-left: 1px solid #e9e9e9;">
							<div class="col-lg-12">
								<textarea placeholder="Write a custom message..." name="message" class="form-control"></textarea>
							</div>
						</div><!-- /.row -->

						
					</div>

					<div class="col-xs-4" style="padding-left: 2em">
						
						<div class="row" style="border-left: 1px solid #e9e9e9;">
							<div class="col-lg-12 text-center" style="padding-top: 4em; padding-bottom: 4em;">
								
								<button type="submit" class="btn btn-default btn-lg btn-success" aria-label="">
									<span class="glyphicon glyphicon-share" aria-hidden="true"></span>
									Invite!
								</button>
							</div>
						</div><!-- /.row -->

						
					</div>
					<input type="hidden" name="send" value="true"/>
				</form>
				</div>
			</div>
		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
		$(".js-example-basic-multiple").select2({
		  placeholder: "Collaborator Name..."
		});
	</script>
  </body>
</html>