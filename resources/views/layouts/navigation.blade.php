<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
	<div
		class="container-fluid px-3 px-md-4">
		<!-- Logo -->
		<a class="navbar-brand" href="{{ route('dashboard') }}">
			<x-application-logo class="d-inline-block align-text-top" style="height: 30px; width: auto;"/>
		</a>

		<!-- Hamburger -->
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div
			class="collapse navbar-collapse" id="navbarSupportedContent">
			<!-- Left Side Of Navbar -->
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">{{ __('Dashboard') }}
					</a>
				</li>
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('particulars.index') ? 'active fw-bold' : '' }}" href="{{ route('particulars.index') }}">{{ __('Particulars') }}
					</a>
				</li>
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('addresses.index') ? 'active fw-bold' : '' }}" href="{{ route('addresses.index') }}">{{ __('Addresses') }}
					</a>
				</li>
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('bookings.*') ? 'active fw-bold' : '' }}" href="{{ route('bookings.index') }}">{{ __('Booking') }}
					</a>
				</li>
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('expenses.*') ? 'active fw-bold' : '' }}" href="{{ route('expenses.index') }}">{{ __('Expenses') }}
					</a>
				</li>
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('invoice_particulars.*') ? 'active fw-bold' : '' }}" href="{{ route('invoice_particulars.index') }}">{{ __('Invoice Particulars') }}
					</a>
				</li>
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('invoices.*') ? 'active fw-bold' : '' }}" href="{{ route('invoices.index') }}">{{ __('Tax Invoices') }}
					</a>
				</li>
				<li class="nav-item">
					<a
						class="nav-link {{ request()->routeIs('company_details.*') ? 'active fw-bold' : '' }}" href="{{ route('company_details.index') }}">{{ __('Company Details') }}
					</a>
				</li>
			</ul>

			<!-- Right Side Of Navbar -->
			<ul
				class="navbar-nav ms-auto mb-2 mb-lg-0">
				<!-- Settings Dropdown -->
				<li class="nav-item dropdown">
					<a
						class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ Auth::user()->name }}
					</a>
					<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
						<li>
							<a
								class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}
							</a>
						</li>
						<li><hr class="dropdown-divider"></li>
						<li>
							<form method="POST" action="{{ route('logout') }}">
								@csrf
								<a
									class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}
								</a>
							</form>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>

