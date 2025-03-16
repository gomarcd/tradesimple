<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\WealthsimpleLogin;

new class extends Component {

    public bool $connected = false;
    public array $holdings = [];
    
    // New properties for multi-account support
    public array $availableLogins = [];
    public ?string $selectedEmail = null;
    
    public function mount()
    {
        $userId = Auth::id();
        
        // Get all active Wealthsimple logins for the user
        $this->availableLogins = WealthsimpleLogin::where('user_id', $userId)
            ->where('is_active', true)
            ->select('id', 'email')
            ->get()
            ->toArray();
            
        $this->connected = count($this->availableLogins) > 0;
        
        // Get selected email from session or default to first one
        $this->selectedEmail = session('selected_ws_login_email');
        
        // If no selection or selected login not found, use first available
        if (!$this->selectedEmail || !collect($this->availableLogins)->contains('email', $this->selectedEmail)) {
            if (count($this->availableLogins) > 0) {
                $this->selectedEmail = $this->availableLogins[0]['email'];
                session(['selected_ws_login_email' => $this->selectedEmail]);
            }
        }
    }

    /**
     * Load holdings from cache into $this->holdings.
     */
    public function fetchCachedPositions(): void
    {
        $userId = Auth::id();
        
        // If no email is selected or user has no active connections
        if (empty($this->selectedEmail)) {
            $this->holdings = [];
            return;
        }
        
        // Use the cache key format from ws-api.blade.php
        $cached = Cache::get('ws_api_cached_positions_' . $userId . '_' . $this->selectedEmail);
    
        if ($cached) {
            $this->holdings = collect(json_decode($cached, true))
                ->map(function ($holding) {
                    $holding['pnlPercentage'] = $holding['book'] != 0
                        ? ($holding['pnl'] / $holding['book']) * 100
                        : 0;
                    return $holding;
                })
                ->sortBy([
                    [$this->sortBy, $this->sortDirection]
                ])
                ->values()
                ->toArray();
        } else {
            $this->holdings = [];
        }
    }
    
    /**
     * Handle account selection change
     */
    public function updatedSelectedEmail($value)
    {
        if ($value) {
            session(['selected_ws_login_email' => $value]);
            $this->fetchCachedPositions();
        }
    }

    public string $sortBy = 'qty';
    public string $sortDirection = 'asc';
    
    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    
        $this->fetchCachedPositions();
    }
    
};
?>

<div wire:init="fetchCachedPositions" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    <!-- Account selector (only shown when multiple accounts exist) -->
    @if(count($availableLogins) > 1)
        <div class="flex justify-end mb-2">
            <div class="inline-flex items-center gap-2">
                <span class="text-sm text-zinc-500">{{ __('Account:') }}</span>
                <select wire:model.live="selectedEmail" class="rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800">
                    @foreach($availableLogins as $login)
                        <option value="{{ $login['email'] }}">{{ $login['email'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy ==='symbol'" :direction="$sortDirection" wire:click="sort('symbol')">Symbol</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'qty'" :direction="$sortDirection" wire:click="sort('qty')">Qty</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'avg'" :direction="$sortDirection" wire:click="sort('avg')">Avg</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy ==='price'" :direction="$sortDirection" wire:click="sort('price')">Price</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy ==='book'" :direction="$sortDirection" wire:click="sort('book')">Book</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy ==='market'" :direction="$sortDirection" wire:click="sort('market')">Market</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy ==='pnl'" :direction="$sortDirection" wire:click="sort('pnl')">P&L ($)</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy ==='pnlPercentage'" :direction="$sortDirection" wire:click="sort('pnlPercentage')">P&L (%)</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @if(empty($holdings))
                <flux:table.row>
                    <flux:table.cell colspan="8" class="text-center py-4">
                        @if(!$connected)
                            No connected Wealthsimple accounts found. Connect your account or refresh the data.
                        @else
                            No holdings found for {{ $selectedEmail }}. Please check your Wealthsimple account or refresh the data.
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @else
                @foreach($holdings as $holding)
                    @php
                        $pnlPercentage = $holding['book'] != 0 ? ($holding['pnl'] / $holding['book']) * 100 : 0;
                        $pnlColor = $pnlPercentage < 0 ? 'text-red-400' : 'text-green-400';
                    @endphp
                    <flux:table.row>
                        <flux:table.cell>{{ $holding['symbol'] }}</flux:table.cell>
                        <flux:table.cell>{{ $holding['qty'] }}</flux:table.cell>
                        <flux:table.cell>${{ number_format($holding['avg'], 2) }}</flux:table.cell>
                        <flux:table.cell>${{ number_format($holding['price'], 2) }}</flux:table.cell>
                        <flux:table.cell>${{ number_format($holding['book'], 2) }}</flux:table.cell>
                        <flux:table.cell>${{ number_format($holding['market'], 2) }}</flux:table.cell>
                        <flux:table.cell>
                            <span class="font-bold {{ $pnlColor }}">
                                ${{ number_format($holding['pnl'], 2) }}
                            </span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="{{ $pnlPercentage < 0 ? 'red' : 'green' }}">
                                {{ $pnlPercentage < 0 ? '' : '+' }}{{ number_format($pnlPercentage, 2) }}%
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            @endif
        </flux:table.rows>
    </flux:table>

</div>