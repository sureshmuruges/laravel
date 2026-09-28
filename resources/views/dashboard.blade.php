<x-app-layout>
	<x-slot name="header">
		<h2
			class="h4 font-weight-bold mb-0 text-dark">{{ __('Dashboard') }}
		</h2>
	</x-slot>

	<div class="row g-4 mb-4">
		<div class="col-md-4">
			<div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
				<div class="card-body">
					<h6 class="text-muted small text-uppercase fw-bold">Total Bookings</h6>
					<h2 class="fw-bold mb-0">{{ number_format($bookingCount) }}</h2>
					<p class="text-success small mb-0 mt-2">
						<i class="bi bi-arrow-up"></i>
						Job management active</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card shadow-sm border-0 border-start border-danger border-4 h-100">
				<div class="card-body">
					<h6 class="text-muted small text-uppercase fw-bold">Total Expenses</h6>
					<h2 class="fw-bold mb-0 text-danger">{{ number_format($totalExpenses, 2) }}</h2>
					<p class="text-muted small mb-0 mt-2">Recorded across all jobs</p>
				</div>
			</div>
		</div>
	</div>

	<div
		class="row g-4">
		<!-- New Expenses Section -->
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
					<h5 class="fw-bold mb-0">Recent Expenses</h5>
					<a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead class="table-light">
								<tr>
									<th class="small py-2">Job No</th>
									<th class="small py-2 text-end">Amount</th>
								</tr>
							</thead>
							<tbody>
								@foreach($recentExpenses as $expense)
                                    <tr>
                                        <td class="small py-3">
                                            <div class="fw-bold text-primary">{{ $expense->JobNo }}</div>
                                            <div class="text-muted smaller">{{ $expense->Reference }}</div>
                                        </td>
                                        <td class="text-end py-3">
                                            <span class="fw-bold text-danger">{{ number_format($expense->Total, 2) }}</span>
                                            <span class="small text-muted">{{ $expense->Currency }}</span>
                                        </td>
                                    </tr>
                                @endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
					<h5 class="fw-bold mb-0">Recent Bookings</h5>
					<a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead class="table-light">
								<tr>
									<th class="small py-2">Booking No</th>
									<th class="small py-2 text-end">Date</th>
								</tr>
							</thead>
							<tbody>
								@foreach($recentBookings as $booking)
                                    <tr>
                                        <td class="small py-3">
                                            <div class="fw-bold text-dark">{{ $booking->BookingNo }}</div>
                                            <div class="text-muted smaller">{{ Str::limit($booking->companyname, 25) }}</div>
                                        </td>
                                        <td class="text-end py-3">
                                            <span class="text-secondary small">{{ $booking->booking_date }}</span>
                                        </td>
                                    </tr>
                                @endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

