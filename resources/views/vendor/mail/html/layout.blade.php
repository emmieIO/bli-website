<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ $subject ?? config('app.name') }}</title>
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
        h4 {
            color: #111827;
            margin-top: 0;
        }

        h1 {
            font-size: 24px;
        }

        h2 {
            font-size: 20px;
        }

        p {
            margin-bottom: 16px;
            font-size: 16px;
        }

        /* Links */
        a {
            color: #0d9488;
            /* Teal color */
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
        }

        /* Footer */
        .footer {
            color: #6b7280;
            font-size: 12px;
            text-align: center;
            padding: 24px 0;
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
                width: 100% !important;
                text-align: center !important;
            }

            h1 {
                font-size: 20px !important;
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
            h4 {
                color: #f9fafb !important;
            }

            p,
            td,
            .footer {
                color: #d1d5db !important;
            }
        }
    </style>
    {!! $head ?? '' !!}
</head>

<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <!-- Header -->
                    {!! $header ?? '' !!}

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                                role="presentation">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        {!! Illuminate\Mail\Markdown::parse($slot) !!}

                                        {!! $subcopy ?? '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td>
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0"
                                role="presentation">
                                <tr>
                                    <td class="content-cell" align="center">
                                        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                                        <p>
                                            <a href="{{ config('app.url') }}">Home</a> |
                                            <a href="{{ config('app.url') }}/privacy">Privacy Policy</a> |
                                            <a href="{{ config('app.url') }}/contact">Contact Us</a>
                                        </p>
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