<x-layout>
  <x-slot:headingTitle>
    Home
  </x-slot>
  @auth
    Hello {{ auth()->user()->email }}
  @else 
    Please Register
  @endauth
</x-layout>