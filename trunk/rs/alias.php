<?php
// delete alias?
if( $callsign && isset( $HTTP_POST_VARS['aktion'] ) && isset( $HTTP_POST_VARS['alias'] ) ) {
  $csalias =  $HTTP_POST_VARS['alias'];
  if( $HTTP_POST_VARS['aktion'] == 'add' ) {
    // add alias to fh_alias
    if( ! preg_match( "/^[a-z\-]{6}$/", $csalias ) ) {
      die( "$callsign: wrong alias '$csalias'" );
    }
    ( $result = mysql_query( "select * from fh_alias where alias='$csalias'" ) ) || die( @mysql_error() );
    if( mysql_num_rows($result) !== 0 ) {
      // alias already exist as an alias in fh_alias
      $q = @mysql_fetch_array( $result );
      echo "<h4>alias '$csalias' already in use as alias by ".$q["callsign"].".</h4>";
      } else {
      ( $result = mysql_query( "select * from fh_user where callsign='$csalias'" ) ) || die( @mysql_error() );
      if( mysql_num_rows($result) !== 0 ) {
        // alias already existi as callsign in fh_user
        $q = @mysql_fetch_array( $result );
        echo "<h4>alias '$csalias' already in use as callsign by ".$q["callsign"].".</h4>";
        } else {
        // alias not in fh_alias and fh_user = all ok for adding to fh_alias
        $sql = "INSERT INTO fh_alias ( callsign, alias ) VALUES ( '$callsign', '$csalias' )";
        @mysql_query( $sql ) || die( @mysql_error() );
        echo "<h4>all ok - alias '$csalias' has assigned to '$callsign'</h4>";
      }
    }
    @mysql_free_result( $result );
  }
  if( $HTTP_POST_VARS['aktion'] == 'delete' ) {
    // delete alias from fh_alias
    $q = "delete from fh_alias where callsign = '$callsign' and alias = '$csalias'";
    $result = @mysql_query( $q ) or die( @mysql_error() );
    $rc = @mysql_affected_rows();
    if( $rc != 1 ) die( 'Alias $csalias unable to delete for $callsign' );
    echo "<h4>$csalias successfuly deleted for $callsign</h4>";
    @mysql_free_result( $result );
  }
}

// show all assigned alias for current callsign
( $result = mysql_query( "select * from fh_alias where callsign='$callsign'" ) ) || die( @mysql_error() );
if( mysql_num_rows($result) !== 0 ) {
  // callsign already use alias
  echo "<table border='1'>\n";
  echo "<H4>You are using following alias:</H4>";
  while( $a = @mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
	echo "<tr><th>".$a["alias"]."</th>";
	echo "<td><form method='post' action='" . $_SERVER["PHP_SELF"] . "'>";
    echo "<input type='hidden' name='rm' value='alias'>";
    echo "<input type='hidden' name='aktion' value='delete'>";
    echo "<input type='hidden' name='alias' value='" . $a["alias"] . "'>";
	echo "<input type='submit' value='Delete' name='alias_del'></form>";
	echo "</td></tr>\n";
  }
  echo "</table>\n";
  } else {
  echo "<H4>You currently not use any alias</H4>";
}
@mysql_free_result( $result );
?>

<H4>Add Alias to your Callsign:</H4>

<form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
<input type='hidden' name='rm' value='alias'>
<input type='hidden' name='aktion' value='add'>
Alias: <input type="text" name="alias" size="20" maxlength="6">
<input type="submit" value="Add Alias" name="alias_add">
</form>
