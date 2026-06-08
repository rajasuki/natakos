{{-- Admin visual polish — inject via @stack('styles') in layout --}}
<style>
    /* ── Page entrance ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .page-shell { animation: fadeUp .35s ease both; }

    /* ── Better card depth ── */
    .card {
        transition: box-shadow .25s ease, transform .25s ease;
    }
    .card:hover {
        box-shadow: 0 2px 8px rgba(28,43,34,.06), 0 8px 28px rgba(28,43,34,.08);
    }

    /* ── Metric card glow ── */
    .metric-card {
        transition: box-shadow .2s ease, transform .2s ease;
    }
    .metric-card:hover {
        box-shadow: 0 4px 20px rgba(74,124,89,.12);
        transform: translateY(-2px);
    }

    /* ── Table row micro-interaction ── */
    tbody tr {
        transition: background .15s ease;
    }
    tbody tr:active {
        background: var(--ui-accent-soft);
    }

    /* ── Badge refinements ── */
    .badge {
        transition: box-shadow .15s ease;
        letter-spacing: .02em;
        font-size: 11px;
        padding: 3px 10px;
    }

    /* ── Button polish ── */
    .button-primary {
        box-shadow: 0 2px 8px rgba(74,124,89,.18);
    }
    .button-primary:hover {
        box-shadow: 0 4px 16px rgba(74,124,89,.28);
        transform: translateY(-1px);
    }
    .button-secondary {
        transition: box-shadow .2s ease, transform .2s ease, background .2s ease;
    }
    .button-secondary:hover {
        box-shadow: 0 2px 8px rgba(28,43,34,.06);
        transform: translateY(-1px);
    }

    /* ── Form inputs ── */
    .input, .select, .textarea {
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .input:focus, .select:focus, .textarea:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 3px rgba(74,124,89,.12), 0 1px 4px rgba(28,43,34,.04);
    }

    /* ── Alert boxes ── */
    .alert-box {
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .alert-box:hover {
        transform: translateX(4px);
    }

    /* ── Flash messages ── */
    .flash {
        animation: flashSlide .4s ease both;
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px rgba(28,43,34,.08);
    }
    @keyframes flashSlide {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Pagination ── */
    .pagination-shell nav[role="navigation"] a,
    .pagination-shell nav[role="navigation"] span {
        transition: all .15s ease;
    }
    .pagination-shell nav[role="navigation"] a:hover {
        background: var(--ui-accent-soft);
        border-color: var(--ui-accent);
        color: var(--ui-accent);
    }

    /* ── Empty state ── */
    .empty-state {
        animation: fadeUp .4s ease both;
        transition: border-color .2s ease;
    }
    .empty-state:hover {
        border-color: var(--gray-300);
    }

    /* ── Scrollbar ── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 999px;
    }
    ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

    /* ── Responsive image cards ── */
    .thumb {
        transition: transform .2s ease, box-shadow .2s ease;
    }
    a:hover .thumb {
        transform: scale(1.03);
        box-shadow: 0 4px 16px rgba(28,43,34,.12);
    }

    /* ── Checkbox items ── */
    .checkbox-item {
        transition: all .15s ease;
    }
    .checkbox-item:has(input:checked) {
        border-color: var(--ui-accent);
        background: var(--ui-accent-soft);
    }

    /* ── Page header subtle separation ── */
    .page-header {
        position: relative;
    }
    .page-header::after {
        content: '';
        position: absolute;
        bottom: -14px;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, var(--ui-border) 0%, transparent 100%);
    }

    /* ── Nav dropdown refinement ── */
    .nav-dropdown {
        backdrop-filter: blur(12px);
        background: rgba(255,255,255,.96);
    }
    .dropdown-link {
        border-radius: 8px;
    }

    /* ── Mobile menu polish ── */
    .mobile-menu-panel {
        backdrop-filter: blur(16px);
        background: rgba(251,248,243,.96);
        border-radius: 20px;
    }

    /* ── Stat card numbers ── */
    .payment-stat-value {
        font-feature-settings: 'tnum';
    }
</style>
