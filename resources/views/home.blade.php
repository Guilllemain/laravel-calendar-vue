@extends('layouts.app')

@section('content')
    <app-component :user="{{$user}}"></app-component>
@endsection
