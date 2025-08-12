<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container py-4">

            <!-- 🔢 Sales Summary Section -->
            <div class="row mb-4">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class="card bg-dark text-white shadow-neon p-4 h-100">
                        <h5 class="text-cyan">📆 Today’s Sales</h5>
                        <h3 class="fw-bold mt-2">₹{{ number_format($todaySales, 2) }}</h3>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card bg-dark text-white shadow-neon p-4 h-100">
                        <h5 class="text-lime">📅 Monthly Sales</h5>
                        <h3 class="fw-bold mt-2">₹{{ number_format($monthSales, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- 💡 Why Medi-Guide Section -->
            <div class="bg-dark p-4 rounded mb-5 shadow-neon text-center text-md-start">
                <h4 class="text-cyan-neon mb-4 fw-semibold text-center" style="text-shadow: 0 0 8px #22d3ee;">
                    Why Medi-Guide?
                </h4>
                <ul class="text-light fs-6 mx-auto" style="max-width: 600px; list-style: none; padding-left: 0;">
                    @php
                        $benefits = [
                            'Reach nearby customers searching for medicines.',
                            'Easily manage your inventory and availability.',
                            'Receive alerts on low stock and expired meds.',
                            'Quickly import, adjust, and maintain records.',
                        ];
                    @endphp

                    @foreach ($benefits as $item)
                        <li class="mb-3 d-flex align-items-start gap-2" style="position: relative; padding-left: 1.5em;">
                            <span style="color: #22d3ee; font-weight: 700; text-shadow: 0 0 6px #22d3ee; position: absolute; left: 0; top: 0.3em;">✔</span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- ⚙️ Dashboard Options Section -->
            <div class="bg-secondary p-4 rounded shadow-neon">
                <h4 class="text-lime-neon mb-4 fw-semibold text-center text-md-start" style="text-shadow: 0 0 6px #a3e635;">
                    Dashboard Options Guide
                </h4>
                <div class="row g-4">
                    @php
                        $options = [
                            [
                                'icon' => 'box-seam',
                                'color' => 'text-info',
                                'title' => 'Inventory',
                                'desc' => 'View and manage your current stock of medicines. Keep everything updated and track availability for users.',
                            ],
                            [
                                'icon' => 'pencil-square',
                                'color' => 'text-info',
                                'title' => 'Entry',
                                'desc' => 'Manually add new medicines or update existing ones in your pharmacy records.',
                            ],
                            [
                                'icon' => 'cloud-arrow-down',
                                'color' => 'text-info',
                                'title' => 'Import',
                                'desc' => 'Bulk upload your medicine database using CSV files. Quick and efficient data entry.',
                            ],
                            [
                                'icon' => 'sliders',
                                'color' => 'text-info',
                                'title' => 'Adjust',
                                'desc' => 'Make quick quantity adjustments to your inventory without affecting entry logs.',
                            ],
                            [
                                'icon' => 'exclamation-triangle',
                                'color' => 'text-warning',
                                'title' => 'Alerts',
                                'desc' => 'Get notified when any medicine goes out of stock or requires urgent attention.',
                            ],
                        ];
                    @endphp

                    @foreach ($options as $option)
                        <div class="col-12 col-md-6 @if ($option['title'] == 'Alerts') col-md-12 @endif">
                            <div class="border p-3 rounded bg-dark text-light option-card h-100 d-flex flex-column">
                                <h5 class="mb-3">
                                    <i class="bi bi-{{ $option['icon'] }} me-2 {{ $option['color'] }}" style="filter: drop-shadow(0 0 4px currentColor);"></i>
                                    {{ $option['title'] }}
                                </h5>
                                <p class="flex-grow-1 fs-6">{{ $option['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 🖌️ Styles -->
        <style>
            .text-lime-neon {
                color: #a3e635;
                text-shadow: 0 0 8px #a3e635;
            }

            .text-cyan-neon {
                color: #22d3ee;
                text-shadow: 0 0 8px #22d3ee;
            }

            .shadow-neon {
                box-shadow: 0 0 12px 1px rgba(6, 182, 212, 0.7);
            }

            .option-card {
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .option-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 0 16px 4px #06b6d4;
                cursor: default;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background-color: #121212;
            }

            .card {
                border: none;
            }
        </style>
    </x-slot>
</x-pharma-layout>
