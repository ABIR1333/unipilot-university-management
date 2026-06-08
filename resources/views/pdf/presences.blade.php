<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Présences — {{ $module->nom }}</title>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#1f2937;margin:0;padding:0}
.header{background:#4f46e5;color:white;padding:16px 24px;display:flex;justify-content:space-between}
.logo{font-size:16px;font-weight:bold}
.body{padding:20px 24px}
h2{font-size:15px;color:#4f46e5;margin:0 0 4px}
.meta{font-size:10px;color:#6b7280;margin-bottom:16px}
table{width:100%;border-collapse:collapse}
th{background:#f9fafb;padding:7px 10px;text-align:left;font-size:9px;text-transform:uppercase;color:#6b7280;border-bottom:1px solid #e5e7eb}
td{padding:7px 10px;border-bottom:1px solid #f3f4f6;font-size:10px}
.green{color:#16a34a;font-weight:bold}
.red{color:#dc2626;font-weight:bold}
.yellow{color:#d97706;font-weight:bold}
.alerte{color:#d97706;font-size:9px}
.progress{background:#e5e7eb;border-radius:9999px;height:6px;overflow:hidden;width:80px;display:inline-block;vertical-align:middle}
.progress-bar{background:#4f46e5;height:100%;border-radius:9999px}
.footer{margin-top:16px;border-top:1px solid #e5e7eb;padding-top:8px;color:#9ca3af;font-size:8px;text-align:center}
</style>
</head>
<body>
<div class="header">
    <div><div class="logo">UniPilot</div><div style="font-size:10px;opacity:.8">Suivi des présences</div></div>
    <div style="text-align:right;font-size:9px;opacity:.8">{{ now()->format('d/m/Y H:i') }}</div>
</div>
<div class="body">
    <h2>Présences — {{ $module->nom }}</h2>
    <div class="meta">{{ $module->programme->nom }} · {{ $etudiants->count() }} étudiants</div>

    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>N° Carte</th>
                <th>Présences</th>
                <th>Absences</th>
                <th>Justifiées</th>
                <th>Taux</th>
                <th>Alerte</th>
            </tr>
        </thead>
        <tbody>
        @foreach($etudiants as $etudiant)
        @php
            $ep = $presences->where('etudiant_id', $etudiant->id);
            $total    = $ep->count();
            $presents = $ep->where('statut','Présent')->count();
            $absents  = $ep->where('statut','Absent')->count();
            $justifies= $ep->where('statut','Justifié')->count();
            $taux     = $total > 0 ? round($presents/$total*100,1) : 0;
        @endphp
        <tr>
            <td style="font-weight:600">{{ $etudiant->nom }}</td>
            <td style="font-family:monospace">{{ $etudiant->numero_carte }}</td>
            <td class="green">{{ $presents }}</td>
            <td class="red">{{ $absents }}</td>
            <td class="yellow">{{ $justifies }}</td>
            <td>
                <div class="progress"><div class="progress-bar" style="width:{{ $taux }}%"></div></div>
                <span style="margin-left:4px;font-weight:600">{{ $taux }}%</span>
            </td>
            <td>@if($taux < 75)<span class="alerte">⚠ Alerte</span>@endif</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <div class="footer">UniPilot — Rapport de présences généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>
</body>
</html>
