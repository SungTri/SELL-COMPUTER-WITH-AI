<?php
session_start();
if (!isset($_SESSION['test_counter'])) {
    $_SESSION['test_counter'] = 0;
}
$_SESSION['test_counter']++;
echo "Session ID: " . session_id() . "<br>";
echo "Counter: " . $_SESSION['test_counter'] . "<br>";
echo "Session Save Path: " . session_save_path() . "<br>";
$path = session_save_path() ?: '/tmp';
echo "Is writable: " . (is_writable($path) ? 'YES' : 'NO') . "<br>";
