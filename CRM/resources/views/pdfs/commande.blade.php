<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $commande->id }}</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            padding: 40px;
        }

        .header {
            background: linear-gradient(90deg, #4CAF50, #2e7d32);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .section {
            margin: 30px 0;
        }

        .info-block {
            display: flex;
            justify-content: space-between;
        }

        .info-block div {
            width: 48%;
        }

        .info-block p {
            margin: 4px 0;
            line-height: 1.5;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .table th {
            background-color: #e8f5e9;
            padding: 10px;
            border: 1px solid #c8e6c9;
            text-align: left;
        }

        .table td {
            padding: 10px;
            border: 1px solid #e0e0e0;
        }

        .totals {
            margin-top: 30px;
            float: right;
            width: 50%;
        }

        .totals td {
            padding: 8px;
            text-align: right;
        }

        .totals tr:last-child td {
            font-weight: bold;
            background-color: #f1f8e9;
        }

        .footer {
            margin-top: 60px;
            font-size: 11px;
            color: #666;
            text-align: center;
        }

        .observations {
            margin-top: 30px;
            border-left: 4px solid #4CAF50;
            padding-left: 15px;
            font-size: 11px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Facture</h1>
    <div>
        <p><strong>Commande n° :</strong> {{ $commande->id }}</p>
        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</p>
    </div>
</div>

<div class="wrapper">

    <div class="section info-block">
        <div>
            <h3>Émetteur :</h3>
            <p><strong>NaturaCorp</strong><br>
                123 Avenue Verte<br>
                contact@natura.test
            </p>
        </div>
        <div>
            <h3>Destinataire :</h3>
            <p><strong>{{ $commande->pharmacie->nom }}</strong><br>
                {{ $commande->pharmacie->adresse }}<br>
                {{ $commande->pharmacie->code_postal }} {{ $commande->pharmacie->ville }}
            </p>
        </div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Description</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Commande de produits</td>
            <td>{{ number_format($commande->tarif_unitaire, 2, ',', ' ') }} €</td>
            <td>{{ $commande->quantite }}</td>
            <td>{{ number_format($commande->quantite * $commande->tarif_unitaire, 2, ',', ' ') }} €</td>
        </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Sous-total HT</td>
            <td>{{ number_format($commande->quantite * $commande->tarif_unitaire, 2, ',', ' ') }} €</td>
        </tr>
        <tr>
            <td>TVA (20 %)</td>
            <td>{{ number_format(($commande->quantite * $commande->tarif_unitaire) * 0.20, 2, ',', ' ') }} €</td>
        </tr>
        <tr>
            <td>Total TTC</td>
            <td>{{ number_format(($commande->quantite * $commande->tarif_unitaire) * 1.20, 2, ',', ' ') }} €</td>
        </tr>
    </table>

    @if($commande->observations)
        <div class="observations">
            <strong>Observations :</strong><br>
            {{ $commande->observations }}
        </div>
    @endif

    <div class="footer">
        Merci pour votre confiance. Cette facture est générée automatiquement et ne nécessite pas de signature.
    </div>

</div>

</body>
</html>
