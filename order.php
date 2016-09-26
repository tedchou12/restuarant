<?php include("ini.php");

$type = $_GET['type'];
$order_no = $_GET['ordernumber'];

//recall data if edit is true:
if (eregi("^([0-9])*$", $order_no) && $order_no != "" && $name == "" && $phone == "" && $tableno == "" && $guestno == "" && $coupon == "" && $couponcat == "" && $coupon1 == "" && $orderlist == "" && $specialorder == "" && $addinfo == "") {$read = mysql_query("SELECT * FROM restaurant.order WHERE id='$order_no'"); $r = mysql_fetch_array($read);
if ($r['type'] == 0 && $type != "togo") {$type = "togo";}
elseif ($r['type'] == 1 && $type == "togo") {$type = "";}
if ($type == "togo") {$name = $r['name']; $phone = $r['phone'];}
else {$tableno = $r['tableno']; $guestno = $r['guestno']; $waiter = $r['waiter'];}
$coupon = $r['coupon']; if ($coupon != 0) {$couponcat = explode(",", $r['couponcat']);} else {$coupon = "";}
$coupon1 = $r['coupon1']; if ($coupon1 == 0) {$coupon1 = "";}
$orderlist = explode(",", $r['orders']);
$specialorders = explode(",", $r['specialorder']); $specialname = $specialorders[2]; $specialprice = $specialorders[1]; $specialno = $specialorders[0];
$addinfo = $r['additional'];}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3><?php if ($type == "togo" && $order_no == "") {echo "Add Orders (To Go)"; $type1 = 0;}
  elseif ($order_no == "") {echo "Add Orders (For Here)"; $type1 = 1;}
  elseif ($type == "togo") {echo "Edit Orders (To Go)";}
  else {echo "Edit Orders (For Here)";}?></h3>
  <?php if (isset($_POST['delete'])) {
  $delete = mysql_query("DELETE FROM restaurant.order WHERE id='$order_no'");
  
  if($delete === true) 
  {header("location: confirm.php?page=orders.php");}
  else 
  {echo "<p>Sorry there are currently some errors in the database, please resolve the problem!</p>";}}
  
  if (isset($_POST['submit'])) {
  if ($type == "togo") {$name = $_POST['name']; $phone = $_POST['phone'];}
  else {$tableno = addslashes($_POST['tableno']); $guestno = $_POST['guestno']; $waiter = $_POST['waiter'];}
  $coupon = $_POST['coupon'];
  $coupon1 = $_POST['coupon1'];
  $couponcheckbox = $_POST['couponcheckbox'];
  $couponcat = $_POST['couponcat'];
  $read2 = 0; unset($orderlist);
  while ($read2 < 10000) {$orders = $_POST[$read2];
  if ($orders != "" && $orders != 0) {$orderlist[] = "$read2-$orders";
  $read3 = mysql_query("SELECT * FROM menu WHERE id='$read2'");
  $r3 = mysql_fetch_array($read3);
  $couponorders[] = $r3['type'];} $read2 ++;}
  $specialname = addslashes($_POST['specialname']);
  $specialno = $_POST['specialno'];
  $specialprice = $_POST['specialprice'];
  $datetime = date("Y-m-d H:i:s");
  $addinfo = addslashes($_POST['addinfo']);
  
  //all required fields filled?
  if ($type == "togo") {if ($name == "" or $phone == "" or $orderlist == "") {echo "<p><b>Required fields are left blank!</b></p>"; $invalid = true;}}
  else {if ($waiter == "" or $tableno == "" or $guestno == "" or $orderlist == "") {echo "<p><b>Required fields are left blank!</b></p>"; $invalid = true;}}
  //is guest number in interger?
  if (!eregi("^([0-9])*$", $guestno) && $guestno != "") 
  {echo "<p><b>Guest number field must be in integers!</b></p>"; $invalid = true;}
  //is phone number in interger and 10 digits?
  if ((!eregi("^([0-9])*$", $phone) or strlen($phone) != 10) && $phone != "") 
  {echo "<p><b>Phone number field must be in integers and 10 digits!</b></p>"; $invalid = true;}
  //is coupon number an numeric?
  if (!is_numeric($coupon) && $coupon != "") 
  {echo "<p><b>Coupon numbers must only have numbers! (Ignore the % sign)</b></p>"; $invalid = true;}
  elseif ($coupon != "") {
  //if coupon is field, is coupon category filled?
  if (($coupon != "" && $couponcat == "") or ($couponcat != "" && $coupon == "")) 
  {echo "<p><b>You must fill both of the coupon fields completely!</b></p>"; $invalid = true;}
  else {
  //is coupon used for the ordered dishes?
  array_unique($couponorders);
  $couponused = false;
  foreach ($_POST['couponcat'] as $couponcat3) {foreach ($couponorders as $couponorder) {if ($couponcat3 == $couponorder) {$couponused = true;}}}
  if ($couponused == false && $couponcheckbox != 1) 
  {$couponcheckbox = true; $invalid = true;}}}
  //if coupon1 is valid?
  if (!is_numeric($coupon1) && $coupon1 != "") 
  {echo "<p><b>Coupon numbers must only have numbers!</b></p>"; $invalid = true;}
  else {
  //is coupon1 exceeding the price by at least enough times?
  foreach ($orderlist as $orderlist5) {
  $orderlis = explode("-", $orderlist5);
  $read6 = mysql_query("SELECT * FROM restaurant.menu WHERE id='{$orderlis[0]}'");
  $r6 = mysql_fetch_array($read6);
  $pricelist_limarray[] = $r6['price'] * $orderlis[1];}
  $pricelist_limarray = array_sum($pricelist_limarray);
  if (($coupon1 * $coupon1_off) > $pricelist_limarray) 
  {echo "<p><b>To use a - $ $coupon1 coupon, you must have orders exceeding $". $coupon1 * $coupon1_off ."!</b></p>"; $invalid = true;}}
  //is all special dish fields field properly?
  if ($specialname != "" && $specialprice != "" && $specialno != "") 
  {//is price numeric?
  if (!is_numeric($specialprice)) 
  {echo "<p><b>Price value for the special dish must only have numbers!</b></p>"; $invalid = true;}
  else {$specialorder = implode(",", array($specialno, $specialprice, $specialname));}}
  //orderlist into string:
  $orderlist_f = implode(",", $orderlist);
  //couponlist into string:
  $couponcat2 = implode(",", $_POST['couponcat']);
  
  if ($invalid == false) 
  {
      if ($order_no != "") {
        $result = mysql_query("UPDATE restaurant.order SET name='$name', phone='$phone', waiter='$waiter', tableno='$tableno', guestno='$guestno', orders='$orderlist_f', specialorder='$specialorder', coupon='$coupon', coupon1='$coupon1', couponcat='$couponcat2', time1='$datetime', additional='$addinfo' WHERE order.id='$order_no'");
      } else {
        $result = mysql_query("INSERT INTO restaurant.order (id, type, name, phone, waiter, tableno, guestno, orders, specialorder, coupon, coupon1, couponcat, time, additional) VALUES (DEFAULT, '$type1', '$name', '$phone', '$waiter', '$tableno', '$guestno', '$orderlist_f', '$specialorder', '$coupon', '$coupon1', '$couponcat2', '$datetime', '$addinfo')");
        $order_no = mysql_insert_id($db);
      }
  
  if ($result === true) 
  {
      if ($name) {
          $SQL = sprintf('SELECT COUNT(*) FROM restaurant.customer WHERE customer_name="%s"', $name);
          $read = mysql_query($SQL);
          $count = mysql_fetch_array($read);
          if (!$count['COUNT(*)']) {
              $SQL = sprintf('INSERT INTO restaurant.customer (customer_name, customer_phone) VALUES ("%s", "%s")', $name, $phone);
              $result = mysql_query($SQL); 
          }
      }
      header("location: confirm.php?page=vieworder.php&query=ordernumber&id=$order_no");
  } else {
      echo "<p>Sorry there are currently some errors in the database, please resolve the problem!</p>";}
  }}
  
  $SQL = sprintf('SELECT customer_name, customer_phone, customer_mobile FROM restaurant.customer');
  $read = mysql_query($SQL);
  $customers = array();
  while ($customer = mysql_fetch_array($read)) {
    $customers[] = $customer;
  }
  ?>
  <br /><br />
  <form name="order" action="" method="post">
  <script type="text/javascript">
  function checkDelete() {var value=confirm("Are you sure that you want to delete this order?");
  if (value == true) {return true;} else {return false;}}
  <!--/* For Message Multiple Checkbox...*/
  var checkflag = "false";
  function check(field) {
  if (checkflag == "false") {
  for (i = 0; i < field.length; i++) {
  field[i].checked = true;}
  checkflag = "true";
  return "Uncheck All"; }
  else {
  for (i = 0; i < field.length; i++) {
  field[i].checked = false; }
  checkflag = "false";
  return "Check All"; }
  }
  //-->
  
    $(function() {
    var availableTags = [];
    <?php 
    foreach ($customers as $customer) {
        echo 'availableTags[\''.$customer['customer_name'].'\'] = \''.$customer['customer_name'].'\';';
    }
    ?>
    $( "#name" ).autocomplete({
      source: availableTags
    });
  });
  </script>
  <?php if ($type == "togo") {?>
  <label>Name of the Customer</label><br />
  <input id="name" name="name" value="<?php echo $name;?>" type="text" size="30" maxlength="100" /><br />
  <label>Phone Number of the Customer</label><br />
  <input name="phone" value="<?php echo $phone;?>" type="text" size="30" maxlength="10" /><br />
  <?php } else {?>
  <label>Waiter/Waitress</label><br />
  <select name="waiter"> 
  <option value="">Select Waiter/Waitress</option>
  <?php $read5 = 1;
  $waiters = file("waiters.txt", FILE_IGNORE_NEW_LINES);
  foreach ($waiters as $waiter1) {echo "<option value=\"$read5\"";
  if ($waiter == $read5) {echo " selected";}
  echo ">$waiter1</option>"; $read5 ++;}?></select>
  <br />
  <label>Table Number</label><br />
  <input name="tableno" value="<?php echo $tableno;?>" type="text" size="30" maxlength="4" /><br />
  <label>Number of Guests</label><br />
  <input name="guestno" value="<?php echo $guestno;?>" type="text" size="30" maxlength="2" /><br />
  <?php }?>
  <label>Coupon used (If any, specify type of coupon in additional information, ignore % sign)</label><br />
  <input name="coupon" value="<?php echo $coupon;?>" type="text" size="30" maxlength="5" /><br />
  <label>Coupon Valid For</label><br />
  <?php if ($couponcheckbox == true) {?><b><font color=red>The coupon you are using is not in use with the order combination, do you still wish to continue? (click the checkbox and submit again)</font></b> <input name="couponcheckbox" type="checkbox" value="1" /><br /><?php }?>
  General Coupon <input name="couponcat" type="checkbox" value="Check All" onClick="this.value=check(this.form)" />
  <?php $types = file("dishtypes.txt", FILE_IGNORE_NEW_LINES);
  $read2 = 0;
  foreach ($types as $type1) {$type1 = current(explode(",", $type1));
  echo "$type1 <input name\"couponcat\" type=\"checkbox\" name=\"couponcat[]\" value=\"$read2\"";
  foreach ($couponcat as $couponcat1) {if ($couponcat1 == $read2) {echo " checked";}}
  echo "/> "; $read2 ++;}?><br />
  <label>Coupon (dollar off)</label><br />
  <input name="coupon1" value="<?php echo $coupon1;?>" type="text" size="30" maxlength="5" /><br />
  <label>Orders</label><br />
  <table>
  <?php $read4 = 0;
  $types = file("dishtypes.txt", FILE_IGNORE_NEW_LINES);
  foreach ($types as $type) {$type = current(explode(",", $type));
  echo "<tr><td colspan=3 align=center><b>$type</b></td></tr>";?>
  <tr><td align=center>Number</td><td align=center>Name</td><td align=center>Number of Orders</td></tr>
  <?php $read = mysql_query("SELECT * FROM menu WHERE type='$read4'");
  while ($r = mysql_fetch_array($read)) {echo "<tr>";
  echo "<td align=center>{$r['label']}</td>";
  echo "<td align=center>{$r['name']}</td>";
  echo "<td align=center><select name=\"{$r['id']}\"><option>0</option>";
  if ($r['availability'] == 0) {$read1 = 1;
  while ($read1 < 21) {echo "<option value=\"$read1\"";
  foreach ($orderlist as $ol) {$ol1 = explode("-", $ol);
  if ($ol1[0] == $r['id'] && $ol1[1] == $read1) {echo " selected";}}
  echo ">$read1</option>"; $read1 ++;}}
  echo "</select>";
  if ($r['availability'] == 1) {echo " (unavailable)";}
  echo "</td></tr>";}?>
  <?php $read4 ++;}?>
  </table><br />
  <label>Special Dish</label><br />
  Name: <input name="specialname" value="<?php echo $specialname;?>" type="text" size="20" maxlength="100" />
  Price: <input name="specialprice" value="<?php echo $specialprice;?>" type="text" size="6" maxlength="6" />
  No. of Orders:<select name="specialno">
  <option>0</option><?php $read7 = 1;
  while ($read7 < 21) {echo "<option value=\"$read7\"";
  if ($read7 == $specialno) {echo " selected";}
  echo ">$read7</option>"; $read7 ++;}?></select>
  <br />
  <label>Additional Information</label><br />
  <textarea name="addinfo" rows="5" cols="30"><?php echo $addinfo;?></textarea><br /><br />
  <input class="button" name="submit" type="submit" value="Submit Order!" />
  <?php if (eregi("^([0-9])*$", $order_no) && $order_no != "") {?>
  <input class="button" name="delete" type="submit" value="Delete this Order" onClick="javascript: return checkDelete()" />
  <?php }?>
  </form>
</div>

<?php include("foot.txt");?>