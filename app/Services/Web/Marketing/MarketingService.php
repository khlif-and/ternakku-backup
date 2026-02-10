<?php

namespace App\Services\Web\Marketing;

use App\Models\FarmUser;
use App\Models\QurbanCustomer;
use App\Models\QurbanSalesOrder;

class MarketingService
{
    public function getMarketingFarms($userId)
    {
        return FarmUser::with('farm')
            ->where('user_id', $userId)
            ->where('farm_role', 'MARKETING')
            ->get()
            ->pluck('farm')
            ->filter();
    }

    public function getStats($userId)
    {
        $farmIds = FarmUser::where('user_id', $userId)
            ->where('farm_role', 'MARKETING')
            ->pluck('farm_id');

        $totalCustomers = QurbanCustomer::whereIn('farm_id', $farmIds)
            ->where('created_by', $userId)
            ->count();

        $totalSalesOrders = QurbanSalesOrder::whereIn('farm_id', $farmIds)
            ->where('created_by', $userId)
            ->count();

        $pendingSalesOrders = QurbanSalesOrder::whereIn('farm_id', $farmIds)
            ->where('created_by', $userId)
            ->where('status', 'pending')
            ->count();

        $completedSalesOrders = QurbanSalesOrder::whereIn('farm_id', $farmIds)
            ->where('created_by', $userId)
            ->where('status', 'completed')
            ->count();

        return [
            'total_customers' => $totalCustomers,
            'total_sales_orders' => $totalSalesOrders,
            'pending_sales_orders' => $pendingSalesOrders,
            'completed_sales_orders' => $completedSalesOrders,
        ];
    }

    public function getCustomers($userId, $params = [])
    {
        $farmIds = FarmUser::where('user_id', $userId)
            ->where('farm_role', 'MARKETING')
            ->pluck('farm_id');

        $query = QurbanCustomer::with(['user', 'farm'])
            ->whereIn('farm_id', $farmIds)
            ->where('created_by', $userId);

        if (!empty($params['search'])) {
            $query->whereHas('user', function ($q) use ($params) {
                $q->where('name', 'like', '%' . $params['search'] . '%')
                    ->orWhere('email', 'like', '%' . $params['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getSalesOrders($userId, $params = [])
    {
        $farmIds = FarmUser::where('user_id', $userId)
            ->where('farm_role', 'MARKETING')
            ->pluck('farm_id');

        $query = QurbanSalesOrder::with(['qurbanCustomer.user', 'farm'])
            ->whereIn('farm_id', $farmIds)
            ->where('created_by', $userId);

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params) {
                $q->where('transaction_number', 'like', '%' . $params['search'] . '%')
                    ->orWhereHas('qurbanCustomer.user', function ($subQ) use ($params) {
                        $subQ->where('name', 'like', '%' . $params['search'] . '%');
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getCustomerById($userId, $customerId)
    {
        $farmIds = FarmUser::where('user_id', $userId)
            ->where('farm_role', 'MARKETING')
            ->pluck('farm_id');

        return QurbanCustomer::with(['user', 'farm', 'qurbanCustomerAddresses'])
            ->whereIn('farm_id', $farmIds)
            ->where('created_by', $userId)
            ->where('id', $customerId)
            ->first();
    }

    public function getSalesOrderById($userId, $salesOrderId)
    {
        $farmIds = FarmUser::where('user_id', $userId)
            ->where('farm_role', 'MARKETING')
            ->pluck('farm_id');

        return QurbanSalesOrder::with(['qurbanCustomer.user', 'farm', 'salesOrderDetails'])
            ->whereIn('farm_id', $farmIds)
            ->where('created_by', $userId)
            ->where('id', $salesOrderId)
            ->first();
    }
}
