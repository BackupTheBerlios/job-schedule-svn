<?php
# $Id$
# $URL$
ob_start();
$rm = $HTTP_POST_VARS['rm'];
if( ! $rm ) $rm = $HTTP_GET_VARS['rm'];
if( ! $rm ) $rm = 'job';
include( "db.php" );
if( $callsign ) {
  include( "menu.php" );
  echo "<h1>Hi $callsign</h1>\n<p>";
  if( $job ) {
    echo "You have Job Number $job.<br>\n";
  } else {
    echo "You have no Job at the moment.<br>\n";
  }
  if( $side ) {
    echo "You are fixed to $side side.";
  } else {
    echo "You are not fixed to any side at the moment.";
  }
  if( $score ) {
  	echo "<br>\nYour score is $score.";
  }
  echo "</p>\n";
}

if( $rm == 'regval' ) {
  include( "register_validate.php" );
} elseif( $rm == 'password_new' ) {
  include( "password_new.php" );
} elseif( $rm == 'login' ) {
  include( "login.php" );
} elseif( $rm == 'register' ) {
  include( "register.php" );
} elseif( ! $callsign ) {
  include( "login_form.php" );
} elseif( $rm == 'mail_form' ) {
  include( "mail_form.php" );
} elseif( $rm == 'mailread' ) {
  include( "mailread.php" );
} elseif( $rm == 'send_mail' ) {
  include( "send_mail.php" );
} elseif( $rm == 'gentest' ) {
  include( "test.php" );
} elseif( $rm == 'jobtest' ) {
  include( "examine.php" );
} elseif( $rm == 'check' ) {
  include( "check.php" );
} elseif( $rm == 'score' ) {
  include( "score.php" );
} elseif( $rm == 'alias' ) {
  include( "alias.php" );
} elseif( $rm == 'chcallsign' ) {
  include( "chcallsign.php" );
} else {
  include( "job.php" );
}
ob_end_flush();
?>
</body>
</html>
