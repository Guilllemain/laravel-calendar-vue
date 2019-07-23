@extends('layouts.app')

@section('content')
<div class="container">
<vue-calendar :user="{{$user}}"></vue-calendar>
</div>
@endsection
