@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form class="w-3/4 md:w-1/3 max-w-sm" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-6">
            <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="email">
                Email
            </label>
            <div class="">
                <input
                    class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('email')is-invalid @enderror"
                    type="email" id="email" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="text-xs text-red-600" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <button
            class="btn"
            type="submit">
            Réinitialiser son mot de passe
        </button>
    </form>
</div>
@endsection
