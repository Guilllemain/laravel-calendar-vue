@extends('layouts.app')

@section('scripts')
    <script src="{{ asset('js/validation.js') }}" defer></script>
@endsection

@section('content')
<div class="flex justify-center">
    <form class="md:w-1/3 max-w-sm" method="POST" name="login" action="{{ route('register') }}">
        @csrf
        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="firstname">
                    Prénom
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('firstname') is-invalid @enderror"
                    type="text" id="firstname" name="firstname" value="{{ old('firstname') }}"
                    required autocomplete="email" autofocus>

                @error('firstname')
                <span class="text-xs text-red-600" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

        </div>
        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="lastname">
                    Nom
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('lastname')is-invalid @enderror"
                    id="lastname" type="text" name="lastname" value="{{ old('lastname') }}" required>

                @error('lastname')
                <span class="text-xs text-red-600" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="email">
                    Email
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('email') is-invalid @enderror"
                    id="email" type="email" name="email" value="{{ old('email') }}" data-error="Vous devez indiquer un email FFT" required>
                <span hidden class="email-error text-xs text-red-600" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        
        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="password">
                    Mot de passe
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('password')is-invalid @enderror"
                    id="password" type="password" name="password" value="{{ old('password') }}" data-error="Votre mot de passe doit faire au moins 8 caractères" required>
                
                <span hidden class="password-error text-xs text-red-600" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>

        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="password_confirmation">
                    Confirmation du mot de passe
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" data-error="Votre mot de passe doit être identique" required>

                <span hidden class="password_confirmation-error text-xs text-red-600" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>

        <div class="">
                <button
                    disabled
                    name="btn"
                    class="btn"
                    type="submit">
                    S'enregister
                </button>
        </div>
    </form>
</div>
@endsection
