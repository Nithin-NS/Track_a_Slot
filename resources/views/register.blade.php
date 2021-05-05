@extends('layouts.app')
@section('content')
    <header style="background-image: url('img/hero.jpg');" class="relative h-screen bg-no-repeat bg-cover lg:bg-center p-6 bg-top-right">
        
        <a href="{{ route('index') }}" class="text-lg font-bold text-white">Track My Slot</a>

        <registration></registration>

    </header>
@stop