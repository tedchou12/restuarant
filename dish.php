<?php include("ini.php");

$dish_no = $_GET['dishnumber'];

//recall data if edit is true:
if (eregi("^([0-9])*$", $dish_no) && $dish_no != "" && $name == "" && $label == "" && $type == "" && $price == "" && $availability == "" && $addinfo == "") {$read = mysql_query("SELECT * FROM restaurant.menu WHERE menu.id='$dish_no'"); $r = mysql_fetch_array($read);
$name = $r['name']; $label = $r['label']; $type = $r['type']; $price = $r['price']; $availability = $r['availability']; $addinfo = $r['additional'];}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3><?php if ($dish_no == "") {echo "Add Dish Details";} else {echo "Edit Dish to Menu";}?></h3>
  <?php if (isset($_POST['delete'])) {
  $delete = mysql_query("DELETE FROM restaurant.menu WHERE id='$dish_no'");
  
  if($delete === true) 
  {header("location: confirm.php?page=dishes.php");}
  else 
  {echo "<p>Sorry there are currently some errors in the database, please resolve the problem!</p>";}}
  
  if (isset($_POST['submit'])) {
  $name = addslashes(htmlentities($_POST['name']));
  $label = addslashes(htmlentities($_POST['label']));
  $type = $_POST['type'];
  $price = $_POST['price'];
  $availability = $_POST['availability'];
  $datetime = date("Y-m-d H:i:s");
  $addinfo = addslashes(htmlentities($_POST['addinfo']));
  
  //all required fields filled?
  if ($name == "" or $label == "" or $type == "" or $price == "" or $availability == "") {echo "<p><b>Required fields are left blank!</b></p>"; $invalid = true;}
  //is price value an numeric?
  if (!is_numeric($price)) 
  {echo "<p><b>Price value must only have numbers!</b></p>"; $invalid = true;}
  
  if ($invalid == false) 
  {if ($dish_no != "") {$insert = mysql_query("UPDATE restaurant.menu SET name='$name', label='$label', type='$type', price='$price', availability='$availability', additional='$addinfo' WHERE menu.id='$dish_no'");}
  else {$insert = mysql_query("INSERT INTO restaurant.menu (name, label, type, price, availability, additional) VALUES ('$name', '$label', '$type', '$price', '$availability', '$addinfo')");}
  
  if ($insert === true) 
  {header("location: confirm.php?page=dishes.php");}
  else 
  {echo "<p>Sorry there are currently some errors in the database, please resolve the problem!</p>";}}}?>
  <br /><br />
  <form name="dish" action="" method="post">
  <script type="text/javascript">
  function checkDelete() {var value=confirm("Are you sure that you want to delete this dish?");
  if (value == true) {return true;} else {return false;}}
  </script>
  <label>Name of the Dish</label><br />
  <input name="name" value="<?php echo $name;?>" type="text" size="30" maxlength="100" /><br />
  <label>Label Code</label><br />
  <input name="label" value="<?php echo $label;?>" type="text" size="10" maxlength="5" /><br />
  <label>Type of Dish</label><br />
  <select name="type" size="1">
  <?php $read = 0;
  $types = file("dishtypes.txt", FILE_IGNORE_NEW_LINES);
  foreach ($types as $type1) {$type1 = current(explode(",", $type1));
  echo "<option value=\"$read\"";
  if ($type == $read) {echo " selected";}
  echo ">$type1</option>"; $read ++;}?>
  </select>
  <br />
  <label>Price</label><br />
  <input name="price" value="<?php echo $price;?>" type="text" size="30" maxlength="6" /><br />
  <label>Availability</label><br />
  <?php if ($availability == 0) {$yes = " checked=\"yes\"";} else {$no = " checked=\"yes\"";}?>
  Yes<input type="radio" name="availability" value="0"<?php echo $yes;?> />
  No<input type="radio" name="availability" value="1"<?php echo $no;?> /><br />
  <label>Additional Information</label><br />
  <textarea name="addinfo" rows="5" cols="30"><?php echo $addinfo;?></textarea><br /><br />
  <input class="button" name="submit" type="submit" value="Submit Dish!" />
  <?php if (eregi("^([0-9])*$", $dish_no) && $dish_no != "") {?>
  <input class="button" name="delete" type="submit" value="Delete this Dish" onClick="javascript: return checkDelete()" />
  <?php }?>
  </form>
</div>

<?php include("foot.txt");?>