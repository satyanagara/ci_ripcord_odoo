<?php 
log_message('debug','Email Config Loaded');
 

$config = array(
	'useragent'		=> 'EmailSender',
	'protocol'		=> 'smtp',
	'smtp_host'		=> 'smtp.mandrillapp.com',
	'smtp_user'		=> 'demo@mandrillapp.com',
	'smtp_pass'		=> '',
	'smtp_port'		=> 587,
	'smtp_timeout'	=> 60,
	'mailtype'		=> 'html',
        'charset'               => 'utf-8',
	'crlf'			=> "\r\n",
	'newline'		=> "\r\n",
	'validate'		=> TRUE,
	'smtp_crypto'	=> 'tls',
	'from_mail'		=> 'info@kliktukang.com',
	'from_name'		=> 'Klik Tukang'
);
