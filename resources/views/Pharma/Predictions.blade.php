{{-- resources/views/Pharma/Predictions.blade.php --}}

<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container">
            <div class="row g-4">

                {{-- 🟠 Low Stock --}}
                <div class="col-md-4">
                    <div class="p-4 bg-dark border border-warning rounded-4 h-100 shadow-sm d-flex flex-column"
                         style="transition: box-shadow 0.3s ease;"
                         onmouseover="this.style.boxShadow='0 0 15px #a3e635';"
                         onmouseout="this.style.boxShadow='';">
                        <h5 class="text-warning mb-3 d-flex align-items-center">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                            Low Stock <br>(Current Qty < 20)
                        </h5>
                        <hr class="text-warning my-2">
                        <div id="low-stock-container" class="text-light flex-grow-1 overflow-auto" style="max-height: 300px;">
                            @if (collect($lowStock)->isEmpty())
                                <p class="text-muted fst-italic">No low stock items currently.</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach ($lowStock as $item)
                                        <li class="list-group-item bg-transparent border-0 d-flex justify-content-between align-items-center text-warning px-0 py-2">
                                            <span>{{ $item->medicine_name }}</span>
                                            <span class="badge bg-warning text-dark rounded-pill">{{ $item->quantity }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 🔥 Hot Stock Prediction --}}
                <div class="col-md-4">
                    <div class="p-4 bg-dark border border-success rounded-4 h-100 shadow-sm d-flex flex-column"
                         style="transition: box-shadow 0.3s ease;"
                         onmouseover="this.style.boxShadow='0 0 15px #22c55e';"
                         onmouseout="this.style.boxShadow='';">
                        <h5 class="text-success mb-3 d-flex align-items-center">
                            <i class="bi bi-fire me-2 fs-4"></i>
                            Hot Stock <br>(Buy More!)
                        </h5>
                        <hr class="text-success my-2">
                        <div id="hot-stock-container" class="text-light flex-grow-1 overflow-auto" style="max-height: 300px;">
                            @if (empty($hotStock))
                                <p class="text-muted fst-italic">No hot stock items currently.</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach ($hotStock as $item)
                                        <li class="list-group-item bg-transparent border-0 d-flex flex-column text-success px-0 py-2">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $item['medicine_name'] }}</span>
                                                <span class="badge bg-success text-dark rounded-pill">Pred: {{ $item['predicted_sales_today'] }}</span>
                                            </div>
                                            <small class="text-muted">Current: {{ $item['current_stock'] }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ❌ No Sales in Last 30 Days --}}
                <div class="col-md-4">
                    <div class="p-4 bg-dark border border-danger rounded-4 h-100 shadow-sm d-flex flex-column"
                         style="transition: box-shadow 0.3s ease;"
                         onmouseover="this.style.boxShadow='0 0 15px #ef4444';"
                         onmouseout="this.style.boxShadow='';">
                        <h5 class="text-danger mb-3 d-flex align-items-center">
                            <i class="bi bi-x-circle-fill me-2 fs-4"></i>
                            No Sales Stock <br>(Last 30 Days)
                        </h5>
                        <hr class="text-danger my-2">
                        <div id="no-sales-stock-container" class="text-light flex-grow-1 overflow-auto" style="max-height: 300px;">
                            @if (empty($noSalesStock))
                                <p class="text-muted fst-italic">All items have recent sales!</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach ($noSalesStock as $item)
                                        <li class="list-group-item bg-transparent border-0 d-flex flex-column text-danger px-0 py-2">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $item['medicine_name'] }}</span>
                                                <span class="badge bg-danger text-dark rounded-pill">Sales: {{ $item['sales_last_30_days'] }}</span>
                                            </div>
                                            <small class="text-muted">Current: {{ $item['current_stock'] }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </x-slot>
</x-pharma-layout>
