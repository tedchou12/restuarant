<?php include("ini.php");

$cat = $_GET['cat'];?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3>View All Orders</h3>
  <br />
  <a href="<?php echo $_SERVER['PHP_SELF'];?>">Back to Main</a>
  <select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<?php function select($cat1, $var){if ($cat1 == $var) echo " selected=\"selected\"";}?>
				<option value="">Categories</option>
				<option value=<?php echo "\"{$_SERVER['PHP_SELF']}?cat=togo\""; select($cat, "togo");?>>To Go Only</option>
				<option value=<?php echo "\"{$_SERVER['PHP_SELF']}?cat=forhere\""; select($cat, "forhere");?>>For Here Only</option>
  </select>
  <input id="catid" value="" type="text" size="10" maxlength="10" />
  <input type="button" value="Search for Order ID" onClick="redirecturl1(document.getElementById('catid').value)" />
  <?php $read = mysql_query("SELECT MIN(time) AS min_time FROM restaurant.order");
  $mintime = mysql_fetch_array($read);
  $time = explode(" ", $mintime['min_time']);
  $date = explode("-", $time[0]);
  //Get Year:
  $thisyear = date("Y");
  $firstyear = $date[0];
  //Month:
  $months = array("none", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
  echo "<form name=\"timecat\">
  <select name=\"year\">";
  while ($firstyear <= $thisyear) {echo "<option value=\"$firstyear\">$firstyear</option>"; $firstyear ++;}
  echo "</select>
  <select name=\"month\">";
  $read = 0;
  foreach ($months as $month) {echo "<option value=\"$read\">$month</option>"; $read ++;}
  echo "</select>
  <select name=\"day\">
  <option value=\"0\">none</option>";
  $read = 1;
  while ($read <= 31) {echo "<option value=\"$read\">$read</option>"; $read ++;}
  echo "</select>
  <input type=\"button\" value=\"Sort by Date!\" onClick=\"redirecturl()\" /></form>";?>
  <script language="javaScript">
  <!--
  function redirecturl() {
    var year = document.timecat.year.value;
    var month = document.timecat.month.value;
    var day = document.timecat.day.value;
    document.location.href="<?php echo "{$_SERVER['PHP_SELF']}";?>?cat=" + year + "-" + month + "-" + day;}
  // -->
  <!--
  function redirecturl1(id) {
    document.location.href="<?php echo "{$_SERVER['PHP_SELF']}";?>?cat=id" + id;}
  // -->
  </script>
  <br /><br />
  <?php //Sort out categories:
  if ($cat == "togo") {$cat_lim = " WHERE type='0'";}
  elseif ($cat == "forhere") {$cat_lim = " WHERE type='1'";}
  elseif (substr($cat, 0, 2) == "id") {$cat_lim = " WHERE id='". substr($cat, 2) ."'";}
  elseif ($cat != "") {$times = explode("-", $cat);
  if ($times[1] == 0) {$cat_lim = " WHERE order.time BETWEEN '{$times[0]}-01-1' AND '{$times[0]}-12-31'";}
  elseif ($times[2] == 0) {$cat_lim = " WHERE order.time BETWEEN '{$times[0]}-{$times[1]}-1' AND '{$times[0]}-{$times[1]}-31'";}
  else {$day2 = $times[2] + 1; $cat_lim = " WHERE order.time BETWEEN '{$times[0]}-{$times[1]}-{$times[2]}' AND '{$times[0]}-{$times[1]}-$day2'";}}
  
  //Table & Top Links
  echo "<table border=0 align=center cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
		<tr height=\"28px\">
		<td align=center width=\"80px\" background=\"images/gradient.jpg\"><b>Order ID</b></td>
		<td align=center width=\"120px\" background=\"images/gradient.jpg\"><b>Type</b></td>
		<td align=center width=\"250px\" background=\"images/gradient.jpg\"><b>Time & Date of Order</b></td>
		<td align=center width=\"200px\" background=\"images/gradient.jpg\"><b>Paid Status</b></td>
		</tr>";
  
  //Set number of entries on each page:
  $limit = $orderlist_table_lim;
  //Query the db to get total entries:
  $read = mysql_query("SELECT * FROM restaurant.order$cat_lim");
  $totalrows = mysql_num_rows($read);
  //Special Cases:
  $pagination = true;
  if ($totalrows == 0) {$pagination = false;
  echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=5 align=center><b>There are no orders in this category</b></td></tr>";}
  elseif ($read === false) {$pagination = false;
  echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=5 align=center><b>Sorry there are currently some errors in the database, please resolve the problem!</b></td></tr>";}
  
  if ($pagination == true) {
  //Page variable set:
  if (isset($_GET['page'])) {$page = $_GET['page'];}
  else {$page = 1;}
  //Set variables:
  $startvalue = ($page * $limit) - $limit;
  $totalpages = ceil($totalrows / $limit);
  //Read entries from mysql:
  $read = mysql_query("SELECT * FROM restaurant.order$cat_lim ORDER BY time DESC LIMIT $startvalue, $limit");
  while ($r = mysql_fetch_array($read)) {
  //Start looping through the entries:
  $datetime = date("M d, Y h:i A", $r['time']);
  //Sort the type of order out:
  if ($r['type'] == 0) {$type = "To Go";}
  else {$type = "For Here";}
  //Sort the paid status:
  if ($r['paid'] == 0) {$paid = "<font color=\"red\">No</font>";}
  else {if ($r['paid'] == 1) {$paid = "Cash";}
  if ($r['paid'] == 2) {$paid = "Visa";}
  if ($r['paid'] == 3) {$paid = "Mastercard";}
  if ($r['paid'] == 4) {$paid = "American Express";}
  if ($r['paid'] == 5) {$paid = "Discover";}}
  //Change time format:
  $time = date("M j, Y g:i a", strtotime($r['time']));
  echo "<tr height=\"28px\" bgcolor=\"white\" onclick=\"window.location.href='vieworder.php?ordernumber={$r['id']}'\">
		<td align=center>{$r['id']}</td>
		<td align=center>$type</td>
		<td align=center>$time</td>
		<td align=center>$paid</td>
		</tr>";}}
  //Close the table
  echo "</table>";
  if ($pagination == true) {
  //Starts page links:
  echo "<p style=\"text-align: center;\">";
  //Sets link for first page:
  if ($page != 1) {$pageprev = $page - 1;
  echo "<a href=\"{$_SERVER['PHP_SELF']}?&cat=$cat&page=1\"><<</a>&nbsp;&nbsp;";
  echo "<a href=\"{$_SERVER['PHP_SELF']}?&cat=$cat&page=$pageprev\">PREV&nbsp;</a>&nbsp;";}
  else {echo "PREV&nbsp;";}
  //Page range sorting:
  if ($page <= 5) {$pagelowerlim = 1;} else {
  if ($totalpages <= 11) {$pagelowerlim = 1;}
  else {if (($totalpages - $page) < 5) {$pagelowerlim = $totalpages - 10;}
  else {$pagelowerlim = $page - 5;}}}
  if ($page <= 5) {
  if ($totalpages <= 11) {$pageupperlim = $totalpages;} else {$pageupperlim = 11;}}
  else {if (($totalpages - $page) >= 5) {$pageupperlim = $page + 5;} else {$pageupperlim = $totalpages;}}
  //Looping through page numbers:
  $i = $pagelowerlim;
  while ($i <= $pageupperlim) {
  if ($i == $page) {echo "[$i] ";}
  else {echo "<a href=\"{$_SERVER['PHP_SELF']}?&cat=$cat&page=$i\">$i</a> ";}
  $i ++;}
  //Set link for last page:
  if ($page != $totalpages) {$pagenext = $page + 1;
  echo "<a href=\"{$_SERVER['PHP_SELF']}?&cat=$cat&page=$pagenext\">NEXT&nbsp;</a>&nbsp;";
  echo "<a href=\"{$_SERVER['PHP_SELF']}?&cat=$cat&page=$totalpages\">>></a>";}
  else {echo "NEXT&nbsp;";}
  echo "</p>";}?>
  <br />
</div>

<?php include("foot.txt");?>