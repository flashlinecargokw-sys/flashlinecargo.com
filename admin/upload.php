<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

$jsonFile = "../gallery.json";

if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([
        "photos" => [],
        "videos" => [],
        "popup"  => []
    ], JSON_PRETTY_PRINT));
}

$data = json_decode(file_get_contents($jsonFile), true);

if (!isset($data["photos"])) $data["photos"] = [];
if (!isset($data["videos"])) $data["videos"] = [];
if (!isset($data["popup"]))  $data["popup"]  = [];

$type  = $_POST["type"];
$title = trim($_POST["title"]);

if (!in_array($type, ["photos","videos","popup"])) {
    die("Invalid type");
}

if (!isset($_FILES["file"])) {
    die("No file uploaded");
}

$uploadDir = "../content/$type/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
$allowed = ($type == "videos") ? ["mp4","webm","ogg"] : ["jpg","jpeg","png","webp"];

if (!in_array($ext, $allowed)) {
    die("Invalid file type");
}

$filename = time() . "_" . basename($_FILES["file"]["name"]);
$target = $uploadDir . $filename;

if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target)) {
    die("Upload failed");
}

$data[$type][] = [
    "title" => $title,
    "src"   => "content/$type/$filename"
];

file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

header("Location: dashboard.php");
exit;
