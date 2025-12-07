<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Submission</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: #4A90E2; color: #ffffff; padding: 20px; text-align: center;">
            <h2 style="margin: 0;">🎵 New User Submission</h2>
        </div>

        <!-- Content -->
        <div style="padding: 20px; color: #333;">
            <p style="font-size: 16px; line-height: 1.5;">Hello Admin,</p>
            <p style="font-size: 15px;">A new user has submitted their details. Here’s the information:</p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <tr>
                    <td style="padding: 8px; font-weight: bold; width: 150px;">Name:</td>
                    <td style="padding: 8px; background: #f9f9f9;">{{ $data['name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Email:</td>
                    <td style="padding: 8px; background: #f9f9f9;">{{ $data['email'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Social Link:</td>
                    <td style="padding: 8px; background: #f9f9f9;">{{ $data['social_link'] ?? 'N/A' }}</td>
                </tr>
                 <tr>
                    <td style="padding: 8px; font-weight: bold;">Demo Link:</td>
                    <td style="padding: 8px; background: #f9f9f9;">{{ $data['submit_demo'] ?? 'N/A' }}</td>
                </tr>
                 <tr>
                    <td style="padding: 8px; font-weight: bold;">Referral:</td>
                    <td style="padding: 8px; background: #f9f9f9;">{{ $data['referral'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">About:</td>
                    <td style="padding: 8px; background: #f9f9f9;">{{ $data['about'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Genres:</td>
                    <td style="padding: 8px; background: #f9f9f9;">
                        @if(!empty($data['genres']) && is_array($data['genres']))
                            {{ implode(', ', $data['genres']) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                @if(!empty($data['other_genre']))
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Other Genre:</td>
                    <td style="padding: 8px; background: #f9f9f9;">{{ $data['other_genre'] }}</td>
                </tr>
                @endif

            </table>
        </div>

        <!-- Footer -->
        <div style="background: #f1f1f1; color: #555; padding: 15px; text-align: center; font-size: 12px;">
            <p style="margin: 0;">This is an automated email. Please do not reply.</p>
        </div>

    </div>

</body>
</html>



