<?php

require_once( "config.php" );
require_once( "Member.class.php" );

function validateField( $fieldName, $missingFields ) {
   if ( in_array( $fieldName, $missingFields ) ) {
      echo ' class="error"';
   }
}

?>