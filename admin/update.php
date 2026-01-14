<?php
session_start();
if(!isset($_SESSION["admin"])) exit;

$type = $_POST["type"];
$index = (int)$_POST["index"];
$title = $_POST["title"];

$jsonFile = "../gallery.json";
$data = json_decode(file_get_contents($jsonFile), true);

$data[$type][$index]["title"] = $title;

file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
header("Location: dashboard.php");
