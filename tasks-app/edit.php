<?php
require "db.php";
$id = intval($_GET["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $status      = $_POST["status"];
    $post_id     = intval($_POST["id"]);
    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $description, $status, $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$task) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskFlow – Edit Task</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
  <a class="brand" href="index.php">&#9654; Task<span>Flow</span></a>
</nav>

<div class="container" style="max-width:560px;">
  <div class="page-header">
    <div>
      <h1>Edit Task</h1>
      <p>Update the task details below.</p>
    </div>
  </div>

  <div class="form-card">
    <form method="POST" action="edit.php">
      <input type="hidden" name="id" value="<?php echo $task['id']; ?>">

      <div class="form-group">
        <label for="title">Title</label>
        <input id="title" type="text" name="title"
               value="<?php echo htmlspecialchars($task['title']); ?>" required autofocus>
      </div>

      <div class="form-group">
        <label for="description">Description <span style="color:var(--muted);font-weight:400;">(optional)</span></label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
          <option value="pending"     <?php echo $task['status']==='pending'     ? 'selected' : ''; ?>>⏳ Pending</option>
          <option value="in_progress" <?php echo $task['status']==='in_progress' ? 'selected' : ''; ?>>🔄 In Progress</option>
          <option value="done"        <?php echo $task['status']==='done'        ? 'selected' : ''; ?>>✅ Done</option>
        </select>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Update Task</button>
        <a class="btn btn-ghost" href="index.php">Cancel</a>
      </div>

    </form>
  </div>
</div>
</body>
</html>