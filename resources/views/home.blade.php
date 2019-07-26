@extends('layouts.app')

@section('content')
    <vue-calendar :user="{{$user}}"></vue-calendar>
@endsection
