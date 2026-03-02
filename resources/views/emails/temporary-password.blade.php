<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe temporaire</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f6fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 15px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        p {
            line-height: 1.6;
        }
        .password-box {
            background-color: #f1f2f6;
            border: 1px dashed #2c3e50;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
        a.button {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 25px;
            background-color: #2c3e50;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Logo de l'entreprise -->
            <img src="{{ asset('assets/images/logo_sahashop.png') }}" alt="Logo ShopEasy">
        </div>

        <h2>Bienvenue sur ShopEasy</h2>
        <p>Votre compte a été créé avec succès.</p>

        <div class="password-box">
            Mot de passe temporaire : {{ $passwordTemp }}
        </div>

        <p>Veuillez vous connecter et modifier votre mot de passe dès que possible.</p>
        <a href="{{ $loginLink }}" class="button">Se connecter</a>

        <div class="footer">
            <p>Cordialement,</p>
            <p>L'équipe administrative de ShopEasy</p>
        </div>
    </div>
</body>
</html>
