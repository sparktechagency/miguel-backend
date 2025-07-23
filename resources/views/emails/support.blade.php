<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Message</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef1f5;
            margin: 0;
            padding: 30px;
            color: #333;
        }
        .email-container {
            max-width: 640px;
            margin: auto;
            background-color: #ffffff;
            border: 1px solid #d0d7e1;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .email-header {
            background-color: #28a745;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        .email-header h2 {
            margin: 0;
            font-size: 22px;
        }
        .email-body {
            padding: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .label {
            font-weight: bold;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }
        .value {
            font-size: 16px;
            color: #222;
            white-space: pre-line;
        }
        .email-footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>📩 New Support Message</h2>
        </div>

        <div class="email-body">
            <div class="section">
                <div class="label">Title</div>
                <div class="value">{{ $support['title'] }}</div>
            </div>

            <div class="section">
                <div class="label">Message</div>
                <div class="value">{{ $support['message'] }}</div>
            </div>
        </div>

        <div class="email-footer">
            This message was submitted via your website's support form.
        </div>
    </div>
</body>
</html>
