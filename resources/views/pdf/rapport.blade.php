<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport Analytique S{{ $semestre }} — {{ $annee }}</title>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#1f2937;margin:0;padding:0}
.header{background:#4f46e5;color:white;padding:20px 28px}
.logo{font-size:18px;font-weight:bold}
.body{padding:24px 28px}
h2{font-size:16px;color:#4f46e5;margin:0 0 4px}
.meta{font-size:10px;color:#6b7280;margin-bottom:20px}
.stats-grid{display:table;width:100%;margin-bottom:20px}
.stat-cell{display:table-cell;width:25%;text-align:center;padding:12px;background:#f9fafb;border:1px solid #e5e7eb}
.stat-val{font-size:22px;font-weight:bold;color:#4f46e5}
.stat-lbl{font-size:9px;color:#6b7280;margin-top:2px}
h3{font-size:13px;color:#374151;margin:16px 0 8px;border-bottom:1px solid #e5e7eb;padding-bottom:4px}
table{width:100%;border-collapse:collapse;margin-bottom:16px}
th{background:#f9fafb;padding:6px 10px;text-align:left;font-size:9px;text-transform:uppercase;color:#6b7280;border-bottom:1px solid #e5e7eb}
td{padding:6px 10px;border-bottom:1px solid #f3f4f6;font-size:10px}
.green{color:#16a34a;font-weight:bold}.red{color:#dc2626;font-weight:bold}.blue{color:#2563eb}
.mentions-table td:first-child{font-weight:600}
.progress{background:#e5e7eb;border-radius:9999px;height:8px;overflow:hidden}
.progress-bar{background:#4f46e5;height:100%;border-radius:9999px}
.footer{margin-top:20px;border-top:1px solid #e5e7eb;padding-top:10px;color:#9ca3af;font-size:8px;text-align:center}
</style>
</head>
<body>
<div class="header">
    <div class="logo">UniPilot</div>
    <div style="font-size:11px;opacity:.8;margin-top:2px">Rapport Analytique — Semestre {{ $semestre }} — {{ $annee }}</div>
    <div style="font-size:9px;opacity:.7;margin-top:4px">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>
<div class="body">
    <h2>Rapport & Analytics — Semestre {{ $semestre }}</h2>
    <div class="meta">Année académique {{ $annee }}</div>

    {{-- Stats générales --}}
    <div class="stats-grid">
        <div class="stat-cell"><div class="stat-val">{{ $statsGenerales['total_etudiants'] }}</div><div class="stat-lbl">Étudiants actifs</div></div>
        <div class="stat-cell"><div class="stat-val">{{ $statsGenerales['moyenne_generale'] }}/20</div><div class="stat-lbl">Moyenne générale</div></div>
        <div class="stat-cell"><div class="stat-val" style="color:#16a34a">{{ $statsGenerales['taux_reussite'] }}%</div><div class="stat-lbl">Taux de réussite</div></div>
        <div class="stat-cell"><div class="stat-val" style="color:#06b6d4">{{ $statsGenerales['taux_presence'] }}%</div><div class="stat-lbl">Taux de présence</div></div>
    </div>

    {{-- Moyennes par module --}}
    <h3>Moyennes par module</h3>
    <table>
        <thead><tr><th>Module</th><th>Programme</th><th>Moyenne</th><th>Progression</th></tr></thead>
        <tbody>
        @foreach($moyennesModules as $module)
        @php $moy = (float)($module->moy ?? 0); @endphp
        <tr>
            <td style="font-weight:600">{{ $module->nom }}</td>
            <td style="color:#6b7280">{{ $module->programme?->code }}</td>
            <td class="{{ $moy>=14?'green':($moy>=10?'blue':'red') }}">{{ number_format($moy,1) }}/20</td>
            <td>
                <div class="progress"><div class="progress-bar" style="width:{{ min(100,($moy/20)*100) }}%"></div></div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Distribution mentions --}}
    <h3>Distribution des mentions</h3>
    <table class="mentions-table">
        <thead><tr><th>Mention</th><th>Nombre</th><th>Pourcentage</th></tr></thead>
        <tbody>
        @php $total = $distributionMentions->sum('count'); @endphp
        @foreach($distributionMentions as $dm)
        <tr>
            <td>{{ $dm->mention }}</td>
            <td>{{ $dm->count }}</td>
            <td>{{ $total > 0 ? round($dm->count/$total*100,1) : 0 }}%</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <div class="footer">UniPilot — Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }} — Document confidentiel</div>
</div>
</body>
</html>
