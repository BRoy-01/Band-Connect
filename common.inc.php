<?php

require_once( "config.php" );
require_once( "Member.class.php" );

function validateField( $fieldName, $missingFields ) {
   if ( in_array( $fieldName, $missingFields ) ) {
      echo ' class="error"';
   }
}

function checkLogin() {

	if( !isset($_SESSION) ) {
		session_start();
	}
	
	if ( !$_SESSION["member"] or !$_SESSION["member"] = Member::getMember( $_SESSION["member"]->getValue( "UserID" ) ) ) {
		$_SESSION["member"] = "";
		header( "Location: login.php" );
		exit;
	}
}

?>