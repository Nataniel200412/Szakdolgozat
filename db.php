<?php
session_start();

$db = new mysqli('localhost', 'root', '', 'rentgamer');

if ($db->connect_error) {
    die("Kapcsolódási hiba");
}

$db->set_charset("utf8mb4");