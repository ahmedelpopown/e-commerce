<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Orders\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverView extends StatsOverviewWidget
{
    protected static ?int $sort=0;

    protected ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        $totalRevenue=Order::where('payment_status','paid')->sum("total");
        $totalRevenue= Order::where("payment_status",'paid')
        ->whereDate('created_at',today())
        ->sum('total');

        $totalOrder=Order::count();
        $pendingOrders=Order::where('status','pending')->count();

        $totalCustomers=Customer::count();
        $newCustomerThisMonth=Customer::whereMonth('created_at',now()->month)
        ->whereYear('created_at',now()->year)->count();
        $lowStockProducts=Product::lowStock()->count();
        return [
            Stat::make('Total Revenue', number_format($totalRevenue,2))
            ->description('Today: $'.number_format($totalRevenue,2))
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            Stat::make('Total Orders', $totalOrder)->description('pending')
            ->description($pendingOrders.' pending')
            ->descriptionIcon('heroicon-m-shopping-cart')
            ->color('warning')
            ->url(route('filament.admin.resources.orders.index')),
            Stat::make('Total customer',$totalCustomers)
            ->description($newCustomerThisMonth .'  new this month')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('info') 
            ->url(route('filament.admin.resources.customers.index')),
            Stat::make('Low Stock Alert',$lowStockProducts)
            ->description('Products running low')
            ->descriptionIcon('heroicon-m-exclamation-triangle')
            ->color('danger')
            ->url(route('filament.admin.resources.products.index')),
            ];
            }
}
