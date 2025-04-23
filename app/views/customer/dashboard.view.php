<?php require_once 'customerHeader.view.php'; ?>

<!-- Include Chart.js and Gauge.js libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #FFC107;
        --secondary: #FCA311;
        --accent: #4895ef;
        --success: #4cc9f0;
        --color1:#06D001;
        --warning: #f72585;
        --danger: #e5383b;
        --neutral: #e9ecef;
        --dark: #212529;
        --light: #f8f9fa;
        --system-yellow: #FFC107; 
    }
    
    body {
        background-color: #f0f2f5;
    }
    
    .modern-dashboard {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Futuristic summary cards with background images */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.75rem;
        margin-bottom: 2.5rem;
    }
    
    .summary-card {
        position: relative;
        border-radius: 16px;
        padding: 1.8rem;
        overflow: hidden;
        transition: all 0.3s ease;
        z-index: 0;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    
    /* Background image styles */
    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        z-index: -3;
        filter: saturate(1.1) contrast(1.05);
        transition: all 0.4s ease;
    }
    
    /* Glass effect overlay */
    .summary-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        z-index: -2;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s ease;
    }
    
    /* Hover effects */
    .summary-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .summary-card:hover::before {
        filter: saturate(1.2);
        transform: scale(1.05);
    }
    
    .summary-card:hover::after {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(1px);
        -webkit-backdrop-filter: blur(1px);
    }
    
    /* Individual card background images - UPDATED with unique images */
    .summary-card.current-rental::before {
        background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3');
    }
    
    .summary-card.expenses::before {
        background-image: url('https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?ixlib=rb-4.0.3');
    }
    
    .summary-card.service-regular::before {
        background-image: url('https://images.unsplash.com/photo-1581578731548-c64695cc6952?ixlib=rb-4.0.3');
    }
    
    .summary-card.service-external::before {
        background-image: url('https://images.unsplash.com/photo-1565183997392-2f6f122e5912?ixlib=rb-4.0.3');
    }
    
    .summary-card.rental-history::before {
        background-image: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3');
    }
    
    /* Modern icon containers */
    .summary-card .icon-container {
        width: 65px;
        height: 65px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 1rem;
        position: relative;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    /* Improved icon container colors */
    .summary-card .icon-container.primary-icon {
        color: var(--primary);
        background: rgba(255, 193, 7, 0.15);
        box-shadow: 0 8px 20px rgba(255, 193, 7, 0.2);
    }
    
    .summary-card .icon-container.warning-icon {
        color: var(--warning);
        background: rgba(247, 37, 133, 0.15);
        box-shadow: 0 8px 20px rgba(247, 37, 133, 0.2);
    }
    
    .summary-card .icon-container.success-icon {
        color: var(--success);
        background: rgba(76, 201, 240, 0.15);
        box-shadow: 0 8px 20px rgba(76, 201, 240, 0.2);
    }
    
    .summary-card .icon-container.color1-icon {
        color: var(--color1);
        background: rgba(6, 208, 1, 0.15);
        box-shadow: 0 8px 20px rgba(6, 208, 1, 0.2);
    }
    
    .summary-card:hover .icon-container {
        transform: translateY(-5px) scale(1.05);
    }
    
    /* Card content styles */
    .card-content {
        position: relative;
        z-index: 1;
    }
    
    .card-content h3 {
        margin: 0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #555;
        font-weight: 600;
        position: relative;
        display: inline-block;
    }
    
    .card-content h3:after {
        content: '';
        position: absolute;
        width: 40%;
        height: 2px;
        bottom: -5px;
        left: 0;
        background: rgba(0,0,0,0.1);
        border-radius: 2px;
    }
    
    .card-content .value {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 1rem 0;
        letter-spacing: -0.5px;
    }
    
    .card-content .value.positive {
        color: var(--success);
    }
    
    .card-content .value.negative {
        color: var(--danger);
    }
    
    .card-footer {
        font-size: 0.85rem;
        color: #666;
        margin-top: auto;
        padding-top: 0.8rem;
        border-top: 1px dashed rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-footer a {
        text-decoration: none;
        color: var(--color1);
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .card-footer a:hover {
        color: var(--secondary);
    }
    
    /* Dashboard sections */
    .two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 768px) {
        .two-columns {
            grid-template-columns: 1fr;
        }
    }
    
    .chart-container, .payment-section, .rental-history, .service-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    }

    .rental-history{
        margin-top: 15px;
        margin-bottom: 15px;
        margin-left: 5px;
        margin-right: 5px;

    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
    }
    
    .action-button {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        margin-left: 0.5rem;
        transition: background-color 0.2s;
    }
    
    .action-button:hover {
        background-color: var(--secondary);
    }
    
    /* Tables */
    .payment-table, .rental-table, .service-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .payment-table th, .rental-table th, .service-table th {
        text-align: left;
        padding: 0.75rem;
        border-bottom: 2px solid #eee;
        font-size: 0.85rem;
        font-weight: 600;
        color: #757575;
    }
    
    .payment-table td, .rental-table td, .service-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
    }
    
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-success {
        background-color: rgba(6, 208, 1, 0.15);
        color: var(--color1);
    }
    
    .badge-warning {
        background-color: rgba(251, 133, 0, 0.15);
        color: var(--warning);
    }
    
    .badge-danger {
        background-color: rgba(230, 57, 70, 0.15);
        color: var(--danger);
    }
    
    .badge-info {
        background-color: rgba(58, 134, 255, 0.15);
        color: var(--primary);
    }
    
    /* Chart area */
    .chart-area {
        height: 300px;
        position: relative;
    }
    
    /* Property card */
    .property-card {
        display: flex;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }
    
    .property-image {
        width: 140px;
        height: 140px;
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
        margin-left: auto;
    }
    
    .property-details {
        padding: 1.5rem;
        flex: 1;
    }
    
    .property-title {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
    }
    
    .property-address {
        color: #757575;
        margin: 0 0 1rem 0;
        font-size: 0.9rem;
    }
    
    .property-meta {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #666;
    }
    
    .fallback-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.9);
    }
    
    .no-data {
        text-align: center;
        padding: 2rem;
        color: #757575;
    }
    
    /* Improved service card styles */
    .service-card {
        border: 1px solid #eee;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
        display: flex;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        min-height: 120px;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .service-details {
        padding: 18px;
        flex: 1;
    }
    
    .service-title {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 1.1rem;
        color: #333;
    }
    
    .service-date {
        color: #777;
        font-size: 0.85rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }
    
    .service-date i {
        margin-right: 5px;
        font-size: 0.85rem;
        color: #aaa;
    }
    
    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        font-size: 0.9rem;
        padding-top: 12px;
        border-top: 1px solid #f5f5f5;
    }
    
    .service-address {
        background-color: #f8f9fa;
        padding: 8px 12px;
        border-radius: 6px;
        color: #555;
        margin-bottom: 8px;
        font-size: 0.9rem;
        display: flex;
        align-items: flex-start;
    }
    
    .service-address i {
        margin-right: 8px;
        margin-top: 3px;
        color: #888;
    }
    
    .service-cost {
        font-weight: 600;
        color: var(--warning);
        padding: 5px 10px;
        border-radius: 20px;
        background-color: rgba(247, 37, 133, 0.1);
    }
    
    .service-image-segment {
        width: 180px;
        min-width: 180px;
        position: relative;
        overflow: hidden;
    }
    
    .service-image {
        width: 100%;
        height: 100%;
        min-height: 160px;
        background-size: cover;
        background-position: center;
        transition: transform 0.4s cubic-bezier(0.4,0.2,0.2,1), box-shadow 0.3s;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .service-card:hover .service-image {
        transform: scale(1.07) rotate(-2deg);
        box-shadow: 0 8px 32px rgba(76,201,240,0.18), 0 2px 10px rgba(0,0,0,0.10);
        filter: brightness(1.08) saturate(1.15) contrast(1.08);
        border-radius: 16px;
    }
    
    .service-card:hover .service-image {
        /* transform: scale(1.05); */
        border-radius: 10px;
    }
    
    .service-status-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        font-size: 0.85rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .image-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 15px;
        justify-content: center;
        background: rgba(0,0,0,0.02);
    }
    
    .image-thumbnail {
        width: 60px;
        height: 60px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #eee;
        cursor: pointer;
        background: #fafafa;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .image-thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-color: var(--primary);
    }
    
    .image-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .property-images-label {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(0,0,0,0.6);
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        z-index: 2;
    }
    
    @media (max-width: 768px) {
        .service-card {
            flex-direction: column;
        }
        
        .service-image-segment {
            width: 100%;
            min-width: 100%;
            height: 180px;
        }
        
        .property-meta {
            flex-wrap: wrap;
            gap: 1rem;
        }
    }
    
    @keyframes zoomIn {
        from {transform: scale(0.1); opacity: 0;}
        to {transform: scale(1); opacity: 1;}
    }

    .view-all-images-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        overflow: hidden;
        z-index: 5;
    }
    
    .view-all-images-btn .btn-content {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .view-all-images-btn .image-count {
        background-color: var(--primary);
        color: #000;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.75rem;
        margin-left: 2px;
        transition: transform 0.3s ease;
    }
    
    .view-all-images-btn:hover {
        background: rgba(0,0,0,0.85);
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }
    
    .view-all-images-btn:hover .image-count {
        transform: scale(1.15);
    }
    
    /* Futuristic Image Modal Styles */
    #imageModal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        overflow: auto;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    .modal-content-wrapper {
        position: relative;
        width: 85%;
        max-width: 1200px;
        margin: auto;
        text-align: center;
        border-radius: 12px;
        overflow: hidden;
        background: rgba(20, 20, 20, 0.7);
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: rgba(30, 30, 30, 0.7);
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    
    .modal-header h3 {
        color: white;
        margin: 0;
        font-weight: 500;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-header h3 .image-counter {
        background: var(--primary);
        color: #000;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
    }
    
    .modal-close {
        color: #f1f1f1;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0,0,0,0.2);
    }
    
    .modal-close:hover {
        background: rgba(255,255,255,0.15);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 20px;
        overflow: hidden;
    }
    
    .modal-image-container {
        position: relative;
        height: calc(70vh - 140px);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    #modalImage {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.3);
        animation: fadeZoomIn 0.4s cubic-bezier(0.19, 1, 0.22, 1);
    }
    
    .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 2;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    .nav-button:hover {
        background: rgba(0,0,0,0.7);
        transform: translateY(-50%) scale(1.1);
    }
    
    #prevImage {
        left: 20px;
    }
    
    #nextImage {
        right: 20px;
    }
    
    .nav-icon {
        font-size: 1.5rem;
    }
    
    #modalGallery {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        overflow-x: auto;
        padding: 15px 5px;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.3) transparent;
        justify-content: center;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        background: rgba(0,0,0,0.3);
        border-radius: 12px;
        border-top: 1px solid rgba(255,255,255,0.07);
    }
    
    #modalGallery::-webkit-scrollbar {
        height: 8px;
    }
    
    #modalGallery::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.2);
        border-radius: 10px;
    }
    
    #modalGallery::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }
    
    .thumbnail {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        transition: all 0.2s cubic-bezier(0.25, 1, 0.5, 1);
        flex-shrink: 0;
        border: 2px solid transparent;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .thumbnail.active {
        border-color: var(--primary);
        transform: scale(1.05);
    }
    
    .thumbnail:hover {
        transform: translateY(-4px);
    }
    
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-index {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.6);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
    }
    
    @keyframes fadeZoomIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-in {
        animation: slideIn 0.4s forwards;
    }
    
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
    }
    
    .loader {
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .modal-content-wrapper {
            width: 95%;
        }
        
        .modal-image-container {
            height: calc(60vh - 140px);
        }
        
        .nav-button {
            width: 40px;
            height: 40px;
        }
        
        .thumbnail {
            width: 60px;
            height: 45px;
        }
        
        .modal-header h3 {
            font-size: 1rem;
        }

        /* .summary-card rental-history{
            background-color: white;
            border-radius: 15px;
            margin-top: 15px;
            margin-bottom: 15px;
        } */
    }

    /* Current Rental Property Styles */
.recent-props-section {
    margin-bottom: 2rem;
}

.current-property-card {
    display: flex;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    max-width: 100%;
    margin-bottom: 1.5rem;
    background: white;
}

/* Property Image Styles - Full Height Cover */
.property-image-container {
    text-decoration: none;
    display: block;
    width: 35%;
    position: relative;
    overflow: hidden;
    min-height: 300px;
}

.property-image {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-size: cover;
    background-position: center;
    transition: all 0.5s ease;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    color: white;
}

.image-overlay i {
    font-size: 2.5rem;
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.3s ease;
}

.property-image-container:hover .property-image {
    transform: scale(1.05);
}

.property-image-container:hover .image-overlay {
    opacity: 1;
}

.property-image-container:hover .image-overlay i {
    opacity: 1;
    transform: scale(1);
}

/* Property Details Styles */
.property-details {
    flex: 1;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.property-info {
    margin-bottom: 1rem;
}

.property-name-link {
    text-decoration: none;
    color: inherit;
}

.property-name {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
    color: var(--dark);
    transition: color 0.2s ease;
}

.property-name-link:hover .property-name {
    color: var(--primary);
}

.property-address {
    font-size: 0.95rem;
    color: #555;
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
}

.property-address i {
    margin-right: 8px;
    color: var(--primary);
}

.property-features {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    margin-top: 1.2rem;
    margin-left: 15px;
    
}

.feature-item {
    display: flex;
    align-items: center;
}

.feature-item i {
    color: var(--primary);
    margin-right: 8px;
}

.feature-item span {
    font-size: 0.95rem;
}

.booking-details {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    margin: 0.75rem 0;
    border-top: 1px dashed #eee;
    padding-top: 1rem;
}

.booking-item {
    display: flex;
    align-items: center;
}

.booking-item i {
    color: var(--accent);
    margin-right: 8px;
}

.booking-item:nth-child(2) i {
    color: var(--warning);
}

.booking-item span {
    font-size: 0.9rem;
}

/* Action Section Styles */
.action-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    border-top: 1px solid #f5f5f5;
    padding-top: 1rem;
}

.status-container .badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-button {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    color: white;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.primary-button {
    background-color: var(--primary);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
}

.primary-button:hover {
    background-color: var(--secondary);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(255, 193, 7, 0.3);
}

.accent-button {
    background-color: var(--color1);
    box-shadow: 0 4px 12px rgba(72, 149, 239, 0.2);
}

.accent-button:hover {
    background-color: #06D001; 
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(72, 149, 239, 0.3);
}

.success-button {
    background-color: var(--success);
    box-shadow: 0 4px 12px rgba(76, 201, 240, 0.2);
}

.success-button:hover {
    background-color: #3a86ff;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(76, 201, 240, 0.3);
}

/* No Rental Data Styles */
.no-rental-data {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.no-rental-data i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1.5rem;
}

.no-rental-data h3 {
    margin-bottom: 1rem;
    color: #555;
}

.no-rental-data p {
    margin-bottom: 1.5rem;
    color: #777;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .current-property-card {
        flex-direction: column;
    }
    
    .property-image-container {
        width: 100%;
        height: 200px;
        min-height: auto;
    }
    
    .action-section {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: space-between;
    }
}
</style>

<div class="user_view-menu-bar">
    <h2>Customer Dashboard</h2>
</div>

<div class="modern-dashboard">
    <!-- Summary Cards for Customer -->
    <div class="summary-cards">
        <!-- Current Rental -->
        <div class="summary-card current-rental">
            <div class="icon-container primary-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-content">
                <h3>Current Rental</h3>
                <div class="value">
                    <?php 
                    // Get count of active rentals
                    $activeRentals = isset($activeBookings) && is_array($activeBookings) 
                        ? count($activeBookings)
                        : 0;
                    echo $activeRentals;
                    ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i>
                    <span>Active Property Rentals</span>
                </div>
            </div>
        </div>
        
        <!-- Total Expenses Card -->
        <div class="summary-card expenses">
            <div class="icon-container warning-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="card-content">
                <h3>Service Expenses</h3>
                <div class="value" style="color: var(--warning);">
                    <?php echo 'LKR ' . number_format($totalExpenses ?? 0, 2); ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-coins" style="color: var(--warning);"></i>
                    <span>Total maintenance costs</span>
                </div>
            </div>
        </div>
        
        <!-- Regular Service Requests Card -->
        <div class="summary-card service-regular">
            <div class="icon-container success-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="card-content">
                <h3>Regular Service Requests</h3>
                <div class="value" style="color: var(--success);">
                    <?php
                    $serviceRequestsRegularCount = isset($serviceRequestsRegular) && is_array($serviceRequestsRegular)
                        ? count($serviceRequestsRegular)
                        : 0;
                    echo $serviceRequestsRegularCount;
                    ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-wrench" style="color: var(--success);"></i>
                    <a href="<?= ROOT ?>/dashboard/requestService">Request New Service</a>
                </div>
            </div>
        </div>

        <!-- External Service Requests Card -->
        <div class="summary-card service-external">
            <div class="icon-container color1-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="card-content">
                <h3>External Service Requests</h3>
                <div class="value" style="color: var(--color1);">
                    <?php
                    $serviceRequestsExternalCount = isset($serviceRequestsExternal) && is_array($serviceRequestsExternal)
                        ? count($serviceRequestsExternal)
                        : 0;
                    echo $serviceRequestsExternalCount;
                    ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-hammer" style="color: var(--color1);"></i>
                    <a href="<?= ROOT ?>/dashboard/requestServiceExternal">Request External Service</a>
                </div>
            </div>
        </div>
        
        <!-- Rental History Card -->
        <div class="summary-card rental-history">
            <div class="icon-container primary-icon">
                <i class="fas fa-history"></i>
            </div>
            <div class="card-content">
                <h3>Rental History</h3>
                <div class="value">
                    <?php
                    $bookingCount = isset($bookings) && is_array($bookings)
                        ? count($bookings)
                        : 0;
                    echo $bookingCount;
                    ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-list" style="color: var(--primary);"></i>
                    <a href="<?= ROOT ?>/dashboard/occupiedProperties">View Rental History</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Current Rental Property -->
    <div class="recent-props-section">
        <div class="card-header">
            <h3>Current Rental Property</h3>
            <?php if (isset($currentProperty) && $currentProperty): ?>
                <a href="<?= ROOT ?>/dashboard/search" class="action-button">
                    <i class="fas fa-search" style="margin-right: 6px;"></i>Search for more...
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($currentProperty) && $currentProperty): ?>
            <?php 
            // Properly handle property image
            $imageUrl = ROOT . '/assets/images/property-default.jpg';
            
            try {
                if (!empty($currentProperty->property_images)) {
                    // Handle property_images as JSON string or array
                    if (is_string($currentProperty->property_images)) {
                        $propertyImages = json_decode($currentProperty->property_images);
                        if (is_array($propertyImages) && !empty($propertyImages[0])) {
                            $imageUrl = ROOT . '/assets/images/uploads/property_images/' . $propertyImages[0];
                        }
                    } else if (is_array($currentProperty->property_images) && !empty($currentProperty->property_images[0])) {
                        $imageUrl = ROOT . '/assets/images/uploads/property_images/' . $currentProperty->property_images[0];
                    }
                }
                // Fallback to image_url if no property_images found
                else if (!empty($currentProperty->image_url)) {
                    $imageUrl = ROOT . '/assets/images/uploads/property_images/' . $currentProperty->image_url;
                }
                
                // Try PropertyImageModel as a last resort (same approach as owner dashboard)
                if ($imageUrl == ROOT . '/assets/images/property-default.jpg' && isset($currentProperty->property_id)) {
                    if (!class_exists('PropertyImageModel')) {
                        class PropertyImageModel {
                            use Model;
                            protected $table = 'property_images';
                            public function where($conditions) {
                                $query = "SELECT * FROM {$this->table} WHERE ";
                                $params = [];
                                foreach ($conditions as $key => $value) {
                                    $query .= "{$key} = ? AND ";
                                    $params[] = $value;
                                }
                                $query = rtrim($query, " AND ");
                                return $this->query($query, $params);
                            }
                            
                            protected function query($query, $params = [])
                            {
                                try {
                                    $conn = new PDO("mysql:host=localhost;dbname=property_finder", "root", "");
                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute($params);
                                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                                    return $result;
                                } catch (PDOException $e) {
                                    return [];
                                }
                            }
                        }
                    }
                    
                    $propertyImage = new PropertyImageModel();
                    $images = $propertyImage->where(['property_id' => $currentProperty->property_id ?? 0]);
                    if (!empty($images)) {
                        $imageUrl = ROOT . '/assets/images/uploads/property_images/' . $images[0]->image_url;
                    }
                }
            } catch (Exception $e) {
                $imageUrl = ROOT . '/assets/images/uploads/property_images/default-property.jpg';
            }
            
            // Get correct square footage
            $squareFeet = $currentProperty->size_sqr_ft ?? $currentProperty->area ?? $currentProperty->size ?? '?';
            ?>
            
            <div class="current-property-card">
                <!-- Property Image with onClick event -->
                <a href="<?= ROOT ?>/property/propertyUnit/<?= $currentProperty->property_id ?>" class="property-image-container">
                    <div class="property-image" style="background-image: url('<?= $imageUrl ?>');">
                        <div class="image-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                </a>
                
                <!-- Property Details Section -->
                <div class="property-details">
                    <div class="property-info">
                        <!-- Property Name with Link -->
                        <a href="<?= ROOT ?>/property/propertyUnit/<?= $currentProperty->property_id ?>" class="property-name-link">
                            <h3 class="property-name"><?= htmlspecialchars($currentProperty->name ?? 'Property Name') ?></h3>
                        </a>
                        
                        <!-- Property Address -->
                        <!-- <p class="property-address">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($currentProperty->address ?? 'Property Address') ?>
                        </p> -->
                        
                        <!-- Property Features -->
                        <div class="property-features">
                            <div class="feature-item">
                                <i class="fas fa-bed"></i>
                                <span><?= $currentProperty->bedrooms ?? '?' ?> Bedrooms</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-bath"></i>
                                <span><?= $currentProperty->bathrooms ?? '?' ?> Bathrooms</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-ruler-combined"></i>
                                <span><?= $squareFeet ?> sq ft</span>
                            </div>
                            <p class="property-address">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($currentProperty->address ?? 'Property Address') ?>
                            </p>
                        </div>
                        
                        <!-- Booking Details -->
                        <?php if (isset($currentBooking)): ?>
                            <div class="booking-details">
                                <div class="booking-item">
                                    <i class="fas fa-calendar-day"></i>
                                    <span>
                                        <?= date('M d, Y', strtotime($currentBooking->start_date ?? 'now')) ?> to 
                                        <?= isset($currentBooking->end_date) ? date('M d, Y', strtotime($currentBooking->end_date)) : 'Ongoing' ?>
                                    </span>
                                </div>
                                <div class="booking-item">
                                    <i class="fas fa-money-bill"></i>
                                    <span>
                                        LKR <?= number_format($currentBooking->total_amount ?? $currentBooking->rental_price ?? 0, 2) ?>
                                        (<?= $currentBooking->rental_period ?? 'Unknown' ?> rate)
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Action Section -->
                    <div class="action-section">
                        <div class="status-container">
                            <?php if (isset($currentBooking)): ?>
                                <?php 
                                    $paymentStatus = $currentBooking->payment_status ?? 'Pending';
                                    $statusClass = 'badge-warning';
                                    
                                    if (strtolower($paymentStatus) === 'paid') {
                                        $statusClass = 'badge-success';
                                    } else if (strtolower($paymentStatus) === 'cancelled') {
                                        $statusClass = 'badge-danger';
                                    }
                                ?>
                                <span class="badge <?= $statusClass ?>">
                                    Payment: <?= htmlspecialchars($paymentStatus) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="<?= ROOT ?>/property/propertyUnit/<?= $currentProperty->property_id ?>" class="action-button primary-button">View Details</a>
                            <a href="<?= ROOT ?>/dashboard/requestService" class="action-button accent-button">Request Service</a>
                            <?php if (isset($currentBooking) && strtolower($currentBooking->payment_status ?? '') !== 'paid'): ?>
                                <a href="<?= ROOT ?>/customer/markAsPaid/<?= $currentBooking->booking_id ?>" class="action-button success-button">Pay Now</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-rental-data">
                <i class="fas fa-home"></i>
                <h3>No Current Rental</h3>
                <p>You don't have any active rentals at the moment.</p>
                <a href="<?= ROOT ?>/properties" class="action-button primary-button">Browse Properties</a>
            </div>
        <?php endif; ?>
    </div>
        
    <!-- Two Columns Section -->
    <div class="two-columns">
        <!-- Expense History Section -->
        <div class="chart-container">
            <div class="card-header">
                <h3>Expense History</h3>
                <div class="period-selector">
                    <select id="chartPeriod" class="form-select" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #ddd;">
                        <option value="6months">Last 6 Months</option>
                        <option value="1year">Last Year</option>
                    </select>
                </div>
            </div>
            <div class="chart-area">
                <canvas id="expenseHistoryChart"></canvas>
                
                <!-- Fallback for when chart doesn't load -->
                <div id="chart-fallback" class="fallback-content">
                    <table style="width: 80%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; text-align: left; border-bottom: 2px solid #eee;">Month</th>
                                <th style="padding: 8px; text-align: right; border-bottom: 2px solid #eee;">Expenses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Real expense data
                            $months = array_keys($monthlyExpenses ?? []);
                            if (empty($months)) {
                                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                            }
                            
                            $expenseAmounts = isset($monthlyExpensesArray) && is_array($monthlyExpensesArray) 
                                ? $monthlyExpensesArray 
                                : array_fill(0, 6, 0);
                            
                            foreach($months as $i => $month): 
                                $amount = $expenseAmounts[$i] ?? 0;
                            ?>
                            <tr>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= $month ?></td>
                                <td style="padding: 8px; text-align: right; border-bottom: 1px solid #eee; color: #e63946;">
                                    LKR <?= number_format($amount, 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Service Requests Section -->
        <div class="service-section">
            <div class="card-header">
                <h3>Service Requests</h3>
                <a href="<?= ROOT ?>/dashboard/repairListing" class="action-button">View All</a>
            </div>
            
            <?php if (isset($serviceRequestsRegular) && is_array($serviceRequestsRegular) && count($serviceRequestsRegular) > 0): ?>
                <table class="service-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_slice($serviceRequestsRegular, 0, 5) as $request): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($request->date ?? 'now')) ?></td>
                                <td><?= htmlspecialchars($request->service_type ?? 'General') ?></td>
                                <td><?= htmlspecialchars(substr($request->service_description ?? 'No description', 0, 30)) . (strlen($request->service_description ?? '') > 30 ? '...' : '') ?></td>
                                <td>
                                    <?php
                                        $statusClass = 'badge-info';
                                        $status = $request->status ?? 'Pending';
                                        
                                        if(strtolower($status) === 'completed' || strtolower($status) === 'done') $statusClass = 'badge-success';
                                        else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                                        else if(strtolower($status) === 'rejected' || strtolower($status) === 'cancelled') $statusClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-tools" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p>No service requests found.</p>
                    <a href="<?= ROOT ?>/dashboard/requestService" class="action-button">Request Service</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- External Service Requests Section -->
    <div class="service-section">
    <div class="card-header">
        <h3>External Service Requests</h3>
        <a href="<?= ROOT ?>/dashboard/externalMaintenance" class="action-button">View All</a>
    </div>

    <?php if (isset($serviceRequestsExternal) && is_array($serviceRequestsExternal) && count($serviceRequestsExternal) > 0): ?>
        <div class="service-cards">
            <?php foreach($serviceRequestsExternal as $request): ?>
                <?php 
                    $service_images = !empty($request->service_images) ? 
                        (is_string($request->service_images) ? json_decode($request->service_images) : $request->service_images) : 
                        [];
                    if (is_array($service_images)) {
                        $service_images = array_unique(array_filter($service_images, function($img) { return !empty($img); }));
                    } else {
                        $service_images = [];
                    }
                    $firstImage = (!empty($service_images) && is_array($service_images)) ? $service_images[0] : null;
                    $imagePath = $firstImage ? (ROOT . '/assets/images/' . htmlspecialchars($firstImage)) : (ROOT . '/assets/images/default.jpg');
                    $statusClass = 'badge-info';
                    $status = $request->status ?? 'Pending';
                    if(strtolower($status) === 'completed' || strtolower($status) === 'done') $statusClass = 'badge-success';
                    else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                    else if(strtolower($status) === 'rejected' || strtolower($status) === 'cancelled') $statusClass = 'badge-danger';
                ?>
                <div class="service-card">
                    <div class="service-details">
                        <div class="service-title"><?= htmlspecialchars($request->service_type ?? 'External Service') ?></div>
                        <div class="service-date">
                            <i class="far fa-calendar-alt"></i>
                            <?= date('M d, Y', strtotime($request->date ?? 'now')) ?>
                        </div>
                        <div class="service-address">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>
                                <?php
                                $address = $request->property_address ?? 'Address not available';
                                echo htmlspecialchars(substr($address, 0, 40)) . (strlen($address) > 40 ? '...' : '');
                                ?>
                            </span>
                        </div>
                        <div class="service-footer">
                            <span class="service-cost"><i class="fas fa-coins" style="margin-right:5px;"></i> LKR <?= number_format($request->cost ?? 0, 2) ?></span>
                            <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                        </div>
                    </div>
                    <div class="service-image-segment">
                        <?php if (!empty($service_images) && is_array($service_images)): ?>
                            <div class="property-images-label"><i class="far fa-images"></i> <?= count($service_images) ?> Images</div>
                            <div class="service-image" 
                                 style="background-image: url('<?= ROOT ?>/assets/images/<?= htmlspecialchars($service_images[0]) ?>');"
                                 onclick="openImageModal('<?= ROOT ?>/assets/images/<?= htmlspecialchars($service_images[0]) ?>')">
                            </div>
                            <?php if (count($service_images) > 1): ?>
                                <div class="view-all-images-btn" onclick="showAllImages(<?= htmlspecialchars(json_encode($service_images)) ?>)">
                                    <div class="btn-content">
                                        <i class="far fa-images"></i>
                                        <span>View Gallery</span>
                                    </div>
                                    <div class="image-count"><?= count($service_images) ?></div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="service-image" style="background-image: url('<?= $imagePath ?>');"></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-tools" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                <p>No external service requests found.</p>
                <a href="<?= ROOT ?>/dashboard/requestServiceExternal" class="action-button">Request External Service</a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Rental History Section -->
    <div class="rental-history">
        <div class="card-header">
            <h3>Rental History</h3>
            <a href="<?= ROOT ?>/dashboard/occupiedProperties" class="action-button">View All</a>
        </div>
        
        <?php if (isset($rentalHistory) && is_array($rentalHistory) && count($rentalHistory) > 0): ?>
            <table class="rental-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach(array_slice($rentalHistory, 0, 5) as $rental): ?>
                        <tr>
                            <td><?= htmlspecialchars($rental->property_name ?? 'Unknown Property') ?></td>
                            <td>
                                <?= date('M d, Y', strtotime($rental->start_date ?? 'now')) ?>
                                <?php if (isset($rental->end_date)): ?>
                                    <br><small>to <?= date('M d, Y', strtotime($rental->end_date)) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>LKR <?= number_format($rental->price ?? 0, 2) ?></td>
                            <td>
                                <?php
                                    $statusClass = 'badge-info';
                                    $status = $rental->status ?? 'Unknown';
                                    
                                    if(strtolower($status) === 'active' || strtolower($status) === 'accepted') {
                                        $statusClass = 'badge-success';
                                    }
                                    else if(strtolower($status) === 'pending' || strtolower($status) === 'upcoming') {
                                        $statusClass = 'badge-warning';
                                    }
                                    else if(strtolower($status) === 'completed') {
                                        $statusClass = 'badge-info';
                                    }
                                    else if(strtolower($status) === 'cancelled' || strtolower($status) === 'rejected') {
                                        $statusClass = 'badge-danger';
                                    }
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td>
                                <?php
                                    $paymentStatusClass = 'badge-warning';
                                    $paymentStatus = $rental->payment_status ?? 'Pending';
                                    
                                    if(strtolower($paymentStatus) === 'paid') {
                                        $paymentStatusClass = 'badge-success';
                                    }
                                    else if(strtolower($paymentStatus) === 'cancelled') {
                                        $paymentStatusClass = 'badge-danger';
                                    }
                                ?>
                                <span class="badge <?= $paymentStatusClass ?>"><?= htmlspecialchars($paymentStatus) ?></span>
                            </td>
                            <!-- <td>
                                <?php if(strtolower($rental->status ?? '') !== 'cancelled' && strtolower($rental->payment_status ?? '') !== 'paid'): ?>
                                    <a href="<?= ROOT ?>/customer/markAsPaid/<?= $rental->booking_id ?>" class="action-button" style="padding: 3px 8px; font-size: 0.8rem; background-color: var(--success);">Pay</a>
                                <?php endif; ?>
                                
                                <?php if(strtolower($rental->status ?? '') !== 'cancelled' && strtolower($rental->status ?? '') !== 'completed'): ?>
                                    <a href="<?= ROOT ?>/customer/cancelBooking/<?= $rental->booking_id ?>" class="action-button" style="padding: 3px 8px; font-size: 0.8rem; background-color: var(--danger);">Cancel</a>
                                <?php endif; ?>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-history" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                <p>No rental history found.</p>
            </div>
        <?php endif; ?>
    </div>

<!-- Improved Image Modal -->
<div id="imageModal" style="display: none;">
    <div class="modal-content-wrapper" id="modalContentWrapper">
        <div class="modal-header">
            <h3>
                <i class="far fa-images"></i> 
                Image Gallery
                <span class="image-counter">
                    <span id="currentImageNum">1</span>/<span id="totalImages">0</span>
                </span>
            </h3>
            <span class="modal-close" onclick="closeImageModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="modal-image-container">
                <button class="nav-button" id="prevImage">
                    <i class="fas fa-chevron-left nav-icon"></i>
                </button>
                <img id="modalImage" src="" alt="Service image">
                <button class="nav-button" id="nextImage">
                    <i class="fas fa-chevron-right nav-icon"></i>
                </button>
                <div id="imageLoadingOverlay" class="loading-overlay">
                    <div class="loader"></div>
                </div>
            </div>
            <div id="modalGallery"></div>
        </div>
    </div>
</div>

<!-- Update chart initialization script -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart !== 'undefined') {
        const ctx = document.getElementById('expenseHistoryChart').getContext('2d');
        const fallback = document.getElementById('chart-fallback');
        
        if (fallback) fallback.style.display = 'none';
        
        // Get expense data from PHP
        const months = <?= json_encode(array_keys($monthlyExpenses ?? [])) ?> || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const expenseData = <?= json_encode($monthlyExpensesArray ?? []) ?> || [0, 0, 0, 0, 0, 0];
        
        // Create expense chart
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Expenses',
                    data: expenseData,
                    backgroundColor: 'rgba(230, 57, 70, 0.6)',
                    borderColor: '#e63946',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'LKR ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Expense: LKR ' + context.raw.toLocaleString();
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
        
        // Period selector functionality
        document.getElementById('chartPeriod').addEventListener('change', function() {
            // In a real app, this would fetch new data based on the selected period
            alert('This would update the chart with data for: ' + this.value);
        });
    }
});
</script>

<script>
let currentImageIndex = 0;
let imageGallery = [];
let ROOT = "<?= ROOT ?>"; // Ensure ROOT is properly defined

function openImageModal(imageSrc) {
    console.log("Opening modal with image:", imageSrc); // Debugging
    
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const loadingOverlay = document.getElementById('imageLoadingOverlay');
    
    if (!modal || !modalImg) {
        console.error("Modal elements not found");
        return;
    }
    
    // Show the modal first
    modal.style.display = 'flex';
    
    // Show loading while image loads
    if (loadingOverlay) loadingOverlay.style.display = 'flex';
    
    // Set up image load handler
    modalImg.onload = function() {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
        console.log("Image loaded successfully");
    };
    
    modalImg.onerror = function() {
        console.error("Failed to load image:", imageSrc);
        // Show a fallback or error message
        modalImg.src = ROOT + '/assets/images/default.jpg';
    };
    
    // Set the image source
    modalImg.src = imageSrc;
    
    // Reset gallery display
    document.getElementById('modalGallery').innerHTML = '';
    document.getElementById('prevImage').style.display = 'none';
    document.getElementById('nextImage').style.display = 'none';
    
    // Update counter
    document.getElementById('currentImageNum').textContent = '1';
    document.getElementById('totalImages').textContent = '1';
}

function showAllImages(images) {
    console.log("Showing all images:", images); // Debugging
    
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const gallery = document.getElementById('modalGallery');
    const loadingOverlay = document.getElementById('imageLoadingOverlay');
    
    if (!modal || !modalImg || !gallery) {
        console.error("Modal elements not found");
        return;
    }
    
    // Show the modal first
    modal.style.display = 'flex';
    
    // Show loading overlay
    if (loadingOverlay) loadingOverlay.style.display = 'flex';
    
    // Process image gallery array
    try {
        // Convert to array of full paths
        imageGallery = images;
        if (typeof imageGallery === 'string') {
            imageGallery = JSON.parse(imageGallery);
        }
        
        // Check if gallery is valid
        if (!Array.isArray(imageGallery) || imageGallery.length === 0) {
            console.error("Invalid image gallery:", imageGallery);
            closeImageModal();
            return;
        }
        
        imageGallery = imageGallery.map(img => `${ROOT}/assets/images/${img}`);
        console.log("Processed gallery:", imageGallery);
        
        currentImageIndex = 0;
        
        // Update counter
        document.getElementById('currentImageNum').textContent = '1';
        document.getElementById('totalImages').textContent = imageGallery.length;
        
        // Show navigation buttons if multiple images
        document.getElementById('prevImage').style.display = imageGallery.length > 1 ? 'flex' : 'none';
        document.getElementById('nextImage').style.display = imageGallery.length > 1 ? 'flex' : 'none';
        
        // Create thumbnails
        gallery.innerHTML = '';
        imageGallery.forEach((img, index) => {
            const thumb = document.createElement('div');
            thumb.className = `thumbnail ${index === 0 ? 'active' : ''}`;
            thumb.classList.add('slide-in');
            
            const thumbImg = document.createElement('img');
            thumbImg.src = img;
            thumbImg.alt = `Thumbnail ${index + 1}`;
            
            thumb.appendChild(thumbImg);
            thumb.onclick = () => {
                setActiveImage(index);
            };
            
            gallery.appendChild(thumb);
        });
        
        // Load first image
        modalImg.onload = function() {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            console.log("First image loaded successfully");
        };
        
        modalImg.onerror = function() {
            console.error("Failed to load image:", imageGallery[0]);
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            // Show a fallback image
            modalImg.src = ROOT + '/assets/images/default.jpg';
        };
        
        // Set the image source
        modalImg.src = imageGallery[0];
        
    } catch (error) {
        console.error("Error processing images:", error);
        closeImageModal();
    }
}

function setActiveImage(index) {
    if (!imageGallery || !Array.isArray(imageGallery) || imageGallery.length === 0) {
        console.error("Invalid image gallery when setting active image");
        return;
    }
    
    if (index < 0) index = imageGallery.length - 1;
    if (index >= imageGallery.length) index = 0;
    
    const loadingOverlay = document.getElementById('imageLoadingOverlay');
    if (loadingOverlay) loadingOverlay.style.display = 'flex';
    
    currentImageIndex = index;
    
    // Update counter
    document.getElementById('currentImageNum').textContent = index + 1;
    
    // Update image
    const modalImg = document.getElementById('modalImage');
    modalImg.onload = function() {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
    };
    
    modalImg.onerror = function() {
        console.error("Failed to load image:", imageGallery[index]);
        if (loadingOverlay) loadingOverlay.style.display = 'none';
        // Show a fallback image
        modalImg.src = ROOT + '/assets/images/default.jpg';
    };
    
    modalImg.src = imageGallery[index];
    
    // Update thumbnail selection
    const thumbs = document.getElementById('modalGallery').children;
    for (let i = 0; i < thumbs.length; i++) {
        thumbs[i].classList.toggle('active', i === index);
    }
    
    // Scroll thumbnail into view if needed
    const activeThumb = thumbs[index];
    if (activeThumb) {
        activeThumb.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center'
        });
    }
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// Add event listeners for next/prev buttons
document.addEventListener('DOMContentLoaded', function() {
    // Check if elements exist before adding listeners
    const prevButton = document.getElementById('prevImage');
    const nextButton = document.getElementById('nextImage');
    
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            setActiveImage(currentImageIndex - 1);
        });
    }
    
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            setActiveImage(currentImageIndex + 1);
        });
    }
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('imageModal');
        if (!modal || modal.style.display === 'none') return;
        
        if (event.key === 'ArrowLeft') {
            setActiveImage(currentImageIndex - 1);
        } else if (event.key === 'ArrowRight') {
            setActiveImage(currentImageIndex + 1);
        } else if (event.key === 'Escape') {
            closeImageModal();
        }
    });
    
    // Make sure ROOT is defined
    if (typeof ROOT === 'undefined') {
        ROOT = '';
        console.warn("ROOT variable is not defined, using empty string");
    }
});
</script>

<?php require_once 'customerFooter.view.php'; ?>