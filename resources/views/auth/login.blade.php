<x-guest-layout>
    <a href="/calendario" class="fixed btn btn-ghost m-4">
        FEYRI LABS
        <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"></path></svg>
    </a>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <div class="mt-12 sm:mt-0">
                <x-jet-authentication-card-logo />
            </div>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-control mb-5">
                <label class="label">
                  <span class="label-text">{{ __('Email') }}</span>
                </label>
                <input id="email" type="text" name="email" :value="old('email')" class="input input-secondary input-bordered" required autofocus>
            </div>

            <div class="form-control mb-5">
                <label class="password">
                  <span class="label-text">{{ __('Password') }}</span>
                </label>
                <input id="password" type="password" name="password" class="input input-secondary input-bordered" required autocomplete="current-password">
            </div>

            <div class="p-6 card bordered">
                <div class="form-control">
                  <label class="cursor-pointer label">
                    <span class="label-text">{{ __('Remember me') }}</span>
                    <input type="checkbox" checked="checked" name="remember" class="checkbox checkbox-primary">
                  </label>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <button class="btn btn-primary ml-4">{{ __('Log in') }}</button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
