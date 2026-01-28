<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Customers\CustomerResource;
use App\Filament\Admin\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 1;
    protected   int|string|array $columnSpan = "full";

    public function table(Table $table): Table
    {   
          
        return $table
            ->query(fn(): Builder => Order::query())
            ->columns([
                TextColumn::make("order_number")
                    ->weight('bold')
                    ->url(fn($record) => OrderResource::getUrl('edit', [$record->id])),
                TextColumn::make('customer.name')
                    ->url(fn($record) => CustomerResource::getUrl('edit', [$record->customer])),
                TextColumn::make("status")
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    }),
                TextColumn::make('total')
                    ->money('USD')
                    ->weight('bold'),
                TextColumn::make('created_at')
                    ->label('ordered')
                    ->since(),
            ])
            ->heading('Last Orders')
            ->filters([

            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
