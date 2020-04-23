<?php
require_once 'start.php';





if (isset($_GET['code']) && isset($_GET['state'])) {
    //Bad practice! No input sanitization!
    $code = $_GET['code'];
    $state = $_GET['state'];

    //Fetch the AccessToken
    $accessToken = $authHelper->getAccessToken($code, $state, $callbackUrl);
    $accessToken = $accessToken->getToken();
} else {

    $code = 'code-presented-directly-to-the-user';

    //Fetch the AccessToken
    $accessToken = $authHelper->getAccessToken($code);
}

// Store token in db

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "
        UPDATE users
        SET dropbox_token = :accessToken
        WHERE id = :user_id
";

$stmt = $db->prepare($sql);
$stmt->bindParam(':accessToken', $accessToken, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
echo '**********************************';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>File Upload</title>
</head>

<body>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" />
        <input type="hidden" name="accessToken" value='<?php $accessToken ?>' />
        <input type="hidden" name="dropboxKey" value=".<?php $dropboxKey ?>." />
        <input type="hidden" name="dropboxSecret" value=".<?php $dropboxSecret ?>." />
        <button type="submit">Upload</button>
    </form>
    <form action="download.php">
        <button type="submit">Download</button>
    </form>
    <form action="submit_content.php" method="POST">
        <div class="field">
            <textarea name="content" cols="30" rows="10"></textarea>
        </div>
        <input type="submit" value="Save" />
    </form>




</body>

</html>