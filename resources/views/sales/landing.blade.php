@extends('layouts.app')

@section('styles')
<style>
    body {
        background: white;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
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
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        display: inline-block;
        font-weight: 600;
    }
    .btn:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .hero-section {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem;
        margin: 1rem;
        max-width: 1000px;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .dashboard-preview {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease;
    }
    .dashboard-preview:hover {
        transform: scale(1.02);
    }
    .feature-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin: 1.5rem 0;
    }
    @media (max-width: 768px) {
        .feature-cards {
            grid-template-columns: 1fr;
        }
        .hero-section {
            margin: 0.5rem;
            padding: 1.5rem;
        }
    }
    /* Loading overlay styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .loading-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Hero Section -->
<section class="hero-section">
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <!-- Left Column - Content -->
        <div class="w-full md:w-1/2 text-center md:text-left">
            <h1 class="text-2xl md:text-3xl font-bold mb-3">Welcome to Sales Dashboard</h1>
            <p class="text-base mb-4 text-gray-600">Track your daily sales, generate reports, and visualize your business performance.</p>
            
            <div class="feature-cards">
                <div class="card p-3">
                    <h3 class="text-base font-semibold mb-1">Sales Tracking</h3>
                    <p class="text-xs text-gray-600">Monitor transactions easily</p>
                </div>
                <div class="card p-3">
                    <h3 class="text-base font-semibold mb-1">Analytics</h3>
                    <p class="text-xs text-gray-600">Visual data insights</p>
                </div>
                <div class="card p-3">
                    <h3 class="text-base font-semibold mb-1">Reports</h3>
                    <p class="text-xs text-gray-600">PDF exports ready</p>
                </div>
            </div>

            <a href="{{ route('sales.index') }}" class="btn mt-4" id="getStartedBtn">Go to Dashboard</a>
        </div>

        <!-- Right Column - Image -->
        <div class="w-full md:w-1/2">
            <div class="relative">
                <img src="{{ asset('images/photo.png') }}" 
                     alt="Sales Dashboard Preview" 
                     class="dashboard-preview">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/10 to-transparent rounded-lg"></div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const getStartedBtn = document.getElementById('getStartedBtn');
        const loadingOverlay = document.getElementById('loadingOverlay');

        getStartedBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadingOverlay.classList.add('active');
            
            // Simulate a small delay before redirecting
            setTimeout(function() {
                window.location.href = getStartedBtn.href;
            }, 500);
        });

        // Hide loading overlay when page is fully loaded
        window.addEventListener('load', function() {
            loadingOverlay.classList.remove('active');
        });
    });
</script>
@endsection 