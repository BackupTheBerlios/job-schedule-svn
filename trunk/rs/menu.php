<p>
<b>Menu: [</b>
<?php
  echo "
<a href='$url/?rm=score'>My Score</a>
| <a href='$url/?rm=chcallsign'>change callsign</a>
| <a href='$url/?rm=job'>Assignment</a>
| <a href='$url/?rm=alias'>Manage Alias</a>
| <a target='_top' href='http://fhscenarios.jinak.cz/index.php?option=com_content&task=view&id=38&Itemid=54'>general Briefing</a>
";
if( $job && ! $side ) {
  echo "| <a href='$url/?rm=gentest'>test general Briefing</a>";
} elseif( $side == 'red' ) {
  echo "
| <a href=''>secret Briefing</a>
| <a href='$url/?rm=jobtest'>test secret Briefing</a>";
} elseif( $side == 'gold' ) {
  echo "
| <a href=''>secret Briefing</a>
| <a href='$url/?rm=jobtest'>test secret Briefing</a>";
}
echo "
| <a href='$url/?rm=mail_form'>write email</a>
| <a href='$url/?rm=mailread'>read email</a>
";
?>
<b>]</b></p>
