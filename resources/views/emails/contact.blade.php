<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        /* Reset et styles de base pour les clients mail */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Conteneur principal */
        .email-wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 40px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* En-tête */
        .email-header {
            background-color: #2563eb; /* Bleu professionnel */
            padding: 30px 40px;
            text-align: center;
        }
        
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        /* Corps du message */
        .email-body {
            padding: 40px;
        }

        /* Bloc d'informations (Nom, Email, Sujet) */
        .info-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .info-row {
            margin-bottom: 12px;
            font-size: 15px;
            line-height: 1.5;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
            display: inline-block;
            width: 80px;
        }

        .info-value {
            color: #111827;
        }

        /* Section du contenu du message */
        .message-section h2 {
            font-size: 16px;
            color: #374151;
            margin: 0 0 15px 0;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
            font-weight: 600;
        }

        .message-content {
            font-size: 15px;
            line-height: 1.6;
            color: #374151;
            white-space: pre-wrap; /* Conserve les sauts de ligne du textarea */
            background-color: #ffffff;
        }

        /* Pied de page */
        .email-footer {
            background-color: #f9fafb;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            
            <!-- En-tête -->
            <div class="email-header">
                <h1>Nouveau message reçu</h1>
            </div>

            <!-- Corps -->
            <div class="email-body">
                
                <!-- Informations de l'expéditeur -->
                <div class="info-card">
                    <div class="info-row">
                        <span class="info-label">Nom :</span>
                        <span class="info-value">{{ $name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email :</span>
                        <span class="info-value">
                            <a href="mailto:{{ $title }}" style="color: #2563eb; text-decoration: none;">{{ $title }}</a>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Sujet :</span>
                        <span class="info-value">{{ $subjectLine }}</span>
                    </div>
                </div>

                <!-- Contenu du message -->
                <div class="message-section">
                    <h2>Contenu du message :</h2>
                    <div class="message-content">{{ $content }}</div>
                </div>

            </div>

            <!-- Pied de page -->
            <div class="email-footer">
                <p>Cet email a été envoyé automatiquement depuis le formulaire de contact de votre site.</p>
            </div>

        </div>
    </div>
</body>
</html>