<?php
if(empty($customers)){
  $customers=[
    ['id'=>1,'name'=>'Rahul Kumar',  'email'=>'rahul@gmail.com', 'phone'=>'+91 98765 43210','orders'=>14,'total_spent'=>8420, 'last_order'=>'2026-02-28','joined'=>'2025-06-12','status'=>'active'],
    ['id'=>2,'name'=>'Priya Sharma', 'email'=>'priya@gmail.com', 'phone'=>'+91 91234 56789','orders'=>9, 'total_spent'=>5310, 'last_order'=>'2026-03-01','joined'=>'2025-08-03','status'=>'active'],
    ['id'=>3,'name'=>'Anil Mehta',   'email'=>'anil@gmail.com',  'phone'=>'+91 87654 32109','orders'=>22,'total_spent'=>14880,'last_order'=>'2026-03-03','joined'=>'2025-04-20','status'=>'active'],
    ['id'=>4,'name'=>'Sara Khan',    'email'=>'sara@gmail.com',  'phone'=>'+91 76543 21098','orders'=>3, 'total_spent'=>1740, 'last_order'=>'2026-01-15','joined'=>'2025-11-08','status'=>'inactive'],
    ['id'=>5,'name'=>'Dev Patel',    'email'=>'dev@gmail.com',   'phone'=>'+91 65432 10987','orders'=>18,'total_spent'=>11200,'last_order'=>'2026-03-02','joined'=>'2025-05-30','status'=>'active'],
    ['id'=>6,'name'=>'Nisha Verma',  'email'=>'nisha@gmail.com', 'phone'=>'+91 32109 87654','orders'=>31,'total_spent'=>21600,'last_order'=>'2026-03-04','joined'=>'2025-03-01','status'=>'active'],
  ];
}
usort($customers,fn($a,$b)=>$b['total_spent']-$a['total_spent']);
$total=count($customers);
$active=count(array_filter($customers,fn($c)=>$c['status']==='active'));
$inactive=$total-$active;
$rev=array_sum(array_column($customers,'total_spent'));
$totalQty=array_sum(array_column($customers,'orders'));
$avg=$totalQty>0?$rev/$totalQty:0;

// CSV export
if(isset($_GET['export'])&&$_GET['export']==='csv'){
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="customers_'.date('Ymd').'.csv"');
  $out=fopen('php://output','w');
  fputcsv($out,['ID','Name','Email','Phone','Orders','Spent','Last Order','Status']);
  foreach($customers as $c)fputcsv($out,[$c['id'],$c['name'],$c['email'],$c['phone'],$c['orders'],'Rs '.$c['total_spent'],$c['last_order'],$c['status']]);
  fclose($out);exit;
}
$detailId=(int)($_GET['id']??0);
$detail=null;
if($detailId){$found=array_values(array_filter($customers,fn($c)=>$c['id']===$detailId));$detail=$found[0]??null;}
?>

<?php if($detail): ?>
<!-- ══ CUSTOMER DETAIL ══════════════════════════════════════════ -->
<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap">
  <a href="index.php?page=customers" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
  <h2 style="font-family:var(--font);font-size:15px;font-weight:800;color:var(--t1)"><?= htmlspecialchars($detail['name']) ?></h2>
  <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;background:<?= $detail['status']==='active'?'var(--green-s)':'var(--red-s)' ?>;color:<?= $detail['status']==='active'?'var(--green)':'var(--red)' ?>"><?= ucfirst($detail['status']) ?></span>
</div>
<div style="display:grid;grid-template-columns:1fr 280px;gap:16px" class="cust-det-grid">
  <div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:14px">
      <?php
      $mini=[['Orders',$detail['orders'],'var(--brand)'],['Total Spent','₹'.number_format($detail['total_spent']),'var(--green)'],['Avg Order','₹'.number_format($detail['total_spent']/max(1,$detail['orders'])),'var(--blue)']];
      foreach($mini as[$lbl,$val,$col]):
      ?>
      <div class="card" style="margin-bottom:0;padding:16px">
        <div style="font-size:10.5px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px"><?= $lbl ?></div>
        <div style="font-family:var(--font);font-size:1.6rem;font-weight:800;color:<?= $col ?>"><?= $val ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="card" style="margin-bottom:0">
      <div class="card-hd"><div class="card-title"><i class="fas fa-history"></i> Order History</div></div>
      <div class="tbl-wrap">
        <table>
          <thead><tr><th>#ID</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
          <tbody>
            <?php
            $ss=['delivered','delivered','preparing','delivered','cancelled'];
            $sa=[649,899,1249,549,799];
            for($i=0;$i<min(5,$detail['orders']);$i++):
              $st=$ss[$i%5];
            ?>
            <tr>
              <td><strong style="color:var(--brand)">#<?= 1000+$i+$detail['id']*3 ?></strong></td>
              <td><strong>₹<?= number_format($sa[$i%5]) ?></strong></td>
              <td><span class="badge badge-<?= $st ?>"><?= ucfirst($st) ?></span></td>
              <td style="font-size:11px;color:var(--t3)"><?= date('d M Y',strtotime($detail['last_order'].' -'.$i.' days')) ?></td>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div>
    <div class="card" style="margin-bottom:0">
      <div style="padding:22px 18px;text-align:center;border-bottom:1px solid var(--bdr)">
        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--brand),#C42B18);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.4rem;color:#fff;margin:0 auto 10px"><?= strtoupper(substr($detail['name'],0,1)) ?></div>
        <div style="font-family:var(--font);font-size:15px;font-weight:700;color:var(--t1);margin-bottom:3px"><?= htmlspecialchars($detail['name']) ?></div>
        <div style="font-size:11.5px;color:var(--t3)">Since <?= date('M Y',strtotime($detail['joined'])) ?></div>
      </div>
      <div style="padding:14px 18px">
        <div class="inf-row"><i class="fas fa-envelope"></i><span class="inf-key">Email</span><span class="inf-val" style="font-size:11.5px;word-break:break-all"><?= htmlspecialchars($detail['email']) ?></span></div>
        <div class="inf-row"><i class="fas fa-phone"></i><span class="inf-key">Phone</span><span class="inf-val"><?= htmlspecialchars($detail['phone']) ?></span></div>
        <div class="inf-row"><i class="fas fa-calendar-check"></i><span class="inf-key">Last Order</span><span class="inf-val"><?= date('d M Y',strtotime($detail['last_order'])) ?></span></div>
      </div>
      <?php
      if($detail['total_spent']>=10000)     $tier=['🏆 VIP',   'var(--gold)'];
      elseif($detail['total_spent']>=5000)  $tier=['⭐ Regular','var(--blue)'];
      else                                  $tier=['🆕 New',    'var(--t3)'];
      ?>
      <div style="padding:12px 18px;border-top:1px solid var(--bdr);text-align:center">
        <span style="font-size:13px;font-weight:700;color:<?= $tier[1] ?>"><?= $tier[0] ?> Customer</span>
      </div>
    </div>
  </div>
</div>
<style>@media(max-width:800px){.cust-det-grid{grid-template-columns:1fr!important}}</style>

<?php else: ?>
<!-- ══ CUSTOMER LIST ══════════════════════════════════════════ -->
<div class="stat-grid" style="margin-bottom:18px">
  <div class="stat-card sc-blue">
    <div class="sc-top"><div class="sc-icon si-blue"><i class="fas fa-users"></i></div></div>
    <div class="sc-val"><?= $total ?></div><div class="sc-lbl">Total Customers</div>
  </div>
  <div class="stat-card sc-green">
    <div class="sc-top"><div class="sc-icon si-green"><i class="fas fa-user-check"></i></div></div>
    <div class="sc-val"><?= $active ?></div><div class="sc-lbl">Active</div>
  </div>
  <div class="stat-card sc-red">
    <div class="sc-top"><div class="sc-icon si-red"><i class="fas fa-rupee-sign"></i></div></div>
    <div class="sc-val">₹<?= number_format($rev) ?></div><div class="sc-lbl">Total Revenue</div>
  </div>
  <div class="stat-card sc-gold">
    <div class="sc-top"><div class="sc-icon si-gold"><i class="fas fa-receipt"></i></div></div>
    <div class="sc-val">₹<?= number_format($avg) ?></div><div class="sc-lbl">Avg Order Value</div>
  </div>
</div>

<!-- Add Customer Modal -->
<div class="modal-overlay" id="modalAddCustomer" onclick="if(event.target===this)closeModal('modalAddCustomer')">
  <div class="modal">
    <div class="modal-hd">
      <div class="modal-title"><i class="fas fa-user-plus" style="color:var(--brand);margin-right:6px"></i> Add New Customer</div>
      <button class="modal-close" onclick="closeModal('modalAddCustomer')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="index.php?page=customers&action=save_customer">
      <div class="modal-bd">
        <div class="form-grid">
          <div class="form-grp"><label class="form-lbl">Full Name *</label><input type="text" name="name" class="form-ctrl" required placeholder="e.g. Rahul Kumar"></div>
          <div class="form-grp"><label class="form-lbl">Email *</label><input type="email" name="email" class="form-ctrl" required placeholder="rahul@gmail.com"></div>
          <div class="form-grp"><label class="form-lbl">Phone</label><input type="text" name="phone" class="form-ctrl" placeholder="+91 98765 43210"></div>
          <div class="form-grp"><label class="form-lbl">Status</label>
            <select name="status" class="form-ctrl"><option value="active">Active</option><option value="inactive">Inactive</option></select>
          </div>
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn btn-ghost" onclick="closeModal('modalAddCustomer')">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Customer</button>
      </div>
    </form>
  </div>
</div>

<div class="card" style="margin-bottom:0">
  <div class="card-hd">
    <div class="card-title"><i class="fas fa-users"></i> All Customers <span style="color:var(--t3);font-weight:400;font-size:12.5px">(<?= $total ?>)</span></div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <div class="srch-wrap"><i class="fas fa-search"></i><input type="text" class="srch-inp" id="tableSearch" placeholder="Search…"></div>
      <a href="index.php?page=customers&export=csv" class="btn btn-ghost btn-sm"><i class="fas fa-download"></i> CSV</a>
      <button class="btn btn-primary btn-sm" onclick="openModal('modalAddCustomer')"><i class="fas fa-plus"></i> Add Customer</button>
    </div>
  </div>
  <div style="padding:10px 18px;border-bottom:1px solid var(--bdr);display:flex;gap:5px;flex-wrap:wrap">
    <?php
    $cf=$_GET['cf']??'';
    $filters=[''=> 'All ('.$total.')','active'=>'Active ('.$active.')','inactive'=>'Inactive ('.$inactive.')'];
    foreach($filters as $v=>$l):
    ?>
    <a href="index.php?page=customers<?= $v?'&cf='.$v:'' ?>" class="pill <?= $cf===$v?'active':'' ?>"><?= $l ?></a>
    <?php endforeach; ?>
  </div>
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>Customer</th><th>Contact</th><th>Orders</th><th>Total Spent</th><th>Last Order</th><th>Tier</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php
        $fc=$cf?array_filter($customers,fn($c)=>$c['status']===$cf):$customers;
        foreach($fc as $i=>$c):
          if($c['total_spent']>=10000)   $tier=['🏆','var(--gold)','VIP'];
          elseif($c['total_spent']>=5000)$tier=['⭐','var(--blue)','Regular'];
          else                           $tier=['🆕','var(--t3)','New'];
        ?>
        <tr class="s-row">
          <td style="color:var(--t3);font-size:11.5px;font-weight:600"><?= $i+1 ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:9px">
              <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--brand),#C42B18);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#fff;flex-shrink:0"><?= strtoupper(substr($c['name'],0,1)) ?></div>
              <div>
                <div style="font-weight:600;font-size:13px"><?= htmlspecialchars($c['name']) ?></div>
                <div style="font-size:10.5px;color:var(--t3)">Since <?= date('M Y',strtotime($c['joined'])) ?></div>
              </div>
            </div>
          </td>
          <td><div style="font-size:12px;color:var(--t2)"><?= htmlspecialchars($c['email']) ?></div><div style="font-size:11px;color:var(--t3)"><?= htmlspecialchars($c['phone']) ?></div></td>
          <td><strong style="font-size:1.1rem"><?= $c['orders'] ?></strong></td>
          <td><strong style="color:var(--green)">₹<?= number_format($c['total_spent']) ?></strong></td>
          <td style="font-size:11.5px;color:var(--t3)"><?= date('d M Y',strtotime($c['last_order'])) ?></td>
          <td><span style="font-size:12px;font-weight:700;color:<?= $tier[1] ?>"><?= $tier[0] ?> <?= $tier[2] ?></span></td>
          <td><span style="padding:2px 8px;border-radius:99px;font-size:11px;font-weight:700;background:<?= $c['status']==='active'?'var(--green-s)':'var(--red-s)' ?>;color:<?= $c['status']==='active'?'var(--green)':'var(--red)' ?>"><?= ucfirst($c['status']) ?></span></td>
          <td><a href="index.php?page=customers&id=<?= $c['id'] ?>" class="btn btn-ghost btn-xs"><i class="fas fa-eye"></i></a></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($fc)): ?>
        <tr><td colspan="9"><div class="empty-box"><i class="fas fa-users"></i><h3>No customers found</h3></div></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>