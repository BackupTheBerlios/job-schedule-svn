<table border='1'>
<tr><th>reason</th><th>score</th></tr>
<?php
$sql2 = "select reason, score from fh_score where callsign = '$callsign'";
($result2 = @mysql_query( $sql2 )) || die( @mysql_error() );
$score = Array();
while( $row = @mysql_fetch_array( $result2 ) ) {
  $score{$row['reason']} = $row['score'];
}
$sql1 = "select reason_id, text from fh_reason order by reason_id";
($result1 = @mysql_query( $sql1 )) || die( @mysql_error() );
while( $row = @mysql_fetch_array( $result1 ) ) {
  echo "<tr><td>{$row['text']}</td>";
  echo "<td align='right'>{$score{$row['reason_id']}}</td></tr>\n";
}
?>
</table>
