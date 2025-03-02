<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

new class extends Component {

    public bool $connected = false;
    public array $holdings = [];

    public function mount()
    {
        $this->connected = Cache::has('ws_api_session_' . Auth::id());
    }

    /**
     * Load holdings from cache into $this->holdings.
     */
    public function fetchCachedPositions(): void
    {
        $cached = Cache::get('ws_api_cached_positions_' . Auth::id());
    
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
                    No holdings found. Connect your account or refresh the data.
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
