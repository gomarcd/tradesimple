<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use App\Services\Wealthsimple\WealthsimpleAPI;
use App\Services\Wealthsimple\Exceptions\OTPRequiredException;
use App\Services\Wealthsimple\Exceptions\LoginFailedException;
use App\Services\Wealthsimple\Sessions\WSAPISession;
use App\Models\WealthsimpleLogin;
use App\Models\WealthsimpleAccount;
use App\Models\WealthsimpleConnectionAudit;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public string $otp = '';
    public string $errorMessage = '';
    public array $connectedAccounts = [];
    public string $userAgent = '';
    public bool $showConnectionForm = false;

    public function mount(): void
    {
        $this->loadConnectedAccounts();
    }

    protected function loadConnectedAccounts(): void
    {
        $userId = Auth::id();
        $this->connectedAccounts = WealthsimpleLogin::where('user_id', $userId)
            ->with(['audit' => function ($query) {
                $query->whereNull('end_at')->latest('start_at');
            }])
            ->get()
            ->map(function ($login) {
                $cacheKey = 'ws_api_session_' . Auth::id() . '_' . $login->email;
                $audit = $login->audit->first();
                return [
                    'id' => $login->id,
                    'email' => $login->email,
                    'is_active' => $login->is_active,
                    'ip_address' => $audit ? $audit->ip_address : 'N/A',
                    'user_agent' => $audit ? $audit->user_agent : 'N/A',
                    'start_at' => $audit ? $audit->start_at->toDateTimeString() : 'N/A',
                    'has_session' => Cache::has($cacheKey) || !empty($login->session_data),
                ];
            })
            ->toArray();
    }

    protected function saveAudit($loginId): void
    {
        $createdAt = now()->toIso8601String();
        try {
            WealthsimpleConnectionAudit::create([
                'login_id' => $loginId,
                'ip_address' => request()->ip(),
                'user_agent' => $this->userAgent,
                'start_at' => $createdAt,
            ]);
            \Log::info("WS Account Connected: $loginId $this->userAgent, $createdAt");
        } catch (\Exception $e) {
            \Log::error("Failed: {$e->getMessage()}");
        }
    }

    public function showAddAccountForm(): void
    {
        $this->showConnectionForm = true;
        $this->reset(['email', 'password', 'otp', 'errorMessage']);
    }

    public function connect(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'otp' => ['nullable', 'string'],
        ]);

        $persistSession = function (WSAPISession $session) {
            $userId = Auth::id();
            $cacheKey = 'ws_api_session_' . $userId . '_' . $this->email;
            Cache::put($cacheKey, json_encode($session));
            WealthsimpleLogin::updateOrCreate(
                ['user_id' => $userId, 'email' => $this->email],
                [
                    'session_data' => json_encode($session),
                    'is_active' => true,
                ]
            );
        };

        try {
            WealthsimpleAPI::login(
                $validated['email'],
                $validated['password'],
                $validated['otp'],
                $persistSession
            );

            $this->errorMessage = '';
            $userId = Auth::id();
            $activeEmailsKey = ['ws_api', 'active_emails', $userId];
            $activeEmails = Cache::get($activeEmailsKey, []);
            $activeEmails[] = $this->email;
            Cache::put($activeEmailsKey, array_unique($activeEmails));
            Flux::toast(variant: 'success', heading: 'Connected', text: 'Wealthsimple account: ' . $this->email . ' successfully connected.');
            $this->dispatch('ws-connected');

            $login = WealthsimpleLogin::where('user_id', Auth::id())->where('email', $this->email)->first();
            $this->saveAudit($login->id);

            $this->fetchAccountData($this->email);
            $this->loadConnectedAccounts();
            $this->reset(['email', 'password', 'otp']);
            $this->showConnectionForm = false;

        } catch (OTPRequiredException $e) {
            $this->errorMessage = 'OTP required. Please enter the code sent to your device.';
        } catch (LoginFailedException $e) {
            $this->errorMessage = 'Login failed. Please check your credentials.';
        } catch (\Exception $e) {
            $this->errorMessage = 'An unexpected error occurred: ' . $e->getMessage();
        }
    }

    public function disconnect($loginId): void
    {
        $userId = Auth::id();
        $login = WealthsimpleLogin::where('user_id', $userId)->where('id', $loginId)->first();
        if ($login && $login->is_active) {
            $cacheKey = 'ws_api_session_' . $userId . '_' . $login->email;
            Cache::forget($cacheKey);

            $login->update(['is_active' => false]);
            $audit = WealthsimpleConnectionAudit::where('login_id', $login->id)
                ->whereNull('end_at')
                ->latest('start_at')
                ->first();
            if ($audit) {
                $audit->update([
                    'end_at' => now(),
                    'ended_by_user' => true,
                ]);
            }

            $this->loadConnectedAccounts();
            $this->dispatch('ws-disconnected');
        }
    }

    public function reconnect($loginId): void
    {
        $userId = Auth::id();
        $login = WealthsimpleLogin::where('user_id', $userId)->where('id', $loginId)->first();
        if ($login && !$login->is_active && $login->session_data) {
            $cacheKey = 'ws_api_session_' . $userId . '_' . $login->email;
            Cache::put($cacheKey, $login->session_data);
            $login->update(['is_active' => true]);
            $this->saveAudit($login->id);
            $this->fetchAccountData($login->email);
            $this->loadConnectedAccounts();
            $this->dispatch('ws-connected');
        }
    }

    public function delete($loginId): void
    {
        $userId = Auth::id();
        $login = WealthsimpleLogin::where('user_id', $userId)->where('id', $loginId)->first();
        if ($login) {
            $cacheKey = 'ws_api_session_' . $userId . '_' . $login->email;
            Cache::forget($cacheKey);
            Cache::forget('ws_api_cached_accounts_' . $userId . '_' . $login->email);
            Cache::forget('ws_api_cached_positions_' . $userId . '_' . $login->email);
            Cache::forget('ws_api_connected_account_' . $userId . '_' . $login->email);
            $login->delete();
            $this->loadConnectedAccounts();
            Flux::toast(variant: 'success', heading: 'Deleted', text: 'Wealthsimple account session deleted.');
        }
    }

    protected function fetchAccountData(string $email): void
    {
        $userId = Auth::id();
        $cacheKey = 'ws_api_session_' . $userId . '_' . $email;
        $sessionData = Cache::get($cacheKey);
        if (!$sessionData) {
            return;
        }

        $persistSession = function (WSAPISession $session) use ($userId, $email) {
            $cacheKey = 'ws_api_session_' . $userId . '_' . $email;
            Cache::put($cacheKey, json_encode($session));
            WealthsimpleLogin::updateOrCreate(
                ['user_id' => $userId, 'email' => $email],
                ['session_data' => json_encode($session), 'is_active' => true]
            );
        };

        $api = WealthsimpleAPI::fromToken(json_decode($sessionData), $persistSession);

        try {
            $accounts = $api->getAccounts();
            Cache::put('ws_api_cached_accounts_' . $userId . '_' . $email, json_encode($accounts));

            $login = WealthsimpleLogin::where('user_id', $userId)->where('email', $email)->first();

            foreach ($accounts as $account) {
                $owner = $account->accountOwners[0] ?? null;
                WealthsimpleAccount::updateOrCreate(
                    ['login_id' => $login->id, 'account_id' => $account->id],
                    [
                        'account_type' => $account->accountType ?? null,
                        'description' => $account->description ?? null,
                        'currency' => $account->currency,
                        'display_name' => $account->description ?? null,
                        'owner_name' => $owner->name ?? null,
                        'owner_email' => $owner->email ?? null,
                        'balance' => isset($account->totalValue->amount) ? (float)$account->totalValue->amount : null,
                        'is_active' => true,
                        'has_multiple_owners' => count($account->accountOwners) > 1,
                        'last_synced_at' => now(),
                    ]
                );
            }

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
            Cache::put('ws_api_cached_positions_' . $userId . '_' . $email, json_encode($holdings));
            Cache::put('ws_api_connected_account_' . $userId . '_' . $email, [
                'name' => $accounts[0]->accountOwners[0]->name ?? 'N/A',
                'email' => $email,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch and cache Wealthsimple data for ' . $email, ['exception' => $e]);
            $this->errorMessage = 'Failed to fetch and cache account data.';
        }
    }
};
?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout>
        <x-slot name="heading">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span>{{ __('Wealthsimple API') }}</span>
                </div>
            </div>
        </x-slot>

        <x-slot name="subheading">
            {{ __('Manage your Wealthsimple account connections.') }}

            @if (count($connectedAccounts) > 0)
                <flux:button variant="ghost" icon="user-plus" tooltip="Add Account" wire:click="showAddAccountForm" />
            @endif
        </x-slot>

        {{-- Connection Form - Only show when no accounts exist or explicitly adding a new account --}}
        @if ($showConnectionForm || count($connectedAccounts) === 0)
            <div x-data @keydown.escape.window="$wire.set('showConnectionForm', false)">
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
                        {{ __('Retrieving accounts & holdings...') }}
                    </div>
                </form>
            </div>
        @endif

        {{-- Connected Accounts Table --}}
        @if (count($connectedAccounts) > 0)
            <div class="mt-6">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Account') }}</flux:table.column>
                        <flux:table.column>{{ __('Status') }}</flux:table.column>
                        <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($connectedAccounts as $account)
                            <flux:table.row :key="$account['id']">
                                <flux:table.cell >{{ $account['email'] }}</flux:table.cell>
                                <flux:table.cell >
                                    @if ($account['is_active'])
                                        <flux:badge color="green">{{ __('Connected') }}</flux:badge>
                                    @else
                                        <flux:badge color="yellow">{{ __('Disconnected') }}</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell align="end">
                                        @if ($account['is_active'])
                                            <flux:button variant="ghost" icon="arrow-left-start-on-rectangle" tooltip="Disconnect" wire:click="disconnect({{ $account['id'] }})" />
                                        @else
                                            <!-- Tooltip inteferes with loading spinner otherwise -->
                                            @if ($account['is_active'])
                                                <flux:button 
                                                    variant="ghost" 
                                                    icon="arrow-left-start-on-rectangle" 
                                                    wire:click="disconnect({{ $account['id'] }})" 
                                                />
                                            @else
                                                <flux:tooltip content="Reconnect">
                                                    <flux:button
                                                        variant="ghost" 
                                                        icon="arrow-path"
                                                        wire:click="reconnect({{ $account['id'] }})"
                                                        :disabled="!$account['has_session']"
                                                    />
                                                </flux:tooltip>
                                            @endif
                                        @endif
                                        
                                        <flux:button variant="ghost" icon="trash" tooltip="Delete" wire:click="delete({{ $account['id'] }})" />

                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        @endif
    </x-settings.layout>
</section>