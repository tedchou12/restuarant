<?php include("ini.php");

$cat = $_GET['cat'];?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3>View All Dishes</h3>
  <br />
  <a href="<?php echo $_SERVER['PHP_SELF'];?>">Back to Main</a> |
  <a href="<?php echo "{$_SERVER['PHP_SELF']}?cat=unavailables";?>">Unavailables</a>
  <select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<option value="">Type of Dish</option>
			<?php $read = 0;
			$types = file("dishtypes.txt", FILE_IGNORE_NEW_LINES);
			foreach ($types as $type) {$type = current(explode(",", $type));
			echo "<option value=\"{$_SERVER['PHP_SELF']}?cat=$read\"";
			if ($cat == $read && isset($cat) == true) {echo " selected";}
			echo ">$type</option>"; $read ++;}
			function select($cat1, $var){if ($cat1 == $var) echo " selected=\"selected\"";}?>
  </select>
  <input id="name" value="" type="text" size="10" maxlength="100" />
  <input type="button" value="Search for Dish Name" onClick="redirecturl1(document.getElementById('name').value)" />
  <script language="javaScript">
  <!--
  function redirecturl1(id) {
    document.location.href="<?php echo "{$_SERVER['PHP_SELF']}";?>?cat=name" + id;}
  // -->
  </script>
  <br /><br />
  <?php //Sort out categories:
  if ($cat == "unavailables") {$cat_lim = " WHERE availability='1'";}
  elseif (substr($cat, 0, 4) == "name") {$cat_lim = " WHERE name LIKE '%". substr($cat, 4) ."%'";}
  elseif ($cat != "" && isset($cat) == true) {$cat_lim = " WHERE type='$cat'";}
  
  //Table & Top Links
  echo "<table border=0 align=center cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
		<tr height=\"28px\">
		<td align=center width=\"80px\" background=\"images/gradient.jpg\"><b>Dish ID</b></td>
		<td align=center width=\"230px\" background=\"images/gradient.jpg\"><b>Dish Name</b></td>
		<td align=center width=\"150px\" background=\"images/gradient.jpg\"><b>Dish Type</b></td>
		<td align=center width=\"140px\" background=\"images/gradient.jpg\"><b>Availability</b></td>
		<td align=center width=\"70px\" background=\"images/gradient.jpg\"><b>Price</b></td>
		</tr>";
  
  //Set number of entries on each page:
  $limit = $dishlist_table_lim;
  //Query the db to get total entries:
  $read = mysql_query("SELECT * FROM restaurant.menu$cat_lim");
  $totalrows = mysql_num_rows($read);
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
  $totalpages = ceil($totalrows / $limit);
  //Read entries from mysql:
  $read = mysql_query("SELECT * FROM restaurant.menu$cat_lim ORDER BY menu.label LIMIT $startvalue, $limit");
  while ($r = mysql_fetch_array($read)) {
  //Sort the type of dish out:
  $read1 = 0;
  foreach ($types as $type1) {$type1 = current(explode(",", $type1));
  if ($r['type'] == $read1) {$type2 = $type1;}
  $read1 ++;}
  //Sort the availability out:
  if ($r['availability'] == 1) {$availability = "<font color=red>Not Available</font>";}
  else {$availability = "Available";}
  echo "<tr height=\"28px\" bgcolor=\"white\" onclick=\"window.location.href='dish.php?dishnumber={$r['id']}'\">
		<td align=center>{$r['label']}</td>
		<td align=center>{$r['name']}</td>
		<td align=center>$type2</td>
		<td align=center>$availability</td>
		<td align=center>$ {$r['price']}</td>
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