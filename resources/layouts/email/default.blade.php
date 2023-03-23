<!DOCTYPE html>
<html lang="it" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style type="text/css">
        :root {
            color-scheme: light dark;
            supported-color-schemes: light dark;
        }

        .hover-bg-blue-600:hover {
            background-color: #2563eb !important;
        }

        .hover-underline:hover {
            text-decoration: underline !important;
        }

        @media (max-width: 600px) {
            .sm-w-full {
                width: 100% !important;
            }

            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }

            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }

            .sm-leading-32 {
                line-height: 32px !important;
            }
        }

        @media (prefers-color-scheme: dark) {
            .dark-mode-bg-gray-999 {
                background-color: #1b1c1e !important;
            }

            .dark-mode-bg-gray-989 {
                background-color: #2d2d2d !important;
            }

            .dark-mode-text-gray-979 {
                color: #a9a9a9 !important;
            }

            .dark-mode-text-white {
                color: #ffffff !important;
            }
        }
    </style>
</head>

<body class="dark-mode-bg-gray-999" style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #f3f4f6;">
    <div role="article" aria-roledescription="email" aria-label="Notifica" lang="en">
        <table class="sm-w-full" align="center" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="sm-py-32 sm-px-24" style="padding: 48px; text-align: center;">
                    <a href="{{ config('app.url') }}">
                        <img src="https://docs.ohmysmtp.com/img/logo.png" width="75" alt="logo" style="max-width: 100%; vertical-align: middle; line-height: 100%; border: 0;">
                    </a>
                </td>
            </tr>
        </table>

        <table style="width: 100%; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" class="dark-mode-bg-gray-999" style="background-color: #f3f4f6;">
                    <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td align="center" class="sm-px-24">
                                <table style="margin-bottom: 48px; width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="dark-mode-bg-gray-989 dark-mode-text-gray-979 sm-px-24" style="background-color: #ffffff; padding: 48px; text-align: left; font-size: 16px; line-height: 24px; color: #1f2937;">
                                            <p class="sm-leading-32 dark-mode-text-white" style="margin: 0; margin-bottom: 36px; font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 600; color: #000000;">
                                                @yield('title')
                                            </p>
                                            <p style="margin: 0; margin-bottom: 24px;">
                                                @yield('body')
                                            </p>
                                            <a href="@yield('button-link')" class="hover-bg-blue-600" style="display: inline-block; background-color: #3b82f6; padding-left: 24px; padding-right: 24px; padding-top: 16px; padding-bottom: 16px; text-align: center; font-size: 16px; font-weight: 600; text-transform: uppercase; color: #ffffff; text-decoration: none;">
                                                <span style="mso-text-raise: 16px">@yield('button-text')</span>
                                            </a>
                                            <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td style="padding-top: 32px; padding-bottom: 32px;">
                                                        <hr style="border-bottom-width: 0px; border-color: #f3f4f6;">
                                                    </td>
                                                </tr>
                                            </table>
                                            <p style="margin: 0; margin-bottom: 16px; color: #6b7280;">
                                                @yield('footer')
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

        <table style="width: 100%; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="padding-left: 24px; padding-right: 24px; text-align: center; font-size: 12px; color: #4b5563;">
                    <p style="margin: 0; margin-bottom: 4px; text-transform: uppercase;">Powered by
                        <a href="{{ config('app.url') }}" class="hover-underline" style="color: #3b82f6; text-decoration: none;">{{ config('app.name') }}</a>
                    </p>
                    <p style="margin: 0; font-style: italic;">{{ config('app-additional.description') }}</p>
                    <p style="cursor: default;">
                        <a href="#" class="hover-underline" style="color: #3b82f6; text-decoration: none;">Site</a> &bull;
                        <a href="https://github.com/Frameck" class="hover-underline" style="color: #3b82f6; text-decoration: none;">Github</a> &bull;
                        <a href="https://www.linkedin.com/in/francesco-mecchi/" class="hover-underline" style="color: #3b82f6; text-decoration: none;">Linkedin</a>
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>