<?php
global $db, $callsign, $side, $job, $url, $baseurl, $rm, $score;
session_start();
if( session_is_registered( 'username' ) ) $callsign = $_SESSION['username'];

include( 'config.php' );

# connet to the database
$db = @mysql_connect("$dbHost", "$dbUser", "$dbPass")
  or die( "database_error: connect" );
@mysql_select_db("$dbDatabase", $db) or die( "database_error: select_db" );

if( $callsign ) {
  $sql1 = "select mail from fh_mail_recv where receiver = '$callsign' and confirm = 0";
  ( $result1 = @mysql_query( $sql1 ) ) || die( @mysql_error() );
  $row1 = @mysql_fetch_array( $result1 );
  if( $row1[0] && $rm <> 'mailread' ) {
    header( "Location: $url/?rm=mailread&mailid={$row1[0]}&unread=1" );
  }
  $sql2 = "select job from fh_assign where callsign = '$callsign'";
  ( $result2 = @mysql_query( $sql2 ) ) || die( @mysql_error() );
  $row2 = @mysql_fetch_array( $result2 );
  if( $row2[0] ) $job = $row2[0];
  $sql3 = "select side from fh_side where callsign = '$callsign'";
  ( $result3 = @mysql_query( $sql3 ) ) || die( @mysql_error() );
  $row3 = @mysql_fetch_array( $result3 );
  if( $row3[0] ) $side = $row3[0];
  $sql4 = "select sum( score ) from fh_score where callsign = '$callsign'";
  ( $result4 = @mysql_query( $sql4 ) ) || die( @mysql_error() );
  $row4 = @mysql_fetch_array( $result4 );
  if( $row4[0] ) $score = $row4[0];
}

$log = @fopen( 'access.log', 'a' );
if( $log ) {
  @fputs( $log, date( 'Y-m-d_H:i:s' ) . "|$PHP_SELF|$rm|$callsign\n" );
  @fclose( $log );
}
?>
<link rel='stylesheet' href='css/template_css.css' type='text/css'>
<link rel="shortcut icon" href="icons/job.ico">
<iframe name="ITOP_GMT" src="http://wwp.greenwichmeantime.com/time/time2.php" marginwidth="1" marginheight="1" height="25" width="100%" scrolling="no" border="0" frameborder="0">
"An accurate and reliable source of information" - BBC</iframe>
<style type='text/css'>
<!--
 a:link { color:#EE0000; text-decoration:none; font-weight:bold; }
 a:visited { color:#EE4444; text-decoration:none; font-weight:bold; }
 a:hover { color:#EE0000; text-decoration:none; background-color:#FFFF99; font-weight:bold; }
 a:active { color:#0000EE; background-color:#FFFF99; font-weight:bold; }
-->
</style>
