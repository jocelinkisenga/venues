<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            background: white;
            margin: 30px auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            text-align: center;
            padding: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .content {
            padding: 30px;
            color: #333;
            font-size: 16px;
            line-height: 1.7;
        }

        .content p {
            margin-bottom: 1em;
        }

        .footer {
            background-color: #f3f4f6;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #777;
        }

        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 15px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #1e40af;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset("Logo.jpg") }}" alt="">
            <h1>{{ $subjectLine }}</h1>
        </div>
        <div class="content">
            {!! nl2br(e($content)) !!}

            <p style="margin-top: 20px;">
                <a href="{{ url('/') }}" class="btn">read more</a>
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} <strong>{{ config('app.name','Iland Solutions') }}</strong> — All rights reserved.
            <br>
            You have received this mail, because you have subscribed to our news-letter
        </div>
    </div>
</body>
</html>
