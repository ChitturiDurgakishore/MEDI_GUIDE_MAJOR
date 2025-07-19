<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container text-center mt-5">
            <h3 class="mb-4" style="color: #58a6ff;">📄 Final Bill Generated</h3>

            <div class="card p-3 bg-dark text-white border-secondary">
                <iframe src="{{ $pdf_path }}" width="100%" height="600px" frameborder="0" style="border: 1px solid #444;"></iframe>
                <a href="{{ $pdf_path }}" target="_blank" class="btn btn-primary mt-4">📥 Download PDF</a>
            </div>

            {{-- Summary section --}}
            <div class="mt-5 text-start text-white bg-secondary p-4 rounded shadow-sm" style="max-width: 480px; margin: 40px auto; font-size: 0.95rem;">
                <h5 class="text-light mb-3">💰 Bill Summary</h5>
                <p><strong>Subtotal:</strong> ₹{{ number_format($subtotal, 2) }}</p>
                <p><strong>Overall Discount ({{ number_format($overall_discount, 2) }}%):</strong> - ₹{{ number_format($total_discount, 2) }}</p>
                <hr class="border-light">
                <p class="fs-5"><strong>Grand Total (After Discount):</strong> ₹{{ number_format($grand_total, 2) }}</p>
                <p class="text-muted small mt-3">🕒 Generated on: {{ $date }}</p>
            </div>
        </div>
    </x-slot>
</x-pharma-layout>
