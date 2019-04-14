<?php
require_once( "common.inc.php" );

session_start();

if ( isset( $_POST["action"] ) and $_POST["action"] == "login" ) {
	processForm();
} else {
	displayForm( array(), array(), new Member( array() ) );
}

function displayForm( $errorMessages, $missingFields, $member ) {
?>
<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv='content-type' content='text/html; charset=utf-8' />
			<meta http-equiv='content-language' content='en-us' />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <title>
         Band Connect
      </title>
			<link type='text/css' href='custom.default.css' rel='stylesheet' />
   </head>
  <body>
		<h1 class="title">Band Connect</h1>
		<div class="login">
      <h2 class="subtitle">Log In</h2>

<?php
	if ( $errorMessages ) {
		foreach ( $errorMessages as $errorMessage ) {
			echo $errorMessage;
		}
	}
?>

			<form action="login.php" method="post">
				<input type="hidden" name="action" value="login" />

				<input<?php validateField( "UserName", $missingFields ) ?> type="text" name="UserName" id="UserName" value="<?php echo $member->getValueEncoded( "UserName" ) ?>" placeholder="Username" />

				<input<?php if ( $missingFields ) echo ' class="error"'?> type="password" name="Password" id="Password" value="" placeholder="Password" />

				<input type="submit" name="submitButton" id="submitButton" value="Login" />
			</form>
			<a href="Login_Signup.html" class="back_btn button">Back</a>
		</div>
	</body>
</html>

<?php
}

function processForm() {
	$requiredFields = array( "UserName", "Password" );
	$missingFields = array();
	$errorMessages = array();

	$member = new Member( array(
		"UserName" => isset( $_POST["UserName"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["UserName"] ) : "",
		"Password" => isset( $_POST["Password"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["Password"] ) : "",
		) );

	foreach ( $requiredFields as $requiredField ) {
		if ( !$member->getValue( $requiredField ) ) {
			$missingFields[] = $requiredField;
		}
	}

	if ( $missingFields ) {
		$errorMessages[] = '<p class="error">There were some missing fields in the form you submitted.  Please complete the fields highlighted below and click Login to resend the form.</p>';
	} elseif ( !$loggedInMember = $member->authenticate() ) {
		$errorMessages[] = '<p class="error">Sorry, we could not log you in with those details.  Please check your username and password, and try again.</p>';
	}

	if ( $errorMessages ) {
		displayForm( $errorMessages, $missingFields, $member );
	} else {
		$_SESSION["member"] = $loggedInMember;

		displayThanks();
	}
}

function displayThanks() {
?>
<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv='content-type' content='text/html; charset=utf-8' />
			<meta http-equiv='content-language' content='en-us' />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
			<title>
				Band Connect
			</title>
			<link type='text/css' href='custom.default.css' rel='stylesheet' />
    </head>
    <body class="welcome">
				<a href="logout.php" class="logout button">Log out</a>
      	<h1>Band Connect</h1>
      	<p>Thank you for logging in.</p>
    </body>
</html>

<?php
}
?>