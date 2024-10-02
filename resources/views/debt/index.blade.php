<x-layout>
  <x-slot:headingTitle>
    Debt
  </x-slot>

  <div class="row">
    <div class="col-12">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Account</th>
            <th>Remark</th>
            <th>Transaction At</th>
            <th>Debt Amount</th>
            <th>Due At</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($debts as $debt)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $debt->transaction->account->name }}</td>
            <td>{{ $debt->transaction->remark }}</td>
            <td>{{ $debt->transaction->transaction_at }}</td>
            <td>{{ number_format($debt->transaction->amount, 0, ',', '.') }}</td>
            <td>{{ $debt->due_at }}</td>
            <td>
              @if ($debt->status === 'paid')
                <span class="badge bg-success">Paid</span>
              @elseif($debt->status === 'partial_paid')
                <span class="badge bg-warning">Partial Paid</span>
              @else
                <span class="badge bg-danger">Unpaid</span>
              @endif
            </td>
            <td class="d-inline-flex">
              <a href="{{ route('debts.show',$debt->transaction_id) }}" class="btn btn-primary me-1">Repayments</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  @push('custom-css')
  <link href="{{ asset('assets/sweetalert2-11.14.1/dist/sweetalert2.min.css') }}"  rel="stylesheet">
  @endpush

  @push('custom-js')
    <script src="{{ asset('assets/sweetalert2-11.14.1/dist/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all delete buttons
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the form from submitting immediately
                    let form = this.closest('form'); // Find the form associated with the delete button
                    // Trigger SweetAlert confirmation
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form if the user confirms
                            form.submit();
                        }
                    })
                });
            });
        });
    </script>
  @endpush
</x-layout>