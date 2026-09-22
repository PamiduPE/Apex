<?php
session_start();
require __DIR__ . '/config.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* ---------------- logout ---------------- */
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: view.php');
    exit;
}

/* ---------------- login (one password, nothing fancy) ---------------- */
$loginError = '';
if (isset($_POST['login'])) {
    if (hash_equals(VIEW_PASSWORD, (string)($_POST['password'] ?? ''))) {
        session_regenerate_id(true);
        $_SESSION['auth'] = true;
        header('Location: view.php');
        exit;
    }
    $loginError = 'Wrong password. Try again.';
}

if (empty($_SESSION['auth'])) { ?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>ApexCV — Submissions</title>
<style>
  *{box-sizing:border-box}
  body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f6f1f7;font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;color:#2b2530}
  form{width:min(92vw,360px);background:#fff;border:1px solid #e6dce8;border-radius:18px;padding:32px 28px;box-shadow:0 24px 50px -28px rgba(74,26,76,.4)}
  h1{margin:0 0 6px;font-size:1.3rem;color:#4a1a4c}
  p{margin:0 0 20px;font-size:14px;color:#706a75}
  input{width:100%;padding:13px 14px;border:1.5px solid #e6dce8;border-radius:12px;font-size:15px;margin-bottom:12px}
  input:focus{outline:none;border-color:#4a1a4c}
  button{width:100%;padding:13px;border:0;border-radius:12px;background:#4a1a4c;color:#fff;font-size:15px;font-weight:600;cursor:pointer}
  .err{color:#c0392b;font-size:13.5px;margin:0 0 12px}
</style></head><body>
<form method="post">
  <h1>ApexCV submissions</h1>
  <p>Enter the password to view client details.</p>
  <?php if ($loginError): ?><div class="err"><?= h($loginError) ?></div><?php endif; ?>
  <input type="password" name="password" placeholder="Password" autofocus required>
  <button type="submit" name="login" value="1">Open</button>
</form></body></html>
<?php
    exit;
}

/* ---------------- logged in ---------------- */
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];
$pdo  = db();

/* ---------- actions: mark done / delete ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) { http_response_code(403); exit('Invalid token'); }
    $id = (int)($_POST['id'] ?? 0);

    if ($_POST['action'] === 'toggle') {
        $pdo->prepare("UPDATE submissions SET status = IF(status='done','new','done') WHERE id = ?")->execute([$id]);
    } elseif ($_POST['action'] === 'delete') {
        $st = $pdo->prepare('SELECT photo FROM submissions WHERE id = ?');
        $st->execute([$id]);
        $ph = $st->fetchColumn();
        if ($ph) @unlink(__DIR__ . '/uploads/' . basename($ph));
        $pdo->prepare('DELETE FROM submissions WHERE id = ?')->execute([$id]);
    }
    $back = array_filter(['f' => $_POST['f'] ?? '', 'q' => $_POST['q'] ?? ''], fn($v) => $v !== '');
    header('Location: view.php' . ($back ? '?' . http_build_query($back) : ''));
    exit;
}

/* ---------- filters ---------- */
$filter = $_GET['f'] ?? 'all';
if (!in_array($filter, ['all', 'new', 'done'], true)) $filter = 'all';
$q = trim((string)($_GET['q'] ?? ''));

$where = []; $params = [];
if ($filter !== 'all') { $where[] = 'status = ?'; $params[] = $filter; }
if ($q !== '') {
    $where[] = '(full_name LIKE ? OR phone LIKE ? OR email LIKE ? OR nic LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like, $like);
}
$sql = 'SELECT * FROM submissions' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY id DESC LIMIT 300';
$st = $pdo->prepare($sql);
$st->execute($params);
$rows = $st->fetchAll();

$counts = $pdo->query("SELECT COUNT(*) AS total, SUM(status='new') AS n, SUM(status='done') AS d FROM submissions")->fetch();
$total = (int)$counts['total']; $cNew = (int)$counts['n']; $cDone = (int)$counts['d'];

/* ---------- helpers ---------- */
function val($s) {
    $s = trim((string)$s);
    return $s === '' ? '<span class="empty">Not provided</span>' : h($s);
}
function block($s) {
    $s = trim((string)$s);
    return $s === '' ? '<span class="empty">Not provided</span>' : nl2br(h($s));
}
function field($label, $html, $wide = false) {
    echo '<div class="f' . ($wide ? ' wide' : '') . '"><div class="l">' . h($label) . '</div><div class="v">' . $html . '</div></div>';
}
function waNumber($phone) {
    $d = preg_replace('/\D+/', '', (string)$phone);
    if ($d === '') return '';
    if (strpos($d, '0') === 0) $d = '94' . substr($d, 1);
    return $d;
}
function pkgClass($name) { return strtolower(strtok((string)$name, ' ')); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Submissions (<?= $total ?>) — ApexCV</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --plum:#4a1a4c; --plum-soft:#f4ebf4; --bg:#f6f1f7; --ink:#141114; --text:#2b2530;
  --muted:#7a7380; --line:#e9e1eb; --green:#1f9d5c; --green-soft:#e6f6ee; --amber:#b7791f; --amber-soft:#fdf3dc;
  --red:#d64545;
}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;font-size:15px;line-height:1.55}
a{color:inherit}
.wrap{width:min(100% - 24px,1000px);margin-inline:auto}

/* top bar */
.top{position:sticky;top:0;z-index:10;background:var(--plum);color:#fff}
.top .wrap{display:flex;align-items:center;justify-content:space-between;gap:12px;height:58px}
.top strong{font-size:17px;letter-spacing:-.01em}
.top nav{display:flex;gap:8px}
.top nav a{padding:7px 14px;border-radius:999px;background:rgba(255,255,255,.12);font-size:13px;font-weight:600;text-decoration:none}
.top nav a:hover{background:rgba(255,255,255,.22)}

/* tools */
.tools{padding:22px 0 8px;display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between}
.tabs{display:flex;gap:6px;flex-wrap:wrap}
.tab{padding:8px 15px;border-radius:999px;border:1px solid var(--line);background:#fff;font-size:13.5px;font-weight:600;text-decoration:none;color:var(--text)}
.tab b{margin-left:6px;color:var(--muted);font-weight:600}
.tab.on{background:var(--plum);border-color:var(--plum);color:#fff}
.tab.on b{color:rgba(255,255,255,.75)}
.search{display:flex;gap:8px;flex:1;min-width:230px;max-width:380px}
.search input{flex:1;min-width:0;padding:10px 14px;border:1.5px solid var(--line);border-radius:999px;font:inherit;font-size:14px;background:#fff}
.search input:focus{outline:none;border-color:var(--plum)}
.search button{padding:0 16px;border:0;border-radius:999px;background:var(--plum);color:#fff;font-weight:600;font-size:14px;cursor:pointer}

/* card */
.card{margin:16px 0;background:#fff;border:1px solid var(--line);border-radius:18px;overflow:hidden}
.head{display:flex;gap:16px;align-items:center;padding:18px 20px;border-bottom:1px solid var(--line);background:#fcfafc}
.pic{width:72px;height:72px;flex:none;border-radius:50%;background:var(--plum-soft);display:grid;place-items:center;color:var(--plum);font-size:26px;font-weight:700;overflow:hidden;text-decoration:none}
.pic img{width:100%;height:100%;object-fit:cover}
.who{flex:1;min-width:0}
.who h2{margin:0;font-size:1.15rem;color:var(--ink);letter-spacing:-.01em;overflow-wrap:anywhere}
.when{font-size:13px;color:var(--muted);margin-top:2px}
.tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.tag{padding:3px 11px;border-radius:999px;font-size:12px;font-weight:700}
.pk-local{background:#eef1f6;color:#3d4b66}
.pk-global{background:var(--plum-soft);color:var(--plum)}
.pk-premium{background:#f6e7d3;color:#8a5a22}
.st-new{background:var(--amber-soft);color:var(--amber)}
.st-done{background:var(--green-soft);color:var(--green)}
.id{font-size:12.5px;color:var(--muted);align-self:flex-start}

.body{padding:6px 20px 8px}
.sec{padding:14px 0;border-bottom:1px dashed var(--line)}
.sec:last-child{border-bottom:0}
.sec h3{margin:0 0 10px;font-size:13px;font-weight:700;color:var(--plum)}
.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px 24px}
.f.wide{grid-column:1/-1}
.l{font-size:12.5px;color:var(--muted);margin-bottom:1px}
.v{font-size:15px;color:var(--ink);overflow-wrap:anywhere}
.v a{color:var(--plum);font-weight:500}
.long{white-space:normal;background:#fbf9fc;border:1px solid var(--line);border-radius:12px;padding:12px 14px;color:var(--ink)}
.empty{color:#a79eae;font-style:italic;font-size:14px}

.actions{display:flex;flex-wrap:wrap;gap:8px;padding:14px 20px;border-top:1px solid var(--line);background:#fcfafc}
.actions form{margin:0}
.btn{display:inline-flex;align-items:center;justify-content:center;height:38px;padding:0 15px;border:1px solid var(--line);border-radius:10px;background:#fff;font:inherit;font-size:13.5px;font-weight:600;color:var(--text);cursor:pointer;text-decoration:none}
.btn:hover{border-color:var(--plum);color:var(--plum)}
.btn.main{background:var(--plum);border-color:var(--plum);color:#fff}
.btn.main:hover{background:#5d2360;color:#fff}
.btn.del{margin-left:auto;color:var(--red)}
.btn.del:hover{border-color:var(--red);color:var(--red)}
textarea.src{position:absolute;left:-9999px;top:0}

.none{margin:60px 0;text-align:center;color:var(--muted)}
@media (max-width:620px){
  .grid{grid-template-columns:1fr}
  .head{align-items:flex-start}
  .pic{width:60px;height:60px}
  .btn.del{margin-left:0}
}
</style>
</head>
<body>

<div class="top"><div class="wrap">
  <strong>ApexCV submissions</strong>
  <nav>
    <a href="view.php?<?= h(http_build_query(array_filter(['f'=>$filter,'q'=>$q]))) ?>">Refresh</a>
    <a href="view.php?logout=1">Log out</a>
  </nav>
</div></div>

<div class="wrap">
  <div class="tools">
    <div class="tabs">
      <a class="tab <?= $filter==='all'?'on':'' ?>"  href="view.php?<?= h(http_build_query(array_filter(['q'=>$q]))) ?>">All<b><?= $total ?></b></a>
      <a class="tab <?= $filter==='new'?'on':'' ?>"  href="view.php?<?= h(http_build_query(array_filter(['f'=>'new','q'=>$q]))) ?>">New<b><?= $cNew ?></b></a>
      <a class="tab <?= $filter==='done'?'on':'' ?>" href="view.php?<?= h(http_build_query(array_filter(['f'=>'done','q'=>$q]))) ?>">Done<b><?= $cDone ?></b></a>
    </div>
    <form class="search" method="get">
      <?php if ($filter !== 'all'): ?><input type="hidden" name="f" value="<?= h($filter) ?>"><?php endif; ?>
      <input type="search" name="q" value="<?= h($q) ?>" placeholder="Search name, phone, email, NIC">
      <button type="submit">Search</button>
    </form>
  </div>

  <?php if (!$rows): ?>
    <div class="none">
      <?= $q !== '' || $filter !== 'all' ? 'No submissions match this filter.' : 'No submissions yet. New form entries will appear here.' ?>
    </div>
  <?php endif; ?>

  <?php foreach ($rows as $r):
      $wa   = waNumber($r['phone']);
      $when = date('d M Y, g:i A', strtotime($r['created_at']));
      $dobF = strtotime($r['dob']) ? date('d M Y', strtotime($r['dob'])) : $r['dob'];
      $initial = mb_strtoupper(mb_substr($r['full_name'], 0, 1));

      $copy = "Full Name: {$r['full_name']}\nDate of Birth: {$dobF}\nNationality: {$r['nationality']}\n"
            . "NIC: " . ($r['nic'] ?: '-') . "\nPassport: " . ($r['passport'] ?: '-') . "\n"
            . "Address: {$r['address']}\nPhone: {$r['phone']}\nEmail: {$r['email']}\n\n"
            . "EDUCATION:\n{$r['education']}\n\nEXPERIENCE:\n{$r['experience']}\n\n"
            . "SKILLS:\n" . ($r['skills'] ?: '-') . "\n\nPackage: {$r['package_name']}";
  ?>
  <article class="card">
    <div class="head">
      <?php if ($r['photo']): ?>
        <a class="pic" href="uploads/<?= h($r['photo']) ?>" target="_blank" rel="noopener" title="Open photo"><img src="uploads/<?= h($r['photo']) ?>" alt=""></a>
      <?php else: ?>
        <span class="pic"><?= h($initial) ?></span>
      <?php endif; ?>
      <div class="who">
        <h2><?= h($r['full_name']) ?></h2>
        <div class="when">Submitted <?= h($when) ?></div>
        <div class="tags">
          <span class="tag pk-<?= h(pkgClass($r['package_name'])) ?>"><?= h($r['package_name']) ?></span>
          <span class="tag st-<?= $r['status']==='done'?'done':'new' ?>"><?= $r['status']==='done' ? 'Done' : 'New' ?></span>
          <?php if (!$r['photo']): ?><span class="tag pk-local">No photo</span><?php endif; ?>
        </div>
      </div>
      <div class="id">#<?= (int)$r['id'] ?></div>
    </div>

    <div class="body">
      <div class="sec">
        <h3>Personal details</h3>
        <div class="grid">
          <?php
            field('Full name', val($r['full_name']), true);
            field('Date of birth', val($dobF));
            field('Nationality', val($r['nationality']));
            field('NIC number', val($r['nic']));
            field('Passport number', val($r['passport']));
          ?>
        </div>
      </div>

      <div class="sec">
        <h3>Contact</h3>
        <div class="grid">
          <?php
            field('Phone', '<a href="tel:' . h($r['phone']) . '">' . h($r['phone']) . '</a>'
                . ($wa ? ' &nbsp;·&nbsp; <a href="https://wa.me/' . h($wa) . '" target="_blank" rel="noopener">WhatsApp</a>' : ''));
            field('Email', '<a href="mailto:' . h($r['email']) . '">' . h($r['email']) . '</a>');
            field('Address', block($r['address']), true);
          ?>
        </div>
      </div>

      <div class="sec">
        <h3>Educational qualifications</h3>
        <div class="long"><?= block($r['education']) ?></div>
      </div>

      <div class="sec">
        <h3>Working experience</h3>
        <div class="long"><?= block($r['experience']) ?></div>
      </div>

      <div class="sec">
        <h3>Other qualifications / skills</h3>
        <div class="long"><?= block($r['skills']) ?></div>
      </div>
    </div>

    <div class="actions">
      <textarea class="src" readonly><?= h($copy) ?></textarea>
      <button type="button" class="btn main" data-copy>Copy all details</button>
      <?php if ($r['photo']): ?>
        <a class="btn" href="uploads/<?= h($r['photo']) ?>" download>Download photo</a>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
        <input type="hidden" name="action" value="toggle">
        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
        <input type="hidden" name="f" value="<?= h($filter === 'all' ? '' : $filter) ?>">
        <input type="hidden" name="q" value="<?= h($q) ?>">
        <button type="submit" class="btn"><?= $r['status']==='done' ? 'Mark as new' : 'Mark as done' ?></button>
      </form>
      <form method="post" onsubmit="return confirm('Delete <?= h(addslashes($r['full_name'])) ?> permanently?');">
        <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
        <input type="hidden" name="f" value="<?= h($filter === 'all' ? '' : $filter) ?>">
        <input type="hidden" name="q" value="<?= h($q) ?>">
        <button type="submit" class="btn del">Delete</button>
      </form>
    </div>
  </article>
  <?php endforeach; ?>
  <div style="height:40px"></div>
</div>

<script>
document.querySelectorAll('[data-copy]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var ta = btn.parentElement.querySelector('textarea.src');
    var done = function () {
      var old = btn.textContent; btn.textContent = 'Copied!';
      setTimeout(function () { btn.textContent = old; }, 1500);
    };
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(ta.value).then(done);
    } else {
      ta.select(); document.execCommand('copy'); done();
    }
  });
});
</script>
</body>
</html>
