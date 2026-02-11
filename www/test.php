<?php
echo "Test started\n";
chdir('/root/d3cd1-main/www');
include 'init.php';
echo "Init loaded\n";
$db = DBConnect();
echo "DB connected\n";
echo "Test passed!";
