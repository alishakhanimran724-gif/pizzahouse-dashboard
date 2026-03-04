<?php
// ── Safe defaults (in case variables not passed from index.php) ──
$totalOrders   = $totalOrders   ?? 0;
$totalProducts = $totalProducts ?? 0;
$totalRevenue  = $totalRevenue  ?? 0;
$pendingOrders = $pendingOrders ?? 0;
$recentRevenue = $recentRevenue ?? 0;
$recentOrders  = $recentOrders  ?? [];

$rev  = [12400,18600,14200,22800,19500,28400,31200,26800,34500,29100,38700,42300];
$mons = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$maxR = max($rev);
$curM = (int)date('n') - 1;
?>

<div class="stat-grid">
  <div class="stat-card sc-red">
    <div class="sc-top">
      <div class="sc-icon si-red"><i class="fas fa-shopping-bag"></i></div>
      <span class="sc-trend tr-up"><i class="fas fa-arrow-up"></i> 12%</span>
    </div>
    <div class="sc-val"><?= number_format($totalOrders) ?></div>
    <div class="sc-lbl">Total Orders</div>
    <div class="sc-sub"><?= $pendingOrders ?> pending</div>
  </div>

  <div class="stat-card sc-green">
    <div class="sc-top">
      <div class="sc-icon si-green"><i class="fas fa-rupee-sign"></i></div>
      <span class="sc-trend tr-up"><i class="fas fa-arrow-up"></i> 8%</span>
    </div>
    <div class="sc-val">₹<?= number_format($totalRevenue, 0) ?></div>
    <div class="sc-lbl">Total Revenue</div>
    <div class="sc-sub">₹<?= number_format($recentRevenue, 0) ?> this month</div>
  </div>

  <div class="stat-card sc-gold">
    <div class="sc-top">
      <div class="sc-icon si-gold"><i class="fas fa-clock"></i></div>
      <span class="sc-trend <?= $pendingOrders > 5 ? 'tr-down' : 'tr-neu' ?>"><?= $pendingOrders > 5 ? 'High' : 'Normal' ?></span>
    </div>
    <div class="sc-val"><?= $pendingOrders ?></div>
    <div class="sc-lbl">Pending Orders</div>
    <div class="sc-sub">Needs attention</div>
  </div>

  <div class="stat-card sc-blue">
    <div class="sc-top">
      <div class="sc-icon si-blue"><i class="fas fa-pizza-slice"></i></div>
      <span class="sc-trend tr-neu">Stable</span>
    </div>
    <div class="sc-val"><?= $totalProducts ?></div>
    <div class="sc-lbl">Menu Items</div>
    <div class="sc-sub">Active products</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:16px;margin-bottom:16px" class="dash-r1">

  <!-- Revenue Chart -->
  <div class="card" style="margin-bottom:0">
    <div class="card-hd">
      <div class="card-title"><i class="fas fa-chart-bar"></i> Monthly Revenue <?= date('Y') ?></div>
      <span style="font-size:12px;font-weight:700;color:var(--green)">+24% vs last year</span>
    </div>
    <div class="card-bd">
      <div style="display:flex;align-items:flex-end;gap:6px;height:150px;margin-bottom:8px">
        <?php foreach ($rev as $i => $v): $p = round(($v / $maxR) * 100); ?>
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;height:100%;justify-content:flex-end;cursor:pointer"
             title="<?= $mons[$i] ?>: ₹<?= number_format($v) ?>">
          <div style="width:100%;height:<?= $p ?>%;background:<?= $i === $curM ? 'var(--brand)' : 'var(--bg-hov)' ?>;border-radius:4px 4px 0 0;min-height:4px;transition:all .3s"
               onmouseover="this.style.background='var(--brand)'"
               onmouseout="this.style.background='<?= $i === $curM ? 'var(--brand)' : 'var(--bg-hov)' ?>'"></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="display:flex;gap:6px">
        <?php foreach ($mons as $i => $m): ?>
        <div style="flex:1;text-align:center;font-size:9.5px;color:<?= $i === $curM ? 'var(--brand)' : 'var(--t3)' ?>;font-weight:<?= $i === $curM ? 700 : 400 ?>"><?= $m ?></div>
        <?php endforeach; ?>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:16px;padding-top:14px;border-top:1px solid var(--bdr)">
        <div style="text-align:center">
          <div style="font-size:1.1rem;font-weight:800;color:var(--t1)">₹<?= number_format(array_sum($rev)) ?></div>
          <div style="font-size:10.5px;color:var(--t3);margin-top:2px">Annual Total</div>
        </div>
        <div style="text-align:center;border-left:1px solid var(--bdr);border-right:1px solid var(--bdr)">
          <div style="font-size:1.1rem;font-weight:800;color:var(--t1)">₹<?= number_format(array_sum($rev) / 12) ?></div>
          <div style="font-size:10.5px;color:var(--t3);margin-top:2px">Monthly Avg</div>
        </div>
        <div style="text-align:center">
          <div style="font-size:1.1rem;font-weight:800;color:var(--green)">↑ 24%</div>
          <div style="font-size:10.5px;color:var(--t3);margin-top:2px">Growth</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right panel -->
  <div style="display:flex;flex-direction:column;gap:14px">
    <div class="card" style="margin-bottom:0;flex:1">
      <div class="card-hd"><div class="card-title"><i class="fas fa-circle-half-stroke"></i> Order Status</div></div>
      <div class="card-bd">
        <?php
        $statuses = [
          ['Pending',   $pendingOrders,                  20, 'var(--amber)'],
          ['Preparing', round($totalOrders * .15),        15, 'var(--purple)'],
          ['Delivered', round($totalOrders * .60),        60, 'var(--green)'],
          ['Cancelled', round($totalOrders * .05),         5, 'var(--red)'],
        ];
        foreach ($statuses as [$l, $c, $p, $col]):
        ?>
        <div style="margin-bottom:11px">
          <div style="display:flex;justify-content:space-between;font-size:11.5px;margin-bottom:4px">
            <span style="color:var(--t2);font-weight:500"><?= $l ?></span>
            <span style="color:var(--t1);font-weight:700"><?= $c ?></span>
          </div>
          <div class="prog"><div class="prog-fill" style="width:<?= $p ?>%;background:<?= $col ?>"></div></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="card" style="margin-bottom:0">
      <div class="card-hd"><div class="card-title"><i class="fas fa-bolt"></i> Quick Actions</div></div>
      <div class="card-bd" style="display:flex;flex-direction:column;gap:7px">
        <a href="index.php?page=products&add=1" class="btn btn-primary" style="justify-content:center"><i class="fas fa-plus"></i> Add New Pizza</a>
        <a href="index.php?page=orders"         class="btn btn-ghost"   style="justify-content:center"><i class="fas fa-shopping-bag"></i> View All Orders</a>
        <a href="../index.php" target="_blank"   class="btn btn-ghost"   style="justify-content:center"><i class="fas fa-globe"></i> Open Website</a>
      </div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:16px" class="dash-r2">

  <!-- Recent Orders Table -->
  <div class="card" style="margin-bottom:0">
    <div class="card-hd">
      <div class="card-title"><i class="fas fa-shopping-bag"></i> Recent Orders</div>
      <a href="index.php?page=orders" class="btn btn-ghost btn-sm">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr><th>#ID</th><th>Customer</th><th>Amount</th><th>Status</th><th>Time</th><th></th></tr>
        </thead>
        <tbody>
          <?php if (!empty($recentOrders)): foreach ($recentOrders as $o): ?>
          <tr class="s-row">
            <td><span style="font-weight:800;color:var(--brand);font-size:12px">#<?= $o['id'] ?></span></td>
            <td>
              <div style="font-weight:600"><?= htmlspecialchars($o['customer_name']) ?></div>
              <div style="font-size:11px;color:var(--t3)"><?= htmlspecialchars($o['customer_phone']) ?></div>
            </td>
            <td><strong>₹<?= number_format($o['total_amount'], 0) ?></strong></td>
            <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst(str_replace('_', ' ', $o['status'])) ?></span></td>
            <td style="font-size:11px;color:var(--t3)"><?= date('d M, H:i', strtotime($o['created_at'])) ?></td>
            <td><a href="index.php?page=orders&id=<?= $o['id'] ?>" class="btn btn-ghost btn-xs"><i class="fas fa-eye"></i></a></td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="6">
            <div class="empty-box"><i class="fas fa-shopping-bag"></i><h3>No orders yet</h3><p>Orders will appear here once customers start ordering.</p></div>
          </td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Top Selling -->
  <div class="card" style="margin-bottom:0">
    <div class="card-hd"><div class="card-title"><i class="fas fa-fire"></i> Top Selling</div></div>
    <div style="padding:6px 0">
      <?php
      $topItems = [
        ['Pepperoni Supreme', 142, 88],
        ['BBQ Chicken',       118, 73],
        ['Margherita Classic', 97, 60],
        ['Meat Lovers',        84, 52],
        ['Four Cheese',        61, 38],
      ];
      foreach ($topItems as $i => [$n, $orders, $pct]):
      ?>
      <div style="padding:10px 16px;border-bottom:1px solid var(--bdr)">
        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
          <div style="display:flex;gap:7px;align-items:center">
            <span style="font-size:10px;font-weight:800;color:var(--t3);width:14px"><?= $i + 1 ?></span>
            <span style="font-size:12.5px;font-weight:600"><?= $n ?></span>
          </div>
          <span style="font-size:11px;color:var(--t3)"><?= $orders ?> orders</span>
        </div>
        <div class="prog"><div class="prog-fill" style="width:<?= $pct ?>%;background:var(--brand)"></div></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>
<style>@media(max-width:1000px){.dash-r1,.dash-r2{grid-template-columns:1fr!important}}</style>