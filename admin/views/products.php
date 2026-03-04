<?php if(!empty($_GET['add'])||!empty($_GET['edit'])): ?>
<!-- ══ ADD / EDIT FORM ══════════════════════════════════════════ -->
<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
  <a href="index.php?page=products" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
  <h2 style="font-family:var(--font);font-size:15px;font-weight:800;color:var(--t1)"><?= empty($editProduct)?'Add New Pizza':'Edit: '.htmlspecialchars($editProduct['name']) ?></h2>
</div>
<div style="display:grid;grid-template-columns:1fr 300px;gap:16px" class="prod-edit-grid">
  <div class="card" style="margin-bottom:0">
    <div class="card-hd"><div class="card-title"><i class="fas fa-<?= empty($editProduct)?'plus':'edit' ?>"></i> Pizza Details</div></div>
    <div class="card-bd">
      <form method="POST" action="index.php?page=products&action=save_product" enctype="multipart/form-data">
        <?php if(!empty($editProduct)): ?><input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>"><?php endif; ?>
        <div class="form-grid" style="margin-bottom:18px">
          <div class="form-grp">
            <label class="form-lbl">Pizza Name *</label>
            <input type="text" name="name" class="form-ctrl" required value="<?= htmlspecialchars($editProduct['name']??'') ?>" placeholder="e.g. Margherita Classic" oninput="pvName(this.value)">
          </div>
          <div class="form-grp">
            <label class="form-lbl">Category *</label>
            <select name="category" class="form-ctrl" required>
              <?php foreach(['Classic','Specialty','Vegetarian','Fusion','Gourmet','Sides','Drinks'] as $c): ?>
              <option value="<?= $c ?>"<?= ($editProduct['category']??'')===$c?' selected':'' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-grp">
            <label class="form-lbl">Base Price (₹) *</label>
            <input type="number" name="price" class="form-ctrl" required min="1" value="<?= htmlspecialchars($editProduct['price']??'') ?>" placeholder="299" oninput="pvPrice(this.value)">
          </div>
          <div class="form-grp">
            <label class="form-lbl">Image URL</label>
            <input type="url" name="image" class="form-ctrl" id="imgUrl" value="<?= htmlspecialchars($editProduct['image']??'') ?>" placeholder="https://…" oninput="prevImg(this.value)">
          </div>
          <div class="form-grp form-full">
            <label class="form-lbl">Description *</label>
            <textarea name="description" class="form-ctrl" required placeholder="Describe the pizza…" oninput="pvDesc(this.value)"><?= htmlspecialchars($editProduct['description']??'') ?></textarea>
          </div>
        </div>
        <div style="display:flex;gap:22px;margin-bottom:22px;flex-wrap:wrap">
          <label class="tog-wrap">
            <div class="tog"><input type="checkbox" name="is_veg"<?= !empty($editProduct['is_veg'])?' checked':'' ?> onchange="document.getElementById('pvVeg').style.display=this.checked?'inline-block':'none'"><div class="tog-track"></div><div class="tog-thumb"></div></div>
            <span class="tog-lbl"><i class="fas fa-leaf" style="color:var(--green);margin-right:4px"></i>Vegetarian</span>
          </label>
          <label class="tog-wrap">
            <div class="tog"><input type="checkbox" name="is_featured"<?= !empty($editProduct['is_featured'])?' checked':'' ?> onchange="document.getElementById('pvFeat').style.display=this.checked?'inline-block':'none'"><div class="tog-track"></div><div class="tog-thumb"></div></div>
            <span class="tog-lbl"><i class="fas fa-star" style="color:var(--gold);margin-right:4px"></i>Featured</span>
          </label>
        </div>
        <div style="display:flex;gap:9px">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= empty($editProduct)?'Add Pizza':'Save Changes' ?></button>
          <a href="index.php?page=products" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Live preview card -->
  <div>
    <div class="card" style="margin-bottom:0;position:sticky;top:72px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-eye"></i> Live Preview</div></div>
      <div class="card-bd" style="padding:14px">
        <div style="border:1.5px solid var(--bdr);border-radius:var(--r-xl);overflow:hidden">
          <div style="aspect-ratio:4/3;background:var(--bg-up);overflow:hidden;position:relative">
            <img id="imgPreview" src="<?= htmlspecialchars($editProduct['image']??'') ?>" style="width:100%;height:100%;object-fit:cover;display:<?= !empty($editProduct['image'])?'block':'none' ?>" onerror="this.style.display='none';document.getElementById('imgPh').style.display='flex'">
            <div id="imgPh" style="position:absolute;inset:0;display:<?= empty($editProduct['image'])?'flex':'none' ?>;align-items:center;justify-content:center;flex-direction:column;color:var(--t3)">
              <i class="fas fa-image" style="font-size:28px;margin-bottom:6px;opacity:.35"></i>
              <span style="font-size:11px">Paste URL above</span>
            </div>
          </div>
          <div style="padding:12px">
            <div style="display:flex;gap:5px;margin-bottom:6px">
              <span id="pvVeg" style="background:var(--green-s);color:var(--green);font-size:10px;font-weight:700;padding:2px 7px;border-radius:99px;display:<?= !empty($editProduct['is_veg'])?'inline-block':'none' ?>">🌿 VEG</span>
              <span id="pvFeat" style="background:var(--gold-soft);color:var(--gold);font-size:10px;font-weight:700;padding:2px 7px;border-radius:99px;display:<?= !empty($editProduct['is_featured'])?'inline-block':'none' ?>">⭐ Featured</span>
            </div>
            <div id="pvName" style="font-family:var(--font);font-size:14px;font-weight:700;color:var(--t1);margin-bottom:3px"><?= htmlspecialchars($editProduct['name']??'Pizza Name') ?></div>
            <div id="pvDesc" style="font-size:11.5px;color:var(--t3);margin-bottom:8px"><?= htmlspecialchars(substr($editProduct['description']??'Description…',0,60)) ?></div>
            <div id="pvPrice" style="font-family:var(--font);font-size:1.3rem;font-weight:800;color:var(--brand)"><?= !empty($editProduct['price'])?'₹'.$editProduct['price']:'₹—' ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>.prod-edit-grid{grid-template-columns:1fr 300px}@media(max-width:900px){.prod-edit-grid{grid-template-columns:1fr!important}}</style>
<script>
function prevImg(u){var i=document.getElementById('imgPreview'),p=document.getElementById('imgPh');if(u){i.src=u;i.style.display='block';p.style.display='none';}else{i.style.display='none';p.style.display='flex';}}
function pvName(v){document.getElementById('pvName').textContent=v||'Pizza Name';}
function pvDesc(v){document.getElementById('pvDesc').textContent=(v||'Description…').substring(0,60);}
function pvPrice(v){document.getElementById('pvPrice').textContent=v?'₹'+v:'₹—';}
(function(){
  var n=document.querySelector('[name="name"]').value;
  var d=document.querySelector('[name="description"]').value;
  var p=document.querySelector('[name="price"]').value;
  if(n)pvName(n); if(d)pvDesc(d); if(p)pvPrice(p);
})();
</script>

<?php else: ?>
<!-- ══ PRODUCT LIST / GRID ══════════════════════════════════════ -->
<div class="card" style="margin-bottom:16px">
  <div class="card-hd">
    <div class="card-title"><i class="fas fa-pizza-slice"></i> All Pizzas <span style="color:var(--t3);font-weight:400;font-size:12px">(<?= count($products) ?>)</span></div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      <div class="srch-wrap"><i class="fas fa-search"></i><input type="text" class="srch-inp" id="tableSearch" placeholder="Search pizzas…"></div>
      <!-- View toggle -->
      <div style="display:flex;border:1.5px solid var(--bdr-md);border-radius:var(--r-sm);overflow:hidden">
        <button onclick="setView('grid')" id="btnGrid" style="padding:6px 10px;background:var(--brand);color:#fff;border:none;cursor:pointer;font-size:13px;transition:all var(--tr)"><i class="fas fa-grid-2"></i></button>
        <button onclick="setView('list')" id="btnList" style="padding:6px 10px;background:var(--bg-up);color:var(--t2);border:none;cursor:pointer;font-size:13px;transition:all var(--tr)"><i class="fas fa-list"></i></button>
      </div>
      <a href="index.php?page=products&add=1" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Pizza</a>
    </div>
  </div>

  <!-- Category filter -->
  <?php $cats=array_unique(array_column($products,'category')); ?>
  <div style="padding:10px 18px;border-bottom:1px solid var(--bdr);display:flex;gap:6px;flex-wrap:wrap">
    <a href="index.php?page=products" class="pill <?= empty($_GET['cat'])?'active':'' ?>">All (<?= count($products) ?>)</a>
    <?php foreach($cats as $c): ?>
    <a href="index.php?page=products&cat=<?= urlencode($c) ?>" class="pill <?= ($_GET['cat']??'')===$c?'active':'' ?>"><?= $c ?> (<?= count(array_filter($products,fn($p)=>$p['category']===$c)) ?>)</a>
    <?php endforeach; ?>
  </div>

  <?php
  $cf=$_GET['cat']??'';
  $fp=$cf?array_filter($products,fn($p)=>$p['category']===$cf):$products;
  ?>

  <!-- Grid view -->
  <div id="viewGrid" style="padding:16px">
    <div class="prod-grid">
      <?php if(empty($fp)): ?>
      <div style="grid-column:1/-1"><div class="empty-box"><i class="fas fa-pizza-slice"></i><h3>No pizzas found</h3></div></div>
      <?php else: foreach($fp as $p): ?>
      <div class="prod-card <?= $p['is_featured']?'featured':'' ?> s-row">
        <div class="prod-card-img">
          <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" onerror="this.src='https://placehold.co/400x300/FEF8F6/E8351F?text=🍕'">
          <?php if($p['is_featured']): ?><span class="prod-card-badge">⭐ Featured</span><?php endif; ?>
        </div>
        <div class="prod-card-body">
          <div style="display:flex;gap:4px;margin-bottom:5px">
            <span style="background:var(--bg-up);color:var(--t3);font-size:10px;font-weight:600;padding:2px 7px;border-radius:99px"><?= htmlspecialchars($p['category']) ?></span>
            <?php if($p['is_veg']): ?><span style="background:var(--green-s);color:var(--green);font-size:10px;font-weight:700;padding:2px 7px;border-radius:99px">🌿 VEG</span><?php endif; ?>
          </div>
          <div class="prod-card-name"><?= htmlspecialchars($p['name']) ?></div>
          <div class="prod-card-desc"><?= htmlspecialchars($p['description']) ?></div>
          <div class="prod-card-foot">
            <span class="prod-card-price">₹<?= number_format($p['price'],0) ?></span>
            <div style="display:flex;gap:5px">
              <a href="index.php?page=products&edit=<?= $p['id'] ?>" class="btn btn-ghost btn-xs"><i class="fas fa-edit"></i></a>
              <form method="POST" action="index.php?page=products&action=delete_product" data-confirm="Delete '<?= htmlspecialchars($p['name']) ?>'?" style="display:inline">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <!-- List view (hidden by default) -->
  <div id="viewList" style="display:none">
    <div class="tbl-wrap">
      <table>
        <thead><tr><th>#</th><th>Pizza</th><th>Category</th><th>Price</th><th>Type</th><th>Featured</th><th>Actions</th></tr></thead>
        <tbody>
          <?php if(empty($fp)): ?>
          <tr><td colspan="7"><div class="empty-box"><i class="fas fa-pizza-slice"></i><h3>No pizzas found</h3></div></td></tr>
          <?php else: foreach($fp as $p): ?>
          <tr class="s-row">
            <td style="color:var(--t3);font-size:11.5px;font-weight:600"><?= $p['id'] ?></td>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                <img src="<?= htmlspecialchars($p['image']) ?>" class="prod-thumb" alt="" onerror="this.src='https://placehold.co/40x40/FEF8F6/E8351F?text=🍕'">
                <div>
                  <div style="font-weight:600;font-size:13px"><?= htmlspecialchars($p['name']) ?></div>
                  <div style="font-size:11px;color:var(--t3);max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars(substr($p['description'],0,55)) ?>…</div>
                </div>
              </div>
            </td>
            <td><span style="background:var(--bg-up);color:var(--t2);font-size:11px;font-weight:600;padding:3px 9px;border-radius:99px"><?= htmlspecialchars($p['category']) ?></span></td>
            <td><strong style="font-size:14px;font-family:var(--font)">₹<?= number_format($p['price'],0) ?></strong></td>
            <td><?= $p['is_veg']?'<span style="color:var(--green);font-size:12px;font-weight:600"><i class="fas fa-leaf"></i> Veg</span>':'<span style="color:var(--brand);font-size:12px;font-weight:600"><i class="fas fa-drumstick-bite"></i> Non-Veg</span>' ?></td>
            <td><?= $p['is_featured']?'<span style="color:var(--gold);font-size:12px;font-weight:600"><i class="fas fa-star"></i> Yes</span>':'<span style="color:var(--t3)">—</span>' ?></td>
            <td>
              <div style="display:flex;gap:5px">
                <a href="index.php?page=products&edit=<?= $p['id'] ?>" class="btn btn-ghost btn-xs"><i class="fas fa-edit"></i> Edit</a>
                <form method="POST" action="index.php?page=products&action=delete_product" data-confirm="Delete '<?= htmlspecialchars($p['name']) ?>'?" style="display:inline">
                  <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                  <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
var _view=localStorage.getItem('ph_prod_view')||'grid';
function setView(v){
  _view=v;localStorage.setItem('ph_prod_view',v);
  document.getElementById('viewGrid').style.display=v==='grid'?'block':'none';
  document.getElementById('viewList').style.display=v==='list'?'block':'none';
  document.getElementById('btnGrid').style.background=v==='grid'?'var(--brand)':'var(--bg-up)';
  document.getElementById('btnGrid').style.color=v==='grid'?'#fff':'var(--t2)';
  document.getElementById('btnList').style.background=v==='list'?'var(--brand)':'var(--bg-up)';
  document.getElementById('btnList').style.color=v==='list'?'#fff':'var(--t2)';
}
setView(_view);
// Search works on both grid and list
document.addEventListener('DOMContentLoaded',function(){
  var ts=document.getElementById('tableSearch');
  if(!ts)return;
  ts.addEventListener('input',function(){
    var q=this.value.toLowerCase();
    document.querySelectorAll('.prod-card.s-row,.s-row').forEach(function(r){
      r.style.display=r.textContent.toLowerCase().includes(q)?'':'none';
    });
  });
});
</script>
<?php endif; ?>