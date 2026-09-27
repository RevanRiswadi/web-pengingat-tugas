<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas – TaskMind</title>
    <meta name="description" content="Edit dan perbarui detail tugasmu di TaskMind.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-primary: #0a0a0f;
            --bg-card: #16161f;
            --border: rgba(255, 255, 255, 0.07);
            --text-primary: #f1f0ff;
            --text-secondary: #8b8aa8;
            --text-muted: #5a5970;
            --accent: #7c3aed;
            --accent-light: #9d5ff5;
            --accent-glow: rgba(124, 58, 237, 0.3);
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(80px); pointer-events: none; z-index: 0; opacity: 0.35;
        }
        .orb-1 {
            width: 450px; height: 450px; top: -150px; left: -100px;
            background: radial-gradient(circle, rgba(124,58,237,0.5), transparent 70%);
            animation: float 12s ease-in-out infinite;
        }
        .orb-2 {
            width: 350px; height: 350px; bottom: -100px; right: -80px;
            background: radial-gradient(circle, rgba(56,189,248,0.4), transparent 70%);
            animation: float 15s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0,0) scale(1); }
            33% { transform: translate(20px,-30px) scale(1.05); }
            66% { transform: translate(-15px,20px) scale(0.95); }
        }

        .container {
            position: relative; z-index: 1;
            width: 100%; max-width: 480px;
        }

        /* Back link */
        .back-link {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.82rem; font-weight: 500; color: var(--text-muted);
            text-decoration: none; margin-bottom: 20px;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--text-secondary); }

        /* Card */
        .card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-lg); padding: 32px;
            box-shadow: var(--shadow-card);
            animation: fadeIn 0.35s ease;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }

        .card-header { margin-bottom: 28px; }
        .card-logo {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, var(--accent), var(--info));
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 16px;
            box-shadow: 0 0 24px var(--accent-glow);
        }
        .card-title {
            font-size: 1.25rem; font-weight: 800; letter-spacing: -0.03em;
            background: linear-gradient(135deg, var(--text-primary) 40%, var(--accent-light));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            margin-bottom: 4px;
        }
        .card-subtitle { font-size: 0.82rem; color: var(--text-muted); }

        /* Task info strip */
        .task-strip {
            background: rgba(124,58,237,0.07); border: 1px solid rgba(124,58,237,0.18);
            border-radius: var(--radius-md); padding: 12px 16px;
            margin-bottom: 24px; font-size: 0.82rem; color: var(--text-secondary);
        }
        .task-strip strong { color: var(--accent-light); }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 0.78rem; font-weight: 600; color: var(--text-secondary);
            text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 8px;
        }
        .form-control {
            width: 100%; padding: 11px 14px;
            background: rgba(255,255,255,0.04); border: 1px solid var(--border);
            border-radius: var(--radius-sm); color: var(--text-primary);
            font-size: 0.9rem; font-family: 'Inter', sans-serif;
            transition: all 0.2s ease; outline: none;
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-control:focus {
            border-color: var(--accent-light);
            background: rgba(124,58,237,0.06);
            box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
        }
        .form-control::-webkit-calendar-picker-indicator { filter: invert(0.7); cursor: pointer; }
        .form-error { color: var(--danger); font-size: 0.75rem; margin-top: 5px; }

        /* Status select */
        .form-control option { background: #1c1c28; }

        /* Buttons */
        .btn-group { display: flex; gap: 10px; margin-top: 28px; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            padding: 12px 20px; border: none; border-radius: var(--radius-sm);
            font-size: 0.9rem; font-weight: 600; font-family: 'Inter', sans-serif;
            cursor: pointer; transition: all 0.2s ease; text-decoration: none;
        }
        .btn svg { width: 16px; height: 16px; stroke-width: 2.2; flex-shrink: 0; }
        .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, var(--accent), #5b21b6); color: white;
            box-shadow: 0 4px 14px var(--accent-glow);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--accent-light), var(--accent));
            transform: translateY(-1px); box-shadow: 0 6px 20px var(--accent-glow);
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-secondary {
            background: rgba(255,255,255,0.06); color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }

        .form-error { color: var(--danger); font-size: 0.75rem; margin-top: 5px; display: flex; align-items: center; gap: 5px; }
        .form-error svg { width: 13px; height: 13px; flex-shrink: 0; stroke-width: 2.5; }

        @media (max-width: 480px) { .card { padding: 22px 18px; } }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <a href="{{ route('tasks.index') }}" class="back-link">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            Kembali ke Daftar Tugas
        </a>

        <div class="card">
            <div class="card-header">
                <div class="card-logo"><i data-lucide="file-edit" style="width:22px;height:22px;stroke:white;stroke-width:2;"></i></div>
                <div class="card-title">Edit Tugas</div>
                <div class="card-subtitle">Perbarui detail tugas kamu di bawah ini.</div>
            </div>

            <div class="task-strip">
                Mengedit: <strong>{{ $task->judul_tugas }}</strong>
            </div>

            <form action="{{ route('tasks.update', $task->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="edit_judul">Nama Tugas</label>
                    <input type="text" id="edit_judul" name="judul_tugas"
                        value="{{ old('judul_tugas', $task->judul_tugas) }}"
                        class="form-control"
                        placeholder="Nama tugas...">
                    @error('judul_tugas')
                        <div class="form-error"><i data-lucide="alert-triangle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_mapel">Mata Pelajaran</label>
                    <input type="text" id="edit_mapel" name="mata_pelajaran"
                        value="{{ old('mata_pelajaran', $task->mata_pelajaran) }}"
                        class="form-control"
                        placeholder="Mata pelajaran...">
                    @error('mata_pelajaran')
                        <div class="form-error"><i data-lucide="alert-triangle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_deadline">Tenggat Waktu</label>
                    <input type="date" id="edit_deadline" name="tenggat_waktu"
                        value="{{ old('tenggat_waktu', $task->tenggat_waktu) }}"
                        class="form-control">
                    @error('tenggat_waktu')
                        <div class="form-error"><i data-lucide="alert-triangle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_status">Status</label>
                    <select id="edit_status" name="status" class="form-control">
                        <option value="belum" {{ old('status', $task->status) === 'belum' ? 'selected' : '' }}>Belum Selesai</option>
                        <option value="selesai" {{ old('status', $task->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="btn-group">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                        <i data-lucide="x"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="updateBtn">
                        <i data-lucide="save"></i> Update Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('editForm').addEventListener('submit', function() {
            const btn = document.getElementById('updateBtn');
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Menyimpan...';
            btn.style.opacity = '.8';
            btn.disabled = true;
        });

        lucide.createIcons();
    </script>
</body>
</html>