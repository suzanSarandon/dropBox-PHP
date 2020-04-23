<?php


session_start();
$_SESSION['user_id'] = 1;
$user_id = $_SESSION['user_id'];
require __DIR__ . '/../vendor/autoload.php';


$dropboxKey = '********';
$dropboxSecret = '*******';
$appName = '****/1.0';


use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

//Configure Dropbox Application
$appInfo = new DropboxApp($dropboxKey, $dropboxSecret);

$callbackUrl = "http://localhost/WORK/fil_test/dropboxapi/app/login-callback.php";


//Configure Dropbox service
$dropbox = new Dropbox($appInfo);

//DropboxAuthHelper
$authHelper = $dropbox->getAuthHelper();


$db = new PDO('mysql:host=localhost;dbname=dropbox_users', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$user = $db->prepare("SELECT * FROM users WHERE id = :user_id");
$user->execute(['user_id' => $_SESSION['user_id']]);
$user = $user->fetchObject();
