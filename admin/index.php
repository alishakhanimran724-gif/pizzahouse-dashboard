<?php
session_start();

// ── Auth guard ────────────────────────────────────────────────
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// ── Models ───────────────────────────────────────────────────
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Product.php';
require_once __DIR__ . '/../app/models/Order.php';

$database     = new Database();
$db           = $database->connect();
$productModel = new Product($db);
$orderModel   = new Order($db);

$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? '';

// ════════════════════════════════════════════════════════════
//  ACTIONS  (all redirects happen before any HTML output)
// ════════════════════════════════════════════════════════════

if ($action === 'logout') {
    session_destroy();
    header('Location: login.php'); exit;
}

if ($action === 'update_status' && isset($_POST['order_id'], $_POST['status'])) {
    $orderModel->updateStatus((int)$_POST['order_id'], $_POST['status']);
    $_SESSION['flash'] = ['type'=>'success','msg'=>'Order status updated!'];
    header('Location: ?page=orders'); exit;
}

if ($action === 'save_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'        => trim($_POST['name']        ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'image'       => trim($_POST['image']       ?? ''),
        'category'    => trim($_POST['category']    ?? ''),
        'price'       => floatval($_POST['price']   ?? 0),
        'is_veg'      => isset($_POST['is_veg'])      ? 1 : 0,
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
    ];
    if (!empty($_POST['product_id'])) {
        $productModel->update((int)$_POST['product_id'], $data);
        $_SESSION['flash'] = ['type'=>'success','msg'=>'Product updated successfully!'];
    } else {
        $productModel->create($data);
        $_SESSION['flash'] = ['type'=>'success','msg'=>'Product added successfully!'];
    }
    header('Location: ?page=products'); exit;
}

if ($action === 'delete_product' && isset($_POST['product_id'])) {
    $productModel->delete((int)$_POST['product_id']);
    $_SESSION['flash'] = ['type'=>'danger','msg'=>'Product deleted.'];
    header('Location: ?page=products'); exit;
}

if ($page === 'coupons') {
    if ($action === 'save_coupon' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $coupons = $_SESSION['coupons'] ?? _defaultCoupons();
        $ids     = array_column($coupons, 'id');
        $entry   = [
            'id'        => !empty($_POST['coupon_id']) ? (int)$_POST['coupon_id'] : (($ids ? max($ids) : 0) + 1),
            'code'      => strtoupper(trim($_POST['code'] ?? '')),
            'type'      => $_POST['type']      ?? 'percent',
            'value'     => floatval($_POST['value']     ?? 0),
            'min_order' => floatval($_POST['min_order'] ?? 0),
            'max_uses'  => (int)($_POST['max_uses']     ?? 100),
            'expires'   => $_POST['expires']   ?? '',
            'status'    => $_POST['status']    ?? 'draft',
            'desc'      => trim($_POST['desc'] ?? ''),
            'uses'      => 0,
        ];
        if (!empty($_POST['coupon_id'])) {
            foreach ($coupons as &$c) {
                if ($c['id'] === $entry['id']) { $entry['uses'] = $c['uses']; $c = $entry; break; }
            } unset($c);
            $_SESSION['flash'] = ['type'=>'success','msg'=>'Coupon updated!'];
        } else {
            $coupons[] = $entry;
            $_SESSION['flash'] = ['type'=>'success','msg'=>'Coupon created!'];
        }
        $_SESSION['coupons'] = $coupons;
        header('Location: ?page=coupons'); exit;
    }
    if ($action === 'delete_coupon' && isset($_POST['coupon_id'])) {
        $coupons = $_SESSION['coupons'] ?? _defaultCoupons();
        $_SESSION['coupons'] = array_values(array_filter($coupons, fn($c) => $c['id'] !== (int)$_POST['coupon_id']));
        $_SESSION['flash'] = ['type'=>'danger','msg'=>'Coupon deleted.'];
        header('Location: ?page=coupons'); exit;
    }
}

if ($page === 'reviews') {
    if ($action === 'approve_review' && isset($_POST['review_id'])) {
        $reviews = $_SESSION['reviews'] ?? _defaultReviews();
        foreach ($reviews as &$r) {
            if ($r['id'] === (int)$_POST['review_id']) { $r['status'] = 'published'; break; }
        } unset($r);
        $_SESSION['reviews'] = $reviews;
        $_SESSION['flash'] = ['type'=>'success','msg'=>'Review approved!'];
        header('Location: ?page=reviews'); exit;
    }
    if ($action === 'delete_review' && isset($_POST['review_id'])) {
        $reviews = $_SESSION['reviews'] ?? _defaultReviews();
        $_SESSION['reviews'] = array_values(array_filter($reviews, fn($r) => $r['id'] !== (int)$_POST['review_id']));
        $_SESSION['flash'] = ['type'=>'danger','msg'=>'Review deleted.'];
        header('Location: ?page=reviews'); exit;
    }
}

if ($page === 'settings' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['settings'] = $_POST;
    $_SESSION['flash'] = ['type'=>'success','msg'=>'Settings saved!'];
    header('Location: ?page=settings&tab=' . ($_GET['tab'] ?? 'general')); exit;
}

// ════════════════════════════════════════════════════════════
//  GLOBAL STATS
// ════════════════════════════════════════════════════════════
$totalOrders   = $orderModel->count();
$totalProducts = $productModel->count();
$totalRevenue  = $orderModel->totalRevenue();
$pendingOrders = $orderModel->countByStatus('pending');
$recentRevenue = $orderModel->recentRevenue(30);

// ════════════════════════════════════════════════════════════
//  SMART PATH RESOLVER
//  Supports BOTH layouts:
//    A) Flat:      admin/header.php, admin/dashboard.php ...
//    B) Sub-dirs:  admin/includes/header.php, admin/views/dashboard.php
// ════════════════════════════════════════════════════════════
$adminDir  = __DIR__;                          // C:\xamppp\htdocs\projects\pizz\admin
$viewsDir  = is_dir($adminDir . DIRECTORY_SEPARATOR . 'views')
             ? $adminDir . DIRECTORY_SEPARATOR . 'views'
             : $adminDir;
$incDir    = is_dir($adminDir . DIRECTORY_SEPARATOR . 'includes')
             ? $adminDir . DIRECTORY_SEPARATOR . 'includes'
             : $adminDir;

function _inc(string $dir, string $file): string {
    return $dir . DIRECTORY_SEPARATOR . $file;
}

// ── Header ────────────────────────────────────────────────────
include _inc($incDir, 'header.php');

// ── Flash message ─────────────────────────────────────────────
if (!empty($_SESSION['flash'])) {
    $f = $_SESSION['flash']; unset($_SESSION['flash']);
    $icon = $f['type'] === 'success' ? 'check-circle' : 'exclamation-circle';
    echo '<div class="alert alert-' . htmlspecialchars($f['type']) . '">'
       . '<i class="fas fa-' . $icon . '"></i> '
       . htmlspecialchars($f['msg']) . '</div>';
}

// ════════════════════════════════════════════════════════════
//  ROUTER
// ════════════════════════════════════════════════════════════
switch ($page) {

    case 'orders':
        $orders      = $orderModel->getAll(200);
        $orderDetail = null;
        $orderItems  = [];
        if (!empty($_GET['id'])) {
            $orderDetail = $orderModel->getById((int)$_GET['id']);
            $orderItems  = $orderModel->getItems((int)$_GET['id']);
        }
        include _inc($viewsDir, 'orders.php');
        break;

    case 'products':
        $products    = $productModel->getAll();
        $editProduct = null;
        if (!empty($_GET['edit']))
            $editProduct = $productModel->getById((int)$_GET['edit']);
        include _inc($viewsDir, 'products.php');
        break;

    case 'customers':
        $customers = method_exists($orderModel, 'getCustomers')
            ? $orderModel->getCustomers() : [];
        include _inc($viewsDir, 'customers.php');
        break;

    case 'revenue':
        include _inc($viewsDir, 'revenue.php');
        break;

    case 'reviews':
        $reviews = $_SESSION['reviews'] ?? _defaultReviews();
        include _inc($viewsDir, 'reviews.php');
        break;

    case 'coupons':
        $coupons = $_SESSION['coupons'] ?? _defaultCoupons();
        include _inc($viewsDir, 'coupons.php');
        break;

    case 'settings':
        include _inc($viewsDir, 'setting.php');
        break;

    default:
        $recentOrders = $orderModel->getAll(10);
        include _inc($viewsDir, 'dashboard.php');
        break;
}

// ── Footer ────────────────────────────────────────────────────
include _inc($incDir, 'footer.php');


// ════════════════════════════════════════════════════════════
//  DEFAULT DATA HELPERS
// ════════════════════════════════════════════════════════════
function _defaultCoupons(): array {
    return [
        ['id'=>1,'code'=>'PIZZA50','type'=>'percent','value'=>50,'min_order'=>499,'uses'=>142,'max_uses'=>200,'expires'=>'2026-03-31','status'=>'active','desc'=>'Weekend special 50% off'],
        ['id'=>2,'code'=>'WELCOME20','type'=>'percent','value'=>20,'min_order'=>0,'uses'=>88,'max_uses'=>100,'expires'=>'2026-04-30','status'=>'active','desc'=>'New user welcome'],
        ['id'=>3,'code'=>'FREESHIP','type'=>'flat','value'=>49,'min_order'=>299,'uses'=>234,'max_uses'=>500,'expires'=>'2026-06-30','status'=>'active','desc'=>'Free delivery'],
        ['id'=>4,'code'=>'FLAT100','type'=>'flat','value'=>100,'min_order'=>599,'uses'=>67,'max_uses'=>100,'expires'=>'2026-03-15','status'=>'active','desc'=>'₹100 flat off'],
        ['id'=>5,'code'=>'SUMMER25','type'=>'percent','value'=>25,'min_order'=>0,'uses'=>0,'max_uses'=>300,'expires'=>'2026-06-01','status'=>'draft','desc'=>'Upcoming summer deal'],
        ['id'=>6,'code'=>'OLD50','type'=>'flat','value'=>50,'min_order'=>399,'uses'=>89,'max_uses'=>89,'expires'=>'2025-12-31','status'=>'expired','desc'=>'Expired'],
    ];
}

function _defaultReviews(): array {
    return [
        ['id'=>1,'customer'=>'Rahul Kumar','pizza'=>'Margherita Classic','rating'=>5,'review'=>'Best pizza in Hyderabad! Crispy crust, fresh basil, perfect sauce.','date'=>'2026-03-03','status'=>'published','helpful'=>24],
        ['id'=>2,'customer'=>'Priya Sharma','pizza'=>'BBQ Chicken','rating'=>5,'review'=>'Lightning fast delivery and arrived piping hot. 10/10!','date'=>'2026-03-02','status'=>'published','helpful'=>18],
        ['id'=>3,'customer'=>'Anil Mehta','pizza'=>'Meat Lovers','rating'=>5,'review'=>'You can taste the freshness in every bite.','date'=>'2026-03-01','status'=>'published','helpful'=>31],
        ['id'=>4,'customer'=>'Sara Khan','pizza'=>'Four Cheese','rating'=>3,'review'=>'Pizza was good but delivery took longer than expected.','date'=>'2026-02-28','status'=>'published','helpful'=>7],
        ['id'=>5,'customer'=>'Dev Patel','pizza'=>'Pepperoni Supreme','rating'=>4,'review'=>'Great pepperoni, generous toppings. Would love a thinner crust option.','date'=>'2026-02-27','status'=>'published','helpful'=>12],
        ['id'=>6,'customer'=>'Karan Singh','pizza'=>'Veggie Garden','rating'=>2,'review'=>'Expected more toppings for the price.','date'=>'2026-02-25','status'=>'pending','helpful'=>3],
        ['id'=>7,'customer'=>'Meera Nair','pizza'=>'Carbonara','rating'=>5,'review'=>'Carbonara pizza is absolutely divine!','date'=>'2026-02-24','status'=>'published','helpful'=>29],
        ['id'=>8,'customer'=>'Nisha Verma','pizza'=>'Margherita Classic','rating'=>5,'review'=>'Ordered for the entire office. Everyone loved it!','date'=>'2026-02-22','status'=>'published','helpful'=>41],
    ];
}