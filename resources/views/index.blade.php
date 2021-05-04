@extends('layouts.app')
@section('content')
    <header style="background-image: url('img/hero.jpg');" class="relative h-screen bg-no-repeat bg-cover lg:bg-center p-6 bg-top-right">
        
        <a href="#" class="text-lg font-bold text-white">Track a Slot</a>

        <div class="grid justify-items-center mt-48">
            <h1 class="text-4xl font-extrabold text-white pb-6 text-center" style="text-shadow: 2px 2px 2px #555555;">
                Registration for Co-WIN vaccination
            </h1>
            <div class="mb-3 pt-0">
                <a href="/register"
                    class="bg-green-500 hover:bg-green-400 text-white active:bg-pink-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    type="button"
                >
                    Register
                </a>
            </div>
        </div>

    </header>
@stop