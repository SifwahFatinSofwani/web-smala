import re

# 1. Update dashboard.php avatars
path_php = r'c:\laragon\www\sma5\php\admin\dashboard.php'
with open(path_php, 'r', encoding='utf-8') as f:
    text = f.read()

# The pattern looks for any user-ava div that has an inline style of background and color
pattern = r'<div class="user-ava" style="background:#[a-zA-Z0-9]+;color:#[a-zA-Z0-9]+;">'
# Replace it with theme colors (e.g. sidebar light blue #d6eaf0)
replacement = '<div class="user-ava" style="background:#e8f0f5;color:#1a2636;">'
text = re.sub(pattern, replacement, text)

with open(path_php, 'w', encoding='utf-8') as f:
    f.write(text)

# 2. Add scrollbar hidden style to admin.css
path_css = r'c:\laragon\www\sma5\css\admin.css'
css_append = '''

/* Hide horizontal scrollbar for table wrapper to look modern */
.table-wrap::-webkit-scrollbar {
  display: none;
}
.table-wrap {
  -ms-overflow-style: none; /* IE and Edge */
  scrollbar-width: none; /* Firefox */
}
'''
with open(path_css, 'a', encoding='utf-8') as f:
    f.write(css_append)

print("Done av and scrollbar fixes!")
