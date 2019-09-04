@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center">
        <table class="m-8">
            <thead class="bg-gray-400">
                <tr>
                    <th class="border border-gray-600 p-1">ID</th>
                    <th class="border border-gray-600 p-1">Name</th>
                    <th class="border border-gray-600 p-1">Parking</th>
                    <th class="border border-gray-600 p-1">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td class="border border-gray-600 p-2">{{ $reservation->id }}</td>
                        <td class="border border-gray-600 p-2">{{ $reservation->user->fullname }}</td>
                        <td class="border border-gray-600 p-2">{{ $reservation->parking_number }}</td>
                        <td class="border border-gray-600 p-2">{{ $reservation->date }}</td>
                        <td class="border border-gray-600 p-2">
                            <form class="inline" action="/admin/reservation/{{$reservation->id}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 rounded px-2 py-1">Delete</button>
                            </form>
                            {{-- <a href="/admin/reservation/{{$reservation->id}}">Edit</a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection