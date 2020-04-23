<?php
require __DIR__ .'/../vendor/autoload.php';
require_once "start.php";


use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use League\Flysystem\Adapter\Local;

use Kunnu\Dropbox\Exceptions\DropboxClientException;


$authUrl = $authHelper->getAuthUrl($callbackUrl);
// $file = $_FILES['file'];
// $filePath = $_FILES['tmp_name'];


// Check if user has token,else authenticate him
if ($user->dropbox_token) {
    
    $client = new Client($user->dropbox_token);
    
    
    try{
        
        $client->getAccountInfo();        
     
    }
    catch(DropboxClientException $e){
        header('Location: ' .$authUrl);
        exit();

    }
}
else{
    header('Location: ' .$authUrl);
        exit();
}


//Download file
$path = "./skata.jpg";
//local and remote adapters
$adapter = new DropboxAdapter($client);
$local_adapter = new League\Flysystem\Adapter\Local(__DIR__ .'/../app/files');
//local and remote filesystems
$local_filesystem =  new Filesystem($local_adapter, ['case_sensitive' => false]);
$filesystem = new Filesystem($adapter, ['case_sensitive' => false]);

$resource = $filesystem->readStream('uploads/Hello-World.jpg');
$local_filesystem->write($path,$resource);



echo 'Great success';