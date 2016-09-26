<?php include("ini.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3>Backup &amp; Restore</h3>
  <br />
  Below are the backup and restore buttons, just click the backup button and a message will show up to tell whether the backup was successful or not. If you want to restore a backup, just select the date that you want to backup, but be aware that the data changes between the date of backup and the day that you restore will be erased permenantly! So only restore when you need to. Furthermore, backup is only allowed once per day, so if you click backup the second time, it will show up an error message. (Backup files are stored under the "backup" directory.)<br /><br />
  <?php //inital filepaths:
  $ini_filepath = "backup/";
  $filepath = "/Applications/MAMP/htdocs/restaurant/$ini_filepath";
  $tbl_array = array("order", "menu");
  //if backup:
  if (isset($_POST['backup'])) {$filename = date("m-d-Y");
  foreach ($tbl_array as $tbl) {
  //get auto increment value:
  $showresult = mysql_query("SHOW TABLE STATUS LIKE '$tbl'");
  $row = mysql_fetch_assoc($showresult);
  $next_increment = $row['Auto_increment'];
  //put backup into mysql:
  $backups[] = mysql_query("SELECT * INTO OUTFILE '$filepath$filename$tbl;$next_increment.sql' FROM restaurant.$tbl");}
  //result:
  if ($backups[0] === true && $backups[1] === true) {echo "<b>Database Backup Successful! ($filename)</b>";}
  else {echo "<b><font color=red>Database Backup Unsuccessful! Please resolve the problem.</font></b>";}}
  //if restore:
  elseif (isset($_POST['restore']) && $_POST['restorefile'] != "") {$restorefile = $_POST['restorefile'];
  foreach ($tbl_array as $tbl) {
  //delete tables first:
  mysql_query("DELETE FROM restaurant.$tbl");
  //get the file want to restore:
  $rfile = current(glob("$ini_filepath$restorefile$tbl;*.sql"));
  $rfile1 = str_replace(".sql", "", $rfile);
  $rfile2 = str_replace("backup/", "", $rfile1);
  $auto_increment = end(explode(";", $rfile2));
  //restore into mysql:
  $restores[] = mysql_query("LOAD DATA INFILE '$filepath$rfile2.sql' INTO TABLE restaurant.$tbl");
  //change auto increment to:
  $restores[] = mysql_query("ALTER TABLE restaurant.$tbl AUTO_INCREMENT=$auto_increment");}
  //result:
  if ($restores[0] === true && $restores[1] === true && $restores[2] === true && $restores[3] === true) {echo "<b>Database Restore Successful!</b>";}
  else {echo "<b><font color=red>Database Restore Unsuccessful! Please resolve the problem.</font></b>";}}?>
  <form method="post" action="">
  <input type="submit" value="Backup Database" name="backup" /><br />
  <b><font color=red>Warning! The changes between the backup date and today will be erased!</font></b><br />
  <select name="restorefile">
  <option value="">Select date to restore</option>
  <?php $files = glob("$ini_filepath*.sql");
  foreach ($files as $file) {$file = str_replace(".sql", "", str_replace("$ini_filepath", "", $file));
  $file = current(explode(";", $file));
  $files1[] = $file;
  $file = str_replace(".sql", "", substr($file, 0, 10));
  $files2[] = $file;}
  $files2 = array_unique($files2);
  $var = true;
  foreach ($files2 as $file2) {foreach ($tbl_array as $tbl) {
  if (!in_array("$file2$tbl", $files1)) {
  $var = false;}}
  if ($var == true) {echo "<option value=\"$file2\">$file2</option>";}}?></select>
  <input type="submit" value="Restore Database" name="restore" />
  </form>
  <br />
</div>

<?php include("foot.txt");?>