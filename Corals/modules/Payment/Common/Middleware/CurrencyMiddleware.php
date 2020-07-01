<?php

namespace Corals\Modules\Payment\Common\Middleware;

use Closure;
use Illuminate\Http\Request;

class CurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Don't redirect the console
        if ($this->runningInConsole()) {
            return $next($request);
        }

        // Check for a user defined currency
        if (($currency = $this->getUserCurrency($request)) === null) {
            $currency = $this->getDefaultCurrency();
        }

        // Set user currency
        $this->setUserCurrency($currency, $request);

        return $next($request);
    }

    /**
     * Get the user selected currency.
     *
     * @param Request $request
     *
     * @return string|null
     */
    protected function getUserCurrency(Request $request)
    {
        // Check request for currency
        $currency = $request->get('currency');
        if ($currency && \Currency::isActive($currency) === true) {
            return $currency;
        }

        // Get currency from session
        $currency = $request->getSession()->get('currency');
        if ($currency && \Currency::isActive($currency) === true) {
            return $currency;
        }

        return null;
    }

    /**
     * Get the application default currency.
     *
     * @return string
     */
    protected function getDefaultCurrency()
    {
        return \Currency::config('default');
    }

    /**
     * Determine if the application is running in the console.
     *
     * @return bool
     */
    private function runningInConsole()
    {
        return app()->runningInConsole();
    }

    /**
     * Set the user currency.
     *
     * @param string $currency
     * @param Request $request
     *
     * @return string
     */
    private function setUserCurrency($currency, $request)
    {
        $currency = strtoupper($currency);

        // Set user selection globally
        \Currency::setUserCurrency($currency);

        // Save it for later too!
        $request->getSession()->put(['currency' => $currency]);

        return $currency;
    }
}
