<?php
$req_id = $HTTP_POST_VARS[request_id];
$sql = "select id, question from fh_question where job_id = '$req_id'";
( $result = @mysql_query( $sql ) ) || die( @mysql_error() );
while( $q = @mysql_fetch_array( $result ) ) {
  echo "<h4>{$q[1]}</h4>";
  echo "<table border='1'>\n";
  echo "<tr><th>This Answer was correct / incorrect:</th>";
  echo "<th>Answer-Text</th>";
  echo "<th>If you made it right, you get so many points</th>";
  echo "<th>If you made it wrong, you loose so many points</th>";
  echo "<th>You made it right / wrong</th>";
  echo "<th>You have so many Points after all Answers above</th></tr>\n";
  $sql2 = "select id, answer, minus_points, plus_points, correct from fh_answer where question_id = '$q[0]'";
  ( $result2 = @mysql_query( $sql2 ) ) || die( @mysql_error() );
  while( $answer = @mysql_fetch_array( $result2 ) ) {
  	if( $answer['correct'] == 1 ) {
      $img = 'check.png';
      if( in_array( $answer['id'], $HTTP_POST_VARS["q{$q[0]}"] ) ) {
        $points += $answer['plus_points'];
        $p = 'up_plus.png';
      } else {
        $points -= $answer['minus_points'];
        $p = 'down_minus.png';
      }
    } else {
      $img = 'error.png';
      if( in_array( $answer['id'], $HTTP_POST_VARS["q{$q[0]}"] ) ) {
        $points -= $answer['minus_points'];
        $p = 'down_minus.png';
      } else {
        $points += $answer['plus_points'];
        $p = 'up_plus.png';
      }
    }
	echo "<tr><td><img src='icons/$img'></td><td>{$answer[1]}";
	echo "</td><td>{$answer['plus_points']}</td><td>{$answer['minus_points']}</td>";
    echo "<td><img src='icons/$p'></td><td>$points</td></tr>\n";
  }
  echo "</table>\n";
  @mysql_free_result( $result2 );
}
@mysql_free_result( $result );

echo "<p>Total Points: $points</p>\n";

if( $points > 0 ) {
  $sql4 = "select side from fh_job where job = '$job'";
  ( $result4 = @mysql_query( $sql4 ) ) || die( @mysql_error() );
  $row4 = @mysql_fetch_array( $result4 );  
  $sql5 = "INSERT INTO fh_side ( callsign, side ) VALUES ( '$callsign', '$row4[0]' )";
  @mysql_query( $sql5 );
  $sql7 = "delete from fh_score where callsign = '$callsign' and reason = 'test for general briefing'";
  @mysql_query( $sql7 );
  $sql6 = "insert into fh_score ( callsign, score, reason ) values ( '$callsign', '$points', 3 )";
  @mysql_query( $sql6 );
} else {
  echo "you failed the test, but you can try this test again.";
}
?>
