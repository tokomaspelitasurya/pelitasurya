<?php

namespace Corals\User\Communication\Providers;

use Corals\User\Communication\Models\Notification;
use Corals\User\Communication\Observers\NotificationObserver;
use Corals\User\Communication\Observers\NotificationTemplateObserver;
use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Support\ServiceProvider;

class NotificationObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {
        NotificationTemplate::observe(NotificationTemplateObserver::class);
        Notification::observe(NotificationObserver::class);
    }
}