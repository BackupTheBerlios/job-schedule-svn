<?php
$from = "fh@localhost";

$sql7 = "select count(*) from fh_score where callsign = '$callsign' and reason = 2";
( $result7 = @mysql_query( $sql7 ) ) or die( @mysql_error() );
$row7 = @mysql_fetch_array( $result7 );
if( ! $row7[0] ) {
  $sql3 = "insert into fh_score ( callsign, score, reason ) values ( '$callsign', 1, 2 )";
  @mysql_query( $sql3 ) or die( @mysql_error() );
}
$sql2 = "select s.callsign, u.email from fh_side as s, fh_user as u where s.callsign = u.callsign and s.side = '$side'";
( $result2 = @mysql_query( $sql2 ) ) or die( @mysql_error() );
$pilot = array();
while( $row2 = @mysql_fetch_array( $result2 ) ) {
  $pilot{$row2["callsign"]} = $row2["email"];
}

$body = stripslashes( $HTTP_POST_VARS["body"] );
$subject = stripslashes( $HTTP_POST_VARS["subject"] );
while( list( $key, $val ) = each( $HTTP_POST_VARS ) ) {
  if( is_array( $val ) ) {
    echo "$key = ( ";
    foreach( $val as $v ) {
      echo ", $v";
    }
    echo ")</br>\n";
  } else {
    echo "$key=$val<br>\n";
  }
}

$s = mysql_escape_string( $subject );
$b = mysql_escape_string( $body );
$sql3 = "insert into fh_mail ( sender, side, subject, text ) values ( '$callsign', '$side', '$s', '$b' )";
@mysql_query( $sql3 ) or die( @mysql_error() );

$subject = "[Scenario] $subject";

function individual_body ( $id ) {
  global $body, $url;
  return "$body

To give a read-confirmation click on:
$url/index.php?rm=mailread&id=$id
";
} # individual_body

ini_set("sendmail_from", "$from" );
$params = sprintf("-oi -f %s", "$from" );
foreach( $HTTP_POST_VARS["mail"] as $m ) {
  $id = substr( md5( microtime() ), 11, 20 );
  $sql4 = "insert into fh_mail_recv ( id, mail, receiver, confirm ) values ( '$id', last_insert_id(), '$m', 0 )";
  @mysql_query( $sql4 ) or die( @mysql_error() );
  $rc = @mail( $pilot{$m}, $subject, individual_body( $id ), "From: \"$callsign\" <$from>\r\nReply-To: $from\r\n", $params );
  if( $rc ) {
    echo "mailing to $m sucessful<br>\n";
  } else {
    echo "mailing to $m NOT sucessful<br>\n";
  }
  sleep(1);
}
?>
