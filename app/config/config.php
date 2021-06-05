<?php   
  	$script = explode('/', $_SERVER['SCRIPT_NAME']);
	$racine = "/".$script[1]."/";
	$courant = str_replace($script[(count($script)-1)], '', $_SERVER['SCRIPT_NAME']);

	//Default path settings
	define('WEBROOT', $racine.'');
	define('CSSROOT', $racine."web/css/");
	define('JSROOT', $racine."web/js/");
	define('IMGROOT', $racine."web/img/");
	define('AVATARROOT', "user/avatars/");

	require_once 'functions.php';

	$config['controller'] = array(
								'user',
								'member',
								'invites',
								'error');

	require_once 'constants.php';

	$config['action'] = array(
								Constant::$ACTION_GET,
								Constant::$ACTION_CREATE,
								Constant::$ACTION_UPDATE,
								Constant::$ACTION_DELETE,
								Constant::$ACTION_CONNEXION,
								Constant::$ACTION_RESET_PASSWORD,
								Constant::$ACTION_GET_FORGOTTEN_PASSWORD_CODE,
								Constant::$ACTION_VALIDATE_FORGOTTEN_PASSWORD_CODE,
								Constant::$ACTION_PRINT_QR_CODE,
								Constant::$ACTION_MISSING);

	require_once 'PHPMailer/src/Exception.php';
	require_once 'PHPMailer/src/PHPMailer.php';
	require_once 'PHPMailer/src/SMTP.php';
