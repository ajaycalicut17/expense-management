<x-layouts.app>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('View Expense') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-3 bg-white">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-8 sm:col-span-6">
                        <x-label for="category_id" :value="__('Category')" />
                        
                        <select id="category_id" name="category_id" class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm" disabled>
                            <option>Select Category</option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected($expense->category_id == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                </div>
                <div class="px-4 py-3 bg-white">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-8 sm:col-span-6">
                        <x-label for="amount" :value="__('Amount (' . Number::defaultCurrency() . ')')" />

                        <x-input id="amount" class="block mt-1 w-full" type="number" step="0.01" min="0" name="amount" :value="$expense->amount" disabled />
                    </div>
                  </div>
                </div>
                <div class="px-4 py-3 bg-white">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-8 sm:col-span-6">
                        <x-label for="description" :value="__('Description')" />

                        <x-input id="description" class="block mt-1 w-full" type="text" name="description" :value="$expense->description" disabled />
                    </div>
                  </div>
                </div>
                <div class="px-4 py-3 bg-white">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-8 sm:col-span-6">
                        <x-label for="spent_at" :value="__('Spent At')" />

                        <x-input id="spent_at" class="block mt-1 w-full" type="datetime-local" name="spent_at" :value="$expense->spent_at" disabled />
                    </div>
                  </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                  <a href="{{ route('expense.index', ['page' => request('page') ?? 1]) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-black bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Back
                  </a>
                </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  </div>
</x-layouts.app>