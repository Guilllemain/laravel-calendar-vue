@extends('layouts.app')

@section('content')
<div class="flex justify-center">
    <form class="md:w-1/3 max-w-sm" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="firstname">
                    Prénom
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    type="text" {{-- @error('firstname') is-invalid @enderror" --}} id="firstname" name="firstname" value="{{ old('firstname') }}"
                    required autocomplete="email" autofocus>

                @error('firstname')
                <span class="invalid-feedback" role="alert">
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
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    {{-- @error('lastname') is-invalid @enderror" --}} id="lastname" type="text" name="lastname" required>

                @error('lastname')
                <span class="invalid-feedback" role="alert">
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
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    {{-- @error('email') is-invalid @enderror" --}} id="email" type="email" name="email" required>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        
        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="password">
                    Mot de passe
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('password') is-invalid @enderror"
                    id="password" type="password" name="password" required>

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="mb-6">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="password_confirmation">
                    Confirmation du mot de passe
                </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation" type="password" name="password_confirmation" required>

                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="">
                <button
                    class="btn"
                    type="submit">
                    S'enregister
                </button>
        </div>
    </form>
</div>
@endsection
