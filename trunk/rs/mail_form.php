<?php
if( $callsign == 'admin' ) {
  $sql2 = "select u.callsign from fh_user as u left outer join fh_side as s on s.callsign = u.callsign order by 1";
} else {
  $sql2 = "select callsign from fh_side where side = '$side' order by 1";
}
( $result2 = @mysql_query( $sql2 ) ) or die( @mysql_error() );
?>
<form method='post' action='index.php' name='f1'>
<input type='hidden' name='rm' value='send_mail'>
<table border='1'>
<tr><th>send email?</th><th>pilot</th></tr>
<tr><td align='right'><input type='checkbox' name='mail[]' value='admin'></td><td>admin</td></tr>
<?php
while( $p = @mysql_fetch_array( $result2, MYSQL_ASSOC ) ) {
  echo "<tr><td align='right'><input type='checkbox' name='mail[]' value='{$p["callsign"]}'></td>";
  echo "<td>{$p["callsign"]}</td></tr>\n";
}
?>
</table>
Subject: <input type='text' name='subject' value=''><br>
<textarea name='body' rows='8' cols='60' wrap='hard'></textarea><br>
<input type='submit'>
</form>
