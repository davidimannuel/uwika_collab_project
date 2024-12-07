<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}"  rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css') }}"  rel="stylesheet">
    <title>My App</title>
    @stack('custom-css')
  </head>
  <body>
    @auth
      @if (!auth()->user()->is_admin && auth()->user()->status != \App\Models\User::STATUS_ACTIVE)
      <div class="alert alert-danger" role="alert">
        Please ask admin to activate your user account for access full feature
      </div>
      @endif    
    @endauth
    @props(['hideNavbar' => false])
    @if (!$hideNavbar)
    <div class="container">
      <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
          <ul class="nav">
            <x-nav-item href="/" :active="request()->is('/')">Home</x-nav-item>
            @auth
            <x-nav-item href="/profile" :active="request()->is('profile')">Profile</x-nav-item>
            @endauth
            <x-nav-item href="/support" :active="request()->is('support')">Support</x-nav-item>
          </ul>
        </div>
  
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
          @auth
            @if (auth()->user()->is_admin)    
              <x-nav-item href="{{ route('admin.users.index') }}" :active="request()->is('admin/users*')">User</x-nav-item>
            @else
              <x-nav-item :disabled="auth()->user()->status != \App\Models\User::STATUS_ACTIVE" href="{{ route('accounts.index') }}" :active="request()->is('accounts*')">Account</x-nav-item>
              <x-nav-item :disabled="auth()->user()->status != \App\Models\User::STATUS_ACTIVE" href="{{ route('categories.index') }}" :active="request()->is('categories')">Category</x-nav-item>    
              <x-nav-item :disabled="auth()->user()->status != \App\Models\User::STATUS_ACTIVE" href="{{ route('debts.index') }}" :active="request()->is('debts*')">Debt</x-nav-item>    
              <x-nav-item :disabled="auth()->user()->status != \App\Models\User::STATUS_ACTIVE" href="{{ route('budgets.index') }}" :active="request()->is('budgets*')">Budget</x-nav-item>    
              <x-nav-item :disabled="auth()->user()->status != \App\Models\User::STATUS_ACTIVE" href="{{ route('transactions.index') }}" :active="request()->is('transactions*')">Transaction</x-nav-item>    
            @endif
          @endauth
        </ul>
  
        <div class="col-md-3 d-flex flex-row-reverse">
          <ul class="nav">
          @guest
            <x-nav-item href="{{ route('register.create') }}" :active="request()->is('register')">Register</x-nav-item>
            <x-nav-item href="{{ route('login') }}" :active="request()->is('login')">Login</x-nav-item>
          @endguest
          @auth
            <form action="{{ route('login.destroy') }}" method="POST">
              @csrf
              <x-nav-item class="text-danger" htmlTag='button'>Logout</x-nav-item>
            </form>
          @endauth
          </ul>
        </div>
      </header>
    </div>
    @endif
    <div class="container">
      <h1>{{ $headingTitle }}</h1>
    </div>
    <div class="container mt-4">
      {{ $slot }}
    </div>

    <script src="{{ asset('assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js') }}"></script>
    @stack('custom-js')
  </body>
</html>