<x-app-layout>
	<x-slot name="header">
		<h2 class="h4 font-weight-bold mb-0 text-dark">
			Company Details (Contact Info)
		</h2>
	</x-slot>
	
	<div class="d-flex justify-content-end mb-2">
		<a href="{{ route('company_details.index') }}" class="btn btn-secondary btn-sm">
			<i class="bi bi-arrow-left me-1"></i>
			Back to List
		</a>
	</div>

	<div class="card shadow-sm border-0 rounded-3">
		<div class="card-body p-0">
			<!-- Notification Messages -->
			@if ($message = Session::get('success'))
				<div class="alert alert-success alert-dismissible fade show m-3" role="alert">
					<p class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>{{ $message }}</p>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			@endif
			
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
					<ul class="mb-0">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			@endif
									        
			@yield('content')
		</div>
	</div>
</x-app-layout>
