<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Models\Farm;
use App\Models\QurbanSalesOrder;
use App\Models\QurbanCustomer;
use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanDeliveryOrderH;
use App\Models\QurbanDeliveryInstructionH;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $farmId = session('selected_farm');
        $farm = Farm::find($farmId);

        // Summary counts
        $salesOrderCount = QurbanSalesOrder::where('farm_id', $farmId)->count();
        $customerCount = QurbanCustomer::where('farm_id', $farmId)->count();
        $saleLivestockCount = QurbanSaleLivestockH::where('farm_id', $farmId)->count();
        $deliveryCount = QurbanDeliveryOrderH::where('farm_id', $farmId)->count();
        $deliveryCompletedCount = QurbanDeliveryOrderH::where('farm_id', $farmId)
            ->where('status', 'completed')->count();
        $instructionCount = QurbanDeliveryInstructionH::where('farm_id', $farmId)->count();

        // Sales order trend (last 6 months)
        $salesOrderTrend = QurbanSalesOrder::where('farm_id', $farmId)
            ->where('order_date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Delivery status breakdown
        $deliveryStatuses = QurbanDeliveryOrderH::where('farm_id', $farmId)
            ->selectRaw("COALESCE(status, 'pending') as status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        // Livestock sales trend (last 6 months)
        $saleLivestockTrend = QurbanSaleLivestockH::where('farm_id', $farmId)
            ->where('transaction_date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Recent activities
        $recentSalesOrders = QurbanSalesOrder::where('farm_id', $farmId)
            ->with('qurbanCustomer')
            ->latest('created_at')->take(5)->get()
            ->map(fn($x) => [
                'type' => 'sales_order',
                'description' => "Sales Order <span class='font-semibold text-slate-900'>{$x->transaction_number}</span> untuk pelanggan <span class='font-semibold text-slate-900'>" . ($x->qurbanCustomer->name ?? '-') . "</span>.",
                'created_at' => $x->created_at,
            ]);

        $recentDeliveries = QurbanDeliveryOrderH::where('farm_id', $farmId)
            ->latest('created_at')->take(5)->get()
            ->map(fn($x) => [
                'type' => 'delivery',
                'description' => "Surat Jalan <span class='font-semibold text-slate-900'>{$x->transaction_number}</span> status <span class='font-semibold text-slate-900'>" . ($x->status ?? 'pending') . "</span>.",
                'created_at' => $x->created_at,
            ]);

        $recentActivities = collect()
            ->merge($recentSalesOrders)
            ->merge($recentDeliveries)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        return view('admin.qurban.dashboard', compact(
            'farm',
            'salesOrderCount',
            'customerCount',
            'saleLivestockCount',
            'deliveryCount',
            'deliveryCompletedCount',
            'instructionCount',
            'salesOrderTrend',
            'deliveryStatuses',
            'saleLivestockTrend',
            'recentActivities'
        ));
    }
}
