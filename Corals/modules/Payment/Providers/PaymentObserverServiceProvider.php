<?php

namespace Corals\Modules\Payment\Providers;

use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Payment\Common\Policies\InvoicePolicy;
use Illuminate\Support\ServiceProvider;

class PaymentObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {

        Invoice::observe(InvoicePolicy::class);
    }
}
