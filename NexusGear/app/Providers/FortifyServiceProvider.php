<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * No registra servicios propios, pero se deja el método por la estructura del provider.
     */
    public function register(): void
    {
    }

    /**
     * Configura Fortify con las vistas del proyecto y los límites de intentos de acceso.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);
        Fortify::loginView(function(){
            return view('auth.login');
        });
        Fortify::registerView(function(){
            return view('auth.register');
        });
        Fortify::requestPasswordResetLinkView(function(){
            return view('auth.forgot-password');
        });
        Fortify::resetPasswordView(function($request){
            return view('auth.reset-password',['request'=> $request]);
        });
        Fortify::verifyEmailView(function(){
            return view('auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });
        
        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });    

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        $this->app->instance(VerifyEmailResponse::class, new class implements VerifyEmailResponse {
            public function toResponse($request)
            {
                // Tras verificar el correo, el usuario completa idioma y datos básicos.
                return redirect()->route('onboarding.index');
            }
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject(__('emails.verification.subject'))
                #->greeting(__('emails.verification.greeting', ['name' => $notifiable->name]))
                #->line(__('emails.verification.line_1'))
                #->action(__('emails.verification.action'), $url)
                #->line(__('emails.verification.line_2'));
                ->view('emails.verify', [
                        'name' => $notifiable->name,
                        'url' => $url
                ]);
        });

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject(__('emails.password.subject'))
                #->line(__('emails.password.line_1'))
                #->action(__('emails.password.action'), $url)
                #->line(__('emails.password.line_2', ['count' => config('auth.passwords.users.expire')]));
                ->view('emails.reset', [
                        'count' => config('auth.passwords.users.expire'),
                        'url' => $url
                ]);
        });
    }
}
