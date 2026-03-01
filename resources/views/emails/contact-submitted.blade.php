<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>We’ve received your message</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        /* Mobile responsiveness */
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; padding: 10px !important; }
            .card { padding: 15px !important; }
            h1 { font-size: 20px !important; }
            td { font-size: 14px !important; }
        }
        /* Hover effect for table rows */
        .info-row:hover { background-color: #f0f9ff; transition: background 0.3s; }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:20px;">
                <table class="container" width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow: 0 0 10px rgba(0,0,0,0.05);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#2563eb; padding:20px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px;">{{ $contact->organization->short_name ?? 'N/A' }}</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="card" style="padding:24px; color:#374151;">
                            <p style="margin-top:0;">Hello <strong>{{ $contact->name }}</strong>,</p>

                            <p style="line-height:1.6;">
                                Thank you for contacting us. We’ve successfully received your message.  
                                Here’s a summary of what you submitted:
                            </p>

                            <table width="100%" style="border-collapse:collapse; margin:20px 0;">
                                <tr class="info-row" style="background:#f9fafb;">
                                    <td style="padding:10px; font-weight:bold; width:150px;">Name:</td>
                                    <td style="padding:10px;">{{ $contact->name }}</td>
                                </tr>
                                <tr class="info-row">
                                    <td style="padding:10px; font-weight:bold;">Email:</td>
                                    <td style="padding:10px;">{{ $contact->email }}</td>
                                </tr>
                                <tr class="info-row" style="background:#f9fafb;">
                                    <td style="padding:10px; font-weight:bold;">Phone:</td>
                                    <td style="padding:10px;">{{ $contact->phone }}</td>
                                </tr>
                                <tr class="info-row">
                                    <td style="padding:10px; font-weight:bold;">Organization:</td>
                                    <td style="padding:10px;">{{ $contact->organization->name ?? 'N/A' }}</td>
                                </tr>
                                @if($contact->message)
                                <tr class="info-row" style="background:#f9fafb;">
                                    <td style="padding:10px; font-weight:bold; vertical-align: top;">Message:</td>
                                    <td style="padding:10px;">{{ $contact->message }}</td>
                                </tr>
                                @endif
                            </table>

                            <p style="line-height:1.6;">
                                Our team will get back to you shortly.  
                                
                            </p>

                            <p style="margin-top:24px;">
                                Best regards,<br>
                                <strong>{{ $contact->organization->short_name ?? 'N/A' }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9fafb; padding:16px; text-align:center; font-size:12px; color:#6b7280;">
                            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
