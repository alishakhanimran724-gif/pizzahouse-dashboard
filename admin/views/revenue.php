<?php
$mons=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$rev=[12400,18600,14200,22800,19500,28400,31200,26800,34500,29100,38700,42300];
$prev=[9800,14200,11000,18400,15600,22000,24800,20900,27100,22800,30200,33600];
$ords=[31,47,36,57,49,71,78,67,86,73,97,106];
$maxR=max($rev);$maxO=max($ords);$curM=(int)date('n')-1;
$totalRev=array_sum($rev);$totalOrd=array_sum($ords);
if(isset($_GET['export'])&&$_GET['export']==='csv'){header('Content-Type: text/csv');header('Content-Disposition: attachment; filename="revenue_'.date('Ymd').'.csv"');$o=fopen('php://output','w');fputcsv($o,['Month','Revenue','Orders','Avg']);foreach($mons as $i=>$m)fputcsv($o,[$m,$rev[$i],$ords[$i],round($rev[$i]/$ords[$i])]);fclose($o);exit;}
$days=['Mon'=>3200,'Tue'=>2800,'Wed'=>3600,'Thu'=>4200,'Fri'=>6800,'Sat'=>8400,'Sun'=>7100];$maxD=max($days);
$cats=[['Classic',98400,31,'var(--brand)'],['Specialty',74200,24,'var(--gold)'],['Vegetarian',58600,19,'var(--green)'],['Fusion',46800,15,'var(--blue)'],['Others',34300,11,'var(--purple)']];
?>
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px">
  <div><div style="font-size:18px;font-weight:800;color:var(--t1);letter-spacing:-.3px">Revenue & Analytics</div><div style="font-size:12px;color:var(--t3);margin-top:2px">Full year overview — <?= date('Y') ?></div></div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <a href="index.php?page=revenue&export=csv" class="btn btn-ghost btn-sm"><i class="fas fa-download"></i> Export CSV</a>
    <span style="display:flex;align-items:center;gap:5px;background:var(--green-s);border:1px solid rgba(34,197,94,.2);border-radius:var(--r-sm);padding:5px 12px;font-size:12px;font-weight:700;color:var(--green)"><i class="fas fa-arrow-up"></i> 24% vs last year</span>
  </div>
</div>

<div class="stat-grid" style="margin-bottom:18px">
  <div class="stat-card sc-red"><div class="sc-top"><div class="sc-icon si-red"><i class="fas fa-rupee-sign"></i></div><span class="sc-trend tr-up"><i class="fas fa-arrow-up"></i>24%</span></div><div class="sc-val">₹<?= number_format($totalRev) ?></div><div class="sc-lbl">Annual Revenue</div></div>
  <div class="stat-card sc-green"><div class="sc-top"><div class="sc-icon si-green"><i class="fas fa-shopping-bag"></i></div><span class="sc-trend tr-up"><i class="fas fa-arrow-up"></i>18%</span></div><div class="sc-val"><?= number_format($totalOrd) ?></div><div class="sc-lbl">Total Orders</div></div>
  <div class="stat-card sc-gold"><div class="sc-top"><div class="sc-icon si-gold"><i class="fas fa-receipt"></i></div><span class="sc-trend tr-up"><i class="fas fa-arrow-up"></i>5%</span></div><div class="sc-val">₹<?= number_format($totalRev/$totalOrd) ?></div><div class="sc-lbl">Avg Order Value</div></div>
  <div class="stat-card sc-blue"><div class="sc-top"><div class="sc-icon si-blue"><i class="fas fa-calendar-day"></i></div></div><div class="sc-val">₹8,400</div><div class="sc-lbl">Best Day (Sat)</div></div>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:16px;margin-bottom:16px" class="rev-r1">
  <div class="card" style="margin-bottom:0">
    <div class="card-hd">
      <div class="card-title"><i class="fas fa-chart-bar"></i> Monthly Revenue vs Orders</div>
      <div style="display:flex;gap:12px;font-size:11.5px;font-weight:600">
        <span style="display:flex;align-items:center;gap:4px;color:var(--brand)"><span style="width:10px;height:3px;background:var(--brand);border-radius:2px;display:inline-block"></span>Revenue</span>
        <span style="display:flex;align-items:center;gap:4px;color:var(--blue)"><span style="width:10px;height:3px;background:var(--blue);border-radius:2px;display:inline-block"></span>Orders</span>
        <span style="display:flex;align-items:center;gap:4px;color:var(--t3)"><span style="width:10px;height:3px;background:var(--bg-hov);border-radius:2px;display:inline-block"></span>Prev Year</span>
      </div>
    </div>
    <div class="card-bd">
      <div style="display:flex;align-items:flex-end;gap:5px;height:160px;margin-bottom:8px">
        <?php foreach($rev as $i=>$v): $rp=round(($v/$maxR)*100);$op=round(($ords[$i]/$maxO)*100);$pp=round(($prev[$i]/$maxR)*100); ?>
        <div style="flex:1;height:100%;display:flex;align-items:flex-end" title="<?= $mons[$i] ?>: ₹<?= number_format($v) ?> | <?= $ords[$i] ?> orders">
          <div style="width:100%;display:flex;gap:1px;align-items:flex-end;height:100%">
            <div style="flex:1;height:<?= $pp ?>%;background:var(--bg-hov);border-radius:3px 3px 0 0;min-height:3px"></div>
            <div style="flex:1;height:<?= $rp ?>%;background:<?= $i===$curM?'var(--brand)':'rgba(232,68,42,.45)' ?>;border-radius:3px 3px 0 0;min-height:3px;transition:all .3s" onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'"></div>
            <div style="flex:1;height:<?= $op ?>%;background:<?= $i===$curM?'var(--blue)':'rgba(59,130,246,.4)' ?>;border-radius:3px 3px 0 0;min-height:3px;transition:all .3s" onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="display:flex;gap:5px;margin-bottom:16px">
        <?php foreach($mons as $i=>$m): ?><div style="flex:1;text-align:center;font-size:9.5px;color:<?= $i===$curM?'var(--brand)':'var(--t3)' ?>;font-weight:<?= $i===$curM?700:400 ?>"><?= $m ?></div><?php endforeach; ?>
      </div>
      <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:0;border:1px solid var(--bdr);border-radius:var(--r-sm);overflow:hidden">
        <?php foreach($mons as $i=>$m): ?>
        <div style="padding:8px 6px;text-align:center;background:<?= $i===$curM?'var(--brand-soft)':'var(--bg-up)' ?>;border-right:<?= $i<11?'1px solid var(--bdr)':'none' ?>;<?= $i===6?'border-top:1px solid var(--bdr)':'' ?>">
          <div style="font-size:9.5px;color:var(--t3);margin-bottom:3px"><?= $m ?></div>
          <div style="font-size:11.5px;font-weight:700;color:var(--t1)">₹<?= number_format($rev[$i]/1000,1) ?>k</div>
          <div style="font-size:9.5px;color:var(--t3)"><?= $ords[$i] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="card" style="margin-bottom:0">
    <div class="card-hd"><div class="card-title"><i class="fas fa-chart-pie"></i> By Category</div></div>
    <div class="card-bd">
      <svg viewBox="0 0 36 36" style="width:120px;height:120px;display:block;margin:0 auto 16px;transform:rotate(-90deg)">
        <?php $off=0;foreach($cats as [$l,$r,$p,$col]): ?>
        <circle cx="18" cy="18" r="15.9" fill="none" stroke="<?= $col ?>" stroke-width="3.8" stroke-dasharray="<?= $p ?> <?= 100-$p ?>" stroke-dashoffset="<?= -$off ?>" opacity=".9"/>
        <?php $off+=$p;endforeach; ?>
        <circle cx="18" cy="18" r="12" fill="var(--bg-card)"/>
      </svg>
      <?php foreach($cats as [$l,$r,$p,$col]): ?>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:9px">
        <div style="width:9px;height:9px;border-radius:50%;background:<?= $col ?>;flex-shrink:0"></div>
        <div style="flex:1"><div style="display:flex;justify-content:space-between;font-size:11.5px;margin-bottom:3px"><span style="color:var(--t2);font-weight:500"><?= $l ?></span><span style="font-weight:700;color:var(--t1)"><?= $p ?>%</span></div><div class="prog"><div class="prog-fill" style="width:<?= $p ?>%;background:<?= $col ?>"></div></div></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px" class="rev-r2">
  <div class="card" style="margin-bottom:0">
    <div class="card-hd"><div class="card-title"><i class="fas fa-calendar-week"></i> Revenue by Day</div></div>
    <div class="card-bd">
      <div style="display:flex;align-items:flex-end;gap:6px;height:110px;margin-bottom:10px">
        <?php foreach($days as $d=>$v): $p=round(($v/$maxD)*100);$wk=in_array($d,['Fri','Sat','Sun']); ?>
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;cursor:pointer" title="<?= $d ?>: ₹<?= number_format($v) ?>">
          <div style="font-size:9.5px;color:var(--t3);font-weight:600">₹<?= number_format($v/1000,1) ?>k</div>
          <div style="width:100%;height:<?= $p ?>%;background:<?= $wk?'var(--gold)':'rgba(232,68,42,.5)' ?>;border-radius:4px 4px 0 0;min-height:4px;opacity:<?= $wk?1:.8 ?>;transition:all .3s" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=<?= $wk?1:.8 ?>"></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="display:flex;gap:6px">
        <?php foreach(array_keys($days) as $d): ?><div style="flex:1;text-align:center;font-size:10.5px;color:<?= in_array($d,['Fri','Sat','Sun'])?'var(--gold)':'var(--t3)' ?>;font-weight:600"><?= $d ?></div><?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="card" style="margin-bottom:0">
    <div class="card-hd"><div class="card-title"><i class="fas fa-table"></i> Year-over-Year</div></div>
    <div class="tbl-wrap"><table>
      <thead><tr><th>Month</th><th><?= date('Y') ?></th><th><?= date('Y')-1 ?></th><th>Growth</th></tr></thead>
      <tbody>
        <?php foreach($mons as $i=>$m): $g=round((($rev[$i]-$prev[$i])/$prev[$i])*100,1); ?>
        <tr style="<?= $i===$curM?'background:var(--brand-soft)':'' ?>">
          <td style="font-weight:<?= $i===$curM?700:400 ?>;color:<?= $i===$curM?'var(--brand)':'var(--t2)' ?>"><?= $m ?></td>
          <td><strong>₹<?= number_format($rev[$i]) ?></strong></td>
          <td style="color:var(--t3)">₹<?= number_format($prev[$i]) ?></td>
          <td><span style="font-size:11.5px;font-weight:700;color:<?= $g>=0?'var(--green)':'var(--red)' ?>"><?= $g>=0?'↑':'↓' ?><?= abs($g) ?>%</span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
  </div>
</div>
<style>@media(max-width:1000px){.rev-r1{grid-template-columns:1fr!important}}@media(max-width:700px){.rev-r2{grid-template-columns:1fr!important}}</style>