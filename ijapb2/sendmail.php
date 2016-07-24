<?php 

extract($_POST);
//define the receiver of the email 
$to = 'chiefeditorijapb@gmail.com'; 
//define the subject of the email 
$subject = $type_of_article.' by '.$full_name; 
//create a boundary string. It must be unique 
//so we use the MD5 algorithm to generate a random hash 
$random_hash = md5(date('r', time())); 
//define the headers we want passed. Note that they are separated with \r\n 
$headers = "From: webmaster@ijapbjournal.com\r\nReply-To: webmaster@ijapbjournal.com"; 
//add boundary string and mime type specification 
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
//read the atachment file contents into a string,
//encode it with MIME base64,
//and split it into smaller chunks
$covering_letter = chunk_split(base64_encode(file_get_contents($_FILES['covering_letter']['name']))); 
$manuscript = chunk_split(base64_encode(file_get_contents($_FILES['manuscript']['name']))); 
//define the body of the message. 
ob_start(); //Turn on output buffering 
?> 
--PHP-mixed-<?php echo $random_hash; ?>  
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>" 

--PHP-alt-<?php echo $random_hash; ?>  
Content-Type: text/plain; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

Hello World!!! 
This is simple text email message. 

--PHP-alt-<?php echo $random_hash; ?>  
Content-Type: text/html; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

<h2>Hello World!</h2> 
<p>This is something with <b>HTML</b> formatting.</p> 

--PHP-alt-<?php echo $random_hash; ?>-- 

--PHP-mixed-<?php echo $random_hash; ?>  
Content-Type: application/msword,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document; name= <?php echo $_FILES['userfile']['name']; ?> 
Content-Transfer-Encoding: base64  
Content-Disposition: covering_letter

Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document; name= <?php echo $_FILES['userfile']['name']; ?> 
Content-Transfer-Encoding: base64  
Content-Disposition: manuscript  

<?php 
	echo $covering_letter; 
	echo $manuscript; 
?> 
--PHP-mixed-<?php echo $random_hash; ?>-- 

<?php 
//copy current buffer contents into $message variable and delete current output buffer 
$message = ob_get_clean(); 
//send the email 
$mail_sent = @mail( $to, $subject, $message, $headers ); 
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
echo $mail_sent ? "Mail sent" : "Mail failed"; 
?>