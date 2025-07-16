@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
    }
    .glass-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    .glass-btn {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
    }
    .glass-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    body {
        background: linear-gradient(45deg, #4158D0, #C850C0, #FFCC70);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
        min-height: 100vh;
    }
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .form-control {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        color: white;
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    .table {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 15px;
        overflow: hidden;
    }
    .table th, .table td {
        color: white;
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>

<div class="container py-5">
    <div class="glass-container p-4 mb-4">
        <h1 class="text-white mb-4">Sales Dashboard</h1>
        
        <!-- Add New Sale Form -->
        <div class="glass-card p-4 mb-4">
            <h3 class="text-white mb-3">Add New Sale</h3>
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount" required>
                    </div>
                </div>
                <button type="submit" class="btn glass-btn">Add Sale</button>
            </form>
        </div>

        <!-- Sales Table -->
        <div class="glass-card p-4">
            <h3 class="text-white mb-3">Recent Sales</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                            <td>{{ $sale->product_name }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>${{ number_format($sale->amount, 2) }}</td>
                            <td>
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn glass-btn btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 
                                    <button type="submit" class="btn glass-btn btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 