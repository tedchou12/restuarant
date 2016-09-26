<?php include("ini.php");

$type = $_GET['type'];
$cat = $_GET['cat'];?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3>Kitchen To Make List</h3>
  <br />
  <a href="<?php echo $_SERVER['PHP_SELF'];?>">Back to Main</a>
  <select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<option value="">Dish Category</option>
			<?php $read = 0;
			foreach ($catarray as $cattype => $type_no) {echo "<option value=\"{$_SERVER['PHP_SELF']}?type=$read&cat=$cat\"";
			if ($type == $read && isset($type) == true) {echo " selected";}
			echo ">$cattype</option>"; $read ++;}?>
  </select>
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
    document.location.href="<?php echo "{$_SERVER['PHP_SELF']}";?>?type=<?php echo $type;?>&cat=" + year + "-" + month + "-" + day;}
  // -->
  </script>
  <br /><br />
  <?php //Sort out categories:
  if ($cat != "") {$times = explode("-", $cat);
  if ($times[1] == 0) {$cat_lim = " WHERE order.time BETWEEN '{$times[0]}-01-1' AND '{$times[0]}-12-31'";}
  elseif ($times[2] == 0) {$cat_lim = " WHERE order.time BETWEEN '{$times[0]}-{$times[1]}-1' AND '{$times[0]}-{$times[1]}-31'";}
  else {$day2 = $times[2] + 1; $cat_lim = " WHERE order.time BETWEEN '{$times[0]}-{$times[1]}-{$times[2]}' AND '{$times[0]}-{$times[1]}-$day2'";}}
  
  //Table & Top Links
  echo "<table border=0 align=center cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
		<tr height=\"28px\">
		<td align=center width=\"80px\" background=\"images/gradient.jpg\"><b>Order ID</b></td>
		<td align=center width=\"80px\" background=\"images/gradient.jpg\"><b>Dish ID</b></td>
		<td align=center width=\"250px\" background=\"images/gradient.jpg\"><b>Dish Name</b></td>
		<td align=center width=\"180px\" background=\"images/gradient.jpg\"><b>Time & Date of Order</b></td>
		<td align=center width=\"100px\" background=\"images/gradient.jpg\"><b>Type</b></td>
		</tr>";
  
  //Set number of entries on each page:
  $limit = $kitchenlist_table_lim;
  //Query the db to get total entries:
  $read = mysql_query("SELECT * FROM restaurant.order$cat_lim");
  while ($r = mysql_fetch_array($read)) {
  $orderlists = explode(",", $r['orders']);
  foreach ($orderlists as $orderlist) {$orders = explode("-", $orderlist);
  $in_array = false;
  if ($type != "" && isset($type)) {$read3 = mysql_query("SELECT * FROM restaurant.menu WHERE id={$orders[0]}");
  $r3 = mysql_fetch_array($read3);
  $read4 = 0;
  foreach ($catarray as $cattype => $type_no) {
  if ($type == $read4) {if ($type_no == 999) {foreach ($catarray as $catype1 => $type_no1) {if ($type_no1 != 999) {
  $in_array10 = explode(",", $type_no1); foreach ($in_array10 as $in_array11) {$in_array1[] = $in_array11;}}}
  array_unique($in_array1);
  $dishtypes = count(file("dishtypes.txt"));
  $read5 = 0;
  while ($read5 < $dishtypes) {$dishtypes1[] = $read5; $read5 ++;}
  $dishtypes = array_diff($dishtypes1, $in_array1);
  foreach ($dishtypes as $type_no) {if ($r3['type'] == $type_no) {$in_array = true;}}}
  else {$type_nos = explode(",", $type_no);
  foreach ($type_nos as $type_no) {if ($r3['type'] == $type_no) {$in_array = true;}}}}
  $read4 ++;}}
  else {$in_array = true;}
  if ($in_array == true) {
  $read1 = 0;
  while ($read1 < $orders[1]) {$orderlist_1f[] = array($r['time'], $r['id'], $orders[0], $r['type']); $read1 ++;}}}}
  $totalrows = count($orderlist_1f);
  rsort($orderlist_1f);
  //Special Cases:
  $pagination = true;
  if ($totalrows == 0) {$pagination = false;
  echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=5 align=center><b>There are no dishes in this category</b></td></tr>";}
  elseif ($read === false) {$pagination = false;
  echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=5 align=center><b>Sorry there are currently some errors in the database, please resolve the problem!</b></td></tr>";}
  
  if ($pagination == true) {
  //Page variable set:
  if (isset($_GET['page'])) {$page = $_GET['page'];}
  else {$page = 1;}
  //Set variables:
  $startvalue = ($page * $limit) - $limit;
  $numofpages = $totalrows / $limit;
  $totalpages = ceil($numofpages);
  if ($page == $totalpages) {$endvalue = $totalrows;}
  else {$endvalue = $startvalue + $limit;}
  //Read entries from array:
  while ($startvalue < $endvalue) {$orderlist_2f = $orderlist_1f[$startvalue];
  //Sort the dish name out:
  $read2 = mysql_query("SELECT * FROM restaurant.menu WHERE id={$orderlist_2f[2]}");
  $r2 = mysql_fetch_array($read2);
  //Sort the type of order:
  if ($orderlist_2f[3] == 0) {$type1 = "To Go";}
  else {$type1 = "For Here";}
  echo "<tr height=\"28px\" bgcolor=\"white\" onclick=\"window.location.href='vieworder.php?ordernumber={$orderlist_2f[1]}'\">
		<td align=center>{$orderlist_2f[1]}</td>
		<td align=center>{$orderlist_2f[2]}</td>
		<td align=center>{$r2[name]}</td>
		<td align=center>{$orderlist_2f[0]}</td>
		<td align=center>$type1</td>
		</tr>";
  $startvalue ++;}}
  //Close the table
  echo "</table>";
  if ($pagination == true) {
  //Starts page links:
  echo "<p style=\"text-align: center;\">";
  //Sets link for first page:
  if ($page != 1) {$pageprev = $page - 1;
  echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type&cat=$cat&page=1\"><<</a>&nbsp;&nbsp;";
  echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type&cat=$cat&page=$pageprev\">PREV&nbsp;</a>&nbsp;";}
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
  else {echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type&cat=$cat&page=$i\">$i</a> ";}
  $i ++;}
  //Set link for last page:
  if ($page != $totalpages) {$pagenext = $page + 1;
  echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type&cat=$cat&page=$pagenext\">NEXT&nbsp;</a>&nbsp;";
  echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type&cat=$cat&page=$totalpages\">>></a>";}
  else {echo "NEXT&nbsp;";}
  echo "</p>";}?>
  <br />
</div>

<?php include("foot.txt");?>