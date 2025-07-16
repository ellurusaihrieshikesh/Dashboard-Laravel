<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DailySaleController extends Controller
{
    public function index()
    {
        $sales = DailySale::orderBy('sale_date', 'desc')
            ->paginate(10);

        // Get daily sales data for the past week
        $dailySalesData = DailySale::select(
                'sale_date',
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('sale_date', [now()->subDays(6), now()])
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        // Get top products data for bar chart
        $topProducts = DailySale::select(
                'product_name',
                DB::raw('SUM(amount) as total_amount')
            )
            ->whereNotNull('product_name')
            ->groupBy('product_name')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        // Get product distribution data for pie chart
        $productDistribution = DailySale::select(
                'product_name',
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('product_name')
            ->groupBy('product_name')
            ->orderByDesc('count')
            ->get();

        // Summary cards
        $monthlyTotalSales = DailySale::whereMonth('sale_date', now()->month)
            ->sum('amount');
        
        $topProductName = $topProducts->first()->product_name ?? 'N/A';
        
        $todaysTotalSales = DailySale::whereDate('sale_date', now())
            ->sum('amount');
        
        $todaysOrderCount = DailySale::whereDate('sale_date', now())
            ->count();
        
        $averageSaleAmount = DailySale::avg('amount');

        return view('sales.index', compact(
            'sales',
            'dailySalesData',
            'topProducts',
            'productDistribution',
            'monthlyTotalSales',
            'topProductName',
            'todaysTotalSales',
            'todaysOrderCount',
            'averageSaleAmount'
        ));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'product_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DailySale::create($validated);

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully!');
    }

    public function todaySummary()
    {
        $today = Carbon::today();
        $totalSales = DailySale::whereDate('sale_date', $today)
            ->sum('amount');
        
        $salesCount = DailySale::whereDate('sale_date', $today)
            ->count();

        return response()->json([
            'date' => $today->format('Y-m-d'),
            'total_sales' => $totalSales,
            'sales_count' => $salesCount
        ]);
    }

    public function destroy($id)
    {
        $sale = DailySale::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale record deleted successfully.');
    }

    public function generatePdf()
    {
        // Get all the necessary data
        $sales = DailySale::orderBy('sale_date', 'desc')
            ->take(10)
            ->get();
        
        // Bar Chart (last 6 months)
        $monthlySales = DailySale::select(
                DB::raw("DATE_FORMAT(sale_date, '%Y-%m') as month"),
                DB::raw("SUM(amount) as total")
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        $monthlyLabels = $monthlySales->pluck('month')->map(function ($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M Y');
        });

        $monthlyTotals = $monthlySales->pluck('total');

        // Pie Chart - product counts
        $productSales = DailySale::select(
                'product_name',
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('product_name')
            ->groupBy('product_name')
            ->orderByDesc('count')
            ->get();

        $productLabels = $productSales->pluck('product_name');
        $productCounts = $productSales->pluck('count');

        // Summary cards
        $monthlyTotalSales = DailySale::whereMonth('sale_date', now()->month)
            ->sum('amount');
        
        $topProductName = $productSales->first()->product_name ?? 'N/A';
        
        $todaysTotalSales = DailySale::whereDate('sale_date', now())
            ->sum('amount');
        
        $todaysOrderCount = DailySale::whereDate('sale_date', now())
            ->count();
        
        $averageSaleAmount = DailySale::avg('amount');

        // Generate chart images
        $barChart = $this->generateBarChart($monthlyLabels, $monthlyTotals);
        $pieChart = $this->generatePieChart($productLabels, $productCounts);
        $lineChart = $this->generateLineChart($monthlyLabels, $monthlyTotals);

        $pdf = PDF::loadView('sales.pdf.summary', compact(
            'sales',
            'monthlyTotalSales',
            'topProductName',
            'todaysTotalSales',
            'todaysOrderCount',
            'averageSaleAmount',
            'barChart',
            'pieChart',
            'lineChart'
        ));

        return $pdf->download('sales-summary.pdf');
    }

    private function generateBarChart($labels, $data)
    {
        // Convert Collections to arrays if needed
        $data = $data instanceof \Illuminate\Support\Collection ? $data->toArray() : $data;
        $labels = $labels instanceof \Illuminate\Support\Collection ? $labels->toArray() : $labels;
        
        // Create a simple HTML table representation of the bar chart
        $html = '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><th>Month</th><th>Amount (Rs.)</th><th>Bar</th></tr>';
        
        $maxValue = max($data);
        foreach ($labels as $index => $label) {
            $value = $data[$index];
            $barWidth = ($value / $maxValue) * 100;
            $html .= '<tr>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">' . $label . '</td>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">Rs. ' . number_format($value, 2) . '</td>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">';
            $html .= '<div style="background-color: #3B82F6; height: 20px; width: ' . $barWidth . '%;"></div>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        return $html;
    }

    private function generatePieChart($labels, $data)
    {
        $data = $data instanceof \Illuminate\Support\Collection ? $data->toArray() : $data;
        $labels = $labels instanceof \Illuminate\Support\Collection ? $labels->toArray() : $labels;
        
        // Create a simple HTML table representation of the pie chart
        $html = '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><th>Product</th><th>Count</th><th>Percentage</th></tr>';
        
        $total = array_sum($data);
        foreach ($labels as $index => $label) {
            $value = $data[$index];
            $percentage = ($value / $total) * 100;
            $html .= '<tr>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">' . $label . '</td>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">' . $value . '</td>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">' . number_format($percentage, 1) . '%</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        return $html;
    }

    private function generateLineChart($labels, $data)
    {
        $data = $data instanceof \Illuminate\Support\Collection ? $data->toArray() : $data;
        $labels = $labels instanceof \Illuminate\Support\Collection ? $labels->toArray() : $labels;
        
        // Create a simple HTML table representation of the line chart
        $html = '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><th>Month</th><th>Amount (Rs.)</th><th>Trend</th></tr>';
        
        $maxValue = max($data);
        foreach ($labels as $index => $label) {
            $value = $data[$index];
            $html .= '<tr>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">' . $label . '</td>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">Rs. ' . number_format($value, 2) . '</td>';
            $html .= '<td style="padding: 5px; border: 1px solid #ddd;">';
            $html .= '<div style="height: 20px; width: 100%; position: relative;">';
            $html .= '<div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background-color: #3B82F6;"></div>';
            $html .= '<div style="position: absolute; bottom: ' . ($value / $maxValue * 100) . '%; left: 0; width: 100%; height: 2px; background-color: #3B82F6;"></div>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        return $html;
    }
}
