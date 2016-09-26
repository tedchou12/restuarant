<?php include("ini.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("head.txt");?>

<div id=content>
  <h3>Contact Me</h3>
  <br />
  If you have any technical problems that you are unable to solve or preferces to the scripts. Wish to report a bug, a design error, or need some help to install, just contact me: <a href="mailto:ted_chou12@hotmail.com">ted_chou12@hotmail.com</a> or reach me at (520) 449-2362. Come signup at <a href="http://netfriending.co.cc">NetFriending.co.cc</a> and post me a message, I will answer your questions shortly! :) Hope you find this script convenient to use and made your restaurant management much easier than you thought.<br /><br />
  <?php if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  
  $email1 = htmlentities($email);
  $subject1 = htmlentities($subject);
  $message1 = htmlentities($message);
  $email2 = stripslashes($email1);
  $subject2 = stripslashes($subject1);
  $message2 = stripslashes($message1);
  $message3 = str_replace("\r\n", "<br />", $message2);
  $email = $email2;
  $subject = $subject2;
  $message = $message3;
  
  //all required fields filled?
  if($email == "" or $subject == "" or $message == "") 
  {echo "<p><b>Required fields are left blank!</b></p>"; $invalid = true;}
  else {
  	
  //email right format, valid?
  $regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"."\.([a-z]{2,}){1}$";
  if(!eregi($regex, $email)) 
  {echo "<p><b>Please enter a valid email address!</b></p>"; $email = ""; $invalid = true;}}
  
  if ($invalid == false)
  {$to = "Ted Chou <ted_chou12@hotmail.com>";
  
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= "From: $email" . "\r\n";
  
  if (mail($to, "Restaurant Script: $subject", $message, $headers)) 
  {echo "<b>Email successfully sent!</b>";}
  else 
  {echo "<b><font color=red>Email was not successfully proceeded!</font></b>";}
  $email = ""; $message = ""; $subject = "";}}?>
  <br /><br />
  <form name="mail" action="" method="post">
  <label>Your E-mail Address:</label><br />
  <input name="email" value="<?php echo $email;?>" type="text" size="30" maxlength="100" /><br />
  <label>Subject:</label><br />
  <input name="subject" value="<?php echo $subject;?>" type="text" size="30" maxlength="100" /><br />
  <label>Message</label><br />
  <textarea name="message" rows="8" cols="50"><?php echo $message;?></textarea><br /><br />
  <input class="button" name="submit" type="submit" value="Send Message" />
  <br />
</div>

<?php include("foot.txt");?>