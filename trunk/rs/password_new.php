<?PHP
if( ! preg_match( "/^[a-z\-]{6}$/", $cs ) ) {
  die( "wrong callsign [$cs]" );
}
$result = mysql_query( "select * from fh_user where callsign='$cs' AND email ='$mail'" );
if( mysql_num_rows($result) == 1 ) {
  $pass = substr(md5(microtime()), 0, 6);
  $sql = "update fh_user set pwd = md5('$pass') where callsign = '$cs'";
  mysql_query( $sql );
  if( mysql_affected_rows() !== 1 ) {
    die( 'database error' );
  } else {
    $message = "Hi $cs

A new password is created for for callsign '$cs' at
$url
The password is now '$pass'.

Best Regards, Maletin.
";
    ini_set("sendmail_from", "fh@localhost" );
    $params = sprintf("-oi -f %s", "fh@localhost" );
    mail($mail, "FH-Scenario-Password", $message, "From: fh@localhost\r\n", "-oi -f fh@localhost\r\n" );
    print "Your new password is mailed to $mail";
  }
} else {
  die( "wrong Email!" );
}
?>
