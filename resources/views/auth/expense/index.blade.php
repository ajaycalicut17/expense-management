<x-layouts.app>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Expenses') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- This example requires Tailwind CSS v2.0+ -->
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <a href="{{ route('expense.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Add
            </a>
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
              <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Sl.No
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Name
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                      <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                      <span class="sr-only">Delete</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  @foreach ($expenses as $index => $expense)
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ $index + $expenses->firstItem() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ $expense->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <a href="{{ route('expense.edit', ['expense' => $expense->id]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <form action="{{ route('expense.destroy', ['expense' => $expense->id]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="text-indigo-600 hover:text-indigo-900">
                      </form>
                    </td>
                  </tr>
                  @endforeach
                  
                </tbody>
              </table>
            </div>
            <div class="mt-2 mb-2">
              {{ $expenses->links() }}
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</x-layouts.app>