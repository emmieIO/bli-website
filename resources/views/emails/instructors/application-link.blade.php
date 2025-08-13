<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Instructor Application - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style type="text/css">
        /* Base styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
            margin: 0;
            padding: 0;
            color: #333333;
            line-height: 1.5;
        }

        /* Layout styles */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f5f7fa;
            padding: 20px 0;
        }

        .content {
            max-width: 600px;
            margin: 0 auto;
            border-collapse: collapse;
        }

        .body {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .inner-body {
            width: 100%;
            border-collapse: collapse;
        }

        .content-cell {
            padding: 32px;
        }

        /* Typography */
        h1,
        h2,
        h3,
        h4,
        h5 {
            color: #111827;
            margin-top: 0;
        }

        h1 {
            font-size: 24px;
        }

        h5 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 16px;
            font-size: 16px;
        }

        /* Links */
        a {
            color: #0d9488;
            text-decoration: underline;
        }

        /* Buttons */
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0d9488;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #0f766e;
        }

        /* Footer */
        .footer {
            color: #6b7280;
            font-size: 12px;
            text-align: center;
            padding: 24px 0;
            border-top: 1px solid #e5e7eb;
            margin-top: 24px;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .content-cell {
                padding: 24px !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
                text-align: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #111827 !important;
            }

            .body {
                background-color: #1f2937 !important;
                color: #f3f4f6 !important;
            }

            h1,
            h2,
            h3,
            h4,
            h5 {
                color: #f9fafb !important;
            }

            p,
            td,
            .footer {
                color: #d1d5db !important;
            }

            .button {
                background-color: #2dd4bf !important;
            }

            .footer {
                border-top-color: #374151 !important;
            }
        }
    </style>
</head>

<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                                role="presentation">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        <h5>Hello {{ $name }},</h5>

                                        <p>Thank you for starting your instructor application with
                                            {{ config('app.name') }}.</p>

                                        <p>Click the button below to continue your application:</p>

                                        <p>
                                            <a href="{{ $url }}" class="button">Resume Application</a>
                                        </p>

                                        <p>If the button doesn't work, copy and paste this link into your browser:</p>
                                        <p><a href="{{ $url }}">{{ $url }}</a></p>

                                        <p>We're excited about the possibility of you joining our team of instructors!
                                        </p>

                                        <table class="footer" width="100%" cellpadding="0" cellspacing="0"
                                            role="presentation">
                                            <tr>
                                                <td>
                                                    <p>Regards,<br>The {{ config('app.name') }} Team</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>