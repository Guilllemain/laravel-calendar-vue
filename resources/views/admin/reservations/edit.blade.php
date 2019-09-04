@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center">
        <form class="w-1/5" action="/admin/reservation/{{$reservation->id}}" method="POST">
            @csrf
            @method('PATCH')
            <label class="block mt-4" for="user">User</label>
            <input 
                class="w-full bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight outline-none focus:bg-white focus:border-purple-500" 
                type="text" 
                name="User" 
                value="{{$reservation->user->fullname}}">
            <label class="block mt-4" for="parking">Parking</label>
            <input 
                class="w-full bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight outline-none focus:bg-white focus:border-purple-500"
                type="number"
                name="parking"
                value="{{$reservation->parking_number}}">
            <label class="block mt-4" for="reservation_date">Date</label>
            <input
                class="w-full bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight outline-none focus:bg-white focus:border-purple-500"
                type="date"
                name="reservation_date"
                value="{{optional(\Carbon\Carbon::parse($reservation->date))->format('Y-m-d')}}">
            <button class="btn mt-4">Save</button>
        </form>
    </div>
@endsection