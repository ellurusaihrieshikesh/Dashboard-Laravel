<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sales Summary Report</title>
    <style>
        @font-face {
            font-family: 'Arial';
            src: local('Arial');
        }
        body { 
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            margin-bottom: 20px;
        }
        .summary-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            width: 20%;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #374151;
        }
        .summary-card p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        .chart-container { margin: 20px 0; }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        .table th { 
            background-color: #f5f5f5; 
        }
        .bar-chart { width: 100%; }
        .bar-chart td { vertical-align: middle; }
        .bar { background-color: #3B82F6; height: 20px; }
        .trend-line { position: relative; height: 20px; }
        .trend-dot { 
            position: absolute; 
            width: 8px; 
            height: 8px; 
            background-color: #3B82F6; 
            border-radius: 50%; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Summary Report</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-card">
                <h3>Monthly Total Sales</h3>
                <p>Rs. {{ number_format($monthlyTotalSales, 2) }}</p>
            </td>
            <td class="summary-card">
                <h3>Top Product</h3>
                <p>{{ $topProductName }}</p>
            </td>
            <td class="summary-card">
                <h3>Today's Sales</h3>
                <p>Rs. {{ number_format($todaysTotalSales, 2) }}</p>
            </td>
            <td class="summary-card">
                <h3>Today's Orders</h3>
                <p>{{ $todaysOrderCount }}</p>
            </td>
            <td class="summary-card">
                <h3>Average Sale</h3>
                <p>Rs. {{ number_format($averageSaleAmount, 2) }}</p>
            </td>
        </tr>
    </table>

    <div class="chart-container">
        <h2>Monthly Sales Chart</h2>
        {!! $barChart !!}
    </div>

    <div class="chart-container">
        <h2>Product Distribution</h2>
        {!! $pieChart !!}
    </div>

    <div class="chart-container">
        <h2>Sales Trend</h2>
        {!! $lineChart !!}
    </div>

    <h2>Recent Sales</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                    <td>{{ $sale->product_name ?? 'N/A' }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>Rs. {{ number_format($sale->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 