<?php 

include_once("/Include/PHPMailer/class.phpmailer.php");

function SendMail($pSubject,$fileContent,$pTo,$pFrom,$pCC){
	$mail = new PHPMailer();
	$mail->setFrom('juso1326@chiliman.com.tw', 'First Last',true);	//Set who the message is to be sent from
	$mail->addReplyTo('juso1326@yahoo.com.tw', 'First Last');	//Set an alternative reply-to address
	$mail->addAddress('juso1326@yahoo.com.tw', 'John Doe');	//Set who the message is to be sent to
	$mail->Subject = 'PHPMailer mail() test';	//Set the subject line
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML("grgioejfciknejfioewhfnoivlkoeh");
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
}
?>