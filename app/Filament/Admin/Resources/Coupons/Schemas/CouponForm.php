<?php

namespace App\Filament\Admin\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use PhpParser\Node\Stmt\Label;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coupon Information')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('code')
                            ->live(onBlur: true)
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set("code", strtoupper($state))
                            )
                            ->required(),

                        Select::make('type')
                            ->options(['fixed' => 'Fixed', 'percentage' => 'Percentage'])
                            ->default('percentage')
                            ->live()
                            ->required(),
                        TextInput::make('value')
                            ->required()
                            ->minValue(0)
                            ->prefix(fn(callable $get) => $get('type') === 'fixed' ? '$' : null)
                            ->suffix(fn(callable $get) => $get('type') === 'percentage' ? '%' : null)
                            ->numeric(),
                        Toggle::make('is_active')
                            ->required()
                            ->label('Active'),
                    ]),

                Section::make("Conditions & Limits")
                    ->schema([
                        TextInput::make('minimum_order_value')
                            ->numeric()
                            ->prefix("0")
                            ->minValue(0)

                            ->default(null),
                        TextInput::make('maximum_discount_value')
                            ->visible(fn(callable $get) => $get("type") === 'percentage')
                            ->prefix("0")
                            ->minValue(0)
                            ->numeric()
                            ->default(null),
                        TextInput::make('usage_limit')
                            ->minValue(1)
                            ->numeric()
                            ->default(null),
                        TextInput::make('usage_limit_per_customer')
                            ->numeric()
                            ->minValue(1)
                            ->default(null),
                    ]),




                Section::make("velidity period")
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->native(false)
                            ->helperText('when the coupon becomes active'),
                        DateTimePicker::make('expires_at')
                            ->native(false),
                    ])



            ]);

    }

}
