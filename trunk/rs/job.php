<?php
#<p class='ontab'>The Registration-Period is over!</p>
$cs = $callsign;
if( $cs && isset( $HTTP_POST_VARS['aktion'] ) ) {

 // arrival path?
 if( $HTTP_POST_VARS['aktion'] == 'arrival' ) {
   // arrival set current value
   $arrival = $HTTP_POST_VARS['arrival'];

   // check $arrival
   if( ! preg_match( "/^[0-9].*$/", $arrival ) OR ( $arrival > $HTTP_POST_VARS['max'] ) ) {
     die( "current: wrong value '$arrival'" );
   }

   // update fh_arrival
   $sql = "UPDATE fh_arrival SET current = " . $arrival . " WHERE callsign = '" . $cs . "'";
   mysql_query( $sql ) || die("<p>current: Error with database" . mysql_error() . "</p>\n") ;

 } elseif( $score > 0 ) {
  $cs = $HTTP_POST_VARS['cs'];
  $jobid = $HTTP_POST_VARS['job'];
  if( $HTTP_POST_VARS['aktion'] == 'ein' ) {
    $q = "delete from fh_assign where callsign = '$cs' and job = '$jobid' and fix = 0";
    $result = @mysql_query( $q ) or die( @mysql_error() );
    $q = "select side from fh_job where job = '$jobid'";
    $result = @mysql_query( $q ) or die( @mysql_error() );
    $row = @mysql_fetch_array( $result, MYSQL_ASSOC );
    if( $side and $side <> $row[side] ) {
      echo "you can't sign-in to the other side";
      $job = 0;
    } else {
      $q = "insert into fh_assign ( callsign, job ) values ( '$cs', '$jobid' )";
      $result = @mysql_query( $q ) or die( @mysql_error() );
      $rc = @mysql_affected_rows();
      if( $rc != 1 ) die( 'Error.' );
      echo "you sucessfully signed-in";
      $job = $jobid;
    }
  } else {
    $q = "delete from fh_assign where callsign = '$cs' and job = '$job' and fix = 0";
    $result = @mysql_query( $q ) or die( @mysql_error() );
    $rc = @mysql_affected_rows();
    if( $rc != 1 ) die( 'your position is fixed.' );
    echo "you sucessfully canceled";
    $job = 0;
  }
 } else {
  echo( "your score is not high enough.<br>Please read this <a target='_top' href='http://fhscenarios.jinak.cz/index.php?option=com_content&task=view&id=40&Itemid=56'>Article</a>." );
 }
}

$q = "select * from fh_job";
$result = @mysql_query( $q ) or die( @mysql_error() );
while( $j = @mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
  $all_jobs{$j["job"]} = $j["brief"];
  $desc_job{$j["job"]} = $j["description"];
  $si{$j["side"]} = $j["side"];
}
@mysql_free_result( $result );

$q = "select * from fh_assign";
$result = @mysql_query( $q ) or die( @mysql_error() );
$j = array();
while( $j = @mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
  $all[$j["job"]] = $j["callsign"];
}
@mysql_free_result( $result );


// probability of arrival
( $result = mysql_query( "select * from fh_arrival where callsign='$cs'" ) ) || die( @mysql_error() );
if( mysql_num_rows($result) == 0 ) {
  // callsign not in fh_arrival - now insert
  $sql = "INSERT INTO fh_arrival ( callsign ) VALUES ( '" . $cs . "' )";
  mysql_query( $sql ) || die("<p>arrival: Error with database" . mysql_error() . "</p>\n") ;
}
@mysql_free_result( $result );

// callsign found or created in fh_arrival
( $result = mysql_query( "select * from fh_arrival where callsign='$cs'" ) ) || die( @mysql_error() );
if( mysql_num_rows($result) !== 0 ) {
  echo "<H4>Your probability of arrival:</H4>";
  $a = @mysql_fetch_array( $result, MYSQL_ASSOC );

  echo "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'>";
  echo "<input type='hidden' name='cs' value='$cs'>";
  echo "<input type='hidden' name='aktion' value='arrival'>";
  echo "<input type='hidden' name='max' value='" . $a['max'] . "'>";
  echo "Current Value: <input type='text' size='5' maxlength='3' name='arrival' value='" . $a['current'] . "'> <b>%</b> ";
  echo "<input type='submit' value='Set Arrival'>";
  echo "<br> max possible value=" . $a['max'] . " % <br></form>\n";
}
@mysql_free_result( $result );


echo "<table border='1'><tr><th>ID</th><th>Job</th><th>Pilot</th><th>Description</th></tr>\n";
ksort( $all_jobs );
$last_group = '';
foreach( $all_jobs as $j => $jobdesc ) {
  echo "<tr>";
  echo "<th>$j</th><th align='left'>$jobdesc</th>";
  print '<td>';
  if( strlen( $cs ) > 0 && $all[$j] == $cs ) {
    print "<form method='post' action='" . $_SERVER["PHP_SELF"];
    print "'><input type='hidden' name='cs' value='$cs'>";
    print "<input type='hidden' name='job' value='$j'>";
    print "<input type='hidden' name='aktion' value='aus'>";
    print "$cs<br><input type='submit' value='Revoke'></form>";
  } elseif( $all[$j] ) {
    print $all[$j];
  } elseif( strlen( $cs ) > 0 ) {
    print "<form method='post'><input type='hidden' name='cs' value='$cs'>";
    print "<input type='hidden' name='job' value='$j'>";
    print "<input type='hidden' name='aktion' value='ein'>";
    print "<input type='submit' value='Sign in'></form>";
  }
  print "</td><td>";
  print $desc_job{$j};
  print "</td></tr>\n";
}
echo "</table>\n"; 
?>
