<?php
//phpinfo();
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//Code	

/*
	$token_string = 'Sy91rpexUiGNN7aTgz5c5g==';
	$secretKey = pack('H*', 'a9c4dbac460019a74b256c207278e1e2');
	$encrypted = base64_encode(trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secretKey, $token_string, MCRYPT_MODE_ECB)));
	
	echo $encrypted."<br>";
	
	$Secret = 'a9c4dbac460019a74b256c207278e1e2';
	$token_string = 'Sy91rpexUiGNN7aTgz5c5g==';	

	$source = $token_string; // 所收到的加密字串
    $secretKey = pack('H*', 'a9c4dbac460019a74b256c207278e1e2');
    $decoded = base64_decode($source);
    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secretKey, $decoded, MCRYPT_MODE_ECB);
	
	echo $decrypted."<br>";	
*/
?>