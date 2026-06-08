<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Notes — {{ $module->nom }}</title>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#1f2937;margin:0;padding:0}
.header{background:#4f46e5;color:white;padding:16px 24px;display:flex;justify-content:space-between;align-items:center}
.logo{font-size:16px;font-weight:bold}
.body{padding:20px 24px}
h2{font-size:15px;color:#4f46e5;margin:0 0 4px}
.meta{font-size:10px;color:#6b7280;margin-bottom:16px}
table{width:100%;border-collapse:collapse}
th{background:#f9fafb;padding:7px 10px;text-align:left;font-size:9px;text-transform:uppercase;color:#6b7280;border-bottom:1px solid #e5e7eb}
td{padding:7px 10px;border-bottom:1px solid #f3f4f6;font-size:10px}
.green{color:#16a34a;font-weight:bold}.red{color:#dc2626;font-weight:bold}.blue{color:#2563eb}
.stats{display:table;width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:12px;margin-top:16px}
.stat-cell{display:table-cell;text-align:center}
.stat-val{font-size:18px;font-weight:bold;color:#4f46e5}
.stat-lbl{font-size:9px;color:#6b7280}
.footer{margin-top:16px;border-top:1px solid #e5e7eb;padding-top:8px;color:#9ca3af;font-size:8px;text-align:center}
.badge{display:inline-block;padding:1px 6px;border-radius:9999px;font-size:8px;font-weight:bold}
.badge-green{background:#dcfce7;color:#166534}.badge-blue{background:#dbeafe;color:#1e40af}
.badge-yellow{background:#fef9c3;color:#854d0e}.badge-red{background:#fee2e2;color:#991b1b}
</style>
</head>
<body>
<div class="header">
    <div><div class="logo">UniPilot</div><div style="font-size:10px;opacity:.8">Gestion des notes</div></div>
    <div style="text-align:right;font-size:9px;opacity:.8">{{ now()->format('d/m/Y H:i') }}</div>
</div>
<div class="body">
    <h2>Notes — {{ $module->nom }}</h2>
    <div class="meta">{{ $module->programme->nom }} · Année {{ $annee }} · {{ $notes->count() }} étudiants</div>
    <table>
        <thead><tr>
            <th>Étudiant</th><th>N° Carte</th><th>CC1</th><th>CC2</th><th>Examen</th><th>Note Finale</th><th>Mention</th>
        </tr></thead>
        <tbody>
        @foreach($notes as $note)
        @php $nf=$note->note_finale??0; $cls=$nf>=14?'green':($nf>=10?'blue':'red');
        $mcls=match($note->mention??''){
            'Très Bien'=>'badge-green','Bien'=>'badge-blue','Assez Bien'=>'badge-blue','Passable'=>'badge-yellow',default=>'badge-red'
        }; @endphp
        <tr>
            <td style="font-weight:600">{{ $note->etudiant->nom }}</td>
            <td style="font-family:monospace">{{ $note->etudiant->numero_carte }}</td>
            <td>{{ $note->note_cc1 !== null ? number_format($note->note_cc1,1) : '—' }}</td>
            <td>{{ $note->note_cc2 !== null ? number_format($note->note_cc2,1) : '—' }}</td>
            <td>{{ $note->note_examen !== null ? number_format($note->note_examen,1) : '—' }}</td>
            <td class="{{ $cls }}">{{ $nf ? number_format($nf,1) : '—' }}</td>
            <td>@if($note->mention)<span class="badge {{ $mcls }}">{{ $note->mention }}</span>@else —@endif</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @if(count($statsClasse))
    <div class="stats">
        <div class="stat-cell"><div class="stat-val">{{ $statsClasse['moyenne'] }}/20</div><div class="stat-lbl">Moyenne classe</div></div>
        <div class="stat-cell"><div class="stat-val green">{{ $statsClasse['taux_reussite'] }}%</div><div class="stat-lbl">Taux réussite</div></div>
        <div class="stat-cell"><div class="stat-val blue">{{ $statsClasse['note_max'] }}</div><div class="stat-lbl">Note max</div></div>
        <div class="stat-cell"><div class="stat-val red">{{ $statsClasse['note_min'] }}</div><div class="stat-lbl">Note min</div></div>
    </div>
    @endif
    <div class="footer">UniPilot — Document généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>
</body>
</html>
