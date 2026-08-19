<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agreement->agreement_number }} - V{{ $version->version }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Helvetica Neue', 'Arial', sans-serif; color: #1f2937; font-size: 12px; line-height: 1.6;
            margin: 0; padding: 0; background: #fff;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }

        .ap-doc-wrapper {
            background: #ffffff; border-radius: 0; overflow: hidden;
            border: none; position: relative;
            border-top: 4px solid #7c3aed;
        }
        .ap-doc-wrapper::before {
            content: ''; position: absolute; top: 18%; right: 2%; transform: none;
            width: 420px; height: 420px;
            background: url('{{ public_path("watermark-logo.png") }}') no-repeat center center;
            background-size: contain; opacity: 0.04; pointer-events: none; z-index: 1;
        }
        .ap-doc-wrapper > * { position: relative; z-index: 2; }

        .ap-header {
            padding: 28px 40px 24px; border-bottom: 3px solid #4c1d95;
            display: flex; align-items: center; justify-content: space-between; gap: 24px;
            background: linear-gradient(180deg, #f9f7ff 0%, #ffffff 100%);
        }
        .ap-brand img { display: block; max-width: 300px; height: auto; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.05)); }
        .ap-meta {
            display: grid; grid-template-columns: 1fr 1fr; gap: 10px 22px;
            min-width: 320px; max-width: 420px;
        }
        .ap-meta-row { display: flex; gap: 10px; align-items: flex-start; }
        .ap-meta-icon {
            width: 30px; height: 30px; background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #4c1d95;
            box-shadow: 0 1px 3px rgba(76,29,149,0.1);
        }
        .ap-meta-icon svg { width: 14px; height: 14px; }
        .ap-meta-label { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px; }
        .ap-meta-value { font-size: 12px; color: #111827; font-weight: 600; }
        .ap-status {
            display: inline-flex; align-items: center; gap: 4px; padding: 3px 12px; border-radius: 999px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
        }
        .ap-status-signed { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534; border: 1px solid #86efac; box-shadow: 0 1px 3px rgba(22,101,52,0.1); }
        .ap-status-pending { background: linear-gradient(135deg, #fef9c3, #fde68a); color: #854d0e; border: 1px solid #fcd34d; box-shadow: 0 1px 3px rgba(133,77,14,0.1); }
        .ap-status-expired { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fca5a5; box-shadow: 0 1px 3px rgba(153,27,27,0.1); }
        .ap-status-default { background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #374151; border: 1px solid #d1d5db; }

        .ap-body { padding: 36px 40px 0; }
        .ap-section-label {
            font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: #4c1d95;
            font-weight: 800; border-left: 4px solid #4c1d95; padding-left: 12px;
            margin: 32px 0 14px; font-family: 'Inter', sans-serif;
        }
        .ap-intro { font-size: 13px; line-height: 1.75; color: #374151; margin: 0 0 8px; }
        .ap-sub-intro { font-size: 12px; color: #6b7280; font-style: italic; margin: 0 0 20px; }
        .ap-block { margin-bottom: 22px; }
        .ap-block h3 { font-size: 12px; color: #4c1d95; font-weight: 800; margin: 0 0 8px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.3px; }
        .ap-block h3 .num {
            display: inline-flex; align-items: center; justify-content: center;
            width: 26px; height: 26px; background: linear-gradient(135deg, #7c3aed, #4c1d95); color: #fff;
            border-radius: 50%; font-size: 10px; font-weight: 800;
            box-shadow: 0 2px 6px rgba(76,29,149,0.25);
        }
        .ap-block p { font-size: 12.5px; line-height: 1.7; color: #374151; margin: 0 0 6px; }
        .ap-content-area { font-size: 12.5px; line-height: 1.7; color: #000000; }
        .ap-content-area * { color: #000000 !important; }
        .ap-content-area h1, .ap-content-area h2, .ap-content-area h3, .ap-content-area h4 { color: #000000 !important; margin: 14px 0 8px; font-weight: 700; }
        .ap-content-area strong, .ap-content-area b { font-weight: 700; }
        .ap-content-area ul, .ap-content-area ol { padding-left: 20px; margin: 8px 0; }
        .ap-content-area li { margin-bottom: 4px; }
        .ap-content-area p { margin: 0 0 6px; }
        .ap-content-area td, .ap-content-area th, .ap-content-area span,
        .ap-content-area a, .ap-content-area em, .ap-content-area i { color: #000000 !important; }

        .ap-acceptance {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #f5f3ff 100%);
            border: 2px solid #c4b5fd; border-radius: 14px; padding: 28px 32px;
            margin: 28px 40px 0; display: flex; gap: 24px; align-items: flex-start;
            box-shadow: 0 4px 16px rgba(76,29,149,0.08), inset 0 2px 12px rgba(76,29,149,0.04);
            position: relative; overflow: hidden;
            page-break-inside: avoid;
        }
        .ap-acceptance::before {
            content: ''; position: absolute; top: 50%; right: 20px;
            transform: translateY(-50%); width: 180px; height: 180px;
            background: url('{{ public_path("watermark-logo.png") }}') no-repeat center center;
            background-size: contain; opacity: 0.07; pointer-events: none;
        }
        .ap-acceptance > * { position: relative; z-index: 1; }
        .ap-acceptance-icon {
            width: 56px; height: 56px; background: linear-gradient(135deg, #7c3aed, #4c1d95);
            border: none; border-radius: 14px; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; color: #fff; box-shadow: 0 6px 20px rgba(76,29,149,0.3);
        }
        .ap-acceptance-icon svg { width: 26px; height: 26px; }
        .ap-acceptance h4 { font-size: 15px; color: #1e1b4b; margin: 0 0 14px; font-weight: 800; letter-spacing: 0.8px; }
        .ap-sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 28px; }
        .ap-sig-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; font-weight: 600; margin-bottom: 2px; }
        .ap-sig-value { font-size: 12px; color: #111827; font-weight: 600; }
        .ap-sig-note {
            margin-top: 14px; padding-top: 12px; border-top: 1px dashed #c4b5fd;
            font-size: 10.5px; color: #4c1d95; font-weight: 600;
            display: flex; align-items: center; gap: 6px;
        }

        .ap-footer {
            background: linear-gradient(180deg, #1e1b4b 0%, #0f0524 100%); color: #fff;
            padding: 0; margin-top: 32px; position: relative; overflow: hidden;
        }
        .ap-footer::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 180px; height: 180px;
            background: url('{{ public_path("watermark-logo.png") }}') no-repeat center center;
            background-size: contain; opacity: 0.04; pointer-events: none;
        }
        .ap-footer > * { position: relative; z-index: 1; }
        .ap-footer-inner { padding: 32px 40px 0; }
        .ap-footer-logo { text-align: center; margin-bottom: 20px; width: 100%; }
        .ap-footer-logo img { display: block; margin: 0 auto; height: 42px; width: auto; filter: brightness(0) invert(1); opacity: 0.85; }
        .ap-footer-divider {
            height: 1px; margin: 0 0 18px;
            background: linear-gradient(90deg, transparent, rgba(167,139,250,0.25), transparent);
        }
        .ap-footer-strip {
            display: flex; justify-content: center; gap: 28px; flex-wrap: wrap;
            font-size: 10.5px; color: #c4b5fd;
        }
        .ap-footer-strip strong { color: #e9d5ff; font-weight: 700; }
        .ap-footer-disclaimer {
            margin-top: 18px; padding: 14px 20px;
            background: rgba(255,255,255,0.03); border-radius: 8px;
            font-size: 9px; color: #8b7fc7; line-height: 1.65; text-align: center;
        }
        .ap-footer-bottom {
            margin-top: 18px; padding: 14px 40px;
            background: rgba(0,0,0,0.25);
            display: flex; justify-content: space-between; align-items: center;
            font-size: 9px; color: #7c6daa;
        }
        .ap-footer-bottom a { color: #a78bfa; text-decoration: none; }
        .ap-footer-links { display: flex; gap: 18px; align-items: center; }
        .ap-footer-social { display: flex; gap: 10px; align-items: center; }
        .ap-footer-social a {
            display: inline-flex; align-items: center; justify-content: center;
            width: 24px; height: 24px; border-radius: 50%;
            background: rgba(167,139,250,0.12); color: #a78bfa;
        }
        .ap-footer-social a svg { width: 12px; height: 12px; }

        .ap-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #e9e5f5; }
        .ap-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .ap-table thead { background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
        .ap-table th { padding: 10px 16px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #4c1d95; border-bottom: 2px solid #e9e5f5; }
        .ap-table td { padding: 12px 16px; color: #374151; border-bottom: 1px solid #f3f4f6; }
        .ap-table tbody tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>
    @if ($version)
        @include('partials._agreement-document', ['version' => $version, 'pdfMode' => true])
    @else
        <div style="padding: 60px; text-align: center; color: #6b7280;">
            <p>No version available for this agreement.</p>
        </div>
    @endif
</body>
</html>
