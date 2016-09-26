<?php include("ini.php");

$cat = $_GET['cat'];?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3>Total Income:</h3>
  <br />
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
  </script>
  <?php if ($cat == "") {$cat = date("Y-n-j");}
  
  $times = explode("-", $cat);
  if ($times[1] == 0) {$cat_lim = " WHERE order.time BETWEEN '{$times[0]}-01-1' AND '{$times[0]}-12-31'";}
  elseif ($times[2] == 0) {$cat_lim = " WHERE order.time BETWEEN '{$times[0]}-{$times[1]}-1' AND '{$times[0]}-{$times[1]}-31'";}
  else {$day2 = $times[2] + 1; $cat_lim = " WHERE order.time BETWEEN '{$times[0]}-{$times[1]}-{$times[2]}' AND '{$times[0]}-{$times[1]}-$day2'";}
  $read = mysql_query("SELECT * FROM restaurant.order$cat_lim");
  while ($r = mysql_fetch_array($read)) {
  $customers[] = $r['guestno'];
  if ($r['type'] == 0) {$togos = $togos + 1;}
  $orders = explode(",", $r['orders']);
  foreach ($orders as $order) {$orderlis = explode("-", $order);
  $dishesarray[] = $order;}
  if ($r['paid'] == 0) {$unpaid_orders[] = "<font color=red><a href=\"vieworder.php?ordernumber={$r['id']}\">{$r['id']}</a></font>";}
  else {unset($pricelist);
  $orders = explode(",", $r['orders']);
  foreach ($orders as $order) {$orderlis = explode("-", $order);
  $read1 = mysql_query("SELECT * FROM restaurant.menu WHERE id='{$orderlis[0]}'");
  $r1 = mysql_fetch_array($read1);
  if ($r['coupon'] != 0) {
  //Coupon specific category:
  $couponcats = explode(",", $r['couponcat']);
  foreach ($couponcats as $couponcat) {if ($r1['type'] == $couponcat) {$bonuses1 = round($r1['price'] * $orderlis[1] * ($r['coupon']/100), 2);
  $bonuses2 = explode(".", $bonuses1);
  if (count($price2) == 2) {
  $bonuses2_lastdigit = substr($price2[1], 1);
  if ($bonuses2_lastdigit == 1 or $bonuses2_lastdigit == 6) {$bonuses1 = $bonuses1 - 0.01;}
  elseif ($bonuses2_lastdigit == 2 or $bonuses2_lastdigit == 7) {$bonuses1 = $bonuses1 - 0.02;}
  elseif ($bonuses2_lastdigit == 3 or $bonuses2_lastdigit == 8) {$bonuses1 = $bonuses1 + 0.02;}
  elseif ($bonuses2_lastdigit == 4 or $bonuses2_lastdigit == 9) {$bonuses1 = $bonuses1 + 0.01;}}
  $bonuses[] = $bonuses1;}}}
  $pricelist1 = round($r1['price'] * $orderlis[1], 2);
  $pricelist2 = explode(".", $pricelist1);
  if (count($pricelist2) == 2) {
  $pricelist2_lastdigit = substr($pricelist2[1], 1);
  if ($pricelist2_lastdigit == 1 or $pricelist2_lastdigit == 6) {$pricelist1 = $pricelist1 - 0.01;}
  elseif ($pricelist2_lastdigit == 2 or $pricelist2_lastdigit == 7) {$pricelist1 = $pricelist1 - 0.02;}
  elseif ($pricelist2_lastdigit == 3 or $pricelist2_lastdigit == 8) {$pricelist1 = $pricelist1 + 0.02;}
  elseif ($pricelist2_lastdigit == 4 or $pricelist2_lastdigit == 9) {$pricelist1 = $pricelist1 + 0.01;}}
  $pricelist[] = $pricelist1;
  $types = file("dishtypes.txt", FILE_IGNORE_NEW_LINES);
  $read4 = 0;
  $in_taxlist = false;
  foreach ($types as $type1) {$type1 = explode(",", $type1);
  if ($r1['type'] == $read4 && $type1[1] != "") {$taxlist[] = ($pricelist1 - $bonuses1) * $type1[1]; $in_taxlist = true;} $read4 ++;}
  if ($in_taxlist == false) {$taxlist[] = ($pricelist1 - $bonuses1) * $tax_rate;}}
  if ($r['specialorder'] != "") {$specialorders = explode(",", $r['specialorder']);
  $pricelist1 = round($specialorders[1] * $specialorders[0], 2);
  $pricelist2 = explode(".", $pricelist1);
  if (count($pricelist2) == 2) {
  $pricelist2_lastdigit = substr($pricelist2[1], 1);
  if ($pricelist2_lastdigit == 1 or $pricelist2_lastdigit == 6) {$pricelist1 = $pricelist1 - 0.01;}
  elseif ($pricelist2_lastdigit == 2 or $pricelist2_lastdigit == 7) {$pricelist1 = $pricelist1 - 0.02;}
  elseif ($pricelist2_lastdigit == 3 or $pricelist2_lastdigit == 8) {$pricelist1 = $pricelist1 + 0.02;}
  elseif ($pricelist2_lastdigit == 4 or $pricelist2_lastdigit == 9) {$pricelist1 = $pricelist1 + 0.01;}}
  $pricelist[] = $pricelist1;
  $taxlist[] = $pricelist1 * $tax_rate;}
  $shared_det = floor(array_sum($pricelist)/$shared_cost);
  if ($shared_det < $r['guestno']  && $r['type'] == 1) {
  $pricelist1 = round($shared_cost * ($r['guestno'] - $shared_det), 2);
  $pricelist2 = explode(".", $pricelist1);
  if (count($pricelist2) == 2) {
  $pricelist2_lastdigit = substr($pricelist2[1], 1);
  if ($pricelist2_lastdigit == 1 or $pricelist2_lastdigit == 6) {$pricelist1 = $pricelist1 - 0.01;}
  elseif ($pricelist2_lastdigit == 2 or $pricelist2_lastdigit == 7) {$pricelist1 = $pricelist1 - 0.02;}
  elseif ($pricelist2_lastdigit == 3 or $pricelist2_lastdigit == 8) {$pricelist1 = $pricelist1 + 0.02;}
  elseif ($pricelist2_lastdigit == 4 or $pricelist2_lastdigit == 9) {$pricelist1 = $pricelist1 + 0.01;}}
  $pricelist2 = number_format($pricelist1, 2, ".", "");
  $pricelist[] = $pricelist1;
  $taxlist[] = $pricelist1 * $tax_rate;}
  //Calculating Dinner Total Cost:
  //Bonus discount from coupon:
  $discount = array_sum($bonuses);
  //Sum up the cost:
  $subtotal = array_sum($pricelist) - $discount;
  if ($r['coupon1'] != 0) {$subtotal = $subtotal - $r['coupon1'];}
  $subtotals[] = $subtotal;
  //Calculating tax:
  $tax = round(array_sum($taxlist), 2);
  $tax1 = explode(".", $tax);
  if (count($tax1) == 2) {
  $tax_lastdigit = substr($tax1[1], 1);
  if ($tax_lastdigit == 1 or $tax_lastdigit == 6) {$tax = $tax - 0.01;}
  elseif ($tax_lastdigit == 2 or $tax_lastdigit == 7) {$tax = $tax - 0.02;}
  elseif ($tax_lastdigit == 3 or $tax_lastdigit == 8) {$tax = $tax + 0.02;}
  elseif ($tax_lastdigit == 4 or $tax_lastdigit == 9) {$tax = $tax + 0.01;}}
  $taxes[] = $tax;
  //8 Guests service fee:
  if ($r['guestno'] >= $guest_no_ini) {$guest8 = round(($subtotal + $tax) * $guest_rate_ini, 2);
  $guest8_2 = explode(".", $guest8);
  if (count($guest8_2) == 2) {
  $guest8_lastdigit = substr($guest8_2[1], 1);
  if ($guest8_lastdigit == 1 or $guest8_lastdigit == 6) {$guest8 = $guest8 - 0.01;}
  elseif ($guest8_lastdigit == 2 or $guest8_lastdigit == 7) {$guest8 = $guest8 - 0.02;}
  elseif ($guest8_lastdigit == 3 or $guest8_lastdigit == 8) {$guest8 = $guest8 + 0.02;}
  elseif ($guest8_lastdigit == 4 or $guest8_lastdigit == 9) {$guest8 = $guest8 + 0.01;}}
  $guest8s[] = $guest8;}
  //Total:
  $total = round($subtotal + $tax + $guest8, 2);
  $totals[] = $total;
  //Cash/Credit Card:
  if ($r['paid'] == 1) {$cash[] = $total;}
  else {$credit[] = $total;}}}
  $subtotals = array_sum($subtotals);
  if ($subtotals == "") {$subtotals = 0;}
  $guest8s = array_sum($guest8s);
  if ($guest8s == "") {$guest8s = 0;}
  $taxes = array_sum($taxes);
  if ($taxes == "") {$taxes = 0;}
  $totals = array_sum($totals);
  if ($totals == "") {$totals = 0;}
  $cash = array_sum($cash);
  if ($cash == "") {$cash = 0;}
  $credit = array_sum($credit);
  if ($credit == "") {$credit = 0;}
  //Unpaid IDs:
  $unpaid_orders = implode(", ", $unpaid_orders);
  //Guest Number:
  $customers = array_sum($customers);
  
  echo "<b>Total Made:</b> $ $totals<br />";
  echo "<b>Taxes Deducted:</b> $ $taxes<br />";
  echo "<b>Service Fee (Party of $guest_no_ini):</b> $ $guest8s<br />";
  echo "<b>Revenue Made:</b> $ $subtotals<br />";
  echo "<b>Cash on Hold:</b> $ $cash<br />";
  echo "<b>Credit Card Credits:</b> $ $credit<br />";
  echo "<b>Total Guest Number (For Here Only):</b> $customers<br />";
  echo "<b>Number of To Go Orders:</b> $togos<br />";
  if ($unpaid_orders != "") {echo "<b>Unpaid Orders:</b> $unpaid_orders";}?>
  <br />
  <b>Inventory Sold by Popularity:</b><br />
  <?php //inventory list:
  $read2 = mysql_query("SELECT MAX(id) AS max_id FROM restaurant.menu");
  $r2 = mysql_fetch_array($read2);
  $read3 = 1;
  while ($read3 <= $r2['max_id']) {
  foreach ($dishesarray as $dishes) {$dishes = explode("-", $dishes);
  if ($read3 == $dishes[0]) {$array[$read3] = $array[$read3] + $dishes[1];}}
  $read3 ++;}
  arsort($array);
  foreach ($array as $key => $val) {
  $read4 = mysql_query("SELECT name FROM restaurant.menu WHERE id='$key'");
  $r4 = mysql_fetch_array($read4);
  if ($val != 0) {echo "<a href=\"dish.php?dishnumber=$key\"><b>{$r4['name']}</b></a> ($val), ";}}?>
</div>

<?php include("foot.txt");?>