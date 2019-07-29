@extends('layouts.app')

@section('content')
            <div class="flex items-center flex-col">
                <h3 class="m-4">{{ __('Verification de votre adresse email') }}</h3>

                <div class="">
                    <p class="text-center">
                        {{ __('Avant de pouvoir réserver un parking, merci de vérifier votre adresse email en cliquant sur le lien qui vient de vous être envoyé.') }}
                    </p>
                    <p class="text-center">
                        {{ __("Si vous n'avez pas reçu d'email") }}, <a href="{{ route('verification.resend') }}">{{ __('cliquez ici pour envoyer un nouvel email') }}</a>.
                    </p>
                </div>
            </div>
@endsection
