<?php
$username = $HTTP_POST_VARS['username'];
$password = $HTTP_POST_VARS['password'];
if (!isset($username) || !isset($password)) {
  header( "Location: index.php" );
} elseif( empty( $username ) || empty( $password ) ) {
} elseif( preg_match( '/[A-Z]/', $username ) ) {
  die( "Please use lower case for the callsign." );
} else {
  //convert the field values to simple variables
  //add slashes to the username and md5() the password
  $user = addslashes($username);
  $pass = md5($password);

  //set the database connection variables
  $result = mysql_query( "select * from fh_user where callsign='$user' AND pwd='$pass'" );
  //check that at least one row was returned
  if( mysql_num_rows($result) > 0){
    while($row = mysql_fetch_array($result)){
      //start the session and register a variable
#      session_start();
      session_register('username');
      $_SESSION['username'] = $username;
      //we will redirect the user to another page where we will make sure they're logged in
      preg_match( "%^$baseurl(\/.*)$%", $_SERVER['HTTP_REFERER'], $match );
      $location = 'index.php';
      if( isset( $match[1] ) ) $location = $match[1];
      if( preg_match( '/login/', $location ) ) $location = 'index.php';
      header( "Location: $location" );
    }
  } else {
    //if nothing is returned by the query, unsuccessful login code goes here...
    echo 'Incorrect login name or password!<br>';
    echo "Please try again or create a new password.";
  }
}
exit( 0 );
?>
