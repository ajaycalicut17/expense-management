<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Average Daily Expenses Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Average Daily Expenses ({{ auth()->user()->name }})</h3>
                        </div>
                        <div class="flex gap-2 mb-4 flex-wrap">
                            <select id="monthSelector" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1 min-w-[120px]">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <select id="yearSelector" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1 min-w-[120px]">
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold text-gray-900" id="averageDailyAmount">$00.00</span>
                            <span class="ml-2 text-sm text-gray-500">per day</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-500" id="monthLabel">
                            {{ date('F') }} {{ date('Y') }}
                        </p>
                    </div>
                </div>

                <!-- Total Expenses Per Category Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Total Expenses per Category</h3>
                        </div>
                        <div class="flex gap-2 mb-4 flex-wrap">
                            <select id="userSelector" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1 min-w-[120px]">
                                <option value="">Select User</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <select id="categoryMonthSelector" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1 min-w-[120px]">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <select id="categoryYearSelector" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1 min-w-[120px]">
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <canvas id="expenseByCategory"></canvas>
                        <p class="mt-3 text-sm text-gray-500" id="categoryMonthLabel">
                            {{ date('F') }} {{ date('Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        // Average Daily Expenses API call
        $(document).ready(function() {
            function fetchAverageDailyExpenses() {
                const month = $('#monthSelector').val();
                const year = $('#yearSelector').val();

                $.ajax({
                    url: '{{ route('average-daily-expense') }}',
                    method: 'GET',
                    data: {
                        month: month,
                        year: year
                    },
                    success: function(response) {
                        $('#averageDailyAmount').text(response.data.average_daily_expenses);
                        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        $('#monthLabel').text(monthNames[month - 1] + ' ' + year);
                    },
                    error: function(xhr) {
                        console.error('Error fetching average daily expenses:', xhr);
                    }
                });
            }

            fetchAverageDailyExpenses();
            $('#monthSelector, #yearSelector').on('change', fetchAverageDailyExpenses);

            // Total Expenses by Category
            let expenseChart = null;

            function fetchTotalExpensesByCategory() {
                const userId = $('#userSelector').val();
                const month = $('#categoryMonthSelector').val();
                const year = $('#categoryYearSelector').val();

                $.ajax({
                    url: '{{ route('total-expenses-by-category') }}',
                    method: 'GET',
                    data: {
                        user_id: userId,
                        month: month,
                        year: year
                    },
                    success: function(response) {

                        const ctx = document.getElementById('expenseByCategory');

                        // Destroy existing chart if it exists
                        if (expenseChart) {
                            expenseChart.destroy();
                        }

                        const data = {
                            labels: response.labels,
                            datasets: [{
                                label: 'Total Expenses',
                                data: response.data,
                                hoverOffset: 4
                            }]
                        };

                        expenseChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: data,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                        
                        const monthNames = [
                            'January', 'February', 'March', 'April',
                            'May', 'June', 'July', 'August',
                            'September', 'October', 'November', 'December'
                        ];

                        $('#categoryMonthLabel').text(
                            monthNames[month - 1] + ' ' + year
                        );
                    },
                    error: function(xhr) {
                        console.error('Error fetching total expenses by category:', xhr);
                    }
                });
            }

            fetchTotalExpensesByCategory();
            $('#userSelector, #categoryMonthSelector, #categoryYearSelector').on('change', fetchTotalExpensesByCategory);
        });
    </script>
</x-layouts.app>
