<?php

return [
    'models' => [
        'subscription' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\SubscriptionPresenter::class,
            'resource_url' => 'subscriptions/subscriptions',
            'statuses' => [
                'active' => 'Subscriptions::attributes.subscription.subscription_statuses.active',
                'cancelled' => 'Subscriptions::attributes.subscription.subscription_statuses.cancelled',
                'pending' => 'Subscriptions::attributes.subscription.subscription_statuses.pending'
            ],
            'actions' => [
                'delete' => []
            ]
        ],
        'product' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\ProductPresenter::class,
            'resource_url' => 'subscriptions/products',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'translatable' => ['name'],
            'actions' => [
                'plans' => [
                    'href_pattern' => ['pattern' => '[arg]/plans', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Subscriptions::labels.feature.plan');"]],
                    'data' => []
                ],
                'features' => [
                    'href_pattern' => ['pattern' => '[arg]/features', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Subscriptions::labels.plan.features');"]],
                    'data' => []
                ]
            ]
        ],
        'feature' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\FeaturePresenter::class,
            'resource_route' => 'products.features.index',
            'translatable' => ['name', 'caption'],
            'resource_relation' => 'product',
            'relation' => 'feature',
            'sources_list' => [
                'list_of_values' => 'Subscriptions::labels.feature.sources_list.list_of_values',
                'config' => 'Subscriptions::labels.feature.sources_list.config',
                'settings' => 'Subscriptions::labels.feature.sources_list.settings'
            ]
        ],
        'feature_model' => [],
        'plan' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\PlanPresenter::class,
            'resource_route' => 'products.plans.index',
            'translatable' => ['name'],
            'resource_relation' => 'product',
            'relation' => 'plan'
        ],

        'subscription_cycle' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\SubscriptionCyclePresenter::class,
            'resource_url' => 'subscriptions/subscription-cycles',
        ],
        'plan_usage' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\PlanUsagePresenter::class,
            'resource_url' => 'subscriptions/plan-usage',
        ],
    ],

    'features_has_widgets_types' => [
        'boolean',
        'quantity'
    ]
];
