<!-- Modern Vertical Wizard -->
<section class="modern-vertical-wizard">
    <div class="bs-stepper vertical wizard-modern modern-vertical-wizard-example">
        <div class="bs-stepper-header">
            <div class="step" data-target="#salesplan-details-vertical-modern" role="tab"
                id="salesplan-details-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i data-feather="file-text" class="font-medium-3"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Sales Plan Approval</span>
                        <span class="bs-stepper-subtitle">Sales Plan Approval Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#receipt-vertical-modern" role="tab"
                id="receipt-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-receipt-cutoff" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Receipt Voucher</span>
                        <span class="bs-stepper-subtitle">Receipt Voucher Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#buyback-vertical-modern" role="tab"
                id="buyback-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Buyback</span>
                        <span class="bs-stepper-subtitle">Buyback Details</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#refund-vertical-modern" role="tab"
                id="refund-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Refund</span>
                        <span class="bs-stepper-subtitle">Refund Details</span>
                    </span>
                </button>
            </div>

            <div class="step" data-target="#cancellation-vertical-modern" role="tab"
                id="cancellation-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Cancelation</span>
                        <span class="bs-stepper-subtitle">Cancelation Details</span>
                    </span>
                </button>
            </div>

            <div class="step" data-target="#title-transfer-vertical-modern" role="tab"
                id="title-transfer-vertical-modern-trigger">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Title transfer</span>
                        <span class="bs-stepper-subtitle">Title transfer Details</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            <div id="salesplan-details-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="salesplan-details-vertical-modern-trigger">
                {{ $salesInvoice->table() }}
                {{-- <table>
                    <tr>
                        <th>asd</th>
                    </tr>
                    <tr>
                        <th>asd</th>
                    </tr>
                </table> --}}


            </div>

            <div id="receipt-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="receipt-vertical-modern-trigger">

            </div>

            <div id="buyback-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="buyback-vertical-modern-trigger">

            </div>

            <div id="refund-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="refund-vertical-modern-trigger">

            </div>

            <div id="cancellation-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="cancellation-vertical-modern-trigger">

            </div>

            <div id="title-transfer-vertical-modern" class="content" role="tabpanel"
                aria-labelledby="title-transfer-vertical-modern-trigger">

            </div>

        </div>
    </div>
    <!-- /Modern Vertical Wizard -->
