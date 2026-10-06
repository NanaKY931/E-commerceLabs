<?php
$ftp_server = "169.239.251.102";
$ftp_port = 421;
$ftp_user_name = "nana.yirenkyi";
$ftp_user_pass = "DestinyV1sion!";
$remote_base_dir = "/public_html";
$local_base_dir = __DIR__;

echo "Connecting to $ftp_server:$ftp_port...\n";
$conn_id = ftp_connect($ftp_server, $ftp_port);

if (!$conn_id) {
    die("FTP connection failed!\n");
}

$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
if (!$login_result) {
    die("FTP login failed! Check credentials.\n");
}
echo "Connected and logged in successfully.\n";

ftp_pasv($conn_id, true);

function ftp_mksubdirs($ftpcon, $ftpbasedir, $ftpath) {
    @ftp_chdir($ftpcon, $ftpbasedir); 
    $parts = explode('/', $ftpath); 
    foreach($parts as $part) {
        if (!@ftp_chdir($ftpcon, $part)) {
            ftp_mkdir($ftpcon, $part);
            ftp_chdir($ftpcon, $part);
        }
    }
}

function sync_dir($conn_id, $local_dir, $remote_dir, $base_local) {
    $dir = opendir($local_dir);
    while ($file = readdir($dir)) {
        if ($file == '.' || $file == '..' || $file == '.git' || $file == 'deploy.php' || $file == '.gitignore' || $file == '__MACOSX' || $file == 'future_tasks') {
            continue;
        }
        $local_file = $local_dir . DIRECTORY_SEPARATOR . $file;
        $relative_path = str_replace($base_local . DIRECTORY_SEPARATOR, '', $local_file);
        $relative_path = str_replace('\\', '/', $relative_path);
        $remote_file = $remote_dir . '/' . $relative_path;
        
        if (is_dir($local_file)) {
            echo "Creating remote directory: $remote_file\n";
            ftp_mksubdirs($conn_id, "/", ltrim($remote_file, "/"));
            sync_dir($conn_id, $local_file, $remote_dir, $base_local);
        } else {
            echo "Uploading: $relative_path\n";
            if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
                echo "  -> Success\n";
            } else {
                echo "  -> Failed\n";
            }
        }
    }
    closedir($dir);
}

echo "Starting upload process...\n";
sync_dir($conn_id, $local_base_dir, $remote_base_dir, $local_base_dir);

ftp_close($conn_id);
echo "Deployment completed!\n";
?>
