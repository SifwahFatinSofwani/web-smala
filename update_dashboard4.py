import re

path = r'c:\laragon\www\sma5\php\admin\dashboard.php'
with open(path, 'r', encoding='utf-8') as f:
    text = f.read()

def fix_card(card_html):
    # delete stat-icon-wrap completely
    c = re.sub(r'<div class="stat-icon-wrap.*?>.*?</div>', '', card_html)
    # make the entire card background sidebar blue
    c = c.replace('<div class="stat-card">', '<div class="stat-card" style="background: var(--sb-bg); border: 1px solid var(--sb-border);">')
    return c

# Find and replace Total Alumni block
match1 = re.search(r'<div class="stat-card">.*?Total Alumni.*?</div>\s*</div>', text, re.DOTALL)
if match1:
    text = text.replace(match1.group(0), fix_card(match1.group(0)))

# Find and replace Laporan Pending block
match2 = re.search(r'<div class="stat-card">.*?Laporan Pending.*?</div>\s*</div>', text, re.DOTALL)
if match2:
    text = text.replace(match2.group(0), fix_card(match2.group(0)))

with open(path, 'w', encoding='utf-8') as f:
    f.write(text)

print("Done!")
