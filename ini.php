<?php //Optimize according to the restaurant:
//Minimum number of guests required for service charge:
$guest_no_ini = 8;
//Percentage of service charge in decimals, (eg. 15% as 0.15):
$guest_rate_ini = 0.15;
//Set your default timezone, (for complete list: http://us2.php.net/manual/en/timezones.others.php):
date_default_timezone_set("US/Eastern");
//Set tax rate percentage as decimals (eg. 7% as 0.07):
$tax_rate = 0.07;
//Maximum number of rows showed on orders list:
$orderlist_table_lim = 20;
//Maximum number of rows showed on dishes list:
$dishlist_table_lim = 50;
//Maximum number of rows showed on kitchen list:
$kitchenlist_table_lim = 50;
//Kitchen list, name => number, number is shown in the ORDER of dishtypes.txt, you can enter more than one number in the number below using comma to separate, eg. "1,2", 999 means the rest of the items not in the other kitchen:
$catarray = array("Hibachi" => 999, "Sushi Bar" => "2", "Alcohol" => "4");
//Price off must be at least how many times? eg. for $5 off must exceed $25 would be 5 times:
$coupon1_off = 5;
//Shared Dish cost, if two or more people share a dish, additional fee applied to the shared:
$shared_cost = 5.95;

//Do not edit below this line unless you know what you are doing ##########################################################:
//turn all errors off, security issue
error_reporting(0);

// Make a MySQL Connection
$db = mysql_connect("localhost:3306", "root", "root");
mysql_select_db("restaurant");
mysql_query("SET character_set_results=utf8");?>