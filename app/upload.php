<?php
require __DIR__ . '/../vendor/autoload.php';
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


    try {

        $client->getAccountInfo();
    } catch (DropboxClientException $e) {
        header('Location: ' . $authUrl);
        exit();
    }
} else {
    header('Location: ' . $authUrl);
    exit();
}


//File upload

$adapter = new DropboxAdapter($client);
$filesystem = new Filesystem($adapter, ['case_sensitive' => false]);
// $contents = $filesystem->read($pathToLocalFile);
// $client->upload("katiexw.txt",$contents);

$stream = fopen($_FILES['file']['tmp_name'], 'r+');
$filesystem->writeStream(
    'uploads/' . $_FILES['file']['name'],
    $stream
);
if (is_resource($stream)) {
    fclose($stream);
    echo 'Cheers! File uploaded.';
}
