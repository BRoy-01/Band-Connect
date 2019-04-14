<?php

require_once "DataObject.class.php";
require_once "common.inc.php";

class Member extends DataObject {

   protected $data = array(
      "UserID" => "",
      "LastName" => "",
      "FirstName" => "",
      "MiddleName" => "",
      "UserName" => "",
      "Email" => "",
      "Password" => "", 
   );

   public static function getByUsername( $username ) {
      $conn = parent::connect();
      $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE username = :username";

      try {
         $st = $conn->prepare( $sql );
         $st->bindValue( ":username", $username, PDO::PARAM_STR );
         $st->execute();
         $row = $st->fetch();
         parent::disconnect( $conn );
         if ( $row ) return new Member( $row );
      } catch (PDOException $e ) {
         parent::disconnect( $conn );
         die( "Query failed: " . $e->getMessage() );
      }
   }

   public static function getByEmailAddress( $emailAddress ) {
      $conn = parent::connect();
      $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE Email = :emailAddress";

      try {
         $st = $conn->prepare( $sql );
         $st->bindValue( ":emailAddress", $emailAddress, PDO::PARAM_STR );
         $st->execute();
         $row = $st->fetch();
         parent::disconnect( $conn );
         if ( $row ) return new Member( $row );
      } catch ( PDOException $e ) {
         parent::disconnect( $conn );
         die( "Query failed: " . $e->getMessage() );
      }
   }

   public function insert() {
      $conn = parent::connect();
      $sql = "INSERT INTO " . TBL_MEMBERS . " (
         LastName,
         FirstName,
         MiddleName,
         UserName,
         Email,
         Password
      )  VALUES (
         :LastName,
         :FirstName,
         :MiddleName,
         :UserName,
         :Email,
         password(:Password)
      )";

      try {
         $st = $conn->prepare( $sql );
         $st->bindValue( ":LastName", $this->data["LastName"], PDO::PARAM_STR );
         $st->bindValue( ":FirstName", $this->data["FirstName"], PDO::PARAM_STR );
         $st->bindValue( ":MiddleName", $this->data["MiddleName"], PDO::PARAM_STR );
         $st->bindValue( ":UserName", $this->data["UserName"], PDO::PARAM_STR );
         $st->bindValue( ":Email", $this->data["Email"], PDO::PARAM_STR );
         $st->bindValue( ":Password", $this->data["Password"], PDO::PARAM_STR );
         $st->execute();
         parent::disconnect( $conn );
      } catch ( PDOException $e ) {
         parent::disconnect( $conn );
         die( "Query failed: " . $e->getMessage() ) ;
      }
   }

   public function authenticate() {
      $conn = parent::connect();
      $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE UserName = :username AND Password = password(:password)";

      try {
         $st = $conn->prepare( $sql );
         $st->bindValue( ":username", $this->data["UserName"], PDO::PARAM_STR );
         $st->bindValue( ":password", $this->data["Password"], PDO::PARAM_STR );
         $st->execute();
         $row = $st->fetch();
         parent::disconnect( $conn );
         if ( $row ) return new Member( $row );
      } catch ( PDOException $e ) {
         parent::disconnect( $conn );
         die( "Query failed: " . $e->getMessage() );
      }
   }

}

?>