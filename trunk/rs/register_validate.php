<?php
$sql = "SELECT callsign, pwd, email, validated FROM fh_notify WHERE id = '{$HTTP_GET_VARS['id']}'";
( $result = @mysql_query( $sql ) ) || die( @mysql_error() );
$row = @mysql_fetch_array( $result );
if( strlen( $row['callsign'] ) == 6 ) {
  if( $row['validated'] ) {
    print( "<P>This account is allready activated</P>\n" );
  } else {

    // check if callsign used in fh_alias
    $callsign = $row['callsign'];
    ( $result = mysql_query( "select * from fh_alias where alias='$callsign'" ) ) || die( @mysql_error() );
    if( mysql_num_rows($result) !== 0 ) {
      // alias already exist as an alias in fh_alias
      $q = @mysql_fetch_array( $result );
      die( "<h4>callsign '$callsign' already in use as alias by callsign ".$q["callsign"].".</h4>");
    }

    $sql = "INSERT INTO fh_user ( callsign, pwd, email ) VALUES ( '{$row['callsign']}', '{$row['pwd']}', '{$row['email']}' )";
    @mysql_query( $sql );
    print "<P>Your account is now active</P>\n";
    $sql = "UPDATE fh_notify SET validated = 1 WHERE id = '{$HTTP_GET_VARS['id']}'";
    @mysql_query( $sql );
  }
  print "<a href='index.php'>Login-Page</a>";
} else {
  die( "Error" );
}
?>
