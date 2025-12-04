<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande Approuvée - Eat&Drink</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #22c55e;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #22c55e;
            margin-bottom: 10px;
        }
        .success-icon {
            font-size: 48px;
            color: #22c55e;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #22c55e;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
        .highlight {
            background-color: #f0f9ff;
            padding: 15px;
            border-left: 4px solid #22c55e;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🍽️ Eat&Drink</div>
            <div class="success-icon">✅</div>
            <h1 style="color: #22c55e; margin: 0;">Demande Approuvée !</h1>
        </div>

        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Se connecter à mon compte</a>
            </div>

            <p>Si vous avez des questions ou besoin d'aide, n'hésitez pas à nous contacter.</p>

            <p>Cordialement,<br>
            <strong>L'équipe Eat&Drink</strong></p>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            <p>© 2025 Eat&Drink - Tous droits réservés</p>
        </div>
    </div>
</body>
</html> 