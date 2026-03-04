<?php
// $reviews already set by index.php
$total = count($reviews);
$pub   = count(array_filter($reviews, fn($r) => $r['status']==='published'));
$pend  = count(array_filter($reviews, fn($r) => $r['status']==='pending'));
$avg   = $total ? round(array_sum(array_column($reviews,'rating'))/$total, 1) : 0;
$five  = count(array_filter($reviews, fn($r) => $r['rating']===5));

$dist = [5=>0,4=>0,3=>0,2=>0,1=>0];
foreach ($reviews as $r) $dist[$r['rating']]++;

$rf       = $_GET['rf'] ?? '';
$filtered = $rf ? array_filter($reviews, function($r) use($rf) {
    if ($rf === 'pending')  return $r['status'] === 'pending';
    if ($rf === 'positive') return $r['rating'] >= 4;
    if ($rf === 'negative') return $r['rating'] <= 2;
    return true;
}) : $reviews;
?>

<!-- Stats -->
<div class="stat-grid" style="margin-bottom:18px">
  <div class="stat-card sc-gold">
    <div class="sc-top"><div class="sc-icon si-gold"><i class="fas fa-star"></i></div></div>
    <div class="sc-val"><?= $avg ?>/5</div>
    <div class="sc-lbl">Avg Rating</div>
  </div>
  <div class="stat-card sc-blue">
    <div class="sc-top"><div class="sc-icon si-blue"><i class="fas fa-comment"></i></div></div>
    <div class="sc-val"><?= $total ?></div>
    <div class="sc-lbl">Total Reviews</div>
    <div class="sc-sub"><?= $pub ?> published</div>
  </div>
  <div class="stat-card sc-green">
    <div class="sc-top"><div class="sc-icon si-green"><i class="fas fa-thumbs-up"></i></div></div>
    <div class="sc-val"><?= $five ?></div>
    <div class="sc-lbl">5-Star Reviews</div>
    <div class="sc-sub"><?= $total ? round($five/$total*100) : 0 ?>% of total</div>
  </div>
  <div class="stat-card sc-red">
    <div class="sc-top"><div class="sc-icon si-red"><i class="fas fa-clock"></i></div></div>
    <div class="sc-val"><?= $pend ?></div>
    <div class="sc-lbl">Pending</div>
    <div class="sc-sub">Needs moderation</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 260px;gap:16px" class="rev-main-grid">

  <!-- Reviews list -->
  <div class="card" style="margin-bottom:0">
    <div class="card-hd">
      <div class="card-title"><i class="fas fa-comments"></i> Reviews <span style="color:var(--t3);font-weight:400;font-size:12.5px">(<?= count($filtered) ?>)</span></div>
      <div style="display:flex;gap:5px;flex-wrap:wrap">
        <?php foreach ([''=>'All','pending'=>'Pending','positive'=>'Positive ★','negative'=>'Negative'] as $v=>$l): ?>
        <a href="index.php?page=reviews<?= $v ? '&rf='.$v : '' ?>" class="pill <?= $rf===$v ? 'active':'' ?>"><?= $l ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div style="padding:4px 0">
      <?php foreach ($filtered as $rev):
        $scol  = $rev['rating']>=4 ? 'var(--gold)' : ($rev['rating']===3 ? 'var(--orange)' : 'var(--red)');
        $stars = str_repeat('★',$rev['rating']).str_repeat('☆',5-$rev['rating']);
      ?>
      <div style="padding:16px 20px;border-bottom:1px solid var(--bdr)">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;flex-wrap:wrap">
          <!-- Author -->
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--brand),#E8901A);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;color:var(--brand-text);flex-shrink:0">
              <?= strtoupper(substr($rev['customer'],0,1)) ?>
            </div>
            <div>
              <div style="font-weight:700;font-size:13.5px"><?= htmlspecialchars($rev['customer']) ?></div>
              <div style="font-size:11px;color:var(--t3)"><i class="fas fa-pizza-slice" style="color:var(--brand);margin-right:3px"></i><?= htmlspecialchars($rev['pizza']) ?></div>
            </div>
          </div>
          <!-- Meta -->
          <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
            <span style="color:<?= $scol ?>;font-size:14px;letter-spacing:1px"><?= $stars ?></span>
            <span style="padding:2px 8px;border-radius:99px;font-size:10.5px;font-weight:700;background:<?= $rev['status']==='published' ? 'var(--green-s)' : 'var(--amber-s)' ?>;color:<?= $rev['status']==='published' ? 'var(--green)' : 'var(--amber)' ?>"><?= ucfirst($rev['status']) ?></span>
            <span style="font-size:10.5px;color:var(--t3)"><?= date('d M Y',strtotime($rev['date'])) ?></span>
          </div>
        </div>
        <!-- Body -->
        <div style="margin:10px 0 0 46px">
          <p style="font-size:13px;color:var(--t2);line-height:1.6">"<?= htmlspecialchars($rev['review']) ?>"</p>
          <div style="display:flex;align-items:center;gap:10px;margin-top:8px;flex-wrap:wrap">
            <span style="font-size:11px;color:var(--t3)"><i class="fas fa-thumbs-up" style="margin-right:3px"></i><?= $rev['helpful'] ?> helpful</span>
            <?php if ($rev['status']==='pending'): ?>
            <form method="POST" action="index.php?page=reviews&action=approve_review" style="display:inline">
              <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
              <button class="btn btn-success btn-xs"><i class="fas fa-check"></i> Approve</button>
            </form>
            <?php endif; ?>
            <form method="POST" action="index.php?page=reviews&action=delete_review"
                  data-confirm="Delete this review?" style="display:inline">
              <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
              <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($filtered)): ?>
      <div class="empty-box"><i class="fas fa-comments"></i><h3>No reviews found</h3></div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Sidebar: rating breakdown + most reviewed -->
  <div>
    <div class="card" style="margin-bottom:14px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-chart-bar"></i> Ratings</div></div>
      <div class="card-bd">
        <div style="text-align:center;margin-bottom:16px">
          <div style="font-size:2.8rem;font-weight:800;color:var(--gold);line-height:1"><?= $avg ?></div>
          <div style="font-size:18px;color:var(--gold);margin:3px 0">★★★★★</div>
          <div style="font-size:11px;color:var(--t3)"><?= $total ?> reviews</div>
        </div>
        <?php foreach ([5,4,3,2,1] as $s):
          $cnt = $dist[$s];
          $p   = $total > 0 ? round($cnt/$total*100) : 0;
          $c   = $s>=4 ? 'var(--gold)' : ($s===3 ? 'var(--orange)' : 'var(--red)');
        ?>
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:7px">
          <span style="font-size:11px;font-weight:600;color:var(--t2);width:7px"><?= $s ?></span>
          <i class="fas fa-star" style="color:<?= $c ?>;font-size:10px"></i>
          <div class="prog" style="flex:1"><div class="prog-fill" style="width:<?= $p ?>%;background:<?= $c ?>"></div></div>
          <span style="font-size:11px;font-weight:600;color:var(--t2);width:18px;text-align:right"><?= $cnt ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="card" style="margin-bottom:0">
      <div class="card-hd"><div class="card-title"><i class="fas fa-pizza-slice"></i> Most Reviewed</div></div>
      <div class="card-bd">
        <?php
        $pr = [];
        foreach ($reviews as $r) $pr[$r['pizza']] = ($pr[$r['pizza']] ?? 0) + 1;
        arsort($pr);
        $mx = max($pr ?: [1]);
        foreach ($pr as $piz => $cnt):
        ?>
        <div style="margin-bottom:10px">
          <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:3px">
            <span style="color:var(--t2);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px"><?= htmlspecialchars($piz) ?></span>
            <strong style="color:var(--t1)"><?= $cnt ?></strong>
          </div>
          <div class="prog"><div class="prog-fill" style="width:<?= round($cnt/$mx*100) ?>%;background:var(--brand)"></div></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<style>@media(max-width:900px){.rev-main-grid{grid-template-columns:1fr!important}}</style>