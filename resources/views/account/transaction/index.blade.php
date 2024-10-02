<x-layout>
  <x-slot:headingTitle>
    Transaction
  </x-slot>
  <div class="text-start">
    <a href="{{ route('accounts.index') }}" class="btn btn-primary">Back</a>
    <a href="{{ route('accounts.transactions.create', $account->id) }}" class="btn btn-success">Create</a>
  </div>
  <div class="row">
    <div class="col-8">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Remark</th>
            {{-- <th>type</th> --}}
            <th>Amount</th>
            <th>Transaction At</th>
            <th>Categories</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($transactions as $transaction)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $transaction->remark }}</td>
            {{-- <td>{{ $transaction->type }}</td> --}}
            <td>
              @if ($transaction->type === 'debit')
                <span class="badge bg-success">{{ number_format($transaction->amount, 0, ',', '.') }}</span>
              @else
                <span class="badge bg-danger">-{{ number_format($transaction->amount, 0, ',', '.') }}</span>
              @endif
            </td>
            <td>{{ $transaction->transaction_at }}</td>
            <td>
              @foreach ($transaction->categories as $category)
                  @php
                      // Determine badge color based on the iteration index
                      $badgeClass = '';
                      if ($category->id % 4 == 0) {
                          $badgeClass = 'dark'; // Red for even indexes
                      } else if ($category->id % 3 == 0) {
                          $badgeClass = 'info'; // Red for even indexes
                      } else if ($category->id % 2 == 0) {
                          $badgeClass = 'primary'; // Red for even indexes
                      } else {
                          $badgeClass = 'success'; // Yellow for odd indexes
                      }
                  @endphp
                  <span class="badge rounded-pill text-bg-{{ $badgeClass }}">
                      {{ $category->name }}
                  </span>
              @endforeach
            </td>
            <td>
              @if ($transaction->is_debt)
                <a href="{{ route('debts.show', $transaction->id) }}" class="btn btn-warning">Debt</a>
              @else
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{-- pagination links --}}
      <div> 
        {{ $transactions->links() }}
      </div>
    </div>
    <div class="col-4">
      <x-form-field>
        <x-form-label>Account</x-form-label>
        <x-form-input type="text" value="{{ $account->name }}" disabled/>
      </x-form-field>
      
      <x-form-field>
        <x-form-label>Balance</x-form-label>
        <x-form-input type="number" value="{{ $account->balance }}" disabled/>
      </x-form-field>
    </div>
  </div>
</x-layout>