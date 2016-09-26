<?php include("ini.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3>Help &amp; Guide</h3>
  <br />
  Hi, this is Ted, I tried to design this to fit the restaurant system as ideal as possible, however, there may be problems occasionally due to some unpredictable situations, this page is designed to guide you smoothly through solving problems in various situations, please find the appropriate heading for specific problems:<br /><br />
  <b>How to turn on server manually?</b><br />
  If the server does not startup automatically, for such circumstances, you must turn on the server manually. For signs of server not properly started are:<br />
  1. Page not found under <a href="http://localhost/">http://localhost/</a>:<br />
  <img src="helpdemo/1.jpg" style="max-width: 500px;" /><br />
  2. Apache server tray icon displays red stop sign:<br />
  <img src="helpdemo/2.jpg" style="max-width: 500px;" /><br />
  3. Wamp server tray icon displays red:<br />
  <img src="helpdemo/3.jpg" style="max-width: 500px;" /><br />
  4. Wamp server database not started:<br />
  <img src="helpdemo/4.jpg" style="max-width: 500px;" /><br />
  Under any of this circumstances, firstly, double click the apache tray icon (picture with red feather), and select wampapache and click start:<br />
  <img src="helpdemo/5.jpg" style="max-width: 500px;" /><br />
  Go to start menu, all programs, WampServer, click on start WampServer:<br />
  <img src="helpdemo/6.jpg" style="max-width: 500px;" /><br />
  This should get the server start working, if the database still doesn't load, then you should contact me for further technical problems.<br /><br />
  <b>How to let the server start automatically each time the windows startup?</b><br />
  Go to start menu, all programs, WampServer, hold and drag the "start WampServer" to the start menu, all programs, startup folder.<br />
  <img src="helpdemo/7.jpg" style="max-width: 500px;" /><br /><br />
  <b>How do I change number of guests required for a extra service charge and the rate of service charge for it, timezone, general tax rate, number of items in each of the lists, kitchen categories, coupon details and shared costs if customer shares a dish?</b><br />
  All of these preferces are available for changes under the file "ini.php", open it with note pad or a text editor, and change according to the instructions inside<br />
  <img src="helpdemo/8.jpg" style="max-width: 500px;" /><br /><br />
  <b>How do I change the names of the categories of dish types?</b><br />
  This is under the file "dishtypes.txt", open it with notepad or a text editor program, and you will be able to change it, if you wish to add a new category, simply add a new line. For a special tax rate, eg 10% for alcohol, instead of 7%, just put a comma after the name and put the tax rate as a decimal number, for example:<br />
  <img src="helpdemo/9.jpg" style="max-width: 500px;" /><br /><br />
  <b>How do I change the names or add a waiter/waitress?</b><br />
  This is under the file "waiters.txt", again, open it with notepad or a text editor program, and you will be able to change it, if you wish to add a new waiter/waitress, simply add it under a new line.<br />
  <img src="helpdemo/10.jpg" style="max-width: 500px;" /><br /><br />
  <b>How to download a backup of the database? (*Important)</b><br />
  Backup is available on this page <a href="backup.php">Backup</a>, if for some reason, you cannot get the backup data successfuly, please visit: <a href="../phpmyadmin/db_export.php?db=restaurant">http://localhost/phpmyadmin/db_export.php?db=restaurant</a> for a complete backup. Make sure all of your tables are selected, SQL is checked and "zipped" is checked like the following picture:<br />
  <img src="helpdemo/11.jpg" style="max-width: 500px;" /><br />
  Also, make sure that backup is done at lease once every two days, check if the backups do work properly. Signs of corrupted files means to do the backup again or you should restore a previous backup.<br /><br />
  <br />
</div>

<?php include("foot.txt");?>