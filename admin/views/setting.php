<?php
$stab = $_GET['tab'] ?? 'general';
$settings = $_SESSION['settings'] ?? [];
$s = fn(string $key, $default='') => htmlspecialchars($settings[$key] ?? $default);
$checked = fn(string $key, bool $default=false) => (!empty($settings) ? !empty($settings[$key]) : $default) ? ' checked' : '';
?>

<div style="display:grid;grid-template-columns:200px 1fr;gap:16px" class="sett-lay">

  <!-- Tab sidebar -->
  <div class="card" style="margin-bottom:0;padding:6px 0">
    <?php
    $tabs = [
      'general'      => ['fa-gear',        'General'],
      'store'        => ['fa-store',       'Store Info'],
      'delivery'     => ['fa-truck',       'Delivery'],
      'payments'     => ['fa-credit-card', 'Payments'],
      'security'     => ['fa-shield-alt',  'Security'],
      'notifications'=> ['fa-bell',        'Notifications'],
    ];
    foreach ($tabs as $k => [$ic,$lb]):
    ?>
    <a href="index.php?page=settings&tab=<?= $k ?>"
       style="display:flex;align-items:center;gap:8px;padding:9px 14px;
              color:<?= $stab===$k?'var(--brand)':'var(--t2)' ?>;
              font-size:12.5px;font-weight:<?= $stab===$k?700:500 ?>;
              background:<?= $stab===$k?'var(--brand-soft)':'transparent' ?>;
              border-left:3px solid <?= $stab===$k?'var(--brand)':'transparent' ?>;
              transition:all var(--tr);text-decoration:none">
      <i class="fas <?= $ic ?>" style="width:14px;text-align:center;font-size:12px"></i><?= $lb ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Tab content -->
  <div>
    <form method="POST" action="index.php?page=settings&tab=<?= $stab ?>">

    <?php if ($stab==='general'): ?>
    <!-- ══ GENERAL ══════════════════════════════════════ -->
    <div class="card" style="margin-bottom:14px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-gear"></i> General Settings</div></div>
      <div class="card-bd">
        <div class="form-grid" style="margin-bottom:20px">
          <div class="form-grp"><label class="form-lbl">Site Name</label>
            <input type="text" class="form-ctrl" name="site_name" value="<?= $s('site_name','Pizza House') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Admin Email</label>
            <input type="email" class="form-ctrl" name="admin_email" value="<?= $s('admin_email','admin@pizzahouse.com') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Currency Symbol</label>
            <input type="text" class="form-ctrl" name="currency" value="<?= $s('currency','₹') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Timezone</label>
            <select class="form-ctrl" name="timezone">
              <option value="Asia/Kolkata"<?= ($settings['timezone']??'Asia/Kolkata')==='Asia/Kolkata'?' selected':'' ?>>Asia/Kolkata (IST)</option>
              <option value="UTC"<?= ($settings['timezone']??'')==='UTC'?' selected':'' ?>>UTC</option>
            </select>
          </div>
          <div class="form-grp form-full"><label class="form-lbl">Tagline</label>
            <input type="text" class="form-ctrl" name="tagline" value="<?= $s('tagline','Artisan Pizza · Hyderabad') ?>"></div>
        </div>
        <!-- Feature toggles -->
        <div style="border-top:1px solid var(--bdr);padding-top:16px">
          <div style="font-size:12.5px;font-weight:700;color:var(--t1);margin-bottom:12px">Feature Toggles</div>
          <?php
          $toggles = [
            ['enable_orders',  'Accept Orders',     'Allow customers to place orders',            true],
            ['enable_wishlist','Wishlist',           'Let users save favourite pizzas',            true],
            ['enable_reviews', 'Reviews',            'Show & accept customer reviews',             true],
            ['maintenance',    'Maintenance Mode',   'Show maintenance page to visitors',          false],
          ];
          foreach ($toggles as [$n,$l,$sub,$def]):
          ?>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:var(--bg-up);border-radius:var(--r-sm);border:1px solid var(--bdr);margin-bottom:8px">
            <div><div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:1px"><?= $l ?></div>
              <div style="font-size:11px;color:var(--t3)"><?= $sub ?></div></div>
            <label class="tog-wrap" style="margin:0"><div class="tog">
              <input type="checkbox" name="<?= $n ?>"<?= $checked($n,$def) ?>>
              <div class="tog-track"></div><div class="tog-thumb"></div>
            </div></label>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <?php elseif ($stab==='store'): ?>
    <!-- ══ STORE INFO ═══════════════════════════════════ -->
    <div class="card" style="margin-bottom:14px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-store"></i> Store Information</div></div>
      <div class="card-bd">
        <div class="form-grid">
          <div class="form-grp"><label class="form-lbl">Restaurant Name</label>
            <input type="text" class="form-ctrl" name="restaurant_name" value="<?= $s('restaurant_name','Pizza House') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Phone</label>
            <input type="tel" class="form-ctrl" name="phone" value="<?= $s('phone','+91 98765 43210') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Email</label>
            <input type="email" class="form-ctrl" name="email" value="<?= $s('email','info@pizzahouse.com') ?>"></div>
          <div class="form-grp"><label class="form-lbl">WhatsApp</label>
            <input type="tel" class="form-ctrl" name="whatsapp" value="<?= $s('whatsapp','+91 98765 43210') ?>"></div>
          <div class="form-grp form-full"><label class="form-lbl">Address</label>
            <textarea class="form-ctrl" name="address"><?= $s('address','Plot 123, Road 10, Banjara Hills, Hyderabad 500034') ?></textarea></div>
          <div class="form-grp"><label class="form-lbl">Opening Time</label>
            <input type="time" class="form-ctrl" name="open_time" value="<?= $s('open_time','10:00') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Closing Time</label>
            <input type="time" class="form-ctrl" name="close_time" value="<?= $s('close_time','23:00') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Instagram</label>
            <input type="text" class="form-ctrl" name="instagram" value="<?= $s('instagram','@PizzaHouseOfficial') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Facebook</label>
            <input type="text" class="form-ctrl" name="facebook" value="<?= $s('facebook','PizzaHouseHyd') ?>"></div>
        </div>
      </div>
    </div>

    <?php elseif ($stab==='delivery'): ?>
    <!-- ══ DELIVERY ══════════════════════════════════════ -->
    <div class="card" style="margin-bottom:14px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-truck"></i> Delivery Settings</div></div>
      <div class="card-bd">
        <div class="form-grid" style="margin-bottom:20px">
          <div class="form-grp"><label class="form-lbl">Delivery Fee (₹)</label>
            <input type="number" class="form-ctrl" name="delivery_fee" value="<?= $s('delivery_fee','49') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Free Delivery Above (₹)</label>
            <input type="number" class="form-ctrl" name="free_above" value="<?= $s('free_above','499') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Est. Time (min)</label>
            <input type="number" class="form-ctrl" name="delivery_time" value="<?= $s('delivery_time','40') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Max Radius (km)</label>
            <input type="number" class="form-ctrl" name="radius" value="<?= $s('radius','15') ?>"></div>
          <div class="form-grp"><label class="form-lbl">GST Rate (%)</label>
            <input type="number" class="form-ctrl" name="gst" step="0.1" value="<?= $s('gst','5') ?>"></div>
          <div class="form-grp"><label class="form-lbl">Minimum Order (₹)</label>
            <input type="number" class="form-ctrl" name="min_order" value="<?= $s('min_order','199') ?>"></div>
        </div>
        <div style="border-top:1px solid var(--bdr);padding-top:16px">
          <?php foreach ([
            ['contactless','Contactless Delivery','Allow contactless delivery option',true],
            ['live_tracking','Live Tracking','Real-time order tracking for customers',false],
            ['cod','Cash on Delivery','Accept cash payment at the door',true],
          ] as [$n,$l,$sub,$def]): ?>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:var(--bg-up);border-radius:var(--r-sm);border:1px solid var(--bdr);margin-bottom:8px">
            <div><div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:1px"><?= $l ?></div>
              <div style="font-size:11px;color:var(--t3)"><?= $sub ?></div></div>
            <label class="tog-wrap" style="margin:0"><div class="tog">
              <input type="checkbox" name="<?= $n ?>"<?= $checked($n,$def) ?>>
              <div class="tog-track"></div><div class="tog-thumb"></div>
            </div></label>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <?php elseif ($stab==='payments'): ?>
    <!-- ══ PAYMENTS ══════════════════════════════════════ -->
    <div class="card" style="margin-bottom:14px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-credit-card"></i> Payment Gateways</div></div>
      <div class="card-bd">
        <?php foreach ([['Razorpay','fa-rupee-sign','#2E6BDB'],['Stripe','fa-stripe-s','#635BFF'],['PayU','fa-money-bill','#FF6600']] as [$gn,$gi,$gc]): ?>
        <div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--bg-up);border-radius:var(--r-sm);border:1px solid var(--bdr);margin-bottom:10px">
          <div style="width:40px;height:40px;border-radius:var(--r-sm);background:<?= $gc ?>20;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="fas <?= $gi ?>" style="color:<?= $gc ?>;font-size:16px"></i>
          </div>
          <div style="flex:1"><div style="font-weight:600;font-size:13px;color:var(--t1);margin-bottom:2px"><?= $gn ?></div>
            <div style="font-size:11px;color:var(--t3)">Not connected</div></div>
          <button type="button" class="btn btn-ghost btn-sm">Connect</button>
        </div>
        <?php endforeach; ?>
        <div style="background:var(--blue-s);border:1px solid rgba(59,130,246,.25);border-radius:var(--r-sm);padding:12px 14px;font-size:12.5px;color:var(--blue);margin-top:6px">
          <i class="fas fa-info-circle" style="margin-right:6px"></i>API keys must be configured in your .env file.
        </div>
      </div>
    </div>

    <?php elseif ($stab==='security'): ?>
    <!-- ══ SECURITY ══════════════════════════════════════ -->
    <div class="card" style="margin-bottom:14px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-shield-alt"></i> Security</div></div>
      <div class="card-bd">
        <div style="font-size:12.5px;font-weight:700;color:var(--t1);margin-bottom:12px">Change Password</div>
        <div class="form-grid" style="margin-bottom:20px">
          <div class="form-grp"><label class="form-lbl">Current Password</label>
            <input type="password" class="form-ctrl" name="current_pass" placeholder="••••••••"></div>
          <div></div>
          <div class="form-grp"><label class="form-lbl">New Password</label>
            <input type="password" class="form-ctrl" name="new_pass" placeholder="Min 8 chars"></div>
          <div class="form-grp"><label class="form-lbl">Confirm New</label>
            <input type="password" class="form-ctrl" name="confirm_pass" placeholder="Re-enter"></div>
        </div>
        <div style="border-top:1px solid var(--bdr);padding-top:16px;margin-bottom:14px">
          <?php foreach ([
            ['two_fa',       'Two-Factor Auth',  'Require 2FA for admin login',        false],
            ['login_alerts', 'Login Alerts',     'Email on new admin login',           true],
            ['ip_whitelist', 'IP Whitelist',     'Restrict access to specific IPs',    false],
          ] as [$n,$l,$sub,$def]): ?>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:var(--bg-up);border-radius:var(--r-sm);border:1px solid var(--bdr);margin-bottom:8px">
            <div><div style="font-size:13px;font-weight:600;color:var(--t1);margin-bottom:1px"><?= $l ?></div>
              <div style="font-size:11px;color:var(--t3)"><?= $sub ?></div></div>
            <label class="tog-wrap" style="margin:0"><div class="tog">
              <input type="checkbox" name="<?= $n ?>"<?= $checked($n,$def) ?>>
              <div class="tog-track"></div><div class="tog-thumb"></div>
            </div></label>
          </div>
          <?php endforeach; ?>
        </div>
        <div style="padding:12px 14px;background:var(--green-s);border:1px solid rgba(34,197,94,.2);border-radius:var(--r-sm);display:flex;align-items:center;gap:10px">
          <i class="fas fa-check-circle" style="color:var(--green);font-size:16px"></i>
          <div>
            <div style="font-size:12.5px;font-weight:600;color:var(--green)">Logged in securely</div>
            <div style="font-size:11px;color:var(--t3)">Session started <?= date('d M Y, H:i') ?></div>
          </div>
        </div>
      </div>
    </div>

    <?php elseif ($stab==='notifications'): ?>
    <!-- ══ NOTIFICATIONS ═════════════════════════════════ -->
    <div class="card" style="margin-bottom:14px">
      <div class="card-hd"><div class="card-title"><i class="fas fa-bell"></i> Notification Preferences</div></div>
      <div class="card-bd">
        <?php
        $groups = [
          ['Orders', [
            ['notif_new_order',   'New Order Placed',    true],
            ['notif_order_cancel','Order Cancelled',     true],
            ['notif_order_delay', 'Delivery Delayed',    false],
          ]],
          ['Reviews', [
            ['notif_new_review',  'New Review',          true],
            ['notif_low_rating',  '1–2 Star Alert',      true],
          ]],
          ['System', [
            ['notif_daily',       'Daily Revenue Report',true],
            ['notif_weekly',      'Weekly Summary Email',true],
          ]],
        ];
        foreach ($groups as [$gs,$gi]):
        ?>
        <div style="margin-bottom:18px">
          <div style="font-size:10.5px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px"><?= $gs ?></div>
          <?php foreach ($gi as [$n,$l,$def]): ?>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--bg-up);border-radius:var(--r-sm);border:1px solid var(--bdr);margin-bottom:7px">
            <span style="font-size:13px;font-weight:500;color:var(--t1)"><?= $l ?></span>
            <label class="tog-wrap" style="margin:0"><div class="tog">
              <input type="checkbox" name="<?= $n ?>"<?= $checked($n,$def) ?>>
              <div class="tog-track"></div><div class="tog-thumb"></div>
            </div></label>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        <div class="form-grp" style="margin-top:6px">
          <label class="form-lbl">Send To</label>
          <input type="email" class="form-ctrl" name="notif_email" value="<?= $s('notif_email','admin@pizzahouse.com') ?>">
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div style="display:flex;gap:10px;align-items:center">
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
      <span style="font-size:11.5px;color:var(--t3)">Changes apply immediately</span>
    </div>
    </form>
  </div>
</div>
<style>@media(max-width:768px){.sett-lay{grid-template-columns:1fr!important}}</style>