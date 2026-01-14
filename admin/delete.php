<?php
session_start();
if(!isset($_SESSION["admin"])) exit;

$type = $_POST["type"];
$index = (int)$_POST["index"];

$jsonFile = "../gallery.json";
$data = json_decode(file_get_contents($jsonFile), true);

$path = "../" . $data[$type][$index]["src"];

if(file_exists($path)){
  unlink($path); // delete file
}

array_splice($data[$type], $index, 1);

file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
header("Location: dashboard.php");
