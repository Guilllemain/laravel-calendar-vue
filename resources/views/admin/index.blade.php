@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center">
        <ul>
            <li class="bg-gray-300 text-center rounded p-2">
                <a href="/admin/users">Users</a>
            </li>
            <li class="mt-4 bg-gray-300 text-center rounded p-2">
                <a href="/admin/reservations">Reservations</a>
            </li>
        </ul>
    </div>
@endsection