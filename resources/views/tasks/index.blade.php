<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMind – Pengingat Tugas</title>
    <meta name="description" content="Kelola tugas dan deadline sekolahmu dengan mudah dan terorganisir bersama TaskMind.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#7c3aed">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-primary: #0a0a0f;
            --bg-card: #16161f;
            --bg-card-hover: #1c1c28;
            --border: rgba(255, 255, 255, 0.07);
            --text-primary: #f1f0ff;
            --text-secondary: #8b8aa8;
            --text-muted: #5a5970;
            --accent: #7c3aed;
            --accent-light: #9d5ff5;
            --accent-glow: rgba(124, 58, 237, 0.3);
            --success: #10d98a;
            --success-bg: rgba(16, 217, 138, 0.1);
            --warning: #f59e0b;
            --warning-bg: rgba(245, 158, 11, 0.1);
            --danger: #f43f5e;
            --danger-bg: rgba(244, 63, 94, 0.1);
            --info: #38bdf8;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --shadow-card: 0 4px 24px rgba(0,0,0,0.4);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.35;
        }
        .orb-1 {
            width: 500px; height: 500px;
            top: -200px; left: -100px;
            background: radial-gradient(circle, rgba(124,58,237,0.5), transparent 70%);
            animation: float 12s ease-in-out infinite;
        }
        .orb-2 {
            width: 400px; height: 400px;
            bottom: -100px; right: -80px;
            background: radial-gradient(circle, rgba(56,189,248,0.4), transparent 70%);
            animation: float 15s ease-in-out infinite reverse;
        }
        .orb-3 {
            width: 300px; height: 300px;
            top: 40%; left: 50%;
            background: radial-gradient(circle, rgba(16,217,138,0.2), transparent 70%);
            animation: float 10s ease-in-out infinite 3s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0,0) scale(1); }
            33% { transform: translate(20px,-30px) scale(1.05); }
            66% { transform: translate(-15px,20px) scale(0.95); }
        }

        .wrapper {
            position: relative; z-index: 1;
            max-width: 1200px; margin: 0 auto;
            padding: 24px 20px 60px;
        }

        /* Header */
        .header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 36px; padding-bottom: 22px;
            border-bottom: 1px solid var(--border);
        }
        .header-brand { display: flex; align-items: center; gap: 12px; }
        .header-logo {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--accent), var(--info));
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            box-shadow: 0 0 20px var(--accent-glow);
        }
        .header-title {
            font-size: 1.35rem; font-weight: 800; letter-spacing: -0.03em;
            background: linear-gradient(135deg, var(--text-primary) 40%, var(--accent-light));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .header-subtitle { font-size: 0.75rem; color: var(--text-muted); }
        .header-right { display: flex; align-items: center; gap: 10px; }
        .header-date {
            font-size: 0.8rem; color: var(--text-muted);
            background: var(--bg-card); border: 1px solid var(--border);
            padding: 6px 14px; border-radius: 100px;
        }
        .header-notif-btn {
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .header-notif-btn:hover {
            border-color: var(--accent);
            color: var(--text-primary);
        }
        .header-notif-btn.is-active {
            border-color: rgba(16, 217, 138, 0.4);
            color: var(--success);
            background: rgba(16, 217, 138, 0.08);
        }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 30px; }
        .stat-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: all 0.25s ease;
        }
        .stat-card:hover { border-color: rgba(255,255,255,0.12); transform: translateY(-2px); box-shadow: var(--shadow-card); }
        .stat-icon {
            width: 44px; height: 44px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg { width: 20px; height: 20px; stroke-width: 2; }
        .stat-icon.purple { background: rgba(124,58,237,0.15); }
        .stat-icon.green  { background: var(--success-bg); }
        .stat-icon.yellow { background: var(--warning-bg); }
        .stat-value { font-size: 1.8rem; font-weight: 800; line-height: 1; letter-spacing: -0.04em; }
        .stat-label { font-size: 0.75rem; color: var(--text-secondary); font-weight: 500; margin-top: 3px; }

        /* Alert */
        .alert {
            border-radius: var(--radius-md); padding: 14px 18px; margin-bottom: 24px;
            font-size: 0.875rem; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
            animation: slideDown 0.3s ease; border: 1px solid;
        }
        .alert-success { background: var(--success-bg); border-color: rgba(16,217,138,0.25); color: var(--success); }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }

        /* Grid */
        .content-grid { display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start; }

        .sidebar-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: sticky;
            top: 24px;
        }

        /* Cards */
        .card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-lg); padding: 26px;
            box-shadow: var(--shadow-card);
        }
        .card-title {
            font-size: 0.95rem; font-weight: 700; color: var(--text-primary);
            margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
        }
        .card-title-icon {
            width: 28px; height: 28px; border-radius: 6px;
            background: rgba(124,58,237,0.15); color: var(--accent-light);
            display: flex; align-items: center; justify-content: center;
        }
        .card-title-icon svg { width: 15px; height: 15px; stroke-width: 2.2; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 0.78rem; font-weight: 600;
            color: var(--text-secondary); margin-bottom: 7px;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .form-control {
            width: 100%; padding: 11px 14px;
            background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            border-radius: var(--radius-sm); color: var(--text-primary);
            font-family: inherit; font-size: 0.875rem;
            transition: all 0.2s ease; outline: none;
        }
        .form-control:focus {
            border-color: var(--accent); background: rgba(124,58,237,0.05);
            box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-error {
            color: var(--danger); font-size: 0.75rem; margin-top: 5px;
            display: flex; align-items: center; gap: 4px;
        }
        .form-error svg { width: 13px; height: 13px; stroke-width: 2; }

        /* Primary Form Button */
        .btn-submit-main {
            width: 100%;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: white;
            padding: 12px 20px;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 16px var(--accent-glow);
            transition: all 0.2s ease;
        }
        .btn-submit-main:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(124,58,237,0.45);
        }
        .btn-submit-main svg { width: 16px; height: 16px; stroke-width: 2.2; }

        /* Push Notification Card */
        .notif-card {
            background: linear-gradient(145deg, #141421, #1a162b);
            border: 1px solid rgba(124, 58, 237, 0.25);
            position: relative;
            overflow: hidden;
        }
        .notif-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--warning));
        }
        .notif-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 100px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .notif-badge.badge-idle {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-muted);
            border: 1px solid var(--border);
        }
        .notif-badge.badge-active {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(16, 217, 138, 0.3);
        }
        .notif-badge.badge-denied {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(244, 63, 94, 0.3);
        }
        .notif-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 14px;
            line-height: 1.45;
        }
        .btn-notif-main {
            width: 100%;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #fff;
            padding: 11px 16px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
            transition: all 0.2s ease;
        }
        .btn-notif-main:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.45);
        }
        .btn-notif-main.btn-active-state {
            background: rgba(16, 217, 138, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 217, 138, 0.3);
            box-shadow: none;
        }
        .notif-btn-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 10px;
        }
        .btn-secondary-sub {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            border: 1px solid var(--border);
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.76rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-secondary-sub:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.15);
        }
        .notif-tip {
            margin-top: 14px;
            padding: 10px 12px;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-sm);
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }
        .tip-icon {
            color: var(--info);
            font-size: 14px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .tip-icon svg { width: 14px; height: 14px; }
        .tip-text {
            font-size: 0.74rem;
            color: var(--text-muted);
            line-height: 1.45;
        }

        /* Task list header */
        .tasks-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .tasks-count {
            font-size: 0.75rem; color: var(--text-muted);
            background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            padding: 3px 10px; border-radius: 100px;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex; gap: 4px;
            background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            padding: 4px; border-radius: var(--radius-sm); margin-bottom: 20px;
        }
        .filter-tab {
            flex: 1; padding: 7px 12px; border-radius: 6px;
            font-size: 0.8rem; font-weight: 500; color: var(--text-secondary);
            border: none; background: transparent; cursor: pointer;
            transition: all 0.2s ease; text-align: center;
        }
        .filter-tab.active { background: var(--bg-card); color: var(--text-primary); font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }

        /* Task items */
        .task-list { display: flex; flex-direction: column; gap: 12px; }
        .task-item {
            background: rgba(255,255,255,0.02); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 16px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: all 0.2s ease;
            animation: fadeIn 0.3s ease both;
        }
        .task-item:hover {
            background: var(--bg-card-hover); border-color: rgba(255,255,255,0.12);
            transform: translateX(3px);
        }
        .task-item.done { opacity: 0.65; }
        .task-item.done .task-name { text-decoration: line-through; color: var(--text-muted); }
        @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

        .task-num {
            width: 30px; height: 30px; border-radius: 8px;
            background: rgba(255,255,255,0.05); color: var(--text-muted);
            font-size: 0.78rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .task-body { flex: 1; min-width: 0; }
        .task-name {
            font-weight: 600; font-size: 0.98rem; color: var(--text-primary);
            margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .task-meta { display: flex; align-items: center; gap: 10px; font-size: 0.78rem; flex-wrap: wrap; }
        .task-subject {
            background: rgba(124,58,237,0.12); color: var(--accent-light);
            padding: 2px 8px; border-radius: 4px; font-weight: 500;
        }
        .task-deadline {
            color: var(--text-muted); display: flex; align-items: center; gap: 4px;
        }
        .task-deadline svg { width: 13px; height: 13px; }
        .task-deadline.soon { color: var(--warning); }
        .task-deadline.urgent { color: var(--danger); font-weight: 600; }

        /* Status Badges */
        .badge {
            font-size: 0.72rem; font-weight: 600; padding: 4px 10px;
            border-radius: 100px; display: inline-flex; align-items: center; gap: 5px;
            flex-shrink: 0;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-pending { background: var(--warning-bg); color: var(--warning); border: 1px solid rgba(245,158,11,0.25); }
        .badge-pending .badge-dot { background: var(--warning); }
        .badge-done { background: var(--success-bg); color: var(--success); border: 1px solid rgba(16,217,138,0.25); }
        .badge-done .badge-dot { background: var(--success); }

        /* ================= CRUD ACTION BUTTONS ================= */
        .task-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .crud-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            line-height: 1;
        }
        .crud-btn svg {
            width: 14px;
            height: 14px;
            stroke-width: 2.2;
            flex-shrink: 0;
        }
        /* Selesai Button */
        .crud-btn-done {
            background: rgba(16, 217, 138, 0.12);
            color: #10d98a;
            border-color: rgba(16, 217, 138, 0.35);
        }
        .crud-btn-done:hover {
            background: #10d98a;
            color: #0a0a0f;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 217, 138, 0.35);
        }
        /* Batal Selesai Button */
        .crud-btn-undo {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
            border-color: rgba(245, 158, 11, 0.35);
        }
        .crud-btn-undo:hover {
            background: #f59e0b;
            color: #0a0a0f;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
        }
        /* Edit Button */
        .crud-btn-edit {
            background: rgba(56, 189, 248, 0.12);
            color: #38bdf8;
            border-color: rgba(56, 189, 248, 0.35);
        }
        .crud-btn-edit:hover {
            background: #38bdf8;
            color: #0a0a0f;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.35);
        }
        /* Hapus Button */
        .crud-btn-delete {
            background: rgba(244, 63, 94, 0.12);
            color: #f43f5e;
            border-color: rgba(244, 63, 94, 0.35);
        }
        .crud-btn-delete:hover {
            background: #f43f5e;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.35);
        }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 60px 20px;
            display: flex; flex-direction: column; align-items: center;
        }
        .empty-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(255,255,255,0.03); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px; color: var(--text-muted);
        }
        .empty-icon svg { width: 28px; height: 28px; stroke-width: 1.5; }
        .empty-title { font-size: 1rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px; }
        .empty-text { font-size: 0.8rem; color: var(--text-muted); }

        /* Modal */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.75);
            backdrop-filter: blur(8px); z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.25s ease;
        }
        .modal-overlay.show { opacity: 1; pointer-events: all; }
        .modal {
            background: var(--bg-card); border: 1px solid rgba(244,63,94,0.3);
            border-radius: var(--radius-lg); padding: 32px;
            max-width: 400px; width: 90%;
            transform: scale(0.92) translateY(8px);
            transition: transform 0.25s ease;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        }
        .modal-overlay.show .modal { transform: scale(1) translateY(0); }
        .modal-icon {
            width: 56px; height: 56px; background: var(--danger-bg); border-radius: 14px;
            display: flex; align-items: center; justify-content: center; margin-bottom: 18px;
        }
        .modal-icon svg { width: 26px; height: 26px; stroke: var(--danger); stroke-width: 1.8; }
        .modal-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; }
        .modal-text { font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 24px; }
        .modal-actions { display: flex; gap: 10px; }
        .btn-cancel {
            flex: 1; background: rgba(255,255,255,0.06); color: var(--text-secondary);
            border: 1px solid var(--border); padding: 10px; border-radius: var(--radius-sm);
            cursor: pointer; font-weight: 600; font-family: inherit; font-size: 0.85rem;
        }
        .btn-cancel:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }
        .btn-confirm-delete {
            flex: 1; background: linear-gradient(135deg, var(--danger), #be123c);
            color: white; border: none; padding: 10px; border-radius: var(--radius-sm);
            cursor: pointer; font-weight: 600; font-family: inherit; font-size: 0.85rem;
            box-shadow: 0 4px 14px rgba(244,63,94,0.3);
        }
        .btn-confirm-delete:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(244,63,94,0.4); }

        /* Toasts */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            background: #1c1c2b;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius-sm);
            padding: 12px 18px;
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 500;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.3s ease;
            max-width: 360px;
        }
        .toast-success { border-color: rgba(16, 217, 138, 0.4); }
        .toast-error { border-color: rgba(244, 63, 94, 0.4); }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsive */
        @media (max-width: 900px) { 
            .content-grid { grid-template-columns: 1fr; }
            .sidebar-col { position: static; }
        }
        @media (max-width: 640px) {
            .wrapper { padding: 16px 14px 48px; }
            .header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .header-right { width: 100%; justify-content: space-between; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid .stat-card:last-child { grid-column: 1 / -1; }
            .task-item { flex-direction: column; align-items: flex-start; gap: 12px; }
            .task-actions { width: 100%; justify-content: flex-end; }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Toast Notifications Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
            </div>
            <div class="modal-title">Hapus Tugas?</div>
            <div class="modal-text">Tugas yang dihapus tidak bisa dikembalikan. Kamu yakin mau menghapusnya?</div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal()">Batalkan</button>
                <button class="btn-confirm-delete" id="confirmDeleteBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <div class="wrapper">

        <!-- Top Header -->
        <header class="header">
            <div class="header-brand">
                <div class="header-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </div>
                <div>
                    <div class="header-title">TaskMind</div>
                    <div class="header-subtitle">Pengingat Tugas Sekolah</div>
                </div>
            </div>
            <div class="header-right">
                <button id="quickNotifBtn" class="header-notif-btn" onclick="enablePushNotifications()" title="Klik untuk mengaktifkan notifikasi di HP">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <span id="quickNotifLabel">Notifikasi HP</span>
                </button>
                <div class="header-date" id="currentDate"></div>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success" id="flashAlert">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @php
            $total   = $tasks->count();
            $selesai = $tasks->where('status', 'selesai')->count();
            $pending = $total - $selesai;
        @endphp

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-label">Total Tugas</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $pending }}</div>
                    <div class="stat-label">Belum Selesai</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10d98a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $selesai }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
        </div>

        <div class="content-grid">

            <!-- Sidebar (Form Tambah Tugas + Widget Notifikasi HP) -->
            <div class="sidebar-col">
                <!-- Form Tambah -->
                <div class="card">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                        Tambah Tugas Baru
                    </div>
                    <form action="{{ route('tasks.store') }}" method="POST" id="addTaskForm">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="judul_tugas">Nama Tugas</label>
                            <input type="text" id="judul_tugas" name="judul_tugas"
                                value="{{ old('judul_tugas') }}" class="form-control"
                                placeholder="Cth: Laporan Fisika bab 3..." required>
                            @error('judul_tugas')
                                <div class="form-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="mata_pelajaran">Mata Pelajaran</label>
                            <input type="text" id="mata_pelajaran" name="mata_pelajaran"
                                value="{{ old('mata_pelajaran') }}" class="form-control"
                                placeholder="Cth: Matematika, Fisika..." required>
                            @error('mata_pelajaran')
                                <div class="form-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group" style="margin-bottom:26px;">
                            <label class="form-label" for="tenggat_waktu">Tenggat Waktu</label>
                            <input type="date" id="tenggat_waktu" name="tenggat_waktu"
                                value="{{ old('tenggat_waktu', date('Y-m-d')) }}" class="form-control" required>
                            @error('tenggat_waktu')
                                <div class="form-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn-submit-main" id="submitBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Simpan Tugas
                        </button>
                    </form>
                </div>

                <!-- Notifikasi HP Card -->
                <div class="card notif-card">
                    <div class="card-title" style="justify-content:space-between;margin-bottom:12px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="card-title-icon" style="background:rgba(245,158,11,0.15);color:var(--warning);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/><path d="M4 2C2.8 3.7 2 5.7 2 8"/><path d="M22 8c0-2.3-.8-4.3-2-6"/></svg>
                            </div>
                            <span>Notifikasi HP</span>
                        </div>
                        <span id="notifStatusBadge" class="notif-badge badge-idle">
                            <span class="badge-dot"></span><span id="notifStatusText">Belum Aktif</span>
                        </span>
                    </div>

                    <p class="notif-desc">
                        Otomatis kirim alarm dan notifikasi ke HP untuk tugas yang belum selesai atau tenggat waktunya sudah mepet.
                    </p>

                    <div class="notif-actions-box">
                        <button type="button" class="btn-notif-main" id="btnEnableNotif" onclick="enablePushNotifications()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                            <span id="btnEnableText">Aktifkan Notifikasi di HP</span>
                        </button>

                        <div class="notif-btn-group" id="notifActionButtons" style="display:none;">
                            <button type="button" class="btn-secondary-sub" onclick="sendTestPush()" title="Tes kirim notifikasi ke HP">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
                                Tes Bunyi HP
                            </button>
                            <button type="button" class="btn-secondary-sub" onclick="triggerDeadlineReminders()" title="Kirim notifikasi untuk tugas yang mepet deadline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                Cek Pengingat
                            </button>
                        </div>
                    </div>

                    <div class="notif-tip">
                        <div class="tip-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        </div>
                        <div class="tip-text">
                            Buka di browser HP kamu (Chrome / Safari), lalu klik <strong>"Aktifkan"</strong> untuk menerima getar & pop-up saat tugas mendekati deadline!
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task List -->
            <div class="card">
                <div class="tasks-header">
                    <div class="card-title" style="margin-bottom:0">
                        <div class="card-title-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><polyline points="3 6 4 7 6 5"/><polyline points="3 12 4 13 6 11"/><polyline points="3 18 4 19 6 17"/></svg>
                        </div>
                        Daftar Tugas
                    </div>
                    <span class="tasks-count">{{ $total }} tugas</span>
                </div>

                <!-- Tabs Filter Status -->
                <div class="filter-tabs" role="tablist">
                    <button class="filter-tab active" onclick="filterTasks('all', this)" role="tab">Semua</button>
                    <button class="filter-tab" onclick="filterTasks('pending', this)" role="tab">Belum</button>
                    <button class="filter-tab" onclick="filterTasks('done', this)" role="tab">Selesai</button>
                </div>

                <!-- Task Items -->
                <div class="task-list" id="taskList">
                    @forelse($tasks as $index => $task)
                        @php
                            $deadline  = \Carbon\Carbon::parse($task->tenggat_waktu);
                            $daysLeft  = now()->startOfDay()->diffInDays($deadline->copy()->startOfDay(), false);
                            $isDone    = $task->status === 'selesai';
                            if ($isDone)              { $dlClass = ''; }
                            elseif ($daysLeft < 0)    { $dlClass = 'urgent'; }
                            elseif ($daysLeft <= 1)   { $dlClass = 'urgent'; }
                            elseif ($daysLeft <= 2)   { $dlClass = 'soon'; }
                            else                      { $dlClass = ''; }
                        @endphp
                        <div class="task-item {{ $isDone ? 'done' : '' }}"
                             data-status="{{ $isDone ? 'done' : 'pending' }}"
                             data-deadline="{{ $task->tenggat_waktu }}"
                             data-title="{{ $task->judul_tugas }}"
                             data-subject="{{ $task->mata_pelajaran }}"
                             style="animation-delay:{{ $index * 0.05 }}s">

                            <div class="task-num">{{ $index + 1 }}</div>

                            <div class="task-body">
                                <div class="task-name">{{ $task->judul_tugas }}</div>
                                <div class="task-meta">
                                    <span class="task-subject">{{ $task->mata_pelajaran }}</span>
                                    <span class="task-deadline {{ $dlClass }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        {{ $deadline->format('d M Y') }}
                                        @if(!$isDone)
                                            @if($daysLeft === 0)
                                                <span style="font-weight:700;color:var(--danger);">(Hari ini!)</span>
                                            @elseif($daysLeft === 1)
                                                <span style="font-weight:600;color:var(--warning);">(Besok!)</span>
                                            @elseif($daysLeft > 1)
                                                <span style="opacity:.7">({{ $daysLeft }}h lagi)</span>
                                            @else
                                                <span style="font-weight:700;color:var(--danger);">(Terlambat!)</span>
                                            @endif
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Badge Status -->
                            @if($isDone)
                                <span class="badge badge-done"><span class="badge-dot"></span>Selesai</span>
                            @else
                                <span class="badge badge-pending"><span class="badge-dot"></span>Pending</span>
                            @endif

                            <!-- ================= CRUD ACTION BUTTONS ================= -->
                            <div class="task-actions">
                                @if(!$isDone)
                                    <!-- 1. Tombol Tandai Selesai -->
                                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display:inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="judul_tugas"    value="{{ $task->judul_tugas }}">
                                        <input type="hidden" name="mata_pelajaran" value="{{ $task->mata_pelajaran }}">
                                        <input type="hidden" name="tenggat_waktu"  value="{{ $task->tenggat_waktu }}">
                                        <input type="hidden" name="status"         value="selesai">
                                        <button type="submit" class="crud-btn crud-btn-done" title="Tandai Selesai">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            <span>Selesai</span>
                                        </button>
                                    </form>
                                @else
                                    <!-- 1b. Tombol Batal Selesai (Kembalikan ke Pending) -->
                                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display:inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="judul_tugas"    value="{{ $task->judul_tugas }}">
                                        <input type="hidden" name="mata_pelajaran" value="{{ $task->mata_pelajaran }}">
                                        <input type="hidden" name="tenggat_waktu"  value="{{ $task->tenggat_waktu }}">
                                        <input type="hidden" name="status"         value="belum">
                                        <button type="submit" class="crud-btn crud-btn-undo" title="Kembalikan ke belum selesai">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                            <span>Batal</span>
                                        </button>
                                    </form>
                                @endif

                                <!-- 2. Tombol Edit -->
                                <a href="{{ route('tasks.edit', $task->id) }}" class="crud-btn crud-btn-edit" title="Edit Tugas">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    <span>Edit</span>
                                </a>

                                <!-- 3. Tombol Hapus -->
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="crud-btn crud-btn-delete" onclick="openModal(this.closest('form'))" title="Hapus Tugas">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                            </div>
                            <div class="empty-title">Belum ada tugas nih</div>
                            <div class="empty-text">Tambahkan tugas pertamamu di form sebelah kiri.</div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        // Global VAPID key
        const VAPID_PUBLIC_KEY = "{{ env('VAPID_PUBLIC_KEY') }}";
        let currentSubscription = null;

        // Current Date
        document.getElementById('currentDate').textContent =
            new Date().toLocaleDateString('id-ID', {weekday:'long',year:'numeric',month:'long',day:'numeric'});

        // Auto-dismiss flash alert
        const flash = document.getElementById('flashAlert');
        if (flash) setTimeout(() => {
            flash.style.transition = 'all .4s ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-8px)';
            setTimeout(() => flash.remove(), 400);
        }, 3500);

        // Filter Tasks by status tab
        function filterTasks(filter, tab) {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            document.querySelectorAll('.task-item').forEach(item => {
                item.style.display = (filter === 'all' || item.dataset.status === filter) ? 'flex' : 'none';
            });
        }

        // Delete Modal Handler
        let pendingDeleteForm = null;
        function openModal(form) {
            pendingDeleteForm = form;
            document.getElementById('deleteModal').classList.add('show');
        }
        function closeModal() {
            document.getElementById('deleteModal').classList.remove('show');
            pendingDeleteForm = null;
        }
        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            if (pendingDeleteForm) pendingDeleteForm.submit();
        });
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // Submit form loading feedback
        document.getElementById('addTaskForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Menyimpan...';
            btn.style.opacity = '.8';
            btn.disabled = true;
        });

        // Toast Helper
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type === 'error' ? 'toast-error' : 'toast-success'}`;
            const iconHtml = type === 'error' 
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>' 
                : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>';
            toast.innerHTML = `${iconHtml}<span>${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.transition = 'all 0.3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Base64 helper for VAPID
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        // Notification UI Status Updater
        function updateNotifUI(state) {
            const badge = document.getElementById('notifStatusBadge');
            const badgeText = document.getElementById('notifStatusText');
            const btnMain = document.getElementById('btnEnableNotif');
            const btnText = document.getElementById('btnEnableText');
            const actionGroup = document.getElementById('notifActionButtons');
            const headerBtn = document.getElementById('quickNotifBtn');
            const headerLabel = document.getElementById('quickNotifLabel');

            badge.className = 'notif-badge';

            if (state === 'active') {
                badge.classList.add('badge-active');
                badgeText.textContent = 'Aktif Terhubung';
                btnMain.classList.add('btn-active-state');
                btnText.textContent = '✓ Notifikasi HP Aktif';
                actionGroup.style.display = 'grid';
                headerBtn.classList.add('is-active');
                headerLabel.textContent = 'Notif Aktif';
            } else if (state === 'denied') {
                badge.classList.add('badge-denied');
                badgeText.textContent = 'Izin Ditolak';
                btnText.textContent = 'Aktifkan Izin di Browser';
                actionGroup.style.display = 'none';
                headerBtn.classList.remove('is-active');
                headerLabel.textContent = 'Notif Ditolak';
            } else {
                badge.classList.add('badge-idle');
                badgeText.textContent = 'Belum Aktif';
                btnText.textContent = 'Aktifkan Notifikasi di HP';
                actionGroup.style.display = 'none';
                headerBtn.classList.remove('is-active');
                headerLabel.textContent = 'Notifikasi HP';
            }
        }

        // Check current notification state on page load
        async function checkNotificationStatus() {
            if (!('Notification' in window)) {
                updateNotifUI('denied');
                return;
            }

            if (Notification.permission === 'granted') {
                if ('serviceWorker' in navigator) {
                    try {
                        const reg = await navigator.serviceWorker.ready;
                        const sub = await reg.pushManager.getSubscription();
                        if (sub) {
                            currentSubscription = sub;
                            updateNotifUI('active');
                            checkUrgentTasksOnPage();
                            return;
                        }
                    } catch (e) {
                        console.warn(e);
                    }
                }
                updateNotifUI('active');
                checkUrgentTasksOnPage();
            } else if (Notification.permission === 'denied') {
                updateNotifUI('denied');
            } else {
                updateNotifUI('idle');
            }
        }

        // Enable Push Notifications
        async function enablePushNotifications() {
            if (!('Notification' in window)) {
                alert('Browser ini tidak mendukung fitur Web Notification.');
                return;
            }

            try {
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    showToast('Izin notifikasi ditolak. Harap izinkan notifikasi di browser.', 'error');
                    updateNotifUI('denied');
                    return;
                }

                if (!('serviceWorker' in navigator)) {
                    showToast('Service Worker tidak aktif pada peramban ini.', 'error');
                    return;
                }

                const registration = await navigator.serviceWorker.register('/sw.js');
                await navigator.serviceWorker.ready;

                const convertedVapidKey = urlBase64ToUint8Array(VAPID_PUBLIC_KEY);
                let subscription = await registration.pushManager.getSubscription();

                if (!subscription) {
                    subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: convertedVapidKey
                    });
                }

                currentSubscription = subscription;
                const subJson = subscription.toJSON();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const resp = await fetch('/push/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        endpoint: subscription.endpoint,
                        p256dh: subJson.keys.p256dh,
                        auth: subJson.keys.auth
                    })
                });

                const data = await resp.json();
                updateNotifUI('active');
                showToast('🎉 Notifikasi HP berhasil diaktifkan!');
                sendTestPush();

            } catch (err) {
                console.error('Push error:', err);
                if (Notification.permission === 'granted') {
                    updateNotifUI('active');
                    showToast('Notifikasi browser aktif!');
                    new Notification('TaskMind – Notifikasi Aktif!', {
                        body: 'Pengingat tugas sekarang sudah aktif di peramban ini.',
                        icon: '/favicon.ico'
                    });
                } else {
                    showToast('Gagal mengaktifkan notifikasi: ' + err.message, 'error');
                }
            }
        }

        // Send Test Push to Device
        async function sendTestPush() {
            try {
                showToast('Mengirim tes notifikasi ke perangkat...');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const resp = await fetch('/push/send-test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        endpoint: currentSubscription ? currentSubscription.endpoint : null
                    })
                });

                const result = await resp.json();
                if (result.success && result.sent > 0) {
                    showToast('🔔 Notifikasi tes berhasil muncul di HP/perangkat!');
                } else {
                    if (Notification.permission === 'granted') {
                        new Notification('🔔 Tes Notifikasi TaskMind!', {
                            body: 'HP/perangkatmu berhasil terhubung dengan pengingat tugas!',
                            icon: '/favicon.ico'
                        });
                        showToast('Notifikasi tes berhasil ditampilkan!');
                    } else {
                        showToast(result.message || 'Belum ada perangkat terhubung.', 'error');
                    }
                }
            } catch (err) {
                console.error(err);
                if (Notification.permission === 'granted') {
                    new Notification('🔔 Tes Notifikasi TaskMind!', {
                        body: 'HP/perangkatmu berhasil terhubung dengan pengingat tugas!',
                        icon: '/favicon.ico'
                    });
                    showToast('Notifikasi tes berhasil ditampilkan!');
                } else {
                    showToast('Gagal mengirim sinyal tes.', 'error');
                }
            }
        }

        // Trigger Deadline Reminders
        async function triggerDeadlineReminders() {
            try {
                showToast('Memeriksa tugas yang mendekati deadline...');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const resp = await fetch('/push/send-reminders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await resp.json();
                if (result.success) {
                    if (result.sent > 0) {
                        showToast(`⏰ Notifikasi pengingat terkirim ke ${result.sent} perangkat!`);
                    } else {
                        showToast(result.message || 'Semua tugas aman, belum ada yang mepet deadline.', 'success');
                    }
                } else {
                    showToast(result.message || 'Gagal memproses pengingat.', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Terjadi kesalahan saat memeriksa tugas.', 'error');
            }
        }

        // Client-side instant check for urgent tasks
        function checkUrgentTasksOnPage() {
            if (Notification.permission !== 'granted') return;

            const pendingUrgentItems = [];
            document.querySelectorAll('.task-item[data-status="pending"]').forEach(item => {
                const dl = item.dataset.deadline;
                const title = item.dataset.title;
                const subject = item.dataset.subject;
                if (dl) {
                    const dlDate = new Date(dl);
                    const now = new Date();
                    const diffTime = dlDate - now;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays <= 1) {
                        pendingUrgentItems.push({ title, subject, diffDays });
                    }
                }
            });

            if (pendingUrgentItems.length > 0 && !sessionStorage.getItem('notified_urgent')) {
                const first = pendingUrgentItems[0];
                const when = first.diffDays <= 0 ? 'Hari ini!' : 'Besok!';
                new Notification(`⚠️ Pengingat: ${pendingUrgentItems.length} Tugas Mepet Deadline!`, {
                    body: `${first.subject} - ${first.title} (${when})`,
                    icon: '/favicon.ico'
                });
                sessionStorage.setItem('notified_urgent', '1');
            }
        }

        // Initialize on load
        window.addEventListener('DOMContentLoaded', () => {
            checkNotificationStatus();
        });
    </script>
</body>

</html>
