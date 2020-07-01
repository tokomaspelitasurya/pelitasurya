<?php


namespace Corals\Modules\Marketplace\Classes;

use Corals\Modules\Marketplace\Models\Store as StoreModel;
use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\User\Models\User;

class Store
{


    protected $store;

    public function getCurrentStore($request, $user = null)
    {
        $enable_domain_parking = \Settings::get('marketplace_general_enable_domain_parking', false);
        $enable_subdomain = \Settings::get('marketplace_general_enable_subdomain', false);
        $domain = parse_url($request->url(), PHP_URL_HOST);

        if ($enable_subdomain) {
            $url_array = explode('.', $domain);
            $slug = $url_array[0];

        } else {
            $slug = $request->route('slug');
        }
        if ($slug) {

            $store = StoreModel::where('slug', $slug)->first();
            if ($store) {
                return $store;
            }
        }
        if ($enable_domain_parking) {
            $store = StoreModel::where('parking_domain', $domain)->first();
            if ($store) {
                return $store;
            }
        }


        if (session()->get('current_store')) {
            $store = StoreModel::find(session()->get('current_store'));
            return $store;
        }

        return false;

    }


    public function getFeaturedStores()
    {
        $products = StoreModel::active()->featured()->get();

        return $products;
    }

    function accessableStores($user = null)
    {
        if (!$user) {
            $user = user();
        }
        if ($this->isStoreAdmin()) {
            $stores = StoreModel::all();
        } else {
            $stores = user()->stores;

        }
        return $stores;


    }

    function accessableStoresList($user = null)
    {
        $stores = $this->accessableStores($user);

        $stores = $stores->pluck('name', 'id');

        return $stores;


    }

    function showStoreSelection()
    {
        if (!user()) {
            return;
        }

        if ($this->isStoreAdmin()) {
            return;
        }


        $stores = StoreModel::where('user_id', user()->id)->get();
        if (count($stores) <= 1) {
            return;
        }

        $current_store = null;


        $current_store = $this->getStore();

        $stores_dropdown = view('Marketplace::partials.store_selection')->with(compact('ul_class', 'li_class', 'stores', 'current_store'))->render();
        echo $stores_dropdown;
    }

    /**
     * @return \Corals\Modules\Marketplace\Models\Store
     */
    public function getStore()
    {
        return $this->store;
    }


    /**
     * @return \Corals\Modules\Marketplace\Models\Store
     */
    public function getVendorStore($user = null)
    {
        if (!$user) {
            $user = user();
        }
        return $this->store ?? StoreModel::where('user_id', $user->id)->first();
    }

    /**
     * @return null
     */
    public function setStore($store)
    {
        return $this->store = $store;
    }

    public function isStoreAdmin(User $user = null)
    {
        if (is_null($user)) {
            $user = user();
        }

        if (!$user) {
            return false;
        }

        return $user->hasPermissionTo('Administrations::admin.marketplace');
    }

    public function getStoreFilters($filters)
    {


        if ($this->accessableStoresList()->count() > 1 && !$this->getStore()) {
            $filters = array_merge(['store.id' => ['title' => trans('Marketplace::attributes.product.store'), 'class' => 'col-md-2', 'type' => 'select2', 'options' => \Store::accessableStoresList(), 'active' => true],
            ], $filters

            );
        }
        return $filters;
    }

    public function getStoreColumns($columns, $view = null)
    {


        if (($this->accessableStoresList()->count() > 1) && !$this->getStore()) {

            $columns['store'] = ['title' => trans('Marketplace::attributes.product.store'), 'orderable' => false, 'searchable' => false];

        }

        return $columns;
    }

    public function getStoreFields($model)
    {
        if (\Store::isStoreAdmin()) {

            return \CoralsForm::select('store_id', 'Marketplace::attributes.product.store', [], false, null,
                ['class' => 'select2-ajax', 'data' => ['model' => \Corals\Modules\Marketplace\Models\Store::class,
                    'columns' => json_encode(['name', 'user_id']),
                    'selected' => json_encode($model->store_id ? [$model->store_id] : []),
                    'where' => json_encode([]),]], 'select2');


        } else {
            return '<div class="form-group">
                            <span data-name="store_id"></span>
            </div>';
        }
    }

    public function setStoreData($data)
    {

        if (!isset($data['store_id'])) {
            $store = \Store::getVendorStore();
            if (!$store) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'store_id' => ['Unable to specify Store to attach object to'],
                ]);
                throw $error;
            }

            $data['store_id'] = $store->id;

        }
        return $data;
    }

    public function createStore($user = null, Subscription $subscription = null)
    {

        if (!$user) {
            $user = user();
        }
        $store = new StoreModel();
        $store->user_id = $user->id;
        $store->name = $user->full_name;
        if ($subscription) {
            $store->causer_id = $subscription->id;
            $store->causer_type = Subscription::class;
        }

        $store->save();

        $vendor_role = \Settings::get('marketplace_general_vendor_role', '');
        if ($vendor_role && !$user->hasRole($vendor_role)) {
            $user->assignRole($vendor_role);
        }

        $store->advertiser()->create([
            'name' => $store->name,
            'contact' => $store->user->full_name,
            'email' => $store->user->email,
            'status' => 'active',
        ]);
    }

    public function getTransactionsSummary($user = null)
    {

        if (!$user) {
            $user = user();

        }

        if ($this->isStoreAdmin()) {
            $total_sales = Transaction::completed()->where('type', 'order_revenue')->sum('amount');
            $total_commision = Transaction::completed()->where('type', 'commision')->sum('amount') * -1;
            $total_completed_withdrawals = Transaction::completed()->where('type', 'withdrawal')->sum('amount') * -1;
            $total_pending_withdrawals = Transaction::pending()->where('type', 'withdrawal')->sum('amount') * -1;

            $profit = Transaction::completed()->where('amount', '>', 0)->sum('amount');
            $deductions = Transaction::whereIn('status', ['completed', 'prending'])->where('amount', '<', 0)->sum('amount');
            $balance = $profit - $deductions;

        } else {

            $total_sales = $user->transactions()->completed()->where('type', 'order_revenue')->sum('amount');
            $total_commision = $user->transactions()->completed()->where('type', 'commision')->sum('amount') * -1;
            $total_completed_withdrawals = $user->transactions()->completed()->where('type', 'withdrawal')->sum('amount') * -1;
            $total_pending_withdrawals = $user->transactions()->pending()->where('type', 'withdrawal')->sum('amount') * -1;

            $profit = $user->transactions()->completed()->where('amount', '>', 0)->sum('amount');
            $deductions = $user->transactions()->whereIn('status', ['completed', 'pending'])->where('amount', '<', 0)->sum('amount');
            $balance = $profit + $deductions;

        }


        return compact('total_sales', 'total_commision', 'total_completed_withdrawals', 'total_pending_withdrawals', 'balance');
    }
}
