<?php

function ftpUploader($ftp_host,$ftp_user,$ftp_pass,$local_dir,$remote_server_dir ){
    $ftp_connection = ftp_connect($ftp_host) or die("Coulndt connect to $ftp_host");
    ftp_login($ftp_connection, $ftp_user, $ftp_pass) or die('Couldnt login to server');
    ftp_pasv($ftp_connection, true);
    $local_dir = "/Applications/MAMP/htdocs/react_php_formdata/uploads";
    $remote_server_dir = '/home/kali/job';
    $files = clean_scandir($local_dir);
    $isUploaded = false;
    for ($i=0 ; $i < count($files) ; $i++){
        $files_on_server = clean_ftp_nlist($ftp_connection, $remote_server_dir);
        if(!in_array("$files[$i]",$files_on_server)){
            if(ftp_put($ftp_connection, "$remote_server_dir/$files[$i]", "$local_dir/$files[$i]", FTP_BINARY)){
                $isUploaded = true;
            } else {
                $isUploaded = false;
            }
        } else {
            $isUploaded = false;
        }
    }
    ftp_close($ftp_connection);
    return $isUploaded;
}

function clean_scandir($dir){
    return array_values(array_diff(scandir($dir), array('..','.')));
}

function clean_ftp_nlist($ftp_connection, $server_dir){
    $files_on_server = ftp_nlist($ftp_connection,$server_dir);
    return array_values(array_diff($files_on_server,array('.','..')));
}
$ftp_host = "192.168.1.23";
$ftp_user = "kali";
$ftp_pass = "kali";
$local_dir = "/Applications/MAMP/htdocs/react_php_formdata/uploads";
$remote_server_dir = '/home/kali/job';
$tmp = ftpUploader($ftp_host,$ftp_user,$ftp_pass,$local_dir,$remote_server_dir);

?>