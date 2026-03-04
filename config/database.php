<?php
/**
 * database.php — Database connection with full error handling
 * Place at: /app/config/database.php
 */

class Database {

    // ── Connection settings — update these for your server ──────────────────
    private string $host     = 'localhost';
    private string $db_name  = 'pizz_a64';
    private string $username = 'root';
    private string $password = '';        // change if your MySQL has a password
    private string $charset  = 'utf8mb4';

    private ?PDO $connection = null;

    /**
     * Returns a PDO connection.
     * On failure, renders a friendly error page and exits.
     */
    public function connect(): PDO {

        if ($this->connection !== null) {
            return $this->connection;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset} COLLATE utf8mb4_unicode_ci",
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            $this->renderError($e);
        }

        return $this->connection;
    }

    // ── Private error renderer ───────────────────────────────────────────────
    private function renderError(PDOException $e): never {

        // Determine a human-readable tip for common error codes
        $code    = (int) $e->getCode();
        $message = $e->getMessage();

        $tips = match(true) {
            str_contains($message, 'Access denied')                => 'Your database username or password is incorrect. Check <code>$username</code> and <code>$password</code> in <em>app/config/database.php</em>.',
            str_contains($message, 'Unknown database')             => "The database <strong>{$this->db_name}</strong> does not exist. Run <em>database.sql</em> in phpMyAdmin or MySQL to create it.",
            str_contains($message, "Can't connect")                => 'MySQL server is not running or the host is wrong. Make sure XAMPP / WAMP / your server is started.',
            str_contains($message, 'Connection refused')           => 'MySQL refused the connection. Ensure the MySQL service is running.',
            str_contains($message, 'No such file or directory')    => 'MySQL socket file not found. Check that your MySQL service is running.',
            default                                                 => 'Double-check your host, database name, username, and password in <em>app/config/database.php</em>.',
        };

        // Only show raw PDO message in development (never in production)
        $show_raw = ($_SERVER['SERVER_NAME'] ?? '') === 'localhost'
                 || ($_SERVER['REMOTE_ADDR'] ?? '')  === '127.0.0.1'
                 || ($_SERVER['REMOTE_ADDR'] ?? '')  === '::1';

        // If headers not yet sent, output a full page
        if (!headers_sent()) {
            header('HTTP/1.1 503 Service Unavailable');
            header('Content-Type: text/html; charset=utf-8');
        }

        ?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Database Error — Pizz_a64</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'DM Sans',sans-serif;background:#fffdf9;color:#1c1512;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
    .box{background:#fff;border:1px solid #ede8e0;border-radius:18px;padding:52px 44px;max-width:540px;width:100%;text-align:center;box-shadow:0 8px 32px rgba(30,10,5,.10)}
    .icon{font-size:3.4rem;color:#e63946;display:block;margin-bottom:18px}
    h1{font-size:1.5rem;margin-bottom:10px}
    p{color:#7a6e68;line-height:1.7;margin-bottom:10px;font-size:.9rem}
    .tip{background:#fff0f1;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin:18px 0;font-size:.85rem;color:#b91c1c;text-align:left;line-height:1.65}
    .tip code{background:#fee2e2;border-radius:4px;padding:1px 6px;font-family:monospace;font-size:.82rem}
    .raw{display:none;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;font-size:.76rem;color:#7f1d1d;text-align:left;line-height:1.6;margin-top:10px;word-break:break-all;font-family:monospace}
    .steps{background:#faf6f0;border-radius:10px;padding:18px 22px;text-align:left;margin:16px 0}
    .steps h3{font-size:.82rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#7a6e68;margin-bottom:10px}
    .steps ol{padding-left:18px}
    .steps li{font-size:.83rem;color:#1c1512;margin-bottom:6px;line-height:1.6}
    .steps li code{background:#ede8e0;border-radius:4px;padding:1px 5px;font-family:monospace;font-size:.78rem}
    a.btn{display:inline-flex;align-items:center;gap:8px;background:#e63946;color:#fff;padding:11px 26px;border-radius:999px;font-weight:600;font-size:.88rem;text-decoration:none;margin-top:18px;transition:.2s}
    a.btn:hover{background:#c1121f}
    .toggle{background:none;border:none;color:#7a6e68;font-size:.78rem;cursor:pointer;text-decoration:underline;margin-top:8px}
  </style>
</head>
<body>
  <div class="box">
    <i class="fas fa-database icon"></i>
    <h1>Database Connection Failed</h1>
    <p>Pizz_a64 could not connect to the MySQL database. This usually means the database is not set up yet, or the credentials are wrong.</p>

    <div class="tip">
      <i class="fas fa-lightbulb" style="margin-right:6px"></i>
      <?= $tips ?>
    </div>

    <div class="steps">
      <h3><i class="fas fa-list-ol" style="margin-right:6px"></i>Quick Fix Checklist</h3>
      <ol>
        <li>Make sure <strong>XAMPP / WAMP / MySQL</strong> is running.</li>
        <li>Open <strong>phpMyAdmin</strong> and import <code>database.sql</code> to create the <code>pizz_a64</code> database.</li>
        <li>Open <code>app/config/database.php</code> and verify <code>$host</code>, <code>$db_name</code>, <code>$username</code>, <code>$password</code>.</li>
        <li>Default XAMPP credentials: host <code>localhost</code>, user <code>root</code>, password <em>(empty)</em>.</li>
        <li>Refresh this page once the database is set up.</li>
      </ol>
    </div>

    <?php if ($show_raw): ?>
    <button class="toggle" onclick="document.querySelector('.raw').style.display='block';this.style.display='none'">
      <i class="fas fa-bug"></i> Show raw error (dev only)
    </button>
    <div class="raw"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <a href="javascript:location.reload()" class="btn">
      <i class="fas fa-redo"></i> Try Again
    </a>
  </div>
</body>
</html>
<?php
        exit;
    }
}