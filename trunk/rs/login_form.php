<H4>Login:</H4>
<form method="POST" action="index.php">
<input type='hidden' name='rm' value='login'>
Username: <input type="text" name="username" size="20">
Password: <input type="password" name="password" size="20">
<input type="submit" value="Submit" name="login">
</form>
<hr>
<H4>Register new Account:</H4>
<form method="POST" action="index.php">
<input type='hidden' name='rm' value='register'>
Callsign: <input type="text" name="cs" size="20">
Email: <input type="text" name="mail" size="40">
<input type="submit" value="Submit" name="register">
</form>
<hr>
<H4>Forgotten Password?<BR>Create new Password and mail it to the old Mailaddress:</H4>
<form method="POST" action="index.php">
<input type='hidden' name='rm' value='password_new'>
Callsign: <input type="text" name="cs" size="6">
Email: <input type="text" name="mail" size="40">
<input type="submit" value="Submit" name="register">
</form>
