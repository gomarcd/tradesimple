<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use App\Services\Wealthsimple\WealthsimpleAPI;
use App\Services\Wealthsimple\Exceptions\OTPRequiredException;
use App\Services\Wealthsimple\Exceptions\LoginFailedException;
use App\Services\Wealthsimple\Sessions\WSAPISession;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public string $otp = '';
    public bool $connected = false;
    public string $errorMessage = '';

    public function mount(): void
    {
        if (Cache::has('ws_api_session_' . Auth::id())) {
            $this->connected = true;
        }
    }

    public function connect(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'otp' => ['nullable', 'string'],
        ]);

        $persistSession = function (WSAPISession $session) {
            Cache::put('ws_api_session_' . Auth::id(), json_encode($session), now()->addDays(7));
        };

        try {
            WealthsimpleAPI::login(
                $validated['email'],
                $validated['password'],
                $validated['otp'],
                $persistSession
            );

            $this->connected = true;
            $this->errorMessage = '';
            Flux::toast(variant: 'success', heading: 'Connected', text: 'Wealthsimple account: ' . $this->email . ' successfully connected.');
            $this->dispatch('ws-connected');

            $this->fetchAndCacheAllUserData();

        } catch (OTPRequiredException $e) {
            $this->errorMessage = 'OTP required. Please enter the code sent to your device.';
        } catch (LoginFailedException $e) {
            $this->errorMessage = 'Login failed. Please check your credentials.';
        } catch (\Exception $e) {
            $this->errorMessage = 'An unexpected error occurred: ' . $e->getMessage();
        }
    }

    public function disconnect(): void
    {
        Cache::forget('ws_api_session_' . Auth::id());
        Cache::forget('ws_api_cached_data_' . Auth::id());
        Cache::forget('ws_api_cached_positions_' . Auth::id());
        Cache::forget('ws_api_connected_account_' . Auth::id());

        $this->connected = false;
        $this->dispatch('ws-disconnected');
    }

    protected function fetchAndCacheAllUserData(): void
    {
        $sessionData = Cache::get('ws_api_session_' . Auth::id());
        if (!$sessionData) {
            return;
        }

        $persistSession = function (WSAPISession $session) {
            Cache::put('ws_api_session_' . Auth::id(), json_encode($session), now()->addDays(7));
        };

        $api = WealthsimpleAPI::fromToken(json_decode($sessionData), $persistSession);

        try {
            $accounts = $api->getAccounts();
            Cache::put('ws_api_cached_accounts_' . Auth::id(), json_encode($accounts), now()->addMinutes(30));

            $holdings = [];

            foreach ($accounts as $account) {
                $positions = $api->getPositions($account->currency, [$account->id]);

                foreach ($positions as $edge) {
                    $position = $edge->node;

                    $holdings[] = [
                        'account_id' => $account->id,
                        'symbol' => $position->security->stock->symbol ?? 'N/A',
                        'qty' => (float)($position->quantity ?? 0),
                        'avg' => (float)($position->averagePrice->amount ?? 0),
                        'price' => (float)($position->security->quote->amount ?? 0),
                        'book' => (float)($position->bookValue->amount ?? 0),
                        'market' => (float)($position->totalValue->amount ?? 0),
                        'pnl' => (float)($position->totalValue->amount ?? 0) - (float)($position->bookValue->amount ?? 0),
                        'currency' => $account->currency,
                    ];
                }
            }

            Cache::put('ws_api_cached_positions_' . Auth::id(), json_encode($holdings), now()->addMinutes(30));

            if (isset($accounts[0]->accountOwners[0])) {
                $owner = $accounts[0]->accountOwners[0];

                Cache::put('ws_api_connected_account_' . Auth::id(), [
                    'name' => $owner->name ?? 'N/A',
                    'email' => $owner->email ?? $this->email,
                ], now()->addMinutes(30));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to fetch and cache Wealthsimple data', ['exception' => $e]);
            $this->errorMessage = 'Failed to fetch and cache account data.';
        }
    }
};
?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span>{{ __('Wealthsimple API') }}</span>
                <flux:badge :color="$connected ? 'green' : 'yellow'">
                    {{ $connected ? __('Connected') : __('Disconnected') }}
                </flux:badge>
            </div>
        </x-slot>

        <x-slot name="subheading">
            {{ __('Manage your Wealthsimple account connection.') }}
        </x-slot>

        @if (!$connected)
            <form wire:submit="connect" class="mt-6 space-y-6">
                <flux:input wire:model="email" id="ws_api_email" label="{{ __('Email') }}" type="email" required autocomplete="email" />
                <flux:input wire:model="password" id="ws_api_password" label="{{ __('Password') }}" type="password" required autocomplete="current-password" />
                @if ($errorMessage === 'OTP required. Please enter the code sent to your device.')
                    <flux:input wire:model="otp" id="ws_api_otp" label="{{ __('2FA Code') }}" type="text" required />
                @endif

                @if ($errorMessage)
                    <div class="text-red-500">{{ $errorMessage }}</div>
                @endif

                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit" class="w-full" wire:loading.attr="disabled">
                        {{ __('Connect') }}
                    </flux:button>
                </div>

                <div wire:loading class="text-sm text-zinc-500">
                    Retrieving accounts & holdings...
                </div>
            </form>
        @else
            @php
                $accountInfo = Cache::get('ws_api_connected_account_' . Auth::id(), [
                    'name' => 'N/A',
                    'email' => 'N/A',
                ]);
            @endphp

            <div class="space-y-2">
                <div class="text-sm text-zinc-300">Connected account: <span class="font-medium">{{ $accountInfo['email'] }}</span></div>
                <div class="text-sm text-zinc-300">Account holder: <span class="font-medium">{{ $accountInfo['name'] }}</span></div>

                <flux:button variant="danger" wire:click="disconnect" class="mt-4">
                    {{ __('Disconnect') }}
                </flux:button>
            </div>
        @endif
    </x-settings.layout>
</section>
