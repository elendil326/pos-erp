<?php

require_once "Mail.php";

Mail {


	/**
	 * Enviar un correo electronico
	 *
	 *
	 *
	 **/
	function send( $email_to = null, $subject = null, $body = null)
	{

		$headers = array (
				'From' 	=> POS_MAIL_SMTP_FROM,
				'To' 	=> $email_to,
				'Subject' => $subject
			);

		$smtp = Mail::factory('smtp',
			array ('host' 	=> POS_MAIL_SMTP_HOST,
				'port' 		=> POS_MAIL_SMTP_PORT,
				'auth' 		=> true,
				'username' 	=> POS_MAIL_SMTP_USERNAME,
				'password' 	=> POS_MAIL_SMTP_PASSWORD));

		$mail = $smtp->send($to, $headers, $body);
		//$mail->getMessage()
		return PEAR::isError($mail);

	}
 
}