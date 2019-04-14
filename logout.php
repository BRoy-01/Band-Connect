<?php
require_once( "common.inc.php" );
session_start();
$_SESSION["member"] = "";
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
   <body class="logout-screen">
      <h1 class="title">Band Connect</h1>
      <h2 class="subtitle">Logged Out</h2>

      <p>You are now logged out.</p>

      <a href="login.php" class="button login">Log in again</a>
    </body>
</html>