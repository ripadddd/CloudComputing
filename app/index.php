<?php
// ============================================================
// Nama : Muhammad Rizki Padillah
// NPM  : 2410010252
// Tugas: Tugas 2 Cloud - Docker Container PHP App
// ============================================================

// Simpan todo di session (tidak perlu database)
session_start();
if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [
        ['id' => 1, 'task' => 'Belajar Docker', 'done' => true],
        ['id' => 2, 'task' => 'Buat Dockerfile', 'done' => true],
        ['id' => 3, 'task' => 'Push ke GitHub', 'done' => false],
        ['id' => 4, 'task' => 'Kumpul ke eLearning', 'done' => false],
    ];
    $_SESSION['next_id'] = 5;
}

// Handle tambah todo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task']) && trim($_POST['task']) !== '') {
    $_SESSION['todos'][] = [
        'id'   => $_SESSION['next_id']++,
        'task' => htmlspecialchars(trim($_POST['task'])),
        'done' => false,
    ];
}

// Handle toggle done
if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];
    foreach ($_SESSION['todos'] as &$t) {
        if ($t['id'] === $id) $t['done'] = !$t['done'];
    }
}

// Handle hapus
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $_SESSION['todos'] = array_values(array_filter($_SESSION['todos'], fn($t) => $t['id'] !== $id));
}

$todos  = $_SESSION['todos'];
$done   = count(array_filter($todos, fn($t) => $t['done']));
$total  = count($todos);
$persen = $total > 0 ? round($done / $total * 100) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tugas 2 Cloud - Muhammad Rizki Padillah</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .card {
      background: #fff;
      border-radius: 16px;
      width: 100%;
      max-width: 520px;
      box-shadow: 0 24px 60px rgba(0,0,0,0.4);
      overflow: hidden;
    }

    /* ── Header ── */
    .header {
      background: linear-gradient(135deg, #0f3460, #533483);
      padding: 28px 28px 20px;
      color: #fff;
    }
    .header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
    .header .sub { font-size: 13px; opacity: .75; margin-bottom: 16px; }

    .badge-row { display: flex; gap: 8px; flex-wrap: wrap; }
    .badge {
      background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.25);
      border-radius: 20px;
      padding: 4px 12px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .5px;
    }

    /* ── Progress ── */
    .progress-wrap { padding: 20px 28px 0; }
    .progress-info { display: flex; justify-content: space-between; font-size: 13px; color: #555; margin-bottom: 8px; }
    .progress-bar { background: #e8e8e8; border-radius: 8px; height: 10px; overflow: hidden; }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #0f3460, #533483);
      border-radius: 8px;
      transition: width .4s ease;
      width: <?= $persen ?>%;
    }

    /* ── Form ── */
    .form-wrap { padding: 20px 28px; border-bottom: 1px solid #f0f0f0; }
    .form-row { display: flex; gap: 8px; }
    .form-row input {
      flex: 1;
      padding: 10px 14px;
      border: 1.5px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
      outline: none;
      transition: border-color .2s;
    }
    .form-row input:focus { border-color: #533483; }
    .form-row button {
      padding: 10px 18px;
      background: linear-gradient(135deg, #0f3460, #533483);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: opacity .2s;
    }
    .form-row button:hover { opacity: .85; }

    /* ── Todo list ── */
    .list { padding: 12px 28px 24px; }
    .todo-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid #f5f5f5;
    }
    .todo-item:last-child { border-bottom: none; }

    .check-btn {
      width: 22px; height: 22px;
      border-radius: 50%;
      border: 2px solid #ccc;
      background: #fff;
      cursor: pointer;
      flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      text-decoration: none;
      transition: all .2s;
      font-size: 13px;
    }
    .check-btn.done {
      background: #533483;
      border-color: #533483;
      color: #fff;
    }
    .check-btn:not(.done):hover { border-color: #533483; }

    .task-text {
      flex: 1;
      font-size: 14px;
      color: #333;
      transition: all .2s;
    }
    .task-text.done {
      text-decoration: line-through;
      color: #aaa;
    }

    .del-btn {
      color: #ccc;
      text-decoration: none;
      font-size: 18px;
      line-height: 1;
      transition: color .2s;
      flex-shrink: 0;
    }
    .del-btn:hover { color: #e74c3c; }

    /* ── Footer ── */
    .footer {
      background: #f9f9f9;
      border-top: 1px solid #eee;
      padding: 14px 28px;
      font-size: 12px;
      color: #888;
      text-align: center;
    }
    .footer span { color: #533483; font-weight: 600; }

    .empty { text-align: center; color: #bbb; font-size: 14px; padding: 20px 0; }
  </style>
</head>
<body>

<div class="card">

  <!-- Header -->
  <div class="header">
    <h1>📝 To-Do List App</h1>
    <p class="sub">Muhammad Rizki Padillah — NPM 2410010252</p>
    <div class="badge-row">
      <span class="badge">🐳 Docker Container</span>
      <span class="badge">🐘 PHP 8.2</span>
      <span class="badge">☁️ Tugas 2 Cloud</span>
    </div>
  </div>

  <!-- Progress -->
  <div class="progress-wrap">
    <div class="progress-info">
      <span>Progress tugas</span>
      <span><?= $done ?>/<?= $total ?> selesai (<?= $persen ?>%)</span>
    </div>
    <div class="progress-bar"><div class="progress-fill"></div></div>
  </div>

  <!-- Form tambah -->
  <div class="form-wrap">
    <form method="POST">
      <div class="form-row">
        <input type="text" name="task" placeholder="Tambah tugas baru..." autocomplete="off">
        <button type="submit">+ Tambah</button>
      </div>
    </form>
  </div>

  <!-- List -->
  <div class="list">
    <?php if (empty($todos)): ?>
      <p class="empty">Belum ada tugas. Tambahkan di atas! 🎉</p>
    <?php else: ?>
      <?php foreach ($todos as $t): ?>
        <div class="todo-item">
          <a href="?toggle=<?= $t['id'] ?>"
             class="check-btn <?= $t['done'] ? 'done' : '' ?>">
            <?= $t['done'] ? '✓' : '' ?>
          </a>
          <span class="task-text <?= $t['done'] ? 'done' : '' ?>">
            <?= $t['task'] ?>
          </span>
          <a href="?delete=<?= $t['id'] ?>" class="del-btn"
             onclick="return confirm('Hapus tugas ini?')">×</a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Footer -->
  <div class="footer">
    Berjalan di dalam <span>Docker Container</span> &bull;
    PHP <?= phpversion() ?> &bull;
    Server: <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Apache' ?>
  </div>

</div>

</body>
</html>
