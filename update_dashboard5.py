import re

path = r'c:\laragon\www\sma5\php\admin\dashboard.php'
with open(path, 'r', encoding='utf-8') as f:
    text = f.read()

# 1. Remove stat-icon-wrap entirely from the remaining cards
text = re.sub(r'<div class="stat-icon-wrap.*?>.*?</div>', '', text)

# 2. Let's remove the trend icon from ALL stat-changes if they still exist
# e.g., <i class="fa-solid fa-arrow-trend-up"></i> +5 baru -> +5 baru
text = re.sub(r'<i class="fa-solid fa-.*?"></i>\s*', '', text)

# Ensure "margin-left: 0" inside the stat-change where the icons were removed so it aligns left nicely
# Already added to the first 2 before, but let's just make sure
text = text.replace('<span class="stat-change up">', '<span class="stat-change up" style="margin-left: 0;">')
text = text.replace('<span class="stat-change down">', '<span class="stat-change down" style="margin-left: 0;">')

# 3. Find the table and reduce rows to exactly 6 or 7 rows
# Right now tbody has ~15 rows (multiplied by 3 earlier).
match = re.search(r'<tbody>(.*?)</tbody>', text, re.DOTALL)
if match:
    tbody_html = match.group(1)
    # find exactly all <tr>...</tr> blocks properly (this is simplistic but works for basic HTML)
    rows = re.findall(r'<tr.*?>.*?</tr>', tbody_html, re.DOTALL)
    # The right sidebar generally fits ~6 rows perfectly.
    if len(rows) > 6:
        rows = rows[:6]
    text = text.replace(match.group(0), '<tbody>\n' + '\n'.join(rows) + '\n</tbody>')

with open(path, 'w', encoding='utf-8') as f:
    f.write(text)
print("Done fixing layout!")
