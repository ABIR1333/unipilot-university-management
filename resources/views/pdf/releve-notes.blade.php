<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Relevé de notes — {{ $etudiant->nom }}</title>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#1f2937;margin:0;padding:0}
.header{background:#4f46e5;color:white;padding:20px 28px;display:flex;justify-content:space-between;align-items:center}
.logo{font-size:18px;font-weight:bold}.subtitle{font-size:10px;opacity:.8;margin-top:2px}
.body{padding:24px 28px}
.title{font-size:16px;font-weight:bold;color:#4f46e5;margin-bottom:16px;border-bottom:2px solid #e5e7eb;padding-bottom:8px}
.info-grid{display:table;width:100%;margin-bottom:20px}
.info-col{display:table-cell;width:50%;vertical-align:top}
.info-label{color:#6b7280;font-size:9px;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px}
.info-value{font-weight:bold;font-size:12px;margin-bottom:6px}
.sem-title{background:#eef2ff;padding:7px 12px;font-weight:bold;color:#3730a3;font-size:11px;margin:14px 0 8px;border-left:4px solid #4f46e5}
table{width:100%;border-collapse:collapse;margin-bottom:10px}
th{background:#f9fafb;padding:7px 10px;text-align:left;font-size:9px;text-transform:uppercase;color:#6b7280;border-bottom:1px solid #e5e7eb}
td{padding:7px 10px;border-bottom:1px solid #f3f4f6;font-size:11px}
.green{color:#16a34a;font-weight:bold}.blue{color:#2563eb;font-weight:bold}
.orange{color:#d97706;font-weight:bold}.red{color:#dc2626;font-weight:bold}
.summary{background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:14px;margin-top:20px;display:table;width:100%}
.summary-cell{display:table-cell;text-align:center;border:none}
.big{font-size:24px;font-weight:bold;color:#4f46e5}
.footer{margin-top:24px;border-top:1px solid #e5e7eb;padding-top:10px;color:#9ca3af;font-size:8px;text-align:center}
.mention-badge{display:inline-block;padding:2px 8px;border-radius:9999px;font-size:9px;font-weight:bold}
.mention-TB{background:#dcfce7;color:#166534}
.mention-B{background:#dbeafe;color:#1e40af}
.mention-AB{background:#cffafe;color:#164e63}
.mention-P{background:#fef9c3;color:#854d0e}
.mention-I{background:#fee2e2;color:#991b1b}
</style>
</head>
<body>
<div class="header">
    <div><div class="logo">UniPilot</div><div class="subtitle">Université — Système de gestion</div></div>
    <div style="text-align:right;font-size:10px;opacity:.8">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>
<div class="body">
    <div class="title">Relevé de Notes Officiel</div>
    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Nom complet</div><div class="info-value">{{ $etudiant->nom }}</div>
            <div class="info-label">N° Carte étudiant</div><div class="info-value">{{ $etudiant->numero_carte }}</div>
            <div class="info-label">Email</div><div class="info-value" style="font-weight:normal">{{ $etudiant->email }}</div>
        </div>
        <div class="info-col">
            <div class="info-label">Programme</div><div class="info-value">{{ $etudiant->programme->nom }}</div>
            <div class="info-label">Type</div><div class="info-value">{{ $etudiant->programme->type }}</div>
            <div class="info-label">Semestre actuel</div><div class="info-value">Semestre {{ $etudiant->semestre_actuel }}</div>
        </div>
    </div>

    @forelse($notesBySemestre as $semestre => $notes)
    <div class="sem-title">Semestre {{ $semestre }}</div>
    <table>
        <thead><tr>
            <th>Module</th><th>CC1 /20</th><th>CC2 /20</th><th>Examen /20</th><th>Note Finale</th><th>Mention</th>
        </tr></thead>
        <tbody>
        @foreach($notes as $note)
        @php
        $nf = $note->note_finale ?? 0;
        $cls = $nf>=14?'green':($nf>=12?'blue':($nf>=10?'orange':'red'));
        $mcls = match($note->mention){
            'Très Bien'=>'mention-TB','Bien'=>'mention-B','Assez Bien'=>'mention-AB','Passable'=>'mention-P',default=>'mention-I'
        };
        @endphp
        <tr>
            <td>{{ $note->module->nom }}</td>
            <td>{{ $note->note_cc1 !== null ? number_format($note->note_cc1,1) : '—' }}</td>
            <td>{{ $note->note_cc2 !== null ? number_format($note->note_cc2,1) : '—' }}</td>
            <td>{{ $note->note_examen !== null ? number_format($note->note_examen,1) : '—' }}</td>
            <td class="{{ $cls }}">{{ $note->note_finale !== null ? number_format($note->note_finale,1) : '—' }}</td>
            <td>@if($note->mention)<span class="mention-badge {{ $mcls }}">{{ $note->mention }}</span>@else —@endif</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @empty
    <p style="color:#9ca3af;text-align:center;padding:20px">Aucune note disponible.</p>
    @endforelse

    <div class="summary">
        <div class="summary-cell"><div class="big">{{ number_format($etudiant->moyenne_generale,2) }}/20</div><div style="color:#6b7280;font-size:9px;margin-top:2px">Moyenne générale</div></div>
        <div class="summary-cell"><div class="big" style="color:#16a34a">{{ $etudiant->statut }}</div><div style="color:#6b7280;font-size:9px;margin-top:2px">Statut académique</div></div>
        <div class="summary-cell"><div class="big" style="color:#6366f1">S{{ $etudiant->semestre_actuel }}</div><div style="color:#6b7280;font-size:9px;margin-top:2px">Semestre en cours</div></div>
    </div>

    <div class="footer">
        Document officiel émis par UniPilot — Université de démonstration · {{ now()->format('Y-m-d H:i:s') }}<br>
        Ce document ne requiert pas de signature pour être valide.
    </div>
</div>
</body>
</html>
