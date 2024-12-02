<x-layout :hideNavbar="true">
  <x-slot:headingTitle>
    Transaction
  </x-slot>
  <h5>Date: {{ $transaction_from }} to {{ $transaction_to }}</h5>
  <div class="row">
    <div class="col-12">
      <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Account</th>
                <th>Remark</th>
                <th>Transaction At</th>
                <th>Amount</th>
                <th>Categories</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td>{{ $transaction->account->name }}</td>
                <td>{{ $transaction->remark }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->transaction_at)->format('Y-m-d H:i') }}</td>
                <td>
                    @php
                        // Format amount based on the type (credit is negative)
                        $amount = $transaction->type === \App\Models\Transaction::TYPE_CREDIT
                            ? -$transaction->amount
                            : $transaction->amount;
                    @endphp
                    {{ number_format($amount, 0, ',', '.') }}
                </td>
                <td>
                    {{-- Display categories as comma-separated values --}}
                    @foreach ($transaction->categories as $category)
                        {{ $category->name }}@if(!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>

      <div class="row">
        <div class="col-6">
          <strong>Total Income:</strong> 
          {{ number_format($transactions->where('type', \App\Models\Transaction::TYPE_CREDIT)->sum('amount'), 0, ',', '.') }}
        </div>
        <div class="col-6">
          <strong>Total Expenses:</strong> 
          {{ number_format($transactions->where('type', \App\Models\Transaction::TYPE_DEBIT)->sum('amount'), 0, ',', '.') }}
        </div>
      </div>
    </div>
  </div>

  @push('custom-js')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        window.print();
      });
    </script>
  @endpush
</x-layout>
