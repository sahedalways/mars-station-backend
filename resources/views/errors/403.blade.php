<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Access Denied</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #0a0518;
            overflow: hidden;
        }
        .bg { position: fixed; inset: 0; }
        .bg::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 20% 60%, rgba(139,92,246,0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 90% 90%, rgba(168,85,247,0.1) 0%, transparent 50%),
                linear-gradient(180deg, #0a0518 0%, #130826 50%, #0a0518 100%);
        }
        .bg::after {
            content: '';
            position: absolute; inset: 0;
            background-image:
                radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.6), transparent),
                radial-gradient(1px 1px at 30% 70%, rgba(255,255,255,0.4), transparent),
                radial-gradient(1px 1px at 50% 40%, rgba(255,255,255,0.5), transparent),
                radial-gradient(1px 1px at 70% 80%, rgba(255,255,255,0.3), transparent),
                radial-gradient(1px 1px at 85% 15%, rgba(255,255,255,0.6), transparent),
                radial-gradient(1px 1px at 15% 90%, rgba(255,255,255,0.4), transparent),
                radial-gradient(1px 1px at 60% 10%, rgba(255,255,255,0.5), transparent),
                radial-gradient(1px 1px at 40% 55%, rgba(255,255,255,0.3), transparent);
        }
        .glow {
            position: absolute; left: -300px; top: 30%;
            width: 700px; height: 700px; border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.2) 0%, rgba(139,92,246,0.05) 40%, transparent 70%);
            pointer-events: none;
        }
        .card {
            position: relative; z-index: 1;
            text-align: center;
            padding: 3rem 2rem;
            max-width: 420px; width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(139,92,246,0.15);
            border-radius: 1.5rem;
            backdrop-filter: blur(12px);
            box-shadow: 0 25px 80px rgba(109,40,217,0.15), 0 8px 32px rgba(0,0,0,0.3);
        }
        .logo { height: 44px; filter: brightness(2) drop-shadow(0 0 10px rgba(168,85,247,0.5)); margin-bottom: 0.5rem; }
        .portal { font-size: 0.7rem; letter-spacing: 0.15em; text-transform: uppercase; color: rgba(203,213,225,0.6); margin-bottom: 2rem; }
        .icon-wrap {
            display: inline-flex; align-items: center; justify-content: center;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(139,92,246,0.1);
            border: 1px solid rgba(139,92,246,0.2);
            margin-bottom: 1.25rem;
        }
        .icon-wrap svg { width: 40px; height: 40px; color: #a78bfa; }
        .code { font-size: 3.5rem; font-weight: 800; line-height: 1; color: transparent; background: linear-gradient(135deg, #8b5cf6, #a855f7, #c084fc); -webkit-background-clip: text; background-clip: text; margin-bottom: 0.25rem; }
        .title { font-size: 1.25rem; font-weight: 700; color: #f1f5f9; margin: 0.5rem 0; }
        .desc { font-size: 0.875rem; color: rgba(148,163,184,0.8); line-height: 1.6; margin-bottom: 2rem; }
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            font-size: 0.875rem; font-weight: 600; color: #fff;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            border: none; border-radius: 0.5rem; cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(124,58,237,0.3);
        }
        .btn:hover { box-shadow: 0 6px 24px rgba(124,58,237,0.5); transform: translateY(-1px); }
        .footer { position: fixed; bottom: 1.5rem; left: 0; right: 0; text-align: center; font-size: 0.7rem; color: rgba(100,116,139,0.5); z-index: 1; }
    </style>
</head>
<body>
    <div class="bg"><div class="glow"></div></div>
    <div class="card">
        <img src="{{ asset('logo.png') }}" alt="Mars Station" class="logo">
        <div class="portal">Client Portal</div>
        <div class="icon-wrap">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
        <div class="code">403</div>
        <h1 class="title">Access Denied</h1>
        <p class="desc">You don't have permission to access this page. Please check your credentials or contact support.</p>
        <a href="https://marsstation.dev" class="btn">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Home
        </a>
    </div>
    <div class="footer">&copy; {{ date('Y') }} Mars Station</div>
</body>
</html>
