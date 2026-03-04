<?php if(!empty($orderDetail)): ?>
<div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;flex-wrap:wrap">
  <a href="index.php?page=orders" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
  <h2 style="font-size:15px;font-weight:800;color:var(--t1)">Order <span style="color:var(--brand)">#<?= $orderDetail['id'] ?></span></h2>
  <span class="badge badge-<?= $orderDetail['status'] ?>"><?= ucfirst(str_replace('_',' ',$orderDetail['status'])) ?></span>
  <span style="margin-left:auto;font-size:11.5px;color:var(--t3)"><i class="fas fa-clock"></i> <?= date('d M Y, H:i',strtotime($orderDetail['created_at'])) ?></span>
</div>
<div style="display:grid;grid-template-columns:1fr 340px;gap:16px" class="ord-det-grid">
  <div>
    <div class="card">
      <div class="card-hd"><div class="card-title"><i class="fas fa-list"></i> Order Items</div><span style="font-size:12px;color:var(--t3)"><?= count($orderItems) ?> item(s)</span></div>
      <div class="tbl-wrap">
        <table>
          <thead><tr><th>Pizza</th><th>Size</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
          <tbody>
            <?php $sub=0; foreach($orderItems as $item): $lt=$item['price']*$item['quantity']; $sub+=$lt; ?>
            <tr>
              <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
              <td><span style="background:var(--bg-up);padding:2px 7px;border-radius:6px;font-size:11.5px;font-weight:600"><?= htmlspecialchars($item['size_name']) ?></span></td>
              <td style="font-weight:600">× <?= $item['quantity'] ?></td>
              <td style="color:var(--t2)">₹<?= number_format($item['price'],0) ?></td>
              <td><strong>₹<?= number_format($lt,0) ?></strong></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php $del=$sub>=499?0:49; $gst=$sub*.05; ?>
      <div style="padding:14px 18px;border-top:1px solid var(--bdr)">
        <div style="max-width:260px;margin-left:auto">
          <div style="display:flex;justify-content:space-between;font-size:12.5px;padding:4px 0;color:var(--t2)"><span>Subtotal</span><span>₹<?= number_format($sub,0) ?></span></div>
          <div style="display:flex;justify-content:space-between;font-size:12.5px;padding:4px 0;color:var(--t2)"><span>Delivery</span><span><?= $del?'₹'.$del:'<span style="color:var(--green)">FREE</span>' ?></span></div>
          <div style="display:flex;justify-content:space-between;font-size:12.5px;padding:4px 0;color:var(--t2)"><span>GST 5%</span><span>₹<?= number_format($gst,0) ?></span></div>
          <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:800;padding:10px 0 0;border-top:1px solid var(--bdr);margin-top:4px"><span>Total</span><span style="color:var(--brand)">₹<?= number_format($orderDetail['total_amount'],0) ?></span></div>
        </div>
      </div>
    </div>
    <!-- Timeline -->
    <div class="card" style="margin-bottom:0">
      <div class="card-hd"><div class="card-title"><i class="fas fa-route"></i> Order Timeline</div></div>
      <div class="card-bd">
        <?php
        $tl=['pending'=>['fa-clock','Order Placed','var(--amber)'],'confirmed'=>['fa-check-circle','Confirmed','var(--blue)'],'preparing'=>['fa-fire','Preparing','var(--purple)'],'out_for_delivery'=>['fa-motorcycle','Out for Delivery','var(--orange)'],'delivered'=>['fa-box-open','Delivered','var(--green)']];
        $keys=array_keys($tl); $curIdx=array_search($orderDetail['status'],$keys);
        foreach($tl as $k=>[$ic,$lb,$col]):
          $idx=array_search($k,$keys); $done=$idx<=$curIdx; $cur=$k===$orderDetail['status'];
        ?>
        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:2px">
          <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
            <div style="width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:<?= $done?$col:'var(--bg-up)' ?>;color:<?= $done?'#fff':'var(--t3)' ?>;font-size:12px;border:2px solid <?= $cur?$col:'transparent' ?>;box-shadow:<?= $cur?'0 0 0 3px '.$col.'33':'none' ?>"><i class="fas <?= $ic ?>"></i></div>
            <?php if($k!=='delivered'): ?><div style="width:2px;height:20px;background:<?= $done?$col:'var(--bg-up)' ?>;margin:2px 0;border-radius:1px"></div><?php endif; ?>
          </div>
          <div style="padding-top:5px"><div style="font-size:12.5px;font-weight:<?= $cur?700:500 ?>;color:<?= $done?'var(--t1)':'var(--t3)' ?>"><?= $lb ?></div><?php if($cur): ?><div style="font-size:10.5px;color:var(--t3)">Current</div><?php endif; ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div>
    <div class="card">
      <div class="card-hd"><div class="card-title"><i class="fas fa-user"></i> Customer</div></div>
      <div style="padding:14px 18px">
        <div class="inf-row"><i class="fas fa-user"></i><span class="inf-key">Name</span><span class="inf-val"><?= htmlspecialchars($orderDetail['customer_name']) ?></span></div>
        <div class="inf-row"><i class="fas fa-envelope"></i><span class="inf-key">Email</span><span class="inf-val" style="word-break:break-all;font-size:12px"><?= htmlspecialchars($orderDetail['customer_email']) ?></span></div>
        <div class="inf-row"><i class="fas fa-phone"></i><span class="inf-key">Phone</span><span class="inf-val"><?= htmlspecialchars($orderDetail['customer_phone']) ?></span></div>
        <div class="inf-row"><i class="fas fa-map-marker-alt"></i><span class="inf-key">Address</span><span class="inf-val"><?= htmlspecialchars($orderDetail['delivery_address']) ?></span></div>
      </div>
    </div>
    <div class="card" style="margin-bottom:0">
      <div class="card-hd"><div class="card-title"><i class="fas fa-edit"></i> Update Status</div></div>
      <div style="padding:16px 18px">
        <form method="POST" action="index.php?page=orders&action=update_status">
          <input type="hidden" name="order_id" value="<?= $orderDetail['id'] ?>">
          <?php foreach(['pending'=>'var(--amber)','confirmed'=>'var(--blue)','preparing'=>'var(--purple)','out_for_delivery'=>'var(--orange)','delivered'=>'var(--green)','cancelled'=>'var(--red)'] as $s=>$col): ?>
          <label style="display:flex;align-items:center;gap:9px;padding:9px 12px;border-radius:var(--r-sm);margin-bottom:5px;cursor:pointer;border:1.5px solid <?= $orderDetail['status']===$s?$col:'var(--bdr)' ?>;background:<?= $orderDetail['status']===$s?$col.'18':'var(--bg-up)' ?>;transition:all .15s">
            <input type="radio" name="status" value="<?= $s ?>"<?= $orderDetail['status']===$s?' checked':'' ?> style="accent-color:<?= $col ?>">
            <span style="width:9px;height:9px;border-radius:50%;background:<?= $col ?>;flex-shrink:0"></span>
            <span style="font-size:12.5px;font-weight:500;color:var(--t1)"><?= ucfirst(str_replace('_',' ',$s)) ?></span>
          </label>
          <?php endforeach; ?>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px"><i class="fas fa-save"></i> Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<style>@media(max-width:800px){.ord-det-grid{grid-template-columns:1fr!important}}</style>

<?php else: ?>
<?php $filter=$_GET['filter']??''; $filtered=$filter?array_filter($orders,fn($o)=>$o['status']===$filter):$orders; ?>
<div class="card" style="margin-bottom:0">
  <div class="card-hd">
    <div class="card-title"><i class="fas fa-shopping-bag"></i> All Orders <span style="color:var(--t3);font-weight:400;font-size:12.5px">(<?= count($filtered) ?>)</span></div>
    <div class="srch-wrap"><i class="fas fa-search"></i><input type="text" class="srch-inp" id="tableSearch" placeholder="Search orders…"></div>
  </div>
  <div style="padding:10px 18px;border-bottom:1px solid var(--bdr);display:flex;gap:5px;flex-wrap:wrap">
    <?php
    $fs=[''=>'All ('.count($orders).')','pending'=>'Pending','confirmed'=>'Confirmed','preparing'=>'Preparing','out_for_delivery'=>'On the Way','delivered'=>'Delivered','cancelled'=>'Cancelled'];
    foreach($fs as $v=>$l): $cnt=$v?count(array_filter($orders,fn($o)=>$o['status']===$v)):count($orders); ?>
    <a href="index.php?page=orders<?= $v?'&filter='.$v:'' ?>" class="pill <?= $filter===$v?'active':'' ?>"><?= $l ?><?= $v?' ('.$cnt.')':'' ?></a>
    <?php endforeach; ?>
  </div>
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>Customer</th><th>Phone</th><th>Address</th><th>Amount</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php if(!empty($filtered)): foreach($filtered as $o): ?>
        <tr class="s-row">
          <td><strong style="color:var(--brand);font-size:12px">#<?= $o['id'] ?></strong></td>
          <td><div style="font-weight:600;font-size:13px"><?= htmlspecialchars($o['customer_name']) ?></div><div style="font-size:10.5px;color:var(--t3)"><?= htmlspecialchars($o['customer_email']??'') ?></div></td>
          <td style="color:var(--t2);font-size:12.5px"><?= htmlspecialchars($o['customer_phone']) ?></td>
          <td><div style="font-size:11.5px;color:var(--t2);max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($o['delivery_address']) ?></div></td>
          <td><strong>₹<?= number_format($o['total_amount'],0) ?></strong></td>
          <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst(str_replace('_',' ',$o['status'])) ?></span></td>
          <td style="font-size:11px;color:var(--t3);white-space:nowrap"><?= date('d M Y',strtotime($o['created_at'])) ?></td>
          <td><a href="index.php?page=orders&id=<?= $o['id'] ?>" class="btn btn-ghost btn-xs"><i class="fas fa-eye"></i></a></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="8"><div class="empty-box"><i class="fas fa-shopping-bag"></i><h3>No orders found</h3></div></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>