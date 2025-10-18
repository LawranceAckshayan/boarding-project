<?php
// ----- turn on error display -----
error_reporting(E_ALL);
ini_set('display_errors', 1);

// results helper
function row($label, $ok, $msg='') {
  echo '<tr><td>'.$label.'</td><td>'.($ok?'✅ PASS':'❌ FAIL').'</td><td>'.htmlspecialchars($msg).'</td></tr>';
}

echo '<!doctype html><meta charset="utf-8"><title>WAMP Project Diagnostics</title>';
echo '<h1>Boarding_management_system – Diagnostics</h1>';
echo '<table border="1" cellpadding="6" cellspacing="0"><tr><th>Check</th><th>Status</th><th>Details</th></tr>';

// 1) PHP works
row('PHP running', true, phpversion());

// 2) db_config.php presence + include
$cfgPath = __DIR__ . DIRECTORY_SEPARATOR . 'db_config.php';
$cfgExists = file_exists($cfgPath);
row('db_config.php exists', $cfgExists, $cfgPath);
if ($cfgExists) {
  ob_start();
  $ok = @include $cfgPath;
  $includeErr = ob_get_clean();
  row('db_config.php included', $ok !== false, $includeErr ?: 'included');
}

// 3) mysqli connection variable and connect
$hasConn = isset($connection);
row('$connection set', $hasConn, $hasConn ? 'variable found' : 'not set in db_config.php');

if ($hasConn) {
  $ping = @mysqli_ping($connection);
  row('MySQL connection alive', $ping, $ping ? 'connected' : (mysqli_connect_error() ?: 'ping failed'));
}

// 4) database basics
if ($hasConn) {
  $res = @mysqli_query($connection, "SHOW TABLES");
  $ok = $res !== false;
  row('SHOW TABLES', $ok, $ok ? ('tables: '.mysqli_num_rows($res)) : (mysqli_error($connection) ?: 'query failed'));
}

// 5) session status
if (session_status() === PHP_SESSION_NONE) session_start();
row('Session started', session_status() === PHP_SESSION_ACTIVE, 'user_id='.(isset($_SESSION['user_id'])?$_SESSION['user_id']:'(not set)'));

// 6) .htaccess issues
$ht = __DIR__ . DIRECTORY_SEPARATOR . '.htaccess';
row('.htaccess present', file_exists($ht), file_exists($ht) ? 'may contain rewrite rules' : 'none');

// 7) PHP short tags scan (basic)
$shortTags = false;
foreach (glob(__DIR__.'/*.php') as $f) {
  if (preg_match('/\<\?(?!php|=)/', file_get_contents($f))) { $shortTags = true; break; }
}
row('No PHP short tags (“<?”)', !$shortTags, $shortTags ? 'Found files using short tags' : 'Good');

// 8) UTF-8 BOM check on index.php
$idx = __DIR__ . DIRECTORY_SEPARATOR . 'index.php';
if (file_exists($idx)) {
  $bytes = file_get_contents($idx);
  $hasBOM = substr($bytes, 0, 3) === "\xEF\xBB\xBF";
  row('index.php without BOM', !$hasBOM, $hasBOM ? 'BOM detected' : 'No BOM');
} else {
  row('index.php exists', false, $idx.' not found');
}

echo '</table>';
echo '<p>Open logs after any 500 error:</p><ul>
<li>C:\\wamp64\\logs\\php_error.log</li>
<li>C:\\wamp64\\logs\\apache_error.log</li>
</ul>';