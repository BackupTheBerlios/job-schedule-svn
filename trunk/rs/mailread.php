<?php
if( $HTTP_GET_VARS["unread"] ) {
  echo "<h3>You are redirected to this email, because you didn't confirm until now,
that you have read this email.<br />
Before you can go to anywhere else, you have to read this email and click on
'Confirm now' at the Receiver-List below this Email.</h3>
";
}
$id = $HTTP_GET_VARS["id"];
if( $id ) {
  $sql1 = "select m.* from fh_mail as m left join fh_mail_recv as r on r.mail = m.id where r.id = '$id'";
  ( $result1 = @mysql_query( $sql1 ) ) or die( @mysql_error );
  $row1 = @mysql_fetch_array( $result1, MYSQL_ASSOC );
  if( $row["confirm"] == 0 ) {
    $sql2 = "update fh_mail_recv set confirm = 1 where id = '$id'";
    @mysql_query( $sql2 ) or die( @mysql_error );
    if( @mysql_affected_rows() == 1 ) echo "thank you for confirming this email.<br>\n";
    $mailid = $row1["id"];
  }
}

function receiver( $mid ) {
  global $callsign;
  $sql4 = "select id, receiver, confirm from fh_mail_recv where mail = '$mid' order by 3, 2";
  ( $result4 = @mysql_query( $sql4 ) ) or die( @mysql_error );
  echo "<table border='1'><tr><th>receiver</th><th>confirmed?</th></tr>\n";
  while( $row4 = @mysql_fetch_array( $result4 ) ) {
    if( $row4["confirm"] ) {
      $c = 'yes';
    } elseif( $row4["receiver"] == $callsign ) {
      $c = "<a href='?rm=mailread&id={$row4["id"]}'>Confirm now</a>";
    } else {
      $c = 'no';
    }
    echo "<tr><td>{$row4["receiver"]}</td><td>$c</td></tr>\n";
  }
  echo "</table>\n";
} # receiver

if( $HTTP_GET_VARS["mailid"] ) $mailid = $HTTP_GET_VARS["mailid"];
if( $HTTP_POST_VARS["mailid"] ) $mailid = $HTTP_POST_VARS["mailid"];
if( $mailid ) {
  $sql3 = "select m.id, m.subject, m.sender, unix_timestamp( m.ts ) as ts, m.text from fh_mail as m left outer join fh_mail_recv as r on m.id = r.mail where m.id = '$mailid' and ( r.receiver = '$callsign' or m.sender = '$callsign' )";
  ( $result3 = @mysql_query( $sql3 ) ) or die( @mysql_error );
  $row3 = @mysql_fetch_array( $result3 );
  if( $row3["id"] ) {
    echo "<pre>Date:    ", gmstrftime( '%Y-%m-%d %H:%M:%S', $row3["ts"] ), "\n";
    echo "From:    {$row3["sender"]}
Subject: {$row3["subject"]}
  
{$row3["text"]}
</pre>";
    receiver( $row3["id"] );
  } else {
    echo "<h2>this email does not exist or was not sent to '$callsign'</h2>";
  }
}
?>
<h1>All Messages to you:</h1>
<table border=1>
<tr><th>date</th><th>subject</th><th>sender</th></tr>
<?php
$sql4 = "select m.id, m.subject, m.sender, unix_timestamp( m.ts ) as ts from fh_mail as m, fh_mail_recv as r where r.mail = m.id and r.receiver = '$callsign' order by ts";
( $result4 = @mysql_query( $sql4 ) ) or die( @mysql_error );
while( $row4 = @mysql_fetch_array( $result4 ) ) {
  echo "<tr><td>", gmstrftime( '%Y-%m-%d %H:%M:%S', $row4['ts'] ), "</td>";
  if( ! $row4['subject'] ) $row4['subject'] = '{empty subject}';
  echo "<td><a href='?rm=mailread&mailid={$row4['id']}'>{$row4['subject']}</td>";
  echo "<td>{$row4['sender']}</td></tr>\n";
}
?>
</table>
<h1>All Messages from you:</h1>
<table border=1>
<tr><th>date</th><th>subject</th></tr>
<?php
$sql5 = "select id, subject, unix_timestamp( ts ) as ts from fh_mail where sender = '$callsign' order by ts";
( $result5 = @mysql_query( $sql5 ) ) or die( @mysql_error );
while( $row5 = @mysql_fetch_array( $result5 ) ) {
  echo "<tr><td>", gmstrftime( '%Y-%m-%d %H:%M:%S', $row5['ts'] ), "</td>";
  if( ! $row5['subject'] ) $row5['subject'] = '{empty subject}';
  echo "<td><a href='?rm=mailread&mailid={$row5['id']}'>{$row5['subject']}</td></tr>\n";
}
?>
</table>
