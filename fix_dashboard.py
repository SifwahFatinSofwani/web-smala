import re

path = r'c:\laragon\www\sma5\php\admin\dashboard.php'
with open(path, 'r', encoding='utf-8') as f:
    text = f.read()

# 1. Add DB and Auth at top
if "requireAdmin()" not in text:
    text = text.replace('<!DOCTYPE html>', "<?php\nrequire_once __DIR__ . '/db.php';\nrequireAdmin();\n\n$db = getDB();\n?>\n<!DOCTYPE html>")

# 2. Replace Sidebar
text = re.sub(r'<!-- ===== SIDEBAR ===== -->.*?<!-- Overlay \(mobile\) -->', '''<!-- ===== SIDEBAR ===== -->
  <?php include 'sidebar.php'; ?>

  <!-- Overlay (mobile) -->''', text, flags=re.DOTALL)

# 3. Stats Grid
stats_grid_html = """      <!-- Stats Cards -->
      <div class="stats-grid">
        
        <div class="stat-card" style="background: var(--sb-bg); border: 1px solid var(--sb-border);">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Total Alumni</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">1.248</h3>
            </div>
          </div>
          <span class="stat-change up" style="margin-left: 0;">+24 bulan ini</span>
        </div>

        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Universitas Terdaftar</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">87</h3>
            </div>
          </div>
          <span class="stat-change up" style="margin-left: 0;">+5 baru</span>
        </div>

        <div class="stat-card" style="background: var(--sb-bg); border: 1px solid var(--sb-border);">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Laporan Pending</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">3</h3>
            </div>
          </div>
          <span class="stat-change down" style="margin-left: 0;">Perlu verifikasi</span>
        </div>

        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-titles">
              <p class="stat-label" style="text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Pengunjung Hari Ini</p>
              <h3 class="stat-value" style="font-size:24px; margin:0;">342</h3>
            </div>
          </div>
          <span class="stat-change up" style="margin-left: 0;">+18%</span>
        </div>

      </div>"""

# replace stats grid
text = re.sub(r'<!-- Stats Cards -->.*?</div>\s*<!-- Content Grid -->', stats_grid_html + '\n\n      <!-- Content Grid -->', text, flags=re.DOTALL)

# 4. Remove Icons from Headers
text = text.replace('<h3><i class="fa-solid fa-users"></i> Alumni Terbaru</h3>', '<h3>Alumni Terbaru</h3>')
text = text.replace('<h3><i class="fa-solid fa-file-shield"></i> Laporan Masuk</h3>', '<h3>Laporan Masuk</h3>')
text = text.replace('<h3><i class="fa-solid fa-chart-pie"></i> Distribusi Jalur</h3>', '<h3>Distribusi Jalur</h3>')

# 5. Fix table length to EXACTLY 6 rows (flush bottom)
tbody_match = re.search(r'<tbody>(.*?)</tbody>', text, re.DOTALL)
if tbody_match:
    rows = re.findall(r'<tr.*?>.*?</tr>', tbody_match.group(1), re.DOTALL)
    # the original has 4 rows (or 5). We will copy them to make 6 rows exactly.
    while len(rows) < 6:
        rows.append(rows[len(rows) % 4]) # copy existing rows
    if len(rows) > 6:
        rows = rows[:6]
    text = text.replace(tbody_match.group(0), '<tbody>\n' + '\n'.join(rows) + '\n</tbody>')

with open(path, 'w', encoding='utf-8') as f:
    f.write(text)
print("Restored and updated!")
