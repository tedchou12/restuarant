	<?php $read99 = mysql_query("SELECT * FROM restaurant.order ORDER BY time DESC LIMIT 0, 5");
	while ($r99 = mysql_fetch_array($read99)) {
	$time99 = date("M j, Y g:i a", strtotime($r99['time']));
	if ($r99['type'] == 0) {$part991 = substr($r99['phone'], 0, 3); $part992 = substr($r99['phone'], 4, 3); $part993 = substr($r99['phone'], 6, 4);
	$echo99 = "<b>Name:</b> {$r99['name']} - <b>Phone:</b> ($part991) $part992-$part993"; $type99 = "To Go";} else {$echo99 = "<b>Tbl No:</b> {$r99['tableno']} - <b>Gus No:</b> {$r99['guestno']}"; $type99 = "For Here";}
	if ($r99['paid'] == 0) {$paid99 = "<font color=red>Not Paid</font>";} else {$paid99 = "Paid";}
	echo "<li><a href=\"vieworder.php?ordernumber={$r99['id']}\"><b>{$r99['id']}</b> - $echo99</a><br />
	<font color=#d6f29e>$paid99 ($type99) - $time99</font>";}