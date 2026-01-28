<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de paiement</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #222;
        }

        /* --- PALETTE SHOPEASY --- */
        :root {
            --noir: #000000;
            --doré: #D4AF37;
            --gris: #555;
        }

        /* --- EN-TÊTE PREMIUM --- */
        header {
            background: var(--noir);
            color: var(--doré);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid var(--doré);
        }

        header img {
            height: 65px;
            width: auto;
        }

        .company-info {
            text-align: right;
            font-size: 13px;
            line-height: 1.6;
        }

        /* --- CONTENU PRINCIPAL --- */
        main {
            background: #fff;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px 50px;
            border-radius: 10px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            color: var(--noir);
            font-size: 24px;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .gold-line {
            width: 80px;
            height: 3px;
            background: var(--doré);
            margin: 0 auto 25px auto;
            border-radius: 4px;
        }

        .section-title {
            font-size: 15px;
            color: var(--noir);
            margin-bottom: 10px;
            font-weight: bold;
            border-left: 4px solid var(--doré);
            padding-left: 8px;
        }

        .info-block {
            margin-bottom: 18px;
            padding: 15px 20px;
            border: 1px solid #eee;
            border-left: 4px solid var(--doré);
            background: #fafafa;
            border-radius: 6px;
        }

        .info-block p {
            font-size: 13px;
            margin: 6px 0;
        }

        .info-block strong {
            color: var(--noir);
        }

        .description {
            font-size: 12px;
            text-align: justify;
            line-height: 1.6;
            margin-top: 18px;
        }

        /* --- SIGNATURES --- */
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            padding-top: 20px;
            font-size: 13px;
            border-top: 2px solid var(--doré);
            color: var(--noir);
        }

        /* --- FOOTER --- */
        footer {
            background: var(--noir);
            color: var(--doré);
            text-align: center;
            padding: 12px;
            font-size: 12px;
            width: 100%;
            position: fixed;
            bottom: 0;
        }

        .header-table{ background:var(--noir); color:var(--doré); padding:20px 40px; border-bottom:4px solid var(--doré); }



    </style>
</head>

<body>

    <!-- EN-TÊTE -->
    <header class="header-table">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="vertical-align:middle; width:1%;">
                    <img src="{{ public_path('assets\images\logo_sahashop_réduits.png') }}" alt="Logo Shopeasy" style="height:100px; width:auto; display:block;">
                </td>
                <td style="vertical-align:middle; text-align:right; white-space:nowrap; padding-left:15px;">
                    <strong style="font-size:16px; display:block;">SHOPEASY SERVICE SARL</strong>
                    Plateforme de vente en ligne & livraison à domicile<br>
                    Tél : +229 01 23 45 67 89 <br>
                    Email : contact@shopeasy.com<br>
                    Web : shopeasy.com
                </td>
            </tr>
        </table>
    </header>
    

    <!-- CONTENU -->
    <main>

        <h2>REÇU DE PAIEMENT</h2>
        <div class="gold-line"></div>

        <div class="info-block">
            <p><strong>Nom du client :</strong> {{ $attribution->client->name }} {{ $attribution->client->surname }}</p>
            <p><strong>Téléphone :</strong> {{ $attribution->client->telephone ?? '—' }}</p>
            <p><strong>Bien acheté :</strong> {{ $attribution->bien->titre }}</p>
            {{-- <p><strong>Catégorie :</strong> {{ $attribution->bien->categorie?->name ?? '—' }}</p>
            <p><strong>Sous-catégorie :</strong> {{ $attribution->bien->sousCategorie?->name ?? '—' }}</p> 
            <p><strong>Type :</strong> {{ $attribution->bien->subType?->name ?? '—' }}</p>       --}}
            <p><strong>Date d’achat :</strong> {{ $attribution->date_attribution->format('d/m/Y') }}</p>
            <p><strong>Prix payé :</strong> {{ number_format($attribution->prix, 0, ',', ' ') }} FCFA</p>
            <p><strong>Statut :</strong> Payé</p>
        </div>

        <p class="description">
            Ce reçu atteste que le client <strong>{{ $attribution->client->name }} {{ $attribution->client->surname }}</strong> a effectué le paiement du bien mentionné ci-dessus via la plateforme 
            <strong>shopeasy.com</strong>.  
            La livraison est assurée à domicile conformément aux modalités définies sur le site.  
            <br><br>
            Shopeasy met en relation les propriétaires et les clients, garantit la transparence des achats 
            et fluidifie les transactions grâce à un système sécurisé et automatisé.
        </p>

        <!-- SIGNATURES -->
        <div class="signatures">
            <div class="signature-box">
                <strong>Le Vendeur</strong><br>
                {{ Auth::user()->name }} {{ Auth::user()->surname }}
            </div>


            <div class="signature-box">
                <strong>Le Client</strong><br>
                {{ $attribution->client->name }} {{ $attribution->client->surname }}
            </div>
        </div>
    

    </main>

    <div>
        <i><h5 class="text-end"><strong>Imprimé le :</strong> {{ now()->format('d/m/Y') }}</h5></i>
    </div>

    <!-- FOOTER -->
    <footer>
        © {{ date('Y') }} SHOPEASY SERVICE SARL — Tous droits réservés
    </footer>

</body>
</html>
