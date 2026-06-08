<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Attestation de scolarité — {{ $etudiant->nom }}</title>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#1f2937;margin:0;padding:40px 50px}
.border-outer{border:3px solid #4f46e5;padding:30px;min-height:650px;position:relative}
.border-inner{border:1px solid #c7d2fe;padding:20px;min-height:600px}
.header{text-align:center;border-bottom:2px solid #e5e7eb;padding-bottom:16px;margin-bottom:20px}
.logo{font-size:22px;font-weight:bold;color:#4f46e5}
.univ{font-size:13px;color:#6b7280;margin-top:3px}
.title{font-size:20px;font-weight:bold;text-align:center;margin:20px 0;color:#1f2937;text-transform:uppercase;letter-spacing:.05em}
.subtitle{text-align:center;font-size:12px;color:#6b7280;margin-bottom:28px}
.body-text{font-size:13px;line-height:2;text-align:justify;color:#374151}
.highlight{font-weight:bold;color:#1f2937}
.field-box{background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:14px 18px;margin:20px 0;font-size:12px}
.field-row{display:table;width:100%;margin-bottom:6px}
.field-label{display:table-cell;width:45%;color:#6b7280;font-size:10px;text-transform:uppercase;letter-spacing:.05em;vertical-align:middle}
.field-value{display:table-cell;font-weight:bold;font-size:12px;vertical-align:middle}
.footer{margin-top:40px;display:table;width:100%}
.footer-left{display:table-cell;width:60%;vertical-align:bottom;font-size:10px;color:#6b7280}
.footer-right{display:table-cell;width:40%;text-align:right;vertical-align:bottom}
.signature-box{border-top:1px solid #374151;padding-top:8px;font-size:10px;color:#374151;text-align:center;width:160px;display:inline-block}
.stamp{width:80px;height:80px;border-radius:50%;border:3px solid #4f46e5;display:flex;align-items:center;justify-content:center;text-align:center;font-size:8px;color:#4f46e5;font-weight:bold;margin:0 auto 8px}
.ref{font-size:9px;color:#9ca3af;text-align:center;margin-top:10px}
</style>
</head>
<body>
<div class="border-outer">
<div class="border-inner">
    <div class="header">
        <div class="logo">UniPilot</div>
        <div class="univ">Université de démonstration — Administration</div>
    </div>

    <div class="title">Attestation de Scolarité</div>
    <div class="subtitle">Année académique 2024–2025</div>

    <div class="body-text">
        <p>Nous soussignés, <span class="highlight">l'Administration de UniPilot</span>, attestons par la présente que :</p>
    </div>

    <div class="field-box">
        <div class="field-row">
            <div class="field-label">Nom et prénom</div>
            <div class="field-value">{{ $etudiant->nom }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">N° carte étudiant</div>
            <div class="field-value">{{ $etudiant->numero_carte }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Programme</div>
            <div class="field-value">{{ $etudiant->programme->nom }} ({{ $etudiant->programme->type }})</div>
        </div>
        <div class="field-row">
            <div class="field-label">Semestre en cours</div>
            <div class="field-value">Semestre {{ $etudiant->semestre_actuel }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Date d'inscription</div>
            <div class="field-value">{{ $etudiant->date_inscription->format('d/m/Y') }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Statut</div>
            <div class="field-value">{{ $etudiant->statut }}</div>
        </div>
    </div>

    <div class="body-text">
        <p>est dûment <span class="highlight">inscrit(e) et régulièrement suivi(e)</span> dans notre établissement pour l'année académique en cours.</p>
        <p>La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>
    </div>

    <div class="footer">
        <div class="footer-left">
            Fait à Paris, le {{ now()->format('d/m/Y') }}<br>
            <span style="color:#9ca3af;font-size:9px">Réf. : ATT-{{ $etudiant->numero_carte }}-{{ now()->format('Ymd') }}</span>
        </div>
        <div class="footer-right">
            <div class="stamp">UNICAMP<br>OFFICIEL<br>2024-2025</div>
            <div class="signature-box">
                Le Responsable de la Scolarité<br>
                Admin UPP
            </div>
        </div>
    </div>

    <div class="ref">Document généré automatiquement par UniPilot le {{ now()->format('d/m/Y à H:i') }}</div>
</div>
</div>
</body>
</html>
