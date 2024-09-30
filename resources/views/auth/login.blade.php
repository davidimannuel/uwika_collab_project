<x-layout>
  <x-slot:headingTitle>
    Login
  </x-slot>
  
  <div class="row">
    <div class="col-6">
      <form action="{{ route('login.store') }}" method="POST">
        @csrf
        <x-form-field>
          <x-form-label for="email">Email</x-form-label>
          <x-form-input name='email' type="email" placeholder="example@example.com" :value="old('email')"/>
          <x-form-error name='email' />
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="password">Password</x-form-label>
          <x-form-input name='password' type="password"/>
          <x-form-error name='password' />
        </x-form-field>
        
        <x-form-field class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember">
            Remember me?
          </label>
        </x-form-field>

        <hr class="my-4">
        <div class="text-end">
          <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>
    </div>
  </div>
</x-layout>