<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use App\Filament\Admin\Resources\Customers\CustomerResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->color('primary')
                    ->url(fn($record) => $record->customer ? CustomerResource::getUrl('edit', [$record->customer]) : null)
                    ->sortable(),
                TextColumn::make('coupon_id')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('subtotal')
                // ->numeric()
                // ->sortable(),
                TextColumn::make('discount_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->money('USD')
                    ->color('success')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->color('info')
                    ->badge(),
                TextColumn::make('tracing_number')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->native(false)
                    ->multiple(),
                SelectFilter::make('payment_status')
                   
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->native(false),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
