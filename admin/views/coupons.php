<?php
// $coupons already set by index.php
$showForm = isset($_GET['add']) || isset($_GET['edit']);
$editId   = (int)($_GET['edit'] ?? 0);
$editC    = $editId
    ? (array_values(array_filter($coupons, fn($c) => $c['id'] === $editId))[0] ?? null)
    : null;
?>

<?php if ($showForm): ?>
<!-- ══ CREATE / EDIT FORM ══════════════════════════════════ -->
<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
  <a href="index.php?page=coupons" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
  <h2 style="font-size:15px;font-weight:800;color:var(--t1)"><?= $editC ? 'Edit: '.$editC['code'] : 'Create Coupon' ?></h2>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:16px" class="coup-grid">
  <div class="card" style="margin-bottom:0">
    <div class="card-hd"><div class="card-title"><i class="fas fa-ticket"></i> Coupon Details</div></div>
    <div class="card-bd">
      <form method="POST" action="index.php?page=coupons&action=save_coupon">
        <?php if ($editC): ?><input type="hidden" name="coupon_id" value="<?= $editC['id'] ?>"><?php endif; ?>
        <div class="form-grid" style="margin-bottom:16px">
          <div class="form-grp">
            <label class="form-lbl">Coupon Code *</label>
            <input type="text" name="code" class="form-ctrl" required
                   value="<?= htmlspecialchars($editC['code'] ?? '') ?>"
                   placeholder="e.g. PIZZA50"
                   style="text-transform:uppercase;font-weight:700;letter-spacing:2px"
                   oninput="this.value=this.value.toUpperCase();updatePrev()">
          </div>
          <div class="form-grp">
            <label class="form-lbl">Status</label>
            <select name="status" class="form-ctrl">
              <?php foreach (['active','draft','expired'] as $s): ?>
              <option value="<?= $s ?>"<?= ($editC['status'] ?? 'draft') === $s ? ' selected' : '' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-grp">
            <label class="form-lbl">Discount Type *</label>
            <select name="type" class="form-ctrl" id="discType" onchange="updatePrev()">
              <?php foreach (['percent' => 'Percentage (%)', 'flat' => 'Flat Amount (₹)'] as $v => $l): ?>
              <option value="<?= $v ?>"<?= ($editC['type'] ?? '') === $v ? ' selected' : '' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-grp">
            <label class="form-lbl">Value *</label>
            <input type="number" name="value" class="form-ctrl" required min="1"
                   value="<?= $editC['value'] ?? '' ?>" placeholder="e.g. 50" oninput="updatePrev()">
          </div>
          <div class="form-grp">
            <label class="form-lbl">Min Order (₹)</label>
            <input type="number" name="min_order" class="form-ctrl" min="0" value="<?= $editC['min_order'] ?? 0 ?>">
          </div>
          <div class="form-grp">
            <label class="form-lbl">Max Uses</label>
            <input type="number" name="max_uses" class="form-ctrl" min="1"
                   value="<?= $editC['max_uses'] ?? '' ?>" placeholder="e.g. 200">
          </div>
          <div class="form-grp">
            <label class="form-lbl">Expiry Date *</label>
            <input type="date" name="expires" class="form-ctrl" required
                   value="<?= $editC['expires'] ?? '' ?>" oninput="updatePrev()">
          </div>
          <div class="form-grp form-full">
            <label class="form-lbl">Description</label>
            <input type="text" name="desc" class="form-ctrl"
                   value="<?= htmlspecialchars($editC['desc'] ?? '') ?>" placeholder="Internal note…">
          </div>
        </div>
        <div style="display:flex;gap:9px">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $editC ? 'Save Changes' : 'Create Coupon' ?></button>
          <a href="index.php?page=coupons" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Live Preview -->
  <div>
    <div class="card" style="position:sticky;top:72px;margin-bottom:0">
      <div class="card-hd"><div class="card-title"><i class="fas fa-eye"></i> Preview</div></div>
      <div class="card-bd">
        <div style="background:linear-gradient(135deg,var(--brand),#D4990A);border-radius:var(--r-lg);padding:22px;text-align:center;position:relative;overflow:hidden;margin-bottom:14px">
          <div style="position:absolute;top:-15px;right:-15px;width:55px;height:55px;background:rgba(0,0,0,.08);border-radius:50%"></div>
          <div style="font-size:9.5px;letter-spacing:2px;font-weight:600;color:rgba(26,20,0,.6);text-transform:uppercase;margin-bottom:6px">Pizza House</div>
          <div id="pvCode" style="font-size:1.5rem;font-weight:800;letter-spacing:3px;color:var(--brand-text);margin-bottom:4px">CODE</div>
          <div id="pvDisc" style="font-size:13px;color:rgba(26,20,0,.75);margin-bottom:14px">Discount</div>
          <div id="pvExp" style="border-top:1px dashed rgba(26,20,0,.25);padding-top:12px;font-size:10.5px;color:rgba(26,20,0,.5)">Valid till —</div>
        </div>
        <div style="font-size:11.5px;color:var(--t3);text-align:center"><i class="fas fa-info-circle" style="margin-right:4px"></i>Preview updates as you type</div>
      </div>
    </div>
  </div>
</div>
<style>.coup-grid{grid-template-columns:1fr 280px}@media(max-width:900px){.coup-grid{grid-template-columns:1fr!important}}</style>
<script>
function updatePrev(){
  var c=document.querySelector('[name="code"]').value,
      v=document.querySelector('[name="value"]').value,
      t=document.getElementById('discType').value,
      e=document.querySelector('[name="expires"]').value;
  document.getElementById('pvCode').textContent=c||'CODE';
  document.getElementById('pvDisc').textContent=v?(t==='percent'?v+'% OFF':'₹'+v+' OFF'):'Discount';
  document.getElementById('pvExp').textContent=e?'Valid till '+e:'Valid till —';
}
updatePrev();
</script>

<?php else: ?>
<!-- ══ COUPON LIST ══════════════════════════════════════════ -->
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:18px">
  <div class="stat-card sc-green">
    <div class="sc-top"><div class="sc-icon si-green"><i class="fas fa-ticket"></i></div></div>
    <div class="sc-val"><?= count(array_filter($coupons, fn($c) => $c['status']==='active')) ?></div>
    <div class="sc-lbl">Active Coupons</div>
  </div>
  <div class="stat-card sc-blue">
    <div class="sc-top"><div class="sc-icon si-blue"><i class="fas fa-check-double"></i></div></div>
    <div class="sc-val"><?= array_sum(array_column($coupons,'uses')) ?></div>
    <div class="sc-lbl">Total Uses</div>
  </div>
  <div class="stat-card sc-gold">
    <div class="sc-top"><div class="sc-icon si-gold"><i class="fas fa-percentage"></i></div></div>
    <div class="sc-val"><?= count($coupons) ?></div>
    <div class="sc-lbl">All Coupons</div>
  </div>
</div>

<div class="card" style="margin-bottom:0">
  <div class="card-hd">
    <div class="card-title"><i class="fas fa-ticket"></i> Promo Codes</div>
    <a href="index.php?page=coupons&add=1" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create</a>
  </div>

  <!-- Filter pills -->
  <div style="padding:10px 18px;border-bottom:1px solid var(--bdr);display:flex;gap:5px;flex-wrap:wrap">
    <?php
    $cf = $_GET['cf'] ?? '';
    foreach ([''=>'All ('.count($coupons).')','active'=>'Active','draft'=>'Draft','expired'=>'Expired'] as $v => $l):
    ?>
    <a href="index.php?page=coupons<?= $v ? '&cf='.$v : '' ?>" class="pill <?= $cf === $v ? 'active' : '' ?>"><?= $l ?></a>
    <?php endforeach; ?>
  </div>

  <!-- Cards grid -->
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:14px;padding:18px">
    <?php
    $fc = $cf ? array_filter($coupons, fn($c) => $c['status'] === $cf) : $coupons;
    foreach ($fc as $coup):
      $up       = $coup['max_uses'] > 0 ? round($coup['uses']/$coup['max_uses']*100) : 0;
      $sc       = ['active'=>['var(--green-s)','var(--green)'],'draft'=>['var(--bg-hov)','var(--t3)'],'expired'=>['var(--red-s)','var(--red)']][$coup['status']];
      $expiring = $coup['status']==='active' && strtotime($coup['expires']) < strtotime('+7 days');
    ?>
    <div style="background:var(--bg-up);border:1px solid var(--bdr);border-radius:var(--r-lg);overflow:hidden;transition:border-color var(--tr)"
         onmouseover="this.style.borderColor='var(--bdr-md)'" onmouseout="this.style.borderColor='var(--bdr)'">
      <!-- Card header -->
      <div style="background:<?= $coup['status']==='active' ? 'linear-gradient(135deg,var(--brand),#D4990A)' : 'var(--bg-card)' ?>;padding:18px;position:relative;overflow:hidden">
        <div style="position:absolute;top:-12px;right:-12px;width:50px;height:50px;background:rgba(0,0,0,.06);border-radius:50%"></div>
        <div style="font-size:9px;letter-spacing:2px;font-weight:600;color:<?= $coup['status']==='active'?'rgba(26,20,0,.55)':'var(--t3)' ?>;text-transform:uppercase;margin-bottom:5px">Code</div>
        <div style="font-size:1.2rem;font-weight:800;letter-spacing:2.5px;color:<?= $coup['status']==='active'?'var(--brand-text)':'var(--t2)' ?>"><?= htmlspecialchars($coup['code']) ?></div>
        <div style="font-size:12px;color:<?= $coup['status']==='active'?'rgba(26,20,0,.7)':'var(--t3)' ?>;margin-top:3px">
          <?= $coup['type']==='percent' ? $coup['value'].'% OFF' : '₹'.$coup['value'].' OFF' ?>
        </div>
      </div>
      <!-- Card body -->
      <div style="padding:14px">
        <div style="font-size:11.5px;color:var(--t3);margin-bottom:10px"><?= htmlspecialchars($coup['desc']) ?></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;font-size:11px">
          <div>
            <div style="color:var(--t3);margin-bottom:1px">Min Order</div>
            <div style="font-weight:700;color:var(--t1)"><?= $coup['min_order']>0 ? '₹'.$coup['min_order'] : 'No min' ?></div>
          </div>
          <div>
            <div style="color:var(--t3);margin-bottom:1px">Expires</div>
            <div style="font-weight:700;color:<?= $expiring ? 'var(--red)' : 'var(--t1)' ?>">
              <?= date('d M Y', strtotime($coup['expires'])) ?><?= $expiring ? ' ⚠️' : '' ?>
            </div>
          </div>
        </div>
        <!-- Usage bar -->
        <div style="margin-bottom:10px">
          <div style="display:flex;justify-content:space-between;font-size:10.5px;color:var(--t3);margin-bottom:4px">
            <span>Usage</span><span><?= $coup['uses'] ?>/<?= $coup['max_uses'] ?></span>
          </div>
          <div class="prog"><div class="prog-fill" style="width:<?= $up ?>%;background:<?= $up>=90?'var(--red)':($up>=70?'var(--gold)':'var(--green)') ?>"></div></div>
        </div>
        <!-- Footer -->
        <div style="display:flex;align-items:center;justify-content:space-between">
          <span style="padding:3px 9px;border-radius:99px;font-size:11px;font-weight:700;background:<?= $sc[0] ?>;color:<?= $sc[1] ?>"><?= ucfirst($coup['status']) ?></span>
          <div style="display:flex;gap:5px">
            <a href="index.php?page=coupons&edit=<?= $coup['id'] ?>" class="btn btn-ghost btn-xs"><i class="fas fa-edit"></i> Edit</a>
            <form method="POST" action="index.php?page=coupons&action=delete_coupon"
                  data-confirm="Delete coupon <?= htmlspecialchars($coup['code']) ?>?" style="display:inline">
              <input type="hidden" name="coupon_id" value="<?= $coup['id'] ?>">
              <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($fc)): ?>
    <div style="grid-column:1/-1"><div class="empty-box"><i class="fas fa-ticket"></i><h3>No coupons found</h3></div></div>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>