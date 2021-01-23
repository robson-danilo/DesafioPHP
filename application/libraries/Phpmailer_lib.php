<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	class Phpmailer_lib {

		public function __construct()
		{
			log_message('Debug', 'PHPMailer class is loaded.');
		}

		public function load()
		{
			require_once __DIR__ ."/PHPMailer/Exception.php";
			require_once __DIR__ ."/PHPMailer/PHPMailer.php";
			require_once __DIR__ ."/PHPMailer/SMTP.php";			
			
			$objMail = new PHPMailer(true);
			return $objMail;
		}
	}