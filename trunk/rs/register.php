<?php
if( preg_match( "/^[a-z\-]{6}$/", $HTTP_POST_VARS['cs'] )
    and preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $HTTP_POST_VARS['mail'] ) ) {

  // check if callsign used in fh_alias
  $callsign=$HTTP_POST_VARS['cs'];
  ( $result = mysql_query( "select * from fh_alias where alias='$callsign'" ) ) || die( @mysql_error() );
  if( mysql_num_rows($result) !== 0 ) {
    // alias already exist as an alias in fh_alias
    $q = @mysql_fetch_array( $result );
    die( "<h4>callsign '$callsign' already in use as alias by callsign ".$q["callsign"].".</h4>");
  }

  $pass = substr(md5(microtime()), 0, 6 );
  $id = substr(md5(microtime()), 6, 20 );
  $sql = "INSERT INTO fh_notify ( id, callsign, pwd, email, validated ) VALUES ( '$id', '{$HTTP_POST_VARS['cs']}', md5('$pass'), '{$HTTP_POST_VARS['mail']}', 0 );";
  mysql_query( $sql );
  if( mysql_affected_rows() != 1 ) {
    print "register: Error with database\n" . mysql_error();
  } else {
    $message = "Hi {$HTTP_POST_VARS['cs']}

To validate your registration please activate your account with this link:
$url/?rm=regval&id=$id
Your Password will be '$pass'.

Best Regards, Maletin.
";
	// test -redw- echo $message;
    ini_set("sendmail_from", "fh@localhost" );
    $params = sprintf("-oi -f %s", "fh@localhost" );
    mail("{$HTTP_POST_VARS['mail']}", "FH-Scenario-Registration", $message, "From: fh@localhost\r\nReply-To: fh@localhost\r\n", $params );
    print "Your password is mailed to {$HTTP_POST_VARS['mail']}";
  }
} else {
  if( preg_match( "/^[a-z\-]{6}$/", $HTTP_POST_VARS['cs'] ) ) {
    print "Mail must be a valid Mailaddress";
  } else {
    print "Callsign must be 6 lower case letters";
  }
} 
?>
