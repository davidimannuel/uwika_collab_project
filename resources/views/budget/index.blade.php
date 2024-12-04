<x-layout>
  <x-slot:headingTitle>
    Budget
  </x-slot>
  <x-alert name='alert' type='danger'/>
  <div class="text-start">
    <a href="{{ route('budgets.create') }}" class="btn btn-success">Create</a>
  </div>
  <div class="row">
    @foreach ($budgets as $budget)
      <div class="col-4">
        <div class="card mt-3">
          <div class="card-body">
            <h5 class="card-title">{{ $budget->name }}</h5>
            <p class="card-text">{{ $budget->transaction_type == \App\Models\Transaction::TYPE_DEBIT ? 'Income' : 'Expenses' }}</p>
            <p class="card-text"><b>Category: </b> {{ $budget->category->name }}</p>
            <p class="card-text"><b>Threshold: </b> Rp. {{ number_format($budget->threshold_amount, 0, ',', '.') }}</p>
            <p class="card-text"><b>Collected: </b> Rp. {{ number_format($budget->collected_amount, 0, ',', '.') }}</p>
            @if ($budget->collected_amount > $budget->threshold_amount)
              <span class="badge text-bg-warning">Limit Exceed Rp. {{ number_format($budget->collected_amount - $budget->threshold_amount, 0, ',', '.') }}</span> 
            @endif
          </div>
          <div class="card-body d-inline-flex">
            <a href="{{ route('budgets.transactions.index',$budget->id) }}" class="btn btn-success me-1">Transactions</a>
            <form action="{{ route('budgets.destroy',$budget->id) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger delete-btn">Delete</button>
            </form>
          </div>
          <div class="card-footer">
            <small class="text-body-secondary">Created {{ $budget->created_at->diffForHumans() }}, </small>
            <small class="text-body-secondary">Last updated {{ $budget->updated_at->diffForHumans() }}</small>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="row">
    {{-- pagination links --}}
    <div> 
      {{ $budgets->links() }}
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