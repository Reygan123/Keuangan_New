<div class="inv-top">
    <div class="logo-header">
        <img src="{{ storage_path('app/public/jd-logo.png') }}" class="logo-img">
    </div>

    <div class="inv-header clearfix">
        <div class="icon-text">
            <img src="{{ storage_path('app/public/jd-text.png') }}">
        </div>

        <div class="inv-number">
            <h1 class="title-number">Invoice</h1>
            <p class="number">Number : {{ $invoice->nomor_invoice }}</p>
        </div>
    </div>
</div>