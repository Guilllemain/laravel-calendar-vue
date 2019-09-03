@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center">
        <table class="m-8">
            <thead class="bg-gray-400">
                <tr>
                    <th class="border border-gray-600 p-1">ID</th>
                    <th class="border border-gray-600 p-1">Name</th>
                    <th class="border border-gray-600 p-1">Email</th>
                    <th class="border border-gray-600 p-1">Verified</th>
                    <th class="border border-gray-600 p-1">Created at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="border border-gray-600 p-2">{{ $user->id }}</td>
                        <td class="border border-gray-600 p-2">{{ $user->fullname }}</td>
                        <td class="border border-gray-600 p-2">{{ $user->email }}</td>
                        <td class="border border-gray-600 p-2">{{ $user->email_verified_at }}</td>
                        <td class="border border-gray-600 p-2">{{ $user->created_at }}</td>
                        <td class="border border-gray-600 p-2">
                            <form class="inline" action="/user/{{$user->id}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 rounded px-2 py-1">Delete</button>
                            </form>
                            <a href="/user/{{$user->id}}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection