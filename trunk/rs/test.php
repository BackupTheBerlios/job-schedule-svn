<?php
$sql = "select score from fh_score where callsign = '$callsign' and reason = 'general Briefing'";
( $result = @mysql_query( $sql ) ) || die( 'no job-assignment' );
$row = @mysql_fetch_array( $result );
@mysql_free_result( $result );

echo "<form method='post' action='index.php'>\n";
echo "<input type='hidden' name='rm' value='check'>\n";
echo "<input type='hidden' name='request_id' value='$job'>\n";

$sql = "select id, question from fh_question where job_id = '$job'";
( $result = @mysql_query( $sql ) ) || die( 'Error' );
while( $q = @mysql_fetch_array( $result ) ) {
  echo "<fieldset><legend><b>{$q[1]}</b></legend>\n";
  echo "<input type='hidden' name='q{$q[0]}[]' value='0'>\n";
  echo "<table>\n";
  $sql2 = "select id, answer from fh_answer where question_id = '$q[0]'";
  ( $result2 = @mysql_query( $sql2 ) ) || die( 'Error' );
  while( $answer = @mysql_fetch_array( $result2 ) ) {
    echo "<tr><td><input type='checkbox' name='q{$q[0]}[]' value='$answer[0]'></td>";
    echo "<td>{$answer[1]}</td></tr>\n";
  }
  echo "</table></fieldset>\n";
  @mysql_free_result( $result2 );
}
@mysql_free_result( $result );
echo "<input type='submit'><input type='reset'>\n";
echo "</form>\n";
?>
