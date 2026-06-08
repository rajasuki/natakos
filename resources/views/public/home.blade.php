@extends('public.layout')

@section('title', $profile['name'] . ' | Homepage')

@push('styles')
    <style>
        .hero-section {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
            padding: 48px 32px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
            max-width: 1200px;
            width: 100%;
        }

        .hero-content {
            max-width: 540px;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border: 1px solid var(--ui-border);
            border-radius: 999px;
            background: var(--ui-soft);
            color: var(--ui-body);
            font-size: 13px;
            font-weight: 600;
            align-self: flex-start;
        }

        .hero-badge svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .hero-heading {
            margin: 0;
            font-size: clamp(36px, 5vw, 54px);
            font-weight: 800;
            line-height: 1.12;
            color: var(--ui-ink);
        }

        .hero-desc {
            margin: 0;
            font-size: 16px;
            color: var(--ui-body);
            line-height: 1.7;
            max-width: 480px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 14px 28px;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 600;
            transition: all .2s ease;
            cursor: pointer;
        }

        .hero-btn-primary {
            background: var(--ui-accent);
            color: #fff;
            border: 0;
            box-shadow: 0 0 24px rgba(74, 124, 89, .35);
        }

        .hero-btn-primary:hover {
            background: var(--ui-accent-hover);
            box-shadow: 0 0 36px rgba(74, 124, 89, .5);
        }

        .hero-btn-secondary {
            background: transparent;
            color: var(--ui-ink);
            border: 1.5px solid var(--ui-border);
            box-shadow: 0 0 20px rgba(74, 124, 89, .12);
        }

        .hero-btn-secondary:hover {
            background: var(--ui-soft);
            border-color: var(--ui-body);
            box-shadow: 0 0 28px rgba(74, 124, 89, .28);
        }

        @media (max-width: 767px) {
            .hero-section { min-height: auto; padding: 40px 16px; }
            .hero-grid { grid-template-columns: 1fr; gap: 32px; }
            .hero-content { max-width: 100%; }
            .hero-heading { font-size: clamp(28px, 8vw, 36px); }
        }

        /* ── Hero visual (kanan) ── */
        .hero-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 420px;
        }

        .hero-visual-frame {
            position: relative;
            width: 100%;
            max-width: 480px;
            aspect-ratio: 4 / 5;
            border-radius: 24px;
            background: linear-gradient(145deg, var(--ui-soft) 0%, var(--ui-softer) 100%);
            border: 1px solid var(--ui-border);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-visual-frame::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 30% 20%, rgba(74,124,89,.15) 0%, transparent 60%),
                radial-gradient(ellipse 50% 60% at 70% 80%, rgba(74,124,89,.1) 0%, transparent 60%);
        }

        .hero-visual-shapes {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 24px;
            width: 100%;
        }

        .hero-shape-card {
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 16px;
            padding: 20px 16px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(28,43,34,.06);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .hero-shape-card:nth-child(1) { transform: translateY(-8px); }
        .hero-shape-card:nth-child(3) { transform: translateY(-8px); }

        .hero-shape-card .icon-wrap {
            width: 44px;
            height: 44px;
            margin: 0 auto 8px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-shape-card .icon-wrap .material-symbols-outlined {
            font-size: 24px;
            font-variation-settings: 'FILL' 1;
        }

        .hero-shape-card .icon-green {
            background: var(--ui-soft);
            color: var(--ui-accent);
        }

        .hero-shape-card .icon-warm {
            background: #fef3c7;
            color: #b45309;
        }

        .hero-shape-card .icon-sky {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .hero-shape-card .icon-rose {
            background: #fce7f3;
            color: #be185d;
        }

        .hero-shape-card .label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--ui-ink);
            margin-bottom: 2px;
        }

        .hero-shape-card .sublabel {
            display: block;
            font-size: 11px;
            color: var(--ui-body);
        }

        .hero-visual-floating-icons {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 2;
        }

        .hero-visual-floating-icons .float-icon {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--ui-border);
            box-shadow: var(--ui-shadow);
            animation: heroIconFloat 4s ease-in-out infinite;
        }

        .hero-visual-floating-icons .float-icon .material-symbols-outlined {
            font-size: 20px;
            font-variation-settings: 'FILL' 1;
            color: var(--ui-accent);
        }

        .hero-visual-floating-icons .float-icon:nth-child(1) { top: 4%; left: -8%; animation-delay: 0s; }
        .hero-visual-floating-icons .float-icon:nth-child(2) { top: 20%; right: -10%; animation-delay: .8s; }
        .hero-visual-floating-icons .float-icon:nth-child(3) { bottom: 18%; left: -6%; animation-delay: 1.6s; }
        .hero-visual-floating-icons .float-icon:nth-child(4) { bottom: 4%; right: -4%; animation-delay: 2.4s; }

        @keyframes heroIconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @media (max-width: 767px) {
            .hero-visual { min-height: 320px; }
            .hero-visual-frame { max-width: 100%; aspect-ratio: auto; min-height: 300px; }
            .hero-visual-floating-icons { display: none; }
            .hero-shape-card { padding: 14px 10px; }
            .hero-shape-card:nth-child(1),
            .hero-shape-card:nth-child(3) { transform: none; }
        }

        .section-dark {
            background: var(--ui-canvas) !important;
            color: var(--ui-ink);
        }

        .section-dark .eyebrow,
        .section-dark .section-copy-on-dark {
            color: var(--ui-body) !important;
        }

        .contact-band {
            background: var(--ui-soft) !important;
            color: var(--ui-ink);
        }

        .contact-band .eyebrow {
            color: var(--ui-body) !important;
        }

        /* ── Floating testimonial cards ── */
        .hero-floating {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .float-card {
            position: absolute;
            top: 50%;
            left: 50%;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 999px;
            box-shadow: var(--ui-shadow);
            opacity: 0;
            white-space: nowrap;
            font-size: 13px;
            animation: floatCard 18s ease-in-out var(--delay) infinite;
            transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(.88);
        }

        .float-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            flex-shrink: 0;
            color: #fff;
        }

        .float-card:nth-child(1) .float-avatar { background: var(--ui-accent); }
        .float-card:nth-child(2) .float-avatar { background: var(--ui-ink); }
        .float-card:nth-child(3) .float-avatar { background: #C8D8C9; color: var(--ui-ink); }
        .float-card:nth-child(4) .float-avatar { background: var(--ui-accent); }
        .float-card:nth-child(5) .float-avatar { background: var(--ui-ink); }
        .float-card:nth-child(6) .float-avatar { background: #C8D8C9; color: var(--ui-ink); }

        .float-text {
            color: var(--ui-ink);
            font-weight: 500;
        }

        @keyframes floatCard {
            0%, 3%   { opacity: 0; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(.88); }
            6%       { opacity: 1; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(1.05); }
            9%, 20%  { opacity: 1; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(1); }
            23%, 25% { opacity: 0; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y) - 12px)) scale(.88); }
            100%     { opacity: 0; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y) - 12px)) scale(.88); }
        }

        @media (max-width: 1023px) {
            .hero-floating {
                display: none;
            }
        }

        /* ── Background décor ── */
        body {
            background-image:
                radial-gradient(ellipse 80% 50% at 0% 20%, rgba(74,124,89,.08) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 100% 60%, rgba(74,124,89,.06) 0%, transparent 70%),
                radial-gradient(ellipse 50% 30% at 50% 0%, rgba(74,124,89,.04) 0%, transparent 60%);
        }

        .hero-bg-decor {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .hero-bg-decor .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .45;
        }

        .hero-bg-decor .blob-1 {
            width: 500px;
            height: 500px;
            background: rgba(74,124,89,.18);
            top: -100px;
            left: -120px;
        }

        .hero-bg-decor .blob-2 {
            width: 400px;
            height: 400px;
            background: rgba(74,124,89,.12);
            bottom: -80px;
            right: -100px;
        }

        .hero-bg-decor .blob-3 {
            width: 300px;
            height: 300px;
            background: rgba(74,124,89,.08);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @media (max-width: 767px) {
            .hero-bg-decor .blob-1 { width: 300px; height: 300px; top: -60px; left: -80px; }
            .hero-bg-decor .blob-2 { width: 250px; height: 250px; }
            .hero-bg-decor .blob-3 { display: none; }
        }

        /* ── Subtle dot pattern overlay ── */
        .page-stack {
            position: relative;
        }

        .page-stack::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            background-image: radial-gradient(circle, var(--ui-border) .8px, transparent .8px);
            background-size: 32px 32px;
            opacity: .4;
        }

        /* ── Map section redesign ──────────────── */
        .lokasi-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            align-items: stretch;
        }

        @media (min-width: 1024px) {
            .lokasi-grid {
                grid-template-columns: 8fr 4fr;
            }
        }

        .lokasi-card {
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ── Map card ── */
        .lokasi-map-frame {
            width: 100%;
            min-height: 400px;
            background: var(--ui-soft);
            position: relative;
            overflow: hidden;
        }

        .lokasi-map-frame iframe,
        .lokasi-map-frame .map-placeholder {
            width: 100%;
            height: 100%;
            min-height: 400px;
            border: 0;
            display: block;
        }

        .lokasi-map-frame .map-placeholder {
            display: grid;
            align-items: center;
            justify-items: center;
            padding: 32px;
            text-align: center;
        }

        .lokasi-map-actions {
            position: absolute;
            bottom: 16px;
            right: 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .lokasi-map-actions button {
            width: 40px;
            height: 40px;
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -2px rgba(0,0,0,.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--ui-ink);
            transition: background .15s;
        }

        .lokasi-map-actions button:hover {
            background: var(--ui-soft);
        }

        .lokasi-map-actions button .material-symbols-outlined {
            font-size: 20px;
        }

        .lokasi-map-info {
            padding: 24px;
            border-top: 1px solid var(--ui-border);
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .lokasi-map-pin {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--ui-accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }

        .lokasi-map-pin .material-symbols-outlined {
            font-size: 22px;
            font-variation-settings: 'FILL' 1;
        }

        .lokasi-map-address h3 {
            margin: 0 0 4px;
            font-size: 16px;
            font-weight: 500;
            color: var(--ui-ink);
        }

        .lokasi-map-address p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
            color: var(--ui-body);
        }

        .lokasi-map-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .lokasi-map-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            background: var(--ui-soft);
            color: var(--ui-body);
        }

        /* ── Nearby card ── */
        .lokasi-nearby-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: var(--ui-soft);
            border-bottom: 1px solid var(--ui-border);
        }

        .lokasi-nearby-header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            color: var(--ui-ink);
        }

        .lokasi-nearby-header .material-symbols-outlined {
            font-size: 20px;
            color: var(--ui-body);
        }

        .lokasi-nearby-list {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .lokasi-nearby-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border-radius: 6px;
            background: var(--ui-softer);
            transition: background .15s, border-color .15s;
            border: 1px solid transparent;
        }

        .lokasi-nearby-item:hover {
            background: #e6e8ea;
            border-color: var(--ui-border);
        }

        .lokasi-nearby-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid var(--ui-border);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lokasi-nearby-icon .material-symbols-outlined {
            font-size: 24px;
        }

        .lokasi-nearby-icon.icon-primary .material-symbols-outlined { color: var(--ui-accent); }
        .lokasi-nearby-icon.icon-tertiary .material-symbols-outlined { color: #4A7C59; }
        .lokasi-nearby-icon.icon-error .material-symbols-outlined { color: #dc2626; }
        .lokasi-nearby-icon.icon-warning .material-symbols-outlined { color: #d97706; }
        .lokasi-nearby-icon.icon-secondary .material-symbols-outlined { color: var(--ui-body); }

        .lokasi-nearby-info {
            flex: 1;
            min-width: 0;
        }

        .lokasi-nearby-name {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--ui-ink);
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .lokasi-nearby-category {
            display: block;
            font-size: 12px;
            color: var(--ui-body);
        }

        .lokasi-nearby-distance {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
            text-align: right;
        }

        .lokasi-nearby-distance .material-symbols-outlined {
            font-size: 16px;
            color: #059669;
        }

        .lokasi-nearby-distance span {
            font-size: 12px;
            font-weight: 500;
            color: #059669;
        }

        .lokasi-nearby-empty {
            padding: 32px;
            text-align: center;
            color: var(--ui-body);
            font-size: 13px;
        }

        /* ── Fasilitas cards ──────────────────────── */
        .fasilitas-section {
            padding: 48px 16px;
            max-width: 1440px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .fasilitas-section { padding: 48px 24px; }
        }

        .fasilitas-header {
            max-width: 896px;
            margin: 0 auto 32px;
            text-align: center;
        }

        .fasilitas-header h2 {
            margin: 0 0 8px;
            font-size: 28px;
            font-weight: 400;
            color: var(--ui-ink);
        }

        .fasilitas-header p {
            margin: 0;
            font-size: 16px;
            color: var(--ui-body);
            line-height: 1.6;
        }

        .fasilitas-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            max-width: 1024px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .fasilitas-grid { grid-template-columns: 1fr 1fr; }
        }

        .fasilitas-card {
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
            transition: box-shadow .2s;
        }

        .fasilitas-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
        }

        .fasilitas-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--ui-border);
        }

        .fasilitas-card-header .material-symbols-outlined {
            color: var(--ui-accent);
            font-size: 24px;
            font-variation-settings: 'FILL' 1;
        }

        .fasilitas-card-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 500;
            color: var(--ui-ink);
        }

        .fasilitas-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 640px) {
            .fasilitas-list { grid-template-columns: 1fr 1fr; }
        }

        .fasilitas-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--ui-body);
            font-size: 14px;
        }

        .fasilitas-item .material-symbols-outlined {
            color: var(--ui-accent);
            opacity: .8;
            font-size: 20px;
        }

        /* ── Kontak CTA ──────────────────────────── */
        .kontak-cta {
            background: var(--ui-ink);
            padding: 48px 16px;
            margin-top: 32px;
        }

        @media (min-width: 768px) {
            .kontak-cta { padding: 48px 24px; }
        }

        .kontak-cta-inner {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 32px;
        }

        @media (min-width: 768px) {
            .kontak-cta-inner { flex-direction: row; }
        }

        .kontak-cta-text {
            text-align: center;
            flex: 1;
        }

        @media (min-width: 768px) {
            .kontak-cta-text { text-align: left; }
        }

        .kontak-cta-text h2 {
            margin: 0 0 8px;
            font-size: 28px;
            font-weight: 400;
            color: #fff;
            line-height: 1.3;
        }

        .kontak-cta-text p {
            margin: 0;
            font-size: 16px;
            color: rgba(255,255,255,.8);
            line-height: 1.6;
            max-width: 540px;
        }

        .kontak-cta-actions {
            display: flex;
            flex-direction: column;
            gap: 16px;
            flex-shrink: 0;
            width: 100%;
        }

        @media (min-width: 640px) {
            .kontak-cta-actions { flex-direction: row; width: auto; }
        }

        .kontak-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 16px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all .15s;
            cursor: pointer;
            white-space: nowrap;
        }

        .kontak-btn-primary {
            background: var(--ui-accent);
            color: #fff;
            border: 0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
        }

        .kontak-btn-primary:hover {
            background: var(--ui-accent-hover);
        }

        .kontak-btn-outline {
            background: transparent;
            color: rgba(255,255,255,.8);
            border: 2px solid rgba(255,255,255,.3);
        }

        .kontak-btn-outline:hover {
            background: rgba(255,255,255,.1);
            border-color: rgba(255,255,255,.5);
        }

        .kontak-btn .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }

        /* ── Kamar section ──────────────────────── */
        .kamar-section {
            padding: 48px 16px;
            max-width: 1440px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .kamar-section { padding: 48px 24px; }
        }

        .kamar-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-end;
            gap: 24px;
            margin-bottom: 32px;
        }

        .kamar-header-left {
            max-width: 640px;
        }

        .kamar-eyebrow {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--ui-accent);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }

        .kamar-title {
            margin: 0 0 8px;
            font-size: 32px;
            font-weight: 400;
            color: var(--ui-ink);
        }

        .kamar-desc {
            margin: 0;
            font-size: 16px;
            line-height: 1.6;
            color: var(--ui-body);
        }

        .kamar-header-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: var(--ui-accent);
            border: 0;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
            flex-shrink: 0;
        }

        .kamar-header-btn:hover {
            background: var(--ui-accent-hover);
        }

        .kamar-header-btn .material-symbols-outlined {
            font-size: 18px;
        }

        .kamar-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .kamar-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (min-width: 1024px) {
            .kamar-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .kamar-card {
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .3s;
        }

        .kamar-card:hover {
            box-shadow: 0 4px 20px rgba(26,28,30,.08);
        }

        .kamar-card-img-wrap {
            position: relative;
            height: 192px;
            overflow: hidden;
            background: #e1e3e6;
        }

        .kamar-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s;
        }

        .kamar-card:hover .kamar-card-img {
            transform: scale(1.05);
        }

        .kamar-card-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: var(--ui-body);
            background: var(--ui-soft);
        }

        .kamar-card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.12);
            mix-blend-mode: multiply;
        }

        .kamar-card-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            display: inline-flex;
            align-items: center;
            gap: 2px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }

        .kamar-card-badge .material-symbols-outlined {
            font-size: 14px;
        }

        .kamar-card-badge-success {
            background: #d1fae5;
            color: #064e3b;
        }

        .kamar-card-badge-warning {
            background: #fef3c7;
            color: #78350f;
        }

        .kamar-card-badge-default {
            background: #eceef0;
            color: var(--ui-body);
            border: 1px solid var(--ui-border);
        }

        .kamar-card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .kamar-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .kamar-card-name {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            color: var(--ui-ink);
            line-height: 1.27;
        }

        .kamar-card-name-muted {
            color: var(--ui-body);
        }

        .kamar-card-price {
            text-align: right;
        }

        .kamar-card-price-value {
            display: block;
            font-size: 16px;
            font-weight: 700;
            color: var(--ui-accent);
            white-space: nowrap;
        }

        .kamar-card-price-period {
            font-size: 12px;
            font-weight: 400;
            color: var(--ui-body);
        }

        .kamar-card-desc {
            margin: 0 0 16px;
            font-size: 14px;
            line-height: 1.43;
            color: var(--ui-body);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .kamar-card-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: auto;
            margin-bottom: 16px;
        }

        .kamar-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            color: var(--ui-body);
            background: var(--ui-soft);
            border: 1px solid var(--ui-border);
        }

        .kamar-chip .material-symbols-outlined {
            font-size: 14px;
        }

        .kamar-chip-muted {
            opacity: .8;
            background: #fff;
        }

        .kamar-card-footer {
            border-top: 1px solid var(--ui-border);
            padding-top: 16px;
            margin-top: 8px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .kamar-btn-detail {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            width: 100%;
            padding: 8px 16px;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: var(--ui-accent);
            background: var(--ui-soft);
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }

        .kamar-btn-detail:hover {
            background: #eceef0;
        }

        .kamar-btn-disabled {
            background: #eceef0;
            color: var(--ui-body);
            border: 1px solid var(--ui-border);
            cursor: not-allowed;
            pointer-events: none;
        }

        .kamar-card-footer-note {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: #73777f;
            text-align: center;
        }

        .kamar-card-footer-note-invisible {
            visibility: hidden;
        }

        /* ── Kamar empty state ──────────────────── */
        .kamar-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px;
            text-align: center;
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            margin-top: 32px;
        }

        .kamar-empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--ui-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .kamar-empty-icon .material-symbols-outlined {
            font-size: 48px;
            color: var(--ui-body);
        }

        .kamar-empty h3 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 400;
            color: var(--ui-ink);
        }

        .kamar-empty p {
            margin: 0 0 24px;
            font-size: 16px;
            color: var(--ui-body);
            line-height: 1.6;
            max-width: 480px;
        }

        .kamar-empty-actions {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        @media (min-width: 640px) {
            .kamar-empty-actions { flex-direction: row; }
        }

        .kamar-empty-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all .15s;
        }

        .kamar-empty-btn-primary {
            background: var(--ui-accent);
            color: #fff;
            border: 0;
        }

        .kamar-empty-btn-primary:hover {
            background: var(--ui-accent-hover);
        }

        .kamar-empty-btn-secondary {
            background: var(--ui-soft);
            color: var(--ui-body);
            border: 1px solid var(--ui-border);
        }

        .kamar-empty-btn-secondary:hover {
            background: #d4e3d6;
        }
    </style>
@endpush

@section('content')
    <div class="page-stack">

        <section class="hero-section">
            <div class="hero-bg-decor" aria-hidden="true">
                <div class="blob blob-1"></div>
                <div class="blob blob-2"></div>
                <div class="blob blob-3"></div>
            </div>

            <div class="hero-grid">
                <div class="hero-content">
                    <span class="hero-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Hunian terkelola
                    </span>

                    <h1 class="hero-heading">
                        Tinggal lebih tenang<br>dengan kamar yang rapi
                    </h1>

                    <p class="hero-desc">{{ $profile['description'] }}</p>

                    <div class="hero-actions">
                        <a href="{{ route('rooms.index') }}" class="hero-btn hero-btn-primary">Lihat kamar</a>
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="hero-btn hero-btn-secondary">Tanya via WhatsApp</a>
                    </div>
                </div>

                <div class="hero-visual" aria-hidden="true">
                    <div class="hero-visual-frame">
                        <div class="hero-visual-shapes">
                            <div class="hero-shape-card">
                                <div class="icon-wrap icon-green"><span class="material-symbols-outlined">bed</span></div>
                                <span class="label">Kamar Bersih</span>
                                <span class="sublabel">Furniture lengkap</span>
                            </div>
                            <div class="hero-shape-card">
                                <div class="icon-wrap icon-warm"><span class="material-symbols-outlined">shield</span></div>
                                <span class="label">Aman & Nyaman</span>
                                <span class="sublabel">24 jam</span>
                            </div>
                            <div class="hero-shape-card">
                                <div class="icon-wrap icon-sky"><span class="material-symbols-outlined">wifi</span></div>
                                <span class="label">WiFi Cepat</span>
                                <span class="sublabel">Unlimited</span>
                            </div>
                            <div class="hero-shape-card">
                                <div class="icon-wrap icon-rose"><span class="material-symbols-outlined">local_laundry_service</span></div>
                                <span class="label">Laundry</span>
                                <span class="sublabel">Ada di lokasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="hero-visual-floating-icons">
                        <span class="float-icon"><span class="material-symbols-outlined">ac_unit</span></span>
                        <span class="float-icon"><span class="material-symbols-outlined">tv</span></span>
                        <span class="float-icon"><span class="material-symbols-outlined">kitchen</span></span>
                        <span class="float-icon"><span class="material-symbols-outlined">park</span></span>
                    </div>
                </div>

                <div class="hero-floating" aria-hidden="true">
                    <div class="float-card" style="--delay: 0s; --x: -600px; --y: -80px;">
                        <span class="float-avatar">A</span>
                        <span class="float-text">"Kosnya nyaman banget!"</span>
                    </div>
                    <div class="float-card" style="--delay: 3s; --x: 600px; --y: -40px;">
                        <span class="float-avatar">R</span>
                        <span class="float-text">"Lokasi strategis 👍"</span>
                    </div>
                    <div class="float-card" style="--delay: 6s; --x: -680px; --y: 60px;">
                        <span class="float-avatar">D</span>
                        <span class="float-text">"Kamarnya bersih rapi"</span>
                    </div>
                    <div class="float-card" style="--delay: 9s; --x: 680px; --y: 80px;">
                        <span class="float-avatar">S</span>
                        <span class="float-text">"Murah meriah!"</span>
                    </div>
                    <div class="float-card" style="--delay: 12s; --x: -540px; --y: 140px;">
                        <span class="float-avatar">F</span>
                        <span class="float-text">"Suasananya adem"</span>
                    </div>
                    <div class="float-card" style="--delay: 15s; --x: 540px; --y: 130px;">
                        <span class="float-avatar">T</span>
                        <span class="float-text">"Fasilitas lengkap"</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="page-section" id="lokasi">
            <div class="site-shell">

                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Lokasi</p>
                        <h2 class="section-title">Lokasi & sekitar kos</h2>
                        <p class="section-copy">
                            Calon penghuni bisa langsung lihat posisi kos dan gambaran
                            akses ke tempat yang sering dicari.
                        </p>
                    </div>

                    <div class="section-actions">
                        @if ($profile['google_maps_url'])
                            <a href="{{ $profile['google_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">
                                Buka di Google Maps
                            </a>
                        @endif
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-primary">
                            Tanya lokasi
                        </a>
                    </div>
                </div>

                <div class="lokasi-grid">

                    {{-- ── Map Card ── --}}
                    <div class="lokasi-card">
                        <div class="lokasi-map-frame">
                            @if ($profile['google_maps_embed_url'])
                                <iframe
                                    src="{{ $profile['google_maps_embed_url'] }}"
                                    loading="lazy"
                                    allowfullscreen
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Lokasi {{ $profile['name'] }} di Google Maps"
                                ></iframe>
                            @else
                                <div class="map-placeholder">
                                    <div>
                                        <p class="eyebrow">Google Maps</p>
                                        <h3 class="room-title">Map embed belum diatur</h3>
                                        <p class="room-copy">
                                            Tambahkan Google Maps Embed URL lewat file
                                            supaya peta tampil langsung di halaman ini.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="lokasi-map-actions">
                                <button type="button" aria-label="Perbesar peta">
                                    <span class="material-symbols-outlined">add</span>
                                </button>
                                <button type="button" aria-label="Perkecil peta">
                                    <span class="material-symbols-outlined">remove</span>
                                </button>
                            </div>
                        </div>

                        <div class="lokasi-map-info">
                            <div class="lokasi-map-pin">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div class="lokasi-map-address">
                                <h3>{{ $profile['name'] }}</h3>
                                <p>{{ $profile['address'] }}</p>
                                <div class="lokasi-map-tags">
                                    <span class="lokasi-map-tag">Pusat Kota</span>
                                    <span class="lokasi-map-tag">Akses Mudah</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Nearby Places Card ── --}}
                    <div class="lokasi-card">
                        <div class="lokasi-nearby-header">
                            <h3>Fasilitas Terdekat</h3>
                            <span class="material-symbols-outlined">explore</span>
                        </div>

                        <div class="lokasi-nearby-list">
                            @php
                                $categories = [
                                    'Institut Teknologi Nasional' => ['icon' => 'school', 'class' => 'icon-primary', 'cat' => 'Universitas'],
                                    'Masjid Al-Muawanah' => ['icon' => 'mosque', 'class' => 'icon-tertiary', 'cat' => 'Tempat Ibadah'],
                                    'Rumah Sakit Kartini Bandung' => ['icon' => 'local_hospital', 'class' => 'icon-error', 'cat' => 'Kesehatan'],
                                    'Gedung Sate' => ['icon' => 'account_balance', 'class' => 'icon-warning', 'cat' => 'Wisata & Budaya'],
                                    'Bandung Electronics Center' => ['icon' => 'shopping_bag', 'class' => 'icon-secondary', 'cat' => 'Perbelanjaan'],
                                ];
                            @endphp

                            @forelse ($profile['nearby_places'] as $place)
                                @php
                                    $meta = $categories[$place['name']] ?? ['icon' => 'place', 'class' => 'icon-primary', 'cat' => 'Lokasi'];
                                    $distanceIcon = ($place['travel_mode'] ?? '') === 'motorcycle' ? 'two_wheeler' : 'directions_walk';
                                    $distanceLabel = $place['estimate_label'] ?? '';
                                @endphp
                                <div class="lokasi-nearby-item">
                                    <div class="lokasi-nearby-icon {{ $meta['class'] }}">
                                        <span class="material-symbols-outlined">{{ $meta['icon'] }}</span>
                                    </div>
                                    <div class="lokasi-nearby-info">
                                        <span class="lokasi-nearby-name">{{ $place['name'] }}</span>
                                        <span class="lokasi-nearby-category">{{ $meta['cat'] }}</span>
                                    </div>
                                    <div class="lokasi-nearby-distance">
                                        <span class="material-symbols-outlined">{{ $distanceIcon }}</span>
                                        <span>{{ $distanceLabel ? explode(' ', $distanceLabel)[0] . ' min' : '' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="lokasi-nearby-empty">
                                    Belum ada daftar tempat sekitar yang ditampilkan.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ── Kamar ──────────────────────────────── --}}
        <section id="kamar" class="kamar-section">
            <div class="kamar-header">
                <div class="kamar-header-left">
                    <span class="kamar-eyebrow">Kamar pilihan</span>
                    <h2 class="kamar-title">Beberapa kamar tersedia</h2>
                    <p class="kamar-desc">Silakan cek detail kamar yang saat ini kosong dan siap untuk dihuni. Hubungi pemilik untuk informasi lebih lanjut mengenai pemesanan.</p>
                </div>
                <a href="{{ route('rooms.index') }}" class="kamar-header-btn">
                    <span>Lihat semua kamar</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>

            @if ($featuredRooms->isEmpty())
                <div class="kamar-empty">
                    <div class="kamar-empty-icon">
                        <span class="material-symbols-outlined">bed</span>
                    </div>
                    <h3>Belum ada kamar tersedia</h3>
                    <p>Saat ini semua kamar dalam daftar pilihan sedang terisi. Silakan hubungi pengelola untuk masuk ke daftar tunggu.</p>
                    <div class="kamar-empty-actions">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="kamar-empty-btn kamar-empty-btn-primary">
                            <span class="material-symbols-outlined">chat</span>
                            Tanya via WhatsApp
                        </a>
                        <a href="{{ route('rooms.index') }}" class="kamar-empty-btn kamar-empty-btn-secondary">
                            Lihat semua kamar
                        </a>
                    </div>
                </div>
            @else
                <div class="kamar-grid">
                    @foreach ($featuredRooms as $room)
                        @php
                            $coverPath = $room->main_image ?: $room->images->first()?->image_path;

                            $badgeClass = match ($room->status) {
                                'available' => 'kamar-card-badge-success',
                                'maintenance' => 'kamar-card-badge-warning',
                                default => 'kamar-card-badge-default',
                            };

                            $badgeIcon = match ($room->status) {
                                'available' => 'check_circle',
                                'maintenance' => 'build',
                                default => 'block',
                            };

                            $isOccupied = $room->status === 'occupied';
                        @endphp

                        <article class="kamar-card">
                            <div class="kamar-card-img-wrap">
                                @if ($coverPath)
                                    <img
                                        src="{{ asset('storage/' . $coverPath) }}"
                                        alt="{{ $room->name }}"
                                        class="kamar-card-img"
                                        @if ($isOccupied) style="filter: grayscale(30%)" @endif
                                    >
                                @else
                                    <div class="kamar-card-img-placeholder">Foto kamar belum tersedia</div>
                                @endif

                                @if ($isOccupied)
                                    <div class="kamar-card-overlay"></div>
                                @endif

                                <span class="kamar-card-badge {{ $badgeClass }}">
                                    <span class="material-symbols-outlined">{{ $badgeIcon }}</span>
                                    {{ $roomStatusLabels[$room->status] ?? $room->status }}
                                </span>
                            </div>

                            <div class="kamar-card-body">
                                <div class="kamar-card-top">
                                    <h3 class="kamar-card-name @if ($isOccupied) kamar-card-name-muted @endif">{{ $room->name }}</h3>
                                    <div class="kamar-card-price">
                                        <span class="kamar-card-price-value @if ($isOccupied) kamar-card-name-muted @endif">
                                            {{ \App\Support\UiFormatter::currency($room->price) }}
                                        </span>
                                        <span class="kamar-card-price-period">/bln</span>
                                    </div>
                                </div>

                                <p class="kamar-card-desc">{{ $room->description ?: 'Kamar ini sudah tercatat di IchiKOS dan siap Anda cek lebih lanjut melalui halaman detail.' }}</p>

                                <div class="kamar-card-chips">
                                    <span class="kamar-chip @if ($isOccupied) kamar-chip-muted @endif">
                                        <span class="material-symbols-outlined">straighten</span>
                                        Ukuran {{ $room->size ?: '-' }}
                                    </span>
                                    <span class="kamar-chip @if ($isOccupied) kamar-chip-muted @endif">
                                        <span class="material-symbols-outlined">stairs</span>
                                        Lt. {{ $room->floor ?: '-' }}
                                    </span>
                                    @foreach ($room->facilities->take(3) as $facility)
                                        <span class="kamar-chip @if ($isOccupied) kamar-chip-muted @endif">
                                            <span class="material-symbols-outlined">{{ \App\Support\FacilityIcon::resolve($facility) }}</span>
                                            {{ $facility->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="kamar-card-footer">
                                    @if ($isOccupied)
                                        <span class="kamar-btn-detail kamar-btn-disabled">
                                            Kamar Penuh
                                        </span>
                                        <span class="kamar-card-footer-note kamar-card-footer-note-invisible">Cek detail sebelum menghubungi pemilik</span>
                                    @else
                                        <a href="{{ route('rooms.show', $room) }}" class="kamar-btn-detail">
                                            Lihat detail kamar
                                        </a>
                                        <span class="kamar-card-footer-note">Cek detail sebelum menghubungi pemilik</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- ── Fasilitas ──────────────────────────────── --}}
        <section id="fasilitas" class="fasilitas-section">
            <div class="fasilitas-header">
                <h2>Fasilitas yang membuat tinggal lebih nyaman</h2>
                <p>IchiKOS mengelola kombinasi fasilitas kamar dan fasilitas umum agar kebutuhan harian penghuni tetap praktis.</p>
            </div>

            <div class="fasilitas-grid">
                @foreach ($facilityTypeLabels as $type => $label)
                    <div class="fasilitas-card">
                        <div class="fasilitas-card-header">
                            <span class="material-symbols-outlined">{{ $type === 'room' ? 'bed' : 'meeting_room' }}</span>
                            <h3>{{ $type === 'room' ? 'Di Dalam Kamar' : 'Area Bersama' }}</h3>
                        </div>

                        <div class="fasilitas-list">
                            @forelse (($facilityGroups[$type] ?? collect()) as $facility)
                                <div class="fasilitas-item">
                                    <span class="material-symbols-outlined">{{ \App\Support\FacilityIcon::resolve($facility) }}</span>
                                    {{ $facility->name }}
                                </div>
                            @empty
                                <div class="fasilitas-item">
                                    <span class="material-symbols-outlined">info</span>
                                    Belum ada data fasilitas.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div>

    {{-- ── Kontak CTA ──────────────────────────────── --}}
    <section class="kontak-cta" id="kontak">
        <div class="kontak-cta-inner">
            <div class="kontak-cta-text">
                <h2>Butuh informasi kamar lebih cepat?</h2>
                <p>Hubungi pengelola langsung melalui WhatsApp untuk menanyakan detail kamar, fasilitas, dan ketersediaan terbaru.</p>
            </div>
            <div class="kontak-cta-actions">
                <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="kontak-btn kontak-btn-primary">
                    <span class="material-symbols-outlined">chat</span>
                    Hubungi via WhatsApp
                </a>
                <a href="{{ route('rooms.index') }}" class="kontak-btn kontak-btn-outline">
                    Lihat Semua Kamar
                </a>
            </div>
        </div>
    </section>
@endsection


