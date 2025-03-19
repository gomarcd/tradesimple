<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\WealthsimpleLogin;

new class extends Component {
    public bool $connected = false;
    public array $holdings = [];
    public array $availableLogins = [];
    public array $selectedEmails = [];
    
    public function mount()
    {
        $userId = Auth::id();
        
        // Get all Wealthsimple logins for user
        $this->availableLogins = WealthsimpleLogin::where('user_id', $userId)
            ->select('id', 'email')
            ->get()
            ->toArray();
            
        $this->connected = count($this->availableLogins) > 0;
        
        // Initialize selected emails from session
        $storedEmails = session('selected_ws_login_email', null);
        
        // Show all if user hasn't toggled selections yet
        if ($storedEmails === null && count($this->availableLogins) > 0) {
            $storedEmails = collect($this->availableLogins)->pluck('email')->toArray();
        } else if (is_string($storedEmails)) {
            $storedEmails = [$storedEmails];
        }
        
        $this->selectedEmails = $storedEmails ?? [];
        
        // Initial fetch of positions
        $this->fetchCachedPositions();
    }
    
    // Toggle email selection
    public function toggleEmail($email)
    {
        if (in_array($email, $this->selectedEmails)) {
            // Remove email if already selected
            $this->selectedEmails = array_values(array_filter($this->selectedEmails, function($e) use ($email) {
                return $e !== $email;
            }));
        } else {
            // Add email if not selected
            $this->selectedEmails[] = $email;
        }
        
        // Update session
        session(['selected_ws_login_email' => $this->selectedEmails]);
        
        // Reload holdings
        $this->fetchCachedPositions();
    }
    
    // Check if email is selected
    public function isSelected($email)
    {
        return in_array($email, $this->selectedEmails);
    }
    
    /**
     * Load holdings from cache into $this->holdings.
     */
    public function fetchCachedPositions(): void
    {
        $userId = Auth::id();
        
        // If no emails are selected, clear holdings
        if (empty($this->selectedEmails)) {
            $this->holdings = [];
            return;
        }

        // Collect holdings for selected emails
        $allHoldings = [];
        foreach ($this->selectedEmails as $email) {
            $cacheKey = 'ws_api_cached_positions_' . $userId . '_' . $email;
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                $holdings = collect(json_decode($cached, true))
                    ->map(function ($holding) use ($email) {
                        $holding['account_email'] = $email;
                        $holding['pnlPercentage'] = $holding['book'] != 0
                            ? ($holding['pnl'] / $holding['book']) * 100
                            : 0;
                        return $holding;
                    })
                    ->toArray();
                
                $allHoldings = array_merge($allHoldings, $holdings);
            }
        }
        
        $this->holdings = collect($allHoldings)
            ->sortBy([
                [$this->sortBy, $this->sortDirection]
            ])
            ->values()
            ->toArray();
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
                <flux:dropdown>
                    <flux:button icon-trailing="chevron-down" variant="ghost">
                        Accounts ({{ count($selectedEmails) }})
                    </flux:button>
                    
                    <flux:menu>
                        @foreach($availableLogins as $login)
                            <flux:menu.checkbox 
                                wire:key="{{ $login['email'] }}" 
                                wire:click="toggleEmail('{{ $login['email'] }}')"
                                :checked="$this->isSelected($login['email'])"
                            >
                                {{ $login['email'] }}
                            </flux:menu.checkbox>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>
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
            @forelse($holdings as $holding)
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
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="8" class="text-center py-4">
                        @if(!$connected)
                            No connected Wealthsimple accounts found. Connect your account or refresh the data.
                        @else
                            No holdings found for selected accounts. Please select an account and refresh the data.
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>