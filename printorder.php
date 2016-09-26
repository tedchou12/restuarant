  <?php include("ini.php");
  
  $order_no = $_GET['ordernumber'];
  
  //recall data if edit is true:
  $read = mysql_query("SELECT * FROM restaurant.order WHERE id='$order_no'");
  $r = mysql_fetch_array($read);?>
  <head><title>Restaurant Name</title></head>
  <link rel="stylesheet" href="images/print.css" type="text/css" media="print">
  <?php //sort type of order:
  if ($r['type'] == 0) {$type = "To Go";
  $echo = "<b>Name of Customer:</b> {$r['name']}<br />
  <b>Phone Number of Customer:</b> (". substr($r['phone'], 0, 3) .") ". substr($r['phone'], 3, 3) ."-". substr($r['phone'], 6) ."<br />";}
  else {$type = "For Here";
  $read3 = 1;
  $waiters = file("waiters.txt", FILE_IGNORE_NEW_LINES);
  foreach ($waiters as $waiter1) {if ($r['waiter'] == $read3) {$waiter2 = $waiter1;} $read3 ++;}
  $echo = "<b>Waiter/Waitress:</b> $waiter2<br />
  <b>Table Number:</b> {$r['tableno']}<br />
  <b>Number of Guests:</b> {$r['guestno']}<br />";}
  $orders = explode(",", $r['orders']);?>
  <script language="javaScript">
  window.print();
  </script>
  <div id=printorder>
  <h3>Restaurant Name</h3>
  <br />
  <b>Order ID:</b> <?php echo $r['id'];?><br />
  <b>Time & Date of Order:</b> <?php //Change format of time:
  $time = date("M j, Y g:i a", strtotime($r['time']));
  echo $time;?><br />
  <?php if ($r['time1'] != 0) {//Change format of time:
  $time1 = date("M j, Y g:i a", strtotime($r['time1']));
  echo "<b>Order Details Updated on:</b> $time1<br />";}?>
  <b>Type of Order:</b> <?php echo $type;?><br />
  <?php echo $echo;
  if ($r['coupon'] != 0) {echo "<b>Coupon Used:</b> {$r['coupon']} %<br />";
  $coupontypes = file("dishtypes.txt", FILE_IGNORE_NEW_LINES);
  $read2 = 0;
  $coupontype1 = $r['couponcat'];
  foreach ($coupontypes as $coupontype) {$coupontype = explode(",", $coupontype);
  $coupontype = $coupontype[0];
  $coupontype1 = str_replace($read2, $coupontype, $coupontype1); $read2 ++;}
  $coupontype1 = str_replace("\r", "", $coupontype1);
  $coupontype1 = str_replace(",", ", ", $coupontype1);
  echo "<b>Coupon Valid For:</b> $coupontype1<br />";}
  if ($r['coupon1'] != 0) {echo "<b>Coupon Used:</b> $ {$r['coupon1']}  off<br />";}
  echo "<b>Orders:</b><br />";
  echo "<table id=printorder><tr><td align=center width=20px><b>ID</b></td><td align=center width=200px><b>Name</b></td><td align=center width=100px><b>No. of Orders</b></td><td align=center width=60px><b>Price</b></td></tr>";
  foreach ($orders as $order) {$orderlis = explode("-", $order);
  $read = mysql_query("SELECT * FROM restaurant.menu WHERE id='{$orderlis[0]}'");
  $r1 = mysql_fetch_array($read);
  echo "<tr><td align=center>{$r1['label']}</td><td align=center>{$r1['name']}</td><td align=center>{$orderlis[1]}</td>";
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
  if ($in_taxlist == false) {$taxlist[] = ($pricelist1 - $bonuses1) * $tax_rate;}
  $price = number_format($pricelist1 - $bonuses1, 2, ".", "");
  echo "<td align=right>$ $price</td></tr>";}
  if ($r['specialorder'] != "") {$specialorders = explode(",", $r['specialorder']);
  $pricelist1 = round($specialorders[1] * $specialorders[0], 2);
  $pricelist2 = explode(".", $pricelist1);
  if (count($pricelist2) == 2) {
  $pricelist2_lastdigit = substr($pricelist2[1], 1);
  if ($pricelist2_lastdigit == 1 or $pricelist2_lastdigit == 6) {$pricelist1 = $pricelist1 - 0.01;}
  elseif ($pricelist2_lastdigit == 2 or $pricelist2_lastdigit == 7) {$pricelist1 = $pricelist1 - 0.02;}
  elseif ($pricelist2_lastdigit == 3 or $pricelist2_lastdigit == 8) {$pricelist1 = $pricelist1 + 0.02;}
  elseif ($pricelist2_lastdigit == 4 or $pricelist2_lastdigit == 9) {$pricelist1 = $pricelist1 + 0.01;}}
  $pricelist2 = number_format($pricelist1, 2, ".", "");
  echo "<tr><td align=center>SS</td><td align=center>{$specialorders[2]}</td><td align=center>{$specialorders[0]}</td><td align=right>$ $pricelist2</td></tr>";
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
  $ordernumbers1 = $r['guestno'] - $shared_det;
  echo "<tr><td align=center>ZZ</td><td align=center>Shared Cost</td><td align=center>$ordernumbers1</td><td align=right>$ $pricelist2</td></tr>";
  $pricelist[] = $pricelist1;
  $taxlist[] = $pricelist1 * $tax_rate;}
  
  //Calculating Total Cost:
  //Bonus discount from coupon:
  $discount = array_sum($bonuses);
  //Sum up the cost:
  $subtotal = number_format(round(array_sum($pricelist) - $discount, 2), 2, ".", "");
  //Calculating tax:
  $tax = round(array_sum($taxlist), 2);
  $tax1 = explode(".", $tax);
  if (count($tax1) == 2) {
  $tax_lastdigit = substr($tax1[1], 1);
  if ($tax_lastdigit == 1 or $tax_lastdigit == 6) {$tax = $tax - 0.01;}
  elseif ($tax_lastdigit == 2 or $tax_lastdigit == 7) {$tax = $tax - 0.02;}
  elseif ($tax_lastdigit == 3 or $tax_lastdigit == 8) {$tax = $tax + 0.02;}
  elseif ($tax_lastdigit == 4 or $tax_lastdigit == 9) {$tax = $tax + 0.01;}}
  $tax = number_format($tax, 2, ".", "");
  //8 Guests service fee:
  if ($r['guestno'] >= $guest_no_ini) {$guest8 = round(($subtotal + $tax) * $guest_rate_ini, 2);
  $guest8_2 = explode(".", $guest8);
  if (count($guest8_2) == 2) {
  $guest8_lastdigit = substr($guest8_2[1], 1);
  if ($guest8_lastdigit == 1 or $guest8_lastdigit == 6) {$guest8 = $guest8 - 0.01;}
  elseif ($guest8_lastdigit == 2 or $guest8_lastdigit == 7) {$guest8 = $guest8 - 0.02;}
  elseif ($guest8_lastdigit == 3 or $guest8_lastdigit == 8) {$guest8 = $guest8 + 0.02;}
  elseif ($guest8_lastdigit == 4 or $guest8_lastdigit == 9) {$guest8 = $guest8 + 0.01;}}
  $guest8 = number_format($guest8, 2, ".", "");
  $guest8_1 = "<tr><td colspan=3 align=right><b>Service Fee (Over $guest_no_ini Guests):</b></td><td align=right> $ $guest8</td></tr>";}
  //Total:
  $total = number_format(round($subtotal + $tax + $guest8, 2), 2, ".", "");
  $coupon1_off1 = number_format($r['coupon1'], 2, ".", "");
  
  echo "<tr><td colspan=3 align=right><b>Subtotal:</b></td><td align=right>$ $subtotal</td></tr>";
  echo "<tr><td colspan=3 align=right><b>Tax:</b></td><td align=right>$ $tax</td></tr>";
  echo $guest8_1;
  if ($r['coupon1'] != 0) {echo "<tr><td colspan=3 align=right><b>Coupon $ off:</b></td><td align=right> - $ $coupon1_off1</td></tr>";
  $total = number_format(round($total - $r['coupon1'], 2), 2, ".", "");}
  echo "<tr><td colspan=3 align=right><b>Total:</b></td><td align=right>$ $total</td></tr>";
  echo "</table>";
  if ($r['coupon'] != 0) {echo "(* %off coupons are already counted within each order)";}
  echo "<br /><br /><b>Paid Status:</b> ";
  if ($r['paid'] == 0) {echo "<font color=\"red\">No</font><br />";} else {echo "Yes, by ";
  if ($r['paid'] == 1) {echo "Cash<br />";}
  if ($r['paid'] == 2) {echo "Visa<br />";}
  if ($r['paid'] == 3) {echo "Mastercard<br />";}
  if ($r['paid'] == 4) {echo "American Express<br />";}
  if ($r['paid'] == 5) {echo "Discover<br />";}}
  if ($r['additional'] != "") {echo "<b>Additional Information:</b><br />{$r['additional']}";}?><br />
  <input class="hideprint" type="button" value="Close Print Window" onClick="window.close();" \>
  </div>