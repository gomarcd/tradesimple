<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    public bool $connected = false;
    public array $accountData = [];

    public function mount()
    {
        $this->connected = Cache::has('ws_api_session_' . Auth::id());

        if ($this->connected) {
            $this->loadFromCache();
        }
    }

    /**
     * Load account data directly from cache.
     */
    public function loadFromCache(): void
    {
        $cached = Cache::get('ws_api_cached_accounts_' . Auth::id());

        if ($cached) {
            $accounts = json_decode($cached);

            $mergedAccounts = [];

            foreach ($accounts as $account) {
                $balances = $account->balances ?? [];

                $isUSD = str_contains($account->description, 'USD');
                $baseDescription = str_replace([' - USD', ' - CAD'], '', $account->description);

                if (!isset($mergedAccounts[$baseDescription])) {
                    $mergedAccounts[$baseDescription] = $account;
                    $mergedAccounts[$baseDescription]->balances = $balances;
                } elseif ($isUSD) {
                    foreach ($balances as $security => $amount) {
                        if ($security === 'sec-c-usd') {
                            continue;
                        }
                        if (!isset($mergedAccounts[$baseDescription]->balances->$security)) {
                            $mergedAccounts[$baseDescription]->balances->$security = 0;
                        }
                        $mergedAccounts[$baseDescription]->balances->$security += $amount;
                    }
                }
            }

            $this->accountData = array_values($mergedAccounts);
        } else {
            $this->accountData = [];
        }
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        @if (!$connected || empty($accountData))
            {{-- Placeholder when no data is available --}}
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col items-center justify-center p-4">
                <p class="text-gray-500 dark:text-gray-400 text-center">No data available</p>
                <a href="{{ route('settings.ws-api') }}" class="text-blue-500 hover:underline mt-2">Connect Wealthsimple API</a>
            </div>
        @else
            {{-- Show actual data if connected --}}
            @foreach ($accountData as $account)
                <flux:card class="overflow-hidden min-w-[12rem] p-4">
                    <flux:subheading>{{ $account->description }}</flux:subheading>

                    <flux:heading size="xl">
                        @isset($account->financials->currentCombined->netLiquidationValue->amount)
                            ${{ number_format($account->financials->currentCombined->netLiquidationValue->amount, 2) }}
                        @else
                            N/A
                        @endisset
                    </flux:heading>

                    <flux:chart class="-mx-8 -mb-8 h-[3rem]" :value="[10, 12, 11, 13, 15, 14, 16, 18, 17, 19, 21, 20]">
                        <flux:chart.svg gutter="0">
                            <flux:chart.line class="text-sky-200 dark:text-sky-400" />
                            <flux:chart.area class="text-sky-100 dark:text-sky-400/30" />
                        </flux:chart.svg>
                    </flux:chart>
                </flux:card>
            @endforeach
        @endif
    </div>
</div>
