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

// Prevent undefined array errors
foreach (["photos","videos","popup"] as $t) {
    if (!isset($data[$t])) $data[$t] = [];
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Gallery Admin</title>
<style>
body{font-family:Arial;background:#f4f4f4;padding:30px}
h2{margin-bottom:10px}
.card{background:#fff;padding:20px;border-radius:10px;margin-bottom:30px;max-width:700px}
input,select,button{width:100%;padding:8px;margin:6px 0}
button{background:#0f2027;color:#fff;border:none;cursor:pointer}
.list{margin-top:20px}
.item{display:flex;align-items:center;gap:10px;margin-bottom:10px}
.item img,.item video{width:80px;height:60px;object-fit:cover;border-radius:6px}
.item form{display:flex;gap:5px;flex:1}
.small-btn{padding:6px 10px;font-size:12px}
.delete{background:#c0392b}
.edit{background:#27ae60}
a{display:inline-block;margin-top:20px}
</style>
</head>

<body>

<h2>Gallery Admin Panel</h2>

<!-- UPLOAD -->
<div class="card">
<h3>Upload Media</h3>
<form action="upload.php" method="post" enctype="multipart/form-data">
  <select name="type" required>
    <option value="photos">Gallery Photos</option>
    <option value="videos">Videos</option>
    <option value="popup">Popup Photos</option>
  </select>
  <input name="title" placeholder="Title" required>
  <input type="file" name="file" required>
  <button>Upload</button>
</form>
</div>

<!-- MEDIA LIST -->
<div class="card">
<h3>Manage Media</h3>

<?php foreach(["photos","videos","popup"] as $type): ?>
<h4><?= ucfirst($type) ?></h4>

<div class="list">
<?php foreach($data[$type] as $index => $item): ?>
  <div class="item">

    <?php if($type=="videos"): ?>
        <video src="../<?= $item["src"] ?>" muted></video>
    <?php else: ?>
        <img src="../<?= $item["src"] ?>">
    <?php endif; ?>

    <!-- EDIT TITLE -->
    <form action="update.php" method="post">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="index" value="<?= $index ?>">
      <input name="title" value="<?= htmlspecialchars($item["title"]) ?>">
      <button class="small-btn edit">Save</button>
    </form>

    <!-- DELETE -->
    <form action="delete.php" method="post" onsubmit="return confirm('Delete this media?')">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="index" value="<?= $index ?>">
      <button class="small-btn delete">Delete</button>
    </form>

  </div>
<?php endforeach; ?>
</div>

<?php endforeach; ?>

</div>

<a href="logout.php">Logout</a>

</body>
</html>
