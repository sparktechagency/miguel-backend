<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Request</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f1f3f9;
            margin: 0;
            padding: 40px 20px;
            color: #444;
        }
        .email-wrapper {
            max-width: 640px;
            margin: auto;
            background: #ffffff;
            border: 1px solid #d0d7e1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #4a90e2, #007BFF);
            color: #fff;
            padding: 30px 40px;
            text-align: center;
            border-bottom: 1px solid #0056b3;
        }
        .email-header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
        }
        .email-body {
            padding: 35px 40px;
        }
        .info-block {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #e2e6ea;
            border-radius: 8px;
            background-color: #fafbfc;
        }
        .info-label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .info-value {
            font-size: 17px;
            font-weight: 500;
            color: #222;
        }
        .divider {
            border-top: 1px solid #ccd2da;
            margin: 25px 0;
        }
        .email-footer {
            background-color: #f9f9f9;
            text-align: center;
            font-size: 13px;
            color: #999;
            padding: 18px 40px;
            border-top: 1px solid #d0d7e1;
        }

        @media (max-width: 600px) {
            .email-body, .email-header, .email-footer {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>📨 New Contact Submission</h1>
        </div>

        <div class="email-body">
            <div class="info-block">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $contact['name'] }}</div>
            </div>

            <div class="info-block">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $contact['email'] }}</div>
            </div>

            <div class="info-block">
                <div class="info-label">Message</div>
                <div class="info-value">{{ $contact['message'] }}</div>
            </div>
        </div>

        <div class="email-footer">
            You received this message via your website's contact form.
        </div>
    </div>
</body>
</html>
