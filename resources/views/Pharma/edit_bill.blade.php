<x-pharma-layout>
    <x-slot name="MainContent">
        <div class="container mt-2 px-2" style="max-width: 900px;">
            <h4 class="mb-3 text-primary" style="font-size: 1.25rem;">🧾 Final Bill - Overall Discount</h4>

            <form action="{{ url('/finalize-bill') }}" method="POST" style="font-size: 0.9rem;">
                @csrf
                <input type="hidden" name="pharmacy_id" value="{{ $pharmacy_id }}">

                {{-- Customer Info --}}
                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <label for="customer_mobile" class="form-label text-white small mb-1">📱 Customer Mobile</label>
                        <input type="tel" name="customer_mobile" id="customer_mobile"
                            class="form-control form-control-sm" placeholder="e.g. 9876543210">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="customer_email" class="form-label text-white small mb-1">✉️ Customer Email</label>
                        <input type="email" name="customer_email" id="customer_email"
                            class="form-control form-control-sm" placeholder="e.g. customer@example.com">
                    </div>
                </div>

                {{-- Billing Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-dark table-striped table-sm" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Qty</th>
                                <th>Price (₹)</th>
                                <th>Amount (₹)</th>
                                <th>Total (₹)</th>
                            </tr>
                        </thead>
                        <tbody id="editable-bill">
                            @foreach ($summary as $i => $item)
                                <tr>
                                    <td>
                                        {{ $item['medicine'] }}
                                        <input type="hidden" name="items[{{ $i }}][medicine]"
                                            value="{{ $item['medicine'] }}">
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $i }}][quantity]"
                                            class="form-control form-control-sm quantity"
                                            value="{{ $item['quantity'] }}" min="1">
                                    </td>
                                    <td>
                                        <span class="original-price text-decoration-line-through text-secondary me-2 d-inline-block" style="min-width: 70px;">
                                            ₹{{ number_format($item['price'], 2) }}
                                        </span>
                                        <span class="discounted-price text-white d-inline-block" style="min-width: 70px;">
                                            ₹{{ number_format($item['price'], 2) }}
                                        </span>
                                        <input type="hidden" name="items[{{ $i }}][price]" class="price"
                                            value="{{ number_format($item['price'], 2, '.', '') }}">
                                    </td>
                                    <td class="amount">₹{{ number_format($item['amount'], 2) }}</td>
                                    <td class="total-row">₹{{ number_format($item['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                                <td id="subtotal-amount">₹0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">
                                    <label for="overall-discount" class="small"><strong>Overall Discount (%)</strong></label>
                                </td>
                                <td>
                                    <input type="number" name="overall_discount" id="overall-discount"
                                        class="form-control form-control-sm" value="0" min="0" step="0.01">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total Discount</strong></td>
                                <td id="total-discount">₹0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Grand Total</strong></td>
                                <td id="grand-total">₹0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3 py-2" style="font-size: 0.95rem;">
                    ✅ Finalize & Generate PDF
                </button>
            </form>
        </div>

        {{-- Script --}}
        <script>
            function updateTotal() {
                let subtotal = 0;

                const overallDiscount = parseFloat(document.getElementById('overall-discount').value) || 0;
                const discountFactor = 1 - (overallDiscount / 100);

                document.querySelectorAll('#editable-bill tr').forEach(row => {
                    const qty = parseFloat(row.querySelector('.quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.price').value) || 0;

                    const originalAmount = qty * price;
                    const discountedAmount = originalAmount * discountFactor;

                    row.querySelector('.amount').innerText = '₹' + discountedAmount.toFixed(2);
                    row.querySelector('.total-row').innerText = '₹' + discountedAmount.toFixed(2);

                    const discountedUnitPriceSpan = row.querySelector('.discounted-price');
                    if (discountedUnitPriceSpan) {
                        const discountedUnitPrice = price * discountFactor;
                        discountedUnitPriceSpan.innerText = '₹' + discountedUnitPrice.toFixed(2);
                    }

                    subtotal += originalAmount;
                });

                const discountAmount = subtotal * (overallDiscount / 100);
                const grandTotal = subtotal - discountAmount;

                document.getElementById('subtotal-amount').innerText = '₹' + subtotal.toFixed(2);
                document.getElementById('total-discount').innerText = '₹' + discountAmount.toFixed(2);
                document.getElementById('grand-total').innerText = '₹' + grandTotal.toFixed(2);
            }

            document.querySelectorAll('.quantity').forEach(input => {
                input.addEventListener('input', updateTotal);
            });

            document.getElementById('overall-discount').addEventListener('input', updateTotal);

            updateTotal();
        </script>
    </x-slot>
</x-pharma-layout>
