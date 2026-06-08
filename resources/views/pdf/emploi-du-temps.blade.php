<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Emploi du temps — S{{ $semestre }}</title>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:9px;color:#1f2937;margin:0;padding:0}
.header{background:#4f46e5;color:white;padding:14px 20px;display:flex;justify-content:space-between;align-items:center}
.logo{font-size:15px;font-weight:bold}
.body{padding:16px 20px}
h2{font-size:14px;color:#4f46e5;margin:0 0 12px}
table{width:100%;border-collapse:collapse}
th{background:#f9fafb;padding:8px 6px;text-align:center;font-size:8px;text-transform:uppercase;color:#6b7280;border:1px solid #e5e7eb;font-weight:700}
td{padding:4px;border:1px solid #e5e7eb;vertical-align:top;width:20%;min-height:60px}
.seance{padding:6px;border-radius:4px;margin-bottom:4px;border-left:3px solid}
.seance-CM{background:#dbeafe;border-color:#3b82f6;color:#1e40af}
.seance-TD{background:#f3e8ff;border-color:#a855f7;color:#6b21a8}
.seance-TP{background:#dcfce7;border-color:#22c55e;color:#166534}
.seance-nom{font-weight:700;font-size:9px}
.seance-info{font-size:8px;margin-top:1px;opacity:.8}
.type-badge{display:inline-block;padding:1px 5px;border-radius:9999px;font-size:8px;font-weight:700;margin-top:2px}
.footer{margin-top:12px;border-top:1px solid #e5e7eb;padding-top:8px;color:#9ca3af;font-size:8px;text-align:center}
</style>
</head>
<body>
<div class="header">
    <div><div class="logo">UniPilot</div><div style="font-size:9px;opacity:.8">Emploi du temps — Semestre {{ $semestre }} — {{ $annee }}</div></div>
    <div style="font-size:8px;opacity:.7">{{ now()->format('d/m/Y H:i') }}</div>
</div>
<div class="body">
    <h2>Emploi du temps — Semestre {{ $semestre }}</h2>
    <table>
        <thead>
            <tr>
                @foreach($jours as $jour)
                <th>{{ strtoupper($jour) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($jours as $jour)
                <td>
                    @foreach($emplois->get($jour, collect()) as $seance)
                    <div class="seance seance-{{ $seance->type_seance }}">
                        <div class="seance-nom">{{ $seance->module->nom }}</div>
                        <div class="seance-info">
                            {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}–{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                        </div>
                        @if($seance->salle)
                        <div class="seance-info">{{ $seance->salle->nom }}</div>
                        @endif
                        <span class="type-badge">{{ $seance->type_seance }}</span>
                    </div>
                    @endforeach
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div style="margin-top:10px;display:flex;gap:10px;font-size:9px">
        @foreach(['CM'=>['#dbeafe','#1e40af'],'TD'=>['#f3e8ff','#6b21a8'],'TP'=>['#dcfce7','#166534']] as $type=>[$bg,$color])
        <span style="background:{{ $bg }};color:{{ $color }};padding:2px 8px;border-radius:9999px;font-weight:700">{{ $type }}</span>
        @endforeach
    </div>

    <div class="footer">UniPilot — Emploi du temps généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>
</body>
</html>
