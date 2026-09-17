<?php
require "db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $status      = $_POST["status"];
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $status);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskFlow – New Task</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
  <a class="brand" href="index.php">&#9654; Task<span>Flow</span></a>
</nav>

<div class="container" style="max-width:560px;">
  <div class="page-header">
    <div>
      <h1>New Task</h1>
      <p>Fill in the details below to add a task.</p>
    </div>
  </div>

  <div class="form-card">
    <form method="POST" action="create.php">

      <div class="form-group">
        <label for="title">Title</label>
        <input id="title" type="text" name="title" placeholder="e.g. Buy groceries" required autofocus>
      </div>

      <div class="form-group">
        <label for="description">Description <span style="color:var(--muted);font-weight:400;">(optional)</span></label>
        <textarea id="description" name="description" placeholder="Add more details…"></textarea>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
          <option value="pending">⏳ Pending</option>
          <option value="in_progress">🔄 In Progress</option>
          <option value="done">✅ Done</option>
        </select>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Save Task</button>
        <a class="btn btn-ghost" href="index.php">Cancel</a>
      </div>

    </form>
  </div>
</div>
</body>
</html>