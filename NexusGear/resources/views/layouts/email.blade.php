<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Inter', system-ui, -apple-system, sans-serif; -webkit-font-smoothing: antialiased;">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; padding: 30px 0;">
        <tr>
            <td align="center">
                
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid rgba(45, 55, 72, 0.08); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);">
                    
                    <tr>
                        <td align="center" style="background-color: #ffffff; padding: 25px; border-bottom: 3px solid #4FD1C5;">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <img src="{{ asset('favicon.svg') }}" alt="NexusGear" style="height: 40px; width: auto; display: block; margin-bottom: 8px;">
                                    </td>
                                    <td style="padding-left: 10px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 800; color: #2D3748; letter-spacing: 0.5px;">
                                        NexusGear
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 40px 35px; background-color: #ffffff;">
                            @yield('content')
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" style="background-color: #ffffff; padding: 25px; border-top: 1px solid rgba(45, 55, 72, 0.08);">
                            <p style="margin: 0; color: #718096; font-size: 13px; font-family: 'Inter', sans-serif;">
                                &copy; {{ date('Y') }} <strong>NexusGear</strong>. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
                
                @yield('subfooter')

            </td>
        </tr>
    </table>

</body>
</html>