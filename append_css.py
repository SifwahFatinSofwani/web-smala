import io

path = r'c:\laragon\www\sma5\css\admin.css'
css_append = '''

/* Dashboard Specific Table Adjustments */
/* Make it narrower horizontally so action buttons aren't hidden */
.dash-table th {
  padding: 12px 6px;
}
.dash-table td {
  padding: 20px 6px; /* taller vertically to fill empty space below */
}

/* Ensure the action buttons don't wrap and are tightly together */
.dash-table .action-btns {
  display: flex;
  gap: 6px;
  justify-content: flex-end;
}
.dash-table th:last-child,
.dash-table td:last-child {
  padding-right: 12px; /* A bit of padding at the very right edge */
}
.dash-table th:first-child,
.dash-table td:first-child {
  padding-left: 12px; /* Pad left edge */
}

/* Allow universitas column to text-wrap if necessary */
.dash-table td:nth-child(3) {
  white-space: normal;
  min-width: 120px;
}
'''
with open(path, 'a', encoding='utf-8') as f:
    f.write(css_append)
print("CSS applied successfully")
