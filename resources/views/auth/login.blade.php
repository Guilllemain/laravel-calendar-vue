@extends('layouts.app')

@section('content')
<div class="flex md:flex-row justify-center items-center flex-col">
	<form class="w-3/4 sm:w-1/2 md:w-1/3 max-w-sm" method="POST" action="{{ route('login') }}">
		@csrf
		<div class="mb-6">
				<label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="email">
					Email
				</label>
			<div class="">
				<input
					class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('email')is-invalid @enderror"
					type="email" id="email" name="email" value="{{ old('email') }}"
					required autocomplete="email" autofocus>
				@error('email')
				<span class="text-xs text-red-600" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror
			</div>

		</div>
		<div class="mb-4">
				<label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4" for="password">
					Mot de passe
				</label>
			<div class="">
				<input
					class="bg-gray-200 appearance-none border border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('password')is-invalid @enderror"
					id="password" type="password" name="password" required
					autocomplete="current-password">

				@error('password')
				<span class="text-xs text-red-600" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror
			</div>
		</div>

		<div class="md:flex md:items-center mb-2">
			<label class="block text-gray-500 font-bold" for="remember">
				<input class="leading-tight" type="checkbox" name="remember"
					{{ old('remember') ? 'checked' : '' }}>
				<span class="text-sm">
					Se souvenir de moi
				</span>
			</label>
		</div>

		<button
			class="btn"
			type="submit">
				Se connecter
		</button>
		<div class="mt-2">
			<a class="hover:text-gray-700 text-sm text-gray-500 font-bold" href="{{ route('password.request') }}">
				Mot de passe oublié ?
			</a>
		</div>
	</form>

	<div class="w-3/4 sm:w-1/2 md:w-1/3 md:ml-24 mb-8 mt-16 max-w-sm">
		<div class="text-gray-500 font-bold mb-2">Pas encore de compte ?</div>
		<a class="btn" href="{{ route('register') }}">Créer un compte</a>
	</div>
</div>
@endsection

@section('javascript')
	<script>
		const ua = window.navigator.userAgent;
		const msie = ua.indexOf('MSIE ');
		const trident = ua.indexOf('Trident/');
		const edge = ua.indexOf('Edge/');

		if (msie > 0 || trident > 0 || edge > 0) {
			alert("Ce site ne marche pas sur les anciennes versions de Microsoft Internet Explorer et Edge. Merci d'utiliser Google Chrome ou Mozilla Firefox");
		}
	</script>
@endsection