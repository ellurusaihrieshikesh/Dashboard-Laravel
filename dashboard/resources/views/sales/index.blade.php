@extends('layouts.app')

@section('styles')
<style>
    body {
        background: white;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .btn {
        background: #3b82f6;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s;
    }
    .btn:hover {
        background: #2563eb;
    }
    .table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .table th {
        background: #f8fafc;
        color: #1f2937;
    }
    .table td {
        border-bottom: 1px solid #e5e7eb;
    }
    .footer {
        background: #f8fafc;
        padding: 2rem 0;
        margin-top: auto;
        position: relative;
        width: 100%;
    }
</style>
@endsection

@section('content')
<!-- Navigation Bar -->
<nav class="bg-white shadow-sm">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="/" class="text-xl font-bold text-gray-800">Sales Dashboard</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('sales.pdf') }}" class="btn">Download Report</a>
                
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="hidden md:block">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile Settings
                        </a>
                        <a href="{{ route('switch.account.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Switch Account
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="container mx-auto py-6 px-4 flex-grow">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="card p-6">
            <h4 class="text-sm font-semibold text-gray-600">Monthly Total Sales</h4>
            <p class="text-2xl font-bold text-blue-600 mt-2">₹{{ number_format($monthlyTotalSales, 2) }}</p>
        </div>
        <div class="card p-6">
            <h4 class="text-sm font-semibold text-gray-600">Top Product Purchased</h4>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $topProductName }}</p>
        </div>
        <div class="card p-6">
            <h4 class="text-sm font-semibold text-gray-600">Today's Total Sales</h4>
            <p class="text-2xl font-bold text-red-600 mt-2">₹{{ number_format($todaysTotalSales, 2) }}</p>
        </div>
        <div class="card p-6">
            <h4 class="text-sm font-semibold text-gray-600">Total Orders Today</h4>
            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $todaysOrderCount }}</p>
        </div>
        <div class="card p-6">
            <h4 class="text-sm font-semibold text-gray-600">Average Sale Amount</h4>
            <p class="text-2xl font-bold text-yellow-600 mt-2">₹{{ number_format($averageSaleAmount, 2) }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Left Column: Table -->
        <div class="w-full md:w-2/3">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daily Sales Records</h2>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table -->
            <div class="table-container mb-6">
                <div class="overflow-auto h-96">
                    <table class="min-w-full table">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="px-6 py-4 text-gray-800">{{ $sale->sale_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-gray-800">{{ $sale->product_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-gray-800">{{ $sale->quantity }}</td>
                                    <td class="px-6 py-4 text-gray-800">₹{{ number_format($sale->amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No sales records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Create Form -->
        <div class="w-full md:w-1/3">
            <div class="card p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Sale</h2>
                
                <form action="{{ route('sales.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Date Field -->
                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="sale_date" id="sale_date" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('sale_date', date('Y-m-d')) }}">
                        @error('sale_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Name Field -->
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="product_name" id="product_name" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('product_name') }}" placeholder="Enter product name">
                        @error('product_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount Field -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" id="amount" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('amount') }}" placeholder="0.00">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity Field -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" id="quantity" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('quantity', 1) }}" min="1">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn w-full">Create Sale</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <!-- Sales by Day Chart -->
        <div class="card p-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Daily Sales This Week</h3>
            <div style="height: 200px;">
                <canvas id="salesByDayChart"></canvas>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="card p-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Top Products by Sales</h3>
            <div style="height: 200px;">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <!-- Product Distribution Chart -->
        <div class="card p-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Product Distribution</h3>
            <div style="height: 200px;">
                <canvas id="productDistributionChart"></canvas>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">About Us</h3>
                <p class="text-gray-600">Your trusted partner for sales tracking and analytics.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-600 hover:text-gray-800">Home</a></li>
                    <li><a href="{{ route('sales.pdf') }}" class="text-gray-600 hover:text-gray-800">Download Report</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Contact</h3>
                <p class="text-gray-600">Email: info@salesdashboard.com</p>
            </div>
        </div>
        <div class="border-t border-gray-200 mt-8 pt-8 text-center">
            <p class="text-gray-600">&copy; {{ date('Y') }} Sales Dashboard. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection

@section('scripts')
<!-- Chart Initialization -->
<script>
    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 8,
                    font: {
                        size: 11
                    }
                }
            }
        }
    };

    // Sales by Day Chart
    const salesByDayCtx = document.getElementById('salesByDayChart').getContext('2d');
    new Chart(salesByDayCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySalesData->pluck('sale_date')) !!},
            datasets: [{
                label: 'Daily Sales',
                data: {!! json_encode($dailySalesData->pluck('total')) !!},
                borderColor: '#3b82f6',
                tension: 0.1
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value;
                        },
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(topProductsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProducts->pluck('product_name')) !!},
            datasets: [{
                label: 'Sales Amount',
                data: {!! json_encode($topProducts->pluck('total_amount')) !!},
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value;
                        },
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // Product Distribution Chart
    const productDistributionCtx = document.getElementById('productDistributionChart').getContext('2d');
    new Chart(productDistributionCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($productDistribution->pluck('product_name')) !!},
            datasets: [{
                data: {!! json_encode($productDistribution->pluck('count')) !!},
                backgroundColor: [
                    '#3b82f6', // blue
                    '#10b981', // green
                    '#f59e0b', // yellow
                    '#ef4444', // red
                    '#8b5cf6', // purple
                    '#ec4899', // pink
                    '#6366f1'  // indigo
                ]
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: false
                }
            }
        }
    });
</script>
@endsection
