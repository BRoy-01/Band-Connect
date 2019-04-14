<?php

require_once( "common.inc.php" );

if ( isset( $_POST["action"] ) and $_POST["action"] == "register" ) {
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
      <h2 class="subtitle">Sign Up</h2>

<?php
   if ( $errorMessages ) {
      foreach ( $errorMessages as $errorMessage ) {
         echo $errorMessage;
      }
   } 
?>

      <form action="register.php" method="post">
         <input type="hidden" name="action" value="register" />

         <input<?php validateField( "FirstName", $missingFields ) ?> type="text" name="FirstName" id="FirstName" value="<?php echo $member->getValueEncoded( "FirstName" ) ?>" placeholder="First Name" />

         <input<?php validateField( "MiddleName", $missingFields ) ?> type="text" name="MiddleName" id="MiddleName" value="<?php echo $member->getValueEncoded( "MiddleName" ) ?>" placeholder="Middle Name" />

         <input<?php validateField( "LastName", $missingFields ) ?> type="text" name="LastName" id="LastName" value="<?php echo $member->getValueEncoded( "LastName" ) ?>" placeholder="Last Name" />

         <input<?php validateField( "Email", $missingFields ) ?> type="text" name="Email" id="Email" value="<?php echo $member->getValueEncoded( "Email" ) ?>" placeholder="Email" />

         <input<?php validateField( "UserName", $missingFields ) ?> type="text" name="UserName" id="UserName" value="<?php echo $member->getValueEncoded( "UserName" ) ?>" placeholder="Username" />

         <input<?php if ( $missingFields ) echo ' class="error"'?> type="password" name="password1" id="password1" value="" placeholder="Password" />

         <input<?php if ( $missingFields ) echo ' class="error"'?> type="password" name="password2" id="password2" value="" placeholder="Confirm Password" />

         <input type="submit" name="submitButton" id="submitButton" value="Sign Up" />
      </form>
      <a href="Login_Signup.html" class="back_btn button">Back</a>
   </body>
</html>

<?php
}

function processForm() {
   $requiredFields = array( "FirstName", "LastName", "Email", "UserName", "Password" );
   $missingFields = array();
   $errorMessages = array();

   $member = new Member( array(
      "LastName" => isset( $_POST["LastName"] ) ? preg_replace( "/[^ \'\-a-zA-Z0-9]/", "", $_POST["LastName"] ) : "",
      "FirstName" => isset( $_POST["FirstName"] ) ? preg_replace( "/[^ \'\-a-zA-Z0-9]/", "", $_POST["FirstName"] ) : "",
      "MiddleName" => isset( $_POST["MiddleName"] ) ? preg_replace( "/[^ \'\-a-zA-Z0-9]/", "", $_POST["MiddleName"] ) : "",
      "UserName" => isset( $_POST["UserName"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["UserName"] ) : "",
      "Email" => isset( $_POST["Email"] ) ? preg_replace( "/[^ \@\.\-\_a-zA-Z0-9]/", "", $_POST["Email"] ) : "",
      "Password" => ( isset( $_POST["password1"] ) and isset( $_POST["password2"] ) and $_POST["password1"] == $_POST["password2"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["password1"] ) : ""
   ) );

   foreach ( $requiredFields as $requiredField ) {
      if ( !$member->getValue( $requiredField ) ) {
         $missingFields[] = $requiredField;
      }
   }

   if ( $missingFields ) {
      $errorMessages[] = '<p class="error">There were some missing fields in the form you submitted.  Please complete the fields highlighted below and click Send Details to resend the form.</p>';
   }

   if ( !isset( $_POST["password1"] ) or !isset( $_POST["password2"] ) or !$_POST["password1"] or !$_POST["password2"] or ( $_POST["password1"] != $_POST["password2"] ) ) {
      $errorMessages[] = '<p class="error">Please make sure you enter your password correctly in both password fields.</p>';
   }

   if ( Member::getByUsername( $member->getValue( "UserName" ) ) ) {
      $errorMessages[] = '<p class="error">A member with that username already exists in the database.  Please choose another username.</p>';
   }

   if ( Member::getByEmailAddress( $member->getValue( "Email" ) ) ) {
      $errorMessages[] = '<p class="error">A member with that email address already exists in the database.  Please choose another email address, or contact the webmaster to retrieve your password.</p>';
   }

   if ( $errorMessages ) {
      displayForm( $errorMessages, $missingFields, $member );
   } else {
      $member->insert();
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
      <title>
         Band Connect
      </title>
      <link type='text/css' href='custom.default.css' rel='stylesheet' />
   </head>
   <body class="welcome">
      <h1>Band Connect</h1>
      <h3>Welcome to Band Connect!</h3>
      <div>
         <p>Blah blah blah, yadda yadda yadda, lorem ipsum, Band Connect is totally awesome.  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc egestas fermentum efficitur. Nulla scelerisque scelerisque magna, sed placerat quam ullamcorper id. Fusce sed lectus felis. Vivamus vitae pharetra lacus.</p>
         <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc egestas fermentum efficitur. Nulla scelerisque scelerisque magna, sed placerat quam ullamcorper id. Fusce sed lectus felis. Vivamus vitae pharetra lacus.</p>
      </div>
   </body>
</html>

<?php
}
?>