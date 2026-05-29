@extends('layouts.email')

@section('title', __('emails.verification.subject'))

@section('content')
    <h2 style="margin-top: 0; margin-bottom: 16px; color: #2D3748; font-size: 22px; font-weight: 800; line-height: 1.3;">
        {{ __('emails.verification.greeting', ['name' => $name]) }}
    </h2>
    
    <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 32px; font-weight: 400;">
        {{ __('emails.verification.line_1') }}
    </p>
    
    <table cellpadding="0" cellspacing="0" border="0" style="margin: 30px auto;">
        <tr>
            <td align="center" style="background-color: #4FD1C5; border-radius: 0.5rem;">
                <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 12px 32px; color: #ffffff; font-family: 'Inter', sans-serif; font-size: 15px; font-weight: 600; text-decoration: none; letter-spacing: 0.3px;">
                    {{ __('emails.verification.action') }}
                </a>
            </td>
        </tr>
    </table>

    <p style="color: #718096; font-size: 13px; line-height: 1.5; margin-bottom: 0; margin-top: 30px; border-top: 1px solid rgba(45, 55, 72, 0.08); padding-top: 20px;">
        {{ __('emails.verification.line_2') }}
    </p>
@endsection

@section('subfooter')
    <table width="600" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding: 20px 10px; color: #a0aec0; font-size: 12px; line-height: 1.5; text-align: left; font-family: 'Inter', sans-serif;">
                Si tienes problemas para hacer clic en el botón "{{ __('emails.verification.action') }}", copia y pega esta URL en tu navegador web: <br>
                <a href="{{ $url }}" style="color: #4FD1C5; text-decoration: underline; word-break: break-all;">{{ $url }}</a>
            </td>
        </tr>
    </table>
@endsection