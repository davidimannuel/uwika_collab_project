<x-layout>
  <x-slot:headingTitle>
    Budget transaction
  </x-slot>
  <x-alert name='success-alert' type='success'/>
  <div class="text-start">
    <a href="{{ route('budgets.index') }}" class="btn btn-primary">Back</a>
  </div>
  <div class="row">
    <div class="col-8">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Account</th>
            <th>Remark</th>
            <th>Amount</th>
            <th>Transaction At</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($budgetTransactions as $budgetTransaction)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $budgetTransaction->transaction->account->name }}</td>
            <td>{{ $budgetTransaction->transaction->remark }}</td>
            <td>
              @if ($budgetTransaction->transaction->type === 'debit')
                <span class="badge bg-success">{{ number_format($budgetTransaction->transaction->amount, 0, ',', '.') }}</span>
              @else
                <span class="badge bg-danger">-{{ number_format($budgetTransaction->transaction->amount, 0, ',', '.') }}</span>
              @endif
            </td>
            <td>{{ $budgetTransaction->transaction->transaction_at }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{-- pagination links --}}
      <div> 
        {{ $budgetTransactions->links() }}
      </div>
    </div>
    <div class="col-4">
      <x-form-field>
        <x-form-label>Name</x-form-label>
        <x-form-input type="text" value="{{ $budget->name }}" disabled/>
      </x-form-field>
      
      <x-form-field>
        <x-form-label>Threshold amount</x-form-label>
        <x-form-input type="text" value="Rp. {{ number_format($budget->threshold_amount, 0, ',', '.') }}" disabled/>
      </x-form-field>
      
      <x-form-field>
        <x-form-label>Collected amount</x-form-label>
        <x-form-input type="text" value="Rp. {{ number_format($budget->collected_amount, 0, ',', '.') }}" disabled/>
      </x-form-field>
    </div>
  </div>
</x-layout>