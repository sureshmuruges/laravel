<x-app-layout>
	<div class="">
		<!-- Notification Messages -->
		@if ($message = Session::get('success'))
                <div class="alert alert-success mb-4" role="alert"> <p class="mb-0">{{ $message }}</p>
            </div>
        @endif
		        
		        @yield('content')
	</div>
</x-app-layout>

