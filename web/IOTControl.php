
<?php 
require 'ConnectDB.php';

if (isset($_POST['Switch'])) {
    $S = $_POST['Switch'];

    if ($S == 'AI Model') {
        $query = "UPDATE IOTControl SET Control = '1';";
        $ourconn->query($query);
    }
    else{
        $query = "UPDATE IOTControl SET Control='0', RGBLED='$S';";
        $ourconn->query($query);
    }
}
$ourconn->close();
?>