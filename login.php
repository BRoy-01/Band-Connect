<?php
require_once( "common.inc.php" );

session_start();

if ( isset($_GET['loggedin']) ) {
	// use checkLogin() here to make sure user is actually logged in before displaying the
	// "thanks" message, otherwise a user could just add ?loggedin=1 to the end of login.php
	// to see the thanks message without actually being logged in.
	checkLogin();
	displayThanks();
} elseif ( isset( $_POST["action"] ) and $_POST["action"] == "login" ) {
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

		// displayThanks();

		// Redirecting here instead of calling displayThanks() as shown in the book in order
		// to prevent seeing a "Confirm Form Resubmission" message or page if a user refreshes 
		// the login page while viewing the "thanks" message, or if the user clicks the link on 
		// the thanks message to go to "search.php" then clicks the 'back' button.

		// This is using the prg(post/redirect/get) design pattern to prevent the "confirm form 
		// resubmission" issue.
		header("Location: login.php?loggedin=1");
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
				<p>Click <a href="search.php">here</a> to go to the search screen.</p>
    </body>
</html>

<?php
}
?>