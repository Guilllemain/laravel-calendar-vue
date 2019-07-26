@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center">
    <form class="md:w-1/3 max-w-sm" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-6">
            <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="email">
                Email
            </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    type="email" {{-- @error('email') is-invalid @enderror" --}} id="email" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    {{-- @error('password') is-invalid @enderror" --}} id="password" type="password" name="password" required>
        
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
                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    {{-- @error('password_confirmation') is-invalid @enderror" --}} id="password_confirmation" type="password"
                    name="password_confirmation" required>
        
                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <button
            class="w-full shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
            type="submit">
            Mettre à jour son mot de passe
        </button>
    </form>
</div>
@endsection
