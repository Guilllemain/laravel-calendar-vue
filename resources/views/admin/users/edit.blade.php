@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center">
        <form class="w-1/5" action="/admin/user/{{$user->id}}" method="POST">
            @csrf
            @method('PATCH')
            <label class="block mt-4" for="firstname">Firstname</label>
            <input 
                class="w-full bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight outline-none focus:bg-white focus:border-purple-500" 
                type="text" 
                name="firstname" 
                value="{{$user->firstname}}">
            <label class="block mt-4" for="lastname">Lastname</label>
            <input 
                class="w-full bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight outline-none focus:bg-white focus:border-purple-500"
                type="text"
                name="lastname"
                value="{{$user->lastname}}">
            <label class="block mt-4" for="email">Email</label>
            <input
                class="w-full bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight outline-none focus:bg-white focus:border-purple-500"
                type="email"
                name="email"
                value="{{$user->email}}">
            <label class="block mt-4" for="verified">Verified At</label>
            <input
                class="w-full bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight outline-none focus:bg-white focus:border-purple-500"
                type="date"
                name="verified"
                value="{{optional($user->email_verified_at)->format('Y-m-d')}}">
            <button class="btn mt-4">Save</button>
        </form>
    </div>
@endsection