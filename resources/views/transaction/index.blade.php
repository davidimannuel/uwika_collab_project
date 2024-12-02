<x-layout>
  <x-slot:headingTitle>
    Transaction
  </x-slot>
  <form action="{{ route('transactions.index') }}" method="POST">
    @csrf
    <div class="row">
      <div class="col-4">
        <x-form-field>
          <x-form-label for="account_id">Account</x-form-label>
          <select class="form-select" name="account_id" id="account_id">
            @foreach ($accounts as $account)
              <option value="{{ $account->id }}" 
                  {{ old('account_id') == $account->id? 'selected' : '' }}>
                  {{ $account->name }}
              </option>
            @endforeach
          </select>
          <x-form-error name='account_id'/>
        </x-form-field>
      </div>
      <div class="col-4">
        <x-form-field>
          <x-form-label for="transaction_from">Transaction from</x-form-label>
          <x-form-input name='transaction_from' id="transaction_from" type="date" value="{{ old('transaction_from',request('transaction_from', now()->format('Y-m-d')),now()->format('Y-m-d')) }}"/>
          <x-form-error name='transaction_from'/>
        </x-form-field>
      </div>
      <div class="col-4">
        <x-form-field>
          <x-form-label for="transaction_to">Transaction to</x-form-label>
          <x-form-input name='transaction_to' id="transaction_to" type="date" value="{{ old('transaction_to',request('transaction_to', now()->format('Y-m-d')),now()->format('Y-m-d')) }}"/>
          <x-form-error name='transaction_to'/>
        </x-form-field>
      </div>
    </div>
    <hr class="my-4">
    <div class="text-end">
      <button type="submit" class="btn btn-primary" id="save-transaction-btn">Find</button>
    </div>
  </form>
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
    </div>
  </div>
</x-layout>