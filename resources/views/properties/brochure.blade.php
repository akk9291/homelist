<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $property->title }} - Brochure | {{ $settings->site_name ?? 'Homelist' }}</title>
    <style>
        @page {
            margin: 12mm;
            size: A4 portrait;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 24px;
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .logo-title {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .logo-title span {
            color: #f59e0b;
        }
        .contact-top {
            text-align: right;
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }
        .hero-img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            border-radius: 18px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .prop-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 6px 0;
        }
        .prop-address {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 16px;
        }
        .price-badge {
            background: #fef3c7;
            color: #b45309;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 22px;
            font-weight: 900;
            display: inline-block;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 12px;
            text-align: center;
        }
        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }
        .stat-value {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
        .desc-text {
            font-size: 12px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 20px;
        }
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
        }
        .amenity-pill {
            background: #f1f5f9;
            color: #334155;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
        }
        .agent-box {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 16px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }
        .agent-info h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }
        .agent-info p {
            margin: 0;
            font-size: 12px;
            color: #78350f;
        }
        .print-btn-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-btn {
            background: #f59e0b;
            color: #fff;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        @media print {
            .print-btn-bar {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-btn-bar">
        <button class="print-btn" onclick="window.print()">🖨️ Print or Save as PDF</button>
    </div>

    <div class="header">
        <div>
            <div class="logo-title">HOME<span>LIST</span></div>
            <div style="font-size: 10px; color: #94a3b8; font-weight: bold; text-transform: uppercase;">Official Property Factsheet</div>
        </div>
        <div class="contact-top">
            <strong>{{ $settings->site_name ?? 'Homelist Real Estate' }}</strong><br>
            Email: {{ $settings->contact_email ?? 'info@homelist.co.in' }}<br>
            Phone: {{ $settings->contact_phone ?? '+91 98265 23333' }}
        </div>
    </div>

    <img src="{{ $property->getFirstMediaUrl('featured') ?: ($property->featured_image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1000&q=80') }}" class="hero-img" alt="{{ $property->title }}">

    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="prop-title">{{ $property->title }}</h1>
            <div class="prop-address">📍 {{ $property->address }}, {{ $property->city?->name }}, {{ $property->state?->name }}</div>
        </div>
        <div class="price-badge">
            ₹{{ number_format($property->price) }}
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Bedrooms</div>
            <div class="stat-value">{{ $property->bedrooms ?: 'N/A' }} BHK</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Bathrooms</div>
            <div class="stat-value">{{ $property->bathrooms ?: 'N/A' }} Baths</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Built-up Area</div>
            <div class="stat-value">{{ number_format($property->area ?: 0) }} Sq. Ft.</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Property Type</div>
            <div class="stat-value">{{ $property->type?->name ?: 'Residential' }}</div>
        </div>
    </div>

    <div class="section-title">Property Overview</div>
    <div class="desc-text">
        {{ $property->description ?: 'No detailed description provided for this listing.' }}
    </div>

    @if($property->amenities->isNotEmpty())
        <div class="section-title">Key Amenities & Features</div>
        <div class="amenities-list">
            @foreach($property->amenities as $amenity)
                <span class="amenity-pill">✔ {{ $amenity->name }}</span>
            @endforeach
        </div>
    @endif

    <div class="agent-box">
        <div class="agent-info">
            <h4>Listed by: {{ $property->agent?->name ?: 'Homelist Direct Listing' }}</h4>
            <p>
                Phone: {{ $property->agent?->agent?->phone ?: ($settings->contact_phone ?? '+91 98265 23333') }} | 
                Email: {{ $property->agent?->email ?: ($settings->contact_email ?? 'info@homelist.co.in') }}
            </p>
        </div>
        <div style="text-align: right; font-size: 11px; color: #92400e; font-weight: bold;">
            Scan / Verified on Homelist Portal
        </div>
    </div>

    <div style="margin-top: 24px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 12px;">
        Generated on {{ date('d M Y, h:i A') }} from {{ url()->current() }}. All rights reserved © {{ date('Y') }} {{ $settings->site_name ?? 'Homelist' }}.
    </div>
</body>
</html>
