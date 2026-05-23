<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Admin') - NATAKOS</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                color-scheme: light;
                font-family: 'Inter', system-ui, sans-serif;

                /* ── Core Palette ── */
                --slate-50:  #f8fafc;
                --slate-100: #f1f5f9;
                --slate-200: #e2e8f0;
                --slate-300: #cbd5e1;
                --slate-400: #94a3b8;
                --slate-500: #64748b;
                --slate-600: #475569;
                --slate-700: #334155;
                --slate-800: #1e293b;
                --slate-900: #0f172a;

                --indigo-50:  #eef2ff;
                --indigo-100: #e0e7ff;
                --indigo-200: #c7d2fe;
                --indigo-500: #6366f1;
                --indigo-600: #4f46e5;
                --indigo-700: #4338ca;

                --ui-ink:         var(--slate-900);
                --ui-body:        var(--slate-500);
                --ui-canvas:      #ffffff;
                --ui-surface:     var(--slate-50);
                --ui-surface-2:   var(--slate-100);
                --ui-border:      var(--slate-200);
                --ui-border-mid:  var(--slate-300);
                --ui-accent:      var(--indigo-600);
                --ui-accent-soft: var(--indigo-50);
                --ui-accent-mid:  var(--indigo-200);

                --ui-warning:        #fffbeb;
                --ui-warning-border: #fcd34d;
                --ui-danger:         #fff1f2;
                --ui-danger-border:  #fda4af;
                --ui-success:        #f0fdf4;
                --ui-success-border: #86efac;

                --ui-shadow:       0 1px 2px rgba(15,23,42,0.04), 0 4px 16px rgba(15,23,42,0.04);
                --ui-shadow-md:    0 2px 8px rgba(15,23,42,0.06), 0 8px 24px rgba(15,23,42,0.05);
                --ui-shadow-soft:  0 1px 3px rgba(15,23,42,0.06);

                --radius-sm:   6px;
                --radius-md:   10px;
                --radius-lg:   14px;
                --radius-xl:   18px;
                --radius-pill: 999px;
            }

            *, *::before, *::after { box-sizing: border-box; }
            html { scroll-behavior: smooth; }

            body {
                margin: 0;
                min-height: 100vh;
                background: var(--slate-50);
                color: var(--ui-ink);
                line-height: 1.5;
                font-size: 14px;
            }

            a { color: inherit; text-decoration: none; }
            img { max-width: 100%; height: auto; }
            button, input, select, textarea { font: inherit; }

            /* ── SIDEBAR LAYOUT ── */
            .app-layout {
                display: flex;
                min-height: 100vh;
            }

            /* ── SIDEBAR ── */
            .sidebar {
                width: 220px;
                flex-shrink: 0;
                background: var(--slate-900);
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0; left: 0; bottom: 0;
                z-index: 40;
                overflow-y: auto;
            }

            .sidebar-brand {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 22px 20px 18px;
                border-bottom: 1px solid rgba(255,255,255,0.06);
            }

            .sidebar-brand-mark {
                width: 30px; height: 30px;
                border-radius: 8px;
                background: var(--indigo-600);
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }

            .sidebar-brand-mark svg {
                width: 16px; height: 16px;
                fill: #fff;
            }

            .sidebar-brand-name {
                font-family: 'Sora', sans-serif;
                font-size: 15px;
                font-weight: 800;
                color: #ffffff;
                letter-spacing: -0.2px;
            }

            .sidebar-nav {
                flex: 1;
                padding: 12px 10px;
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .sidebar-label {
                padding: 10px 10px 4px;
                font-size: 10px;
                font-weight: 600;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                color: var(--slate-500);
            }

            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 9px;
                padding: 9px 12px;
                border-radius: var(--radius-md);
                font-size: 13.5px;
                font-weight: 500;
                color: var(--slate-400);
                transition: background 0.15s, color 0.15s;
            }

            .sidebar-link svg {
                width: 16px; height: 16px;
                opacity: 0.7;
                flex-shrink: 0;
            }

            .sidebar-link:hover {
                background: rgba(255,255,255,0.06);
                color: #ffffff;
            }

            .sidebar-link:hover svg { opacity: 1; }

            .sidebar-link.is-active {
                background: var(--indigo-600);
                color: #ffffff;
            }

            .sidebar-link.is-active svg { opacity: 1; }

            .sidebar-footer {
                padding: 12px 10px;
                border-top: 1px solid rgba(255,255,255,0.06);
            }

            .sidebar-logout {
                display: flex;
                align-items: center;
                gap: 9px;
                width: 100%;
                padding: 9px 12px;
                border: 0;
                border-radius: var(--radius-md);
                background: transparent;
                color: var(--slate-400);
                font-size: 13.5px;
                font-weight: 500;
                cursor: pointer;
                transition: background 0.15s, color 0.15s;
                text-align: left;
            }

            .sidebar-logout svg { width: 16px; height: 16px; opacity: 0.7; }

            .sidebar-logout:hover {
                background: rgba(255,255,255,0.06);
                color: #ffffff;
            }

            /* ── MAIN CONTENT ── */
            .main-wrap {
                flex: 1;
                margin-left: 220px;
                display: flex;
                flex-direction: column;
                min-width: 0;
            }

            /* ── TOPBAR ── */
            .topbar {
                position: sticky;
                top: 0;
                z-index: 30;
                background: rgba(255,255,255,0.92);
                border-bottom: 1px solid var(--ui-border);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 0 32px;
                height: 56px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
            }

            .topbar-breadcrumb {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: var(--ui-body);
            }

            .topbar-breadcrumb span { color: var(--slate-300); }

            .topbar-breadcrumb strong {
                color: var(--ui-ink);
                font-weight: 600;
            }

            .topbar-right {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .topbar-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                border-radius: var(--radius-pill);
                background: var(--ui-accent-soft);
                border: 1px solid var(--ui-accent-mid);
                font-size: 12px;
                font-weight: 600;
                color: var(--ui-accent);
            }

            .topbar-badge-dot {
                width: 6px; height: 6px;
                border-radius: 50%;
                background: var(--indigo-500);
            }

            /* ── PAGE SHELL ── */
            .page-shell {
                padding: 28px 32px 48px;
                flex: 1;
            }

            /* ── PAGE HEADER ── */
            .page-header {
                display: flex;
                flex-direction: column;
                gap: 20px;
                margin-bottom: 28px;
            }

            .eyebrow {
                margin: 0 0 5px;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: var(--ui-accent);
            }

            .page-title {
                margin: 0;
                font-family: 'Sora', sans-serif;
                font-size: 26px;
                font-weight: 700;
                line-height: 1.2;
                letter-spacing: -0.3px;
                color: var(--ui-ink);
            }

            .page-copy {
                margin: 8px 0 0;
                max-width: 680px;
                color: var(--ui-body);
                font-size: 14px;
                line-height: 1.7;
            }

            /* ── FLASH MESSAGES ── */
            .flash {
                margin-bottom: 20px;
                padding: 14px 18px;
                font-size: 14px;
                line-height: 1.6;
                border: 1px solid transparent;
                border-radius: var(--radius-md);
                box-shadow: var(--ui-shadow-soft);
            }

            .flash-success {
                background: var(--ui-success);
                color: #166534;
                border-color: var(--ui-success-border);
            }

            .flash-error {
                background: var(--ui-danger);
                color: #9f1239;
                border-color: var(--ui-danger-border);
            }

            .flash ul { margin: 0; padding-left: 18px; }

            /* ── CONTENT STACK ── */
            .content-stack { display: grid; gap: 22px; }

            /* ── CARDS ── */
            .card {
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                border-radius: var(--radius-lg);
                box-shadow: var(--ui-shadow);
                overflow: hidden;
            }

            .card-head {
                display: flex;
                flex-direction: column;
                gap: 4px;
                padding: 20px 22px 0;
            }

            .card-head.has-divider {
                padding-bottom: 16px;
                border-bottom: 1px solid var(--ui-border);
            }

            .card-body { padding: 20px 22px; }
            .card-body-tight { padding: 14px 22px; }

            .card-title {
                margin: 0;
                font-family: 'Sora', sans-serif;
                font-size: 16px;
                font-weight: 700;
                color: var(--ui-ink);
                letter-spacing: -0.1px;
            }

            .card-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
            }

            /* ── METRIC GRID ── */
            .metric-grid {
                display: grid;
                gap: 12px;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }

            .metric-card {
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                border-radius: var(--radius-lg);
                padding: 18px 20px;
                box-shadow: var(--ui-shadow-soft);
                position: relative;
                overflow: hidden;
                transition: box-shadow 0.15s, transform 0.15s;
            }

            .metric-card:hover {
                box-shadow: var(--ui-shadow-md);
                transform: translateY(-1px);
            }

            .metric-label {
                margin: 0 0 10px;
                font-size: 11.5px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                color: var(--ui-body);
            }

            .metric-value {
                margin: 0;
                font-family: 'Sora', sans-serif;
                font-size: 30px;
                font-weight: 800;
                line-height: 1;
                letter-spacing: -0.5px;
                color: var(--ui-ink);
            }

            .metric-hint {
                margin: 8px 0 0;
                font-size: 12px;
                color: var(--ui-body);
                line-height: 1.5;
            }

            .metric-accent-bar {
                position: absolute;
                top: 0; left: 0; right: 0;
                height: 2px;
                background: var(--ui-border);
            }

            .metric-card.is-warning .metric-accent-bar { background: #f59e0b; }
            .metric-card.is-danger  .metric-accent-bar { background: #f43f5e; }
            .metric-card.is-success .metric-accent-bar { background: #22c55e; }
            .metric-card.is-info    .metric-accent-bar { background: var(--indigo-500); }

            /* ── ALERT STACK ── */
            .alert-stack { display: grid; gap: 10px; }

            .alert-box {
                border-radius: var(--radius-md);
                padding: 14px 18px;
                border: 1px solid transparent;
                display: flex;
                gap: 14px;
                align-items: flex-start;
            }

            .alert-box-icon {
                width: 18px; height: 18px;
                flex-shrink: 0;
                margin-top: 1px;
            }

            .alert-box h2 {
                margin: 0 0 3px;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.3;
            }

            .alert-box p {
                margin: 0;
                font-size: 13px;
                line-height: 1.6;
            }

            .alert-box-warning {
                background: var(--ui-warning);
                color: #92400e;
                border-color: var(--ui-warning-border);
            }

            .alert-box-danger {
                background: var(--ui-danger);
                color: #9f1239;
                border-color: var(--ui-danger-border);
            }

            .alert-box-success {
                background: var(--ui-success);
                color: #166534;
                border-color: var(--ui-success-border);
            }

            /* ── TABLE ── */
            .table-wrap { overflow-x: auto; }

            table { width: 100%; border-collapse: collapse; min-width: 800px; }

            th, td {
                padding: 12px 18px;
                text-align: left;
                border-bottom: 1px solid var(--ui-border);
                vertical-align: middle;
                font-size: 13.5px;
                line-height: 1.5;
            }

            th {
                background: var(--slate-50);
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.07em;
                color: var(--slate-500);
                white-space: nowrap;
            }

            tbody tr { transition: background 0.1s; }
            tbody tr:hover { background: var(--slate-50); }
            tbody tr:last-child td { border-bottom: 0; }

            /* ── BADGES ── */
            .badge {
                display: inline-flex;
                align-items: center;
                border-radius: var(--radius-pill);
                padding: 4px 10px;
                font-size: 11.5px;
                font-weight: 600;
                white-space: nowrap;
                letter-spacing: 0.01em;
            }

            .badge-available   { background: #dcfce7; color: #15803d; }
            .badge-occupied    { background: var(--indigo-50); color: var(--indigo-700); }
            .badge-maintenance { background: #fef9c3; color: #854d0e; }
            .badge-inactive    { background: var(--slate-100); color: var(--slate-600); }
            .badge-moved-out   { background: var(--slate-100); color: var(--slate-600); }
            .badge-active      { background: #dcfce7; color: #15803d; }
            .badge-public      { background: var(--indigo-50); color: var(--indigo-700); }
            .badge-room        { background: var(--slate-100); color: var(--slate-600); }

            .badge-unpaid, .badge-safe {
                background: var(--slate-100);
                color: var(--slate-600);
            }

            .badge-pending-verification {
                background: #fff7ed;
                color: #9a3412;
            }

            .badge-paid       { background: #dcfce7; color: #15803d; }
            .badge-rejected,
            .badge-overdue    { background: #ffe4e6; color: #9f1239; }
            .badge-due-soon   { background: #fef9c3; color: #854d0e; }
            .badge-due-today  { background: #ffedd5; color: #9a3412; }
            .badge-ending-soon { background: #fef9c3; color: #854d0e; }
            .badge-ends-today { background: #ffedd5; color: #9a3412; }
            .badge-ended      { background: #ffe4e6; color: #9f1239; }
            .badge-no-end-date { background: var(--slate-100); color: var(--slate-600); }

            /* ── BUTTONS ── */
            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                border: 0;
                border-radius: var(--radius-pill);
                cursor: pointer;
                padding: 9px 18px;
                min-height: 38px;
                font-size: 13px;
                font-weight: 600;
                line-height: 1.2;
                transition: all 0.15s ease;
                white-space: nowrap;
            }

            .button-primary {
                background: var(--ui-accent);
                color: #ffffff;
                box-shadow: 0 1px 3px rgba(79,70,229,0.3);
            }

            .button-primary:hover {
                background: var(--indigo-700);
                box-shadow: 0 3px 8px rgba(79,70,229,0.35);
                transform: translateY(-1px);
            }

            .button-secondary {
                background: var(--ui-canvas);
                color: var(--ui-ink);
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow-soft);
            }

            .button-secondary:hover {
                background: var(--slate-50);
                border-color: var(--ui-border-mid);
            }

            .button-subtle {
                background: var(--slate-100);
                color: var(--slate-700);
                border: 1px solid var(--ui-border);
            }

            .button-subtle:hover {
                background: var(--slate-200);
            }

            .button-danger {
                background: var(--ui-canvas);
                color: #9f1239;
                border: 1px solid var(--ui-danger-border);
            }

            .button-danger:hover { background: var(--ui-danger); }

            /* ── FORM ELEMENTS ── */
            .surface-soft {
                background: var(--ui-surface);
                border: 1px solid var(--ui-border);
                border-radius: var(--radius-lg);
            }

            .form-card { padding: 24px; }

            .form-layout { display: grid; gap: 20px; }

            .form-section {
                display: grid;
                gap: 16px;
                padding: 20px;
                border-radius: var(--radius-lg);
                background: var(--slate-50);
                border: 1px solid var(--ui-border);
            }

            .form-section-title {
                margin: 0;
                font-family: 'Sora', sans-serif;
                font-size: 15px;
                font-weight: 700;
            }

            .form-section-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
            }

            .grid { display: grid; gap: 16px; }

            .field { display: grid; gap: 6px; }
            .field-full { grid-column: 1 / -1; }

            .field label {
                font-size: 13px;
                font-weight: 600;
                color: var(--slate-700);
            }

            .input, .select, .textarea, .file-input {
                width: 100%;
                border: 1px solid var(--ui-border);
                background: var(--ui-canvas);
                color: var(--ui-ink);
                padding: 10px 14px;
                border-radius: var(--radius-md);
                font-size: 14px;
                transition: border-color 0.15s, box-shadow 0.15s;
            }

            .input:focus, .select:focus, .textarea:focus, .file-input:focus {
                outline: none;
                border-color: var(--indigo-500);
                box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
            }

            .textarea { min-height: 140px; resize: vertical; }

            .helper, .field-error, .preview-meta { font-size: 12px; line-height: 1.6; }

            .helper, .preview-meta { color: var(--ui-body); }

            .field-error { color: #be123c; font-weight: 600; }

            .preview { display: grid; gap: 12px; margin-top: 6px; }

            .preview-frame {
                display: grid;
                gap: 10px;
                padding: 14px;
                border-radius: var(--radius-lg);
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
            }

            .preview img {
                width: 100%;
                max-width: 280px;
                border-radius: 10px;
                object-fit: cover;
                background: var(--slate-100);
                border: 1px solid var(--ui-border);
            }

            .form-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 4px;
            }

            /* ── TOOLBAR ── */
            .toolbar-form {
                padding: 18px 22px;
                border-bottom: 1px solid var(--ui-border);
                background: var(--slate-50);
            }

            .toolbar-grid { display: grid; gap: 10px; }

            .toolbar-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-items: center;
            }

            /* ── PAGINATION ── */
            .pagination-shell {
                padding: 14px 22px 18px;
                border-top: 1px solid var(--ui-border);
                background: var(--slate-50);
            }

            /* ── MISC ── */
            .actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

            .split-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-items: center;
                justify-content: space-between;
            }

            .muted { color: var(--ui-body); font-size: 13px; }
            .muted-note { margin-top: 6px; line-height: 1.5; }

            .meta-line {
                display: flex;
                flex-wrap: wrap;
                gap: 6px 10px;
                align-items: center;
            }

            .room-name { margin: 0 0 3px; font-size: 14px; font-weight: 600; }
            .room-slug { color: var(--ui-body); font-size: 12px; }

            .thumb {
                width: 80px;
                height: 60px;
                border-radius: 8px;
                object-fit: cover;
                background: var(--slate-100);
                display: block;
                border: 1px solid var(--ui-border);
            }

            .thumb-placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 6px;
                color: var(--ui-body);
                font-size: 11px;
                line-height: 1.4;
                text-align: center;
            }

            .tag-list { display: flex; flex-wrap: wrap; gap: 6px; }

            .tag {
                display: inline-flex;
                align-items: center;
                border-radius: var(--radius-pill);
                padding: 5px 10px;
                background: var(--slate-100);
                font-size: 12px;
                font-weight: 600;
                line-height: 1.2;
                color: var(--slate-600);
            }

            .tag-muted { color: var(--ui-body); }

            .section-divider { border-top: 1px solid var(--ui-border); margin: 4px 0; }

            .checkbox-sections { display: grid; gap: 14px; }

            .checkbox-group {
                display: grid;
                gap: 10px;
                padding: 16px;
                border-radius: var(--radius-lg);
                background: var(--slate-50);
                border: 1px solid var(--ui-border);
            }

            .checkbox-group-title { margin: 0; font-size: 14px; font-weight: 600; }

            .checkbox-grid { display: grid; gap: 8px; }

            .checkbox-item {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 10px 12px;
                border-radius: var(--radius-md);
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                transition: border-color 0.15s;
            }

            .checkbox-item:hover { border-color: var(--indigo-200); }
            .checkbox-item input { margin-top: 2px; accent-color: var(--indigo-600); }

            .checkbox-copy { display: grid; gap: 3px; }
            .checkbox-copy strong { font-size: 13px; font-weight: 600; }

            .empty-state {
                padding: 36px 28px;
                background: var(--slate-50);
                border: 1.5px dashed var(--ui-border-mid);
                border-radius: var(--radius-lg);
                text-align: center;
            }

            .empty-state h2 {
                margin: 0 0 8px;
                font-family: 'Sora', sans-serif;
                font-size: 20px;
                font-weight: 700;
            }

            .empty-state p {
                margin: 0 auto;
                max-width: 560px;
                color: var(--ui-body);
                line-height: 1.7;
                font-size: 14px;
            }

            .empty-state-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 18px;
                justify-content: center;
            }

            /* ── FOCUS ── */
            .button:focus-visible,
            .input:focus-visible,
            .select:focus-visible,
            .textarea:focus-visible {
                outline: 2px solid var(--indigo-500);
                outline-offset: 2px;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 900px) {
                .sidebar { display: none; }
                .main-wrap { margin-left: 0; }
                .topbar { padding: 0 18px; }
                .page-shell { padding: 20px 18px 40px; }
            }

            @media (min-width: 901px) {
                .page-header {
                    flex-direction: row;
                    align-items: flex-end;
                    justify-content: space-between;
                }

                .toolbar-grid {
                    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                    align-items: end;
                }

                .grid-two {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
        </style>

        @stack('styles')
    </head>
    <body>
        <div class="app-layout">

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-brand-mark">
                        <svg viewBox="0 0 16 16"><path d="M8 1L14 4V8C14 11.3 11.3 14 8 15C4.7 14 2 11.3 2 8V4L8 1Z"/></svg>
                    </div>
                    <span class="sidebar-brand-name">NATAKOS</span>
                </div>

                <nav class="sidebar-nav">
                    <span class="sidebar-label">Menu</span>

                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"
                       @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a1 1 0 011-1h5a1 1 0 011 1v5a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm8 0a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1h-2a1 1 0 01-1-1v-5zM3 12a1 1 0 011-1h5a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3z"/></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.rooms.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.rooms.*') ? 'is-active' : '' }}"
                       @if(request()->routeIs('admin.rooms.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                        Kamar
                    </a>

                    <a href="{{ route('admin.facilities.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.facilities.*') ? 'is-active' : '' }}"
                       @if(request()->routeIs('admin.facilities.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Fasilitas
                    </a>

                    <a href="{{ route('admin.tenants.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.tenants.*') ? 'is-active' : '' }}"
                       @if(request()->routeIs('admin.tenants.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        Penghuni
                    </a>

                    <a href="{{ route('admin.payments.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}"
                       @if(request()->routeIs('admin.payments.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                        Pembayaran
                    </a>

                    <a href="{{ route('admin.settings.kos-profile.edit') }}"
                       class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}"
                       @if(request()->routeIs('admin.settings.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                        Pengaturan Kos
                    </a>
                </nav>

                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-logout">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h7a1 1 0 000-2H4V5h6a1 1 0 000-2H3zm10.293 4.293a1 1 0 011.414 0L17 9.586l.293.293a1 1 0 010 1.414L14.414 14a1 1 0 01-1.414-1.414L14.586 11H9a1 1 0 010-2h5.586l-1.586-1.586a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ── MAIN WRAP ── --}}
            <div class="main-wrap">

                {{-- TOPBAR --}}
                <div class="topbar">
                    <div class="topbar-breadcrumb">
                        NATAKOS
                        <span>›</span>
                        <strong>@yield('eyebrow', 'Admin')</strong>
                    </div>
                    <div class="topbar-right">
                        <div class="topbar-badge">
                            <span class="topbar-badge-dot"></span>
                            Admin
                        </div>
                    </div>
                </div>

                {{-- PAGE CONTENT --}}
                <main class="page-shell">
                    <section class="page-header">
                        <div>
                            <p class="eyebrow">@yield('eyebrow', 'Admin')</p>
                            <h1 class="page-title">@yield('page_title')</h1>

                            @hasSection('page_description')
                                <p class="page-copy">@yield('page_description')</p>
                            @endif
                        </div>

                        @hasSection('page_actions')
                            <div class="actions">
                                @yield('page_actions')
                            </div>
                        @endif
                    </section>

                    @if (session('success'))
                        <div class="flash flash-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="flash flash-error">{{ session('error') }}</div>
                    @endif

                    @if (isset($errors) && $errors->any())
                        <div class="flash flash-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>