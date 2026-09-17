<?php
require "db.php";
$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks  = $result->fetch_all(MYSQLI_ASSOC);

// Count stats
$counts = ['pending' => 0, 'in_progress' => 0, 'done' => 0];
foreach ($tasks as $t) {
    if (isset($counts[$t['status']])) $counts[$t['status']]++;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskFlow – My Tasks</title>
  <meta name="description" content="Manage your tasks efficiently with TaskFlow.">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
  <a class="brand" href="index.php">&#9654; Task<span>Flow</span></a>
  <a class="btn btn-primary btn-sm" href="create.php">+ New Task</a>
</nav>

<div class="container">

  <div class="page-header">
    <div>
      <h1>My Tasks</h1>
      <p><?php echo count($tasks); ?> task<?php echo count($tasks) !== 1 ? 's' : ''; ?> total</p>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats">
    <div class="stat-card stat-pending">
      <div class="num"><?php echo $counts['pending']; ?></div>
      <div class="label">Pending</div>
    </div>
    <div class="stat-card stat-progress">
      <div class="num"><?php echo $counts['in_progress']; ?></div>
      <div class="label">In Progress</div>
    </div>
    <div class="stat-card stat-done">
      <div class="num"><?php echo $counts['done']; ?></div>
      <div class="label">Done</div>
    </div>
  </div>

  <!-- Task List -->
  <?php if (empty($tasks)): ?>
  <div class="empty">
    <div class="icon">📋</div>
    <h3>No tasks yet</h3>
    <p>Click <strong>+ New Task</strong> to get started.</p>
  </div>
  <?php else: ?>
  <div class="task-list">
    <?php foreach ($tasks as $row):
      $statusClass = $row['status'] === 'done' ? 'done' : '';
      $badgeClass  = $row['status'] === 'pending'     ? 'badge-pending'
                   : ($row['status'] === 'in_progress' ? 'badge-progress' : 'badge-done');
      $badgeLabel  = $row['status'] === 'in_progress'  ? 'In Progress'
                   : ucfirst($row['status']);
    ?>
    <div class="task-card <?php echo $statusClass; ?>">
      <div class="task-body">
        <div class="task-title"><?php echo htmlspecialchars($row['title']); ?></div>
        <?php if (!empty($row['description'])): ?>
        <div class="task-desc"><?php echo htmlspecialchars($row['description']); ?></div>
        <?php endif; ?>
        <div style="margin-top:.5rem;">
          <span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
        </div>
      </div>
      <div class="task-actions">
        <a class="btn btn-edit" href="edit.php?id=<?php echo $row['id']; ?>" title="Edit">✏️ Edit</a>
        <a class="btn btn-danger-ghost" href="delete.php?id=<?php echo $row['id']; ?>"
           onclick="return confirm('Delete this task?');" title="Delete">🗑</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>
</body>
</html>