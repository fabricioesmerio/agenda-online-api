<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinição de Senha</title>
    <style>
        /* Compatibilidade básica */
        body {
            background-color: #f4f4f7;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            margin: 40px auto;
            padding: 30px;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            font-size: 24px;
        }

        p {
            color: #555;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Redefinição de Senha</h1>
    <p>Recebemos uma solicitação para redefinir a sua senha.</p>
    <p>Para redefinir sua senha, clique no botão abaixo:</p>

    <a href="{{ $resetLink }}" class="button">Redefinir Senha</a>

    <p style="margin-top: 20px;">Se você não solicitou esta alteração, apenas ignore este e-mail.</p>

    <div class="footer">
        &copy; {{ date('Y') }} Tuagenda. Todos os direitos reservados.
    </div>
</div>
</body>
</html>
