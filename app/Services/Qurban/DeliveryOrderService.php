<?php

namespace App\Services\Qurban;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\QurbanDeliveryOrderD;
use App\Models\QurbanDeliveryOrderH;
use App\Models\QurbanSaleLivestockH;
use Illuminate\Support\Facades\File;
use App\Models\QurbanDeliveryInstructionD;

class DeliveryOrderService
{
    public function storeDeliveryOrder($farm_id, $request)
    {
        $data = [];
        $error = false;
    
        DB::beginTransaction();
    
        try {
            $saleLivestock = QurbanSaleLivestockH::where('farm_id', $farm_id)
                ->findOrFail($request['qurban_sales_livestock_id']);
    
            // CEK: Apakah sudah ada surat jalan untuk sale ini?
            $existingOrders = QurbanDeliveryOrderH::where('qurban_sale_livestock_h_id', $saleLivestock->id)->get();
    
            if ($existingOrders->count() > 0) {
                return [
                    'data' => $existingOrders,
                    'error' => false,
                ];
            }
    
            $groupedDetails = $saleLivestock->qurbanSaleLivestockD
                ->groupBy('qurban_customer_address_id');
    
            foreach ($groupedDetails as $customerAddressId => $details) {
                // Buat surat jalan (Header)
                $deliveryOrder = QurbanDeliveryOrderH::create([
                    'farm_id' => $saleLivestock->farm_id,
                    'transaction_date' => $request['transaction_date'],
                    'qurban_customer_address_id' => $customerAddressId,
                    'qurban_sale_livestock_h_id' => $saleLivestock->id,
                ]);
    
                // Tambahkan detail
                foreach ($details as $detail) {
                    QurbanDeliveryOrderD::create([
                        'qurban_delivery_order_h_id' => $deliveryOrder->id,
                        'livestock_id' => $detail->livestock_id,
                    ]);
                }
    
                // Generate PDF
                $pdf = Pdf::loadView('pdf.delivery_order', [
                    'deliveryOrder' => $deliveryOrder,
                ]);
    
                $fileName = now()->format('YmdHis') . '-delivery-order-' . $deliveryOrder->id . '.pdf';
                $tempPath = storage_path('app/temp/' . $fileName);
    
                // Pastikan direktori temp ada
                if (!File::exists(dirname($tempPath))) {
                    File::makeDirectory(dirname($tempPath), 0755, true);
                }
    
                $pdf->save($tempPath);
    
                // Upload ke S3
                $s3Path = 'qurban/delivery_orders/';
                $s3Url = uploadNeoObject($tempPath, $fileName, $s3Path);
    
                // Simpan ke database
                $deliveryOrder->file = $s3Url;
                $deliveryOrder->save();
    
                // Simpan ke hasil
                $data[] = $deliveryOrder;
    
                // Hapus file sementara
                unlink($tempPath);
            }
    
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            \Log::error('Gagal membuat surat jalan: ' . $e->getMessage());
            $error = true;
        }
    
        return [
            'data' => $data,
            'error' => $error,
        ];
    }
    
    public function getDeliveryOrders($farmId, $param)
    {
        $query = QurbanDeliveryOrderH::with(['qurbanDeliveryOrderD', 'qurbanCustomerAddress.qurbanCustomer', 'qurbanSaleLivestockH', 'farm'])
            ->where('farm_id', $farmId);

        // Date range filter
        $query->when($param['transaction_date_start'] ?? null, function ($q, $start) {
            $q->whereDate('transaction_date', '>=', $start);
        });

        $query->when($param['transaction_date_end'] ?? null, function ($q, $end) {
            $q->whereDate('transaction_date', '<=', $end);
        });

        $query->when($param['qurban_customer_address_id'] ?? null, function ($q, $id) {
            $q->where('qurban_customer_address_id', $id);
        });

        $query->when($param['qurban_customer_id'] ?? null, function ($q, $customerId) {
            $q->whereHas('qurbanCustomerAddress.qurbanCustomer', function ($q2) use ($customerId) {
                $q2->where('id', $customerId);
            });
        });

        $query->when($param['qurban_customer_name'] ?? null, function ($q, $name) {
            $q->whereHas('qurbanCustomerAddress.qurbanCustomer', function ($q2) use ($name) {
                $q2->where('name', 'like', '%' . $name . '%');
            });
        });

        $query->when($param['status'] ?? null, function ($q, $name) {
            $q->where('status', $name);
        });

        return $query->get();
    }

    public function setDeliverySchedule($farmId, $id, $schedule)
    {
        $error = false;
        $data = null;

        try {
            $deliveryOrder = QurbanDeliveryOrderH::where('farm_id' , $farmId)->findOrFail($id);
            $deliveryOrder->delivery_schedule = $schedule;
            $deliveryOrder->save();

            $data = $deliveryOrder;
        } catch (\Exception $e) {
            \Log::error('Gagal set jadwal pengiriman: ' . $e->getMessage());
            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error,
        ];
    }

    public function getById($farm_id, $id)
    {
        return QurbanDeliveryOrderH
            ::where('farm_id', $farm_id)
            ->find($id);
    }

    public function uploadReceiptPhoto($driverId, $deliveryOrderId, $file)
    {
        $error = false;
        $data = null;

        try {
            $deliveryOrder = QurbanDeliveryOrderH::findOrFail($deliveryOrderId);

            //VALIDASI: Apakah driver ini memiliki akses ke surat jalan ini?

            $fileName = now()->format('YmdHis') . '-receipt-' . $deliveryOrder->id . '.jpg';
            $s3Path = 'qurban/receipt_photos/';
            $s3Url = uploadNeoObject($file, $fileName, $s3Path);

            // Simpan ke database
            $deliveryOrder->receipt_photo = $s3Url;
            $deliveryOrder->status = 'delivered';
            $deliveryOrder->receipt_at = now()->format('Y-m-d H:i:s');
            $deliveryOrder->save();

            $data = $deliveryOrder;
        } catch (\Exception $e) {
            \Log::error('Gagal upload foto tanda terima: ' . $e->getMessage());
            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error,
        ];
    }

    public function deleteDeliveryOrder($farmId, $id)
    {
        $error = false;

        try {
            DB::beginTransaction();
            $deliveryOrder = QurbanDeliveryOrderH::where('farm_id', $farmId)->findOrFail($id);

            $deliveryInstructionD = QurbanDeliveryInstructionD::where('qurban_delivery_order_h_id', $deliveryOrder->id)->first();


            if ($deliveryInstructionD) {

                $deliveryInstruction = $deliveryInstructionD->qurbanDeliveryInstructionH;

                if ($deliveryInstruction->status == 'in_delivery' || $deliveryInstruction->status == 'delivered') {
                    throw new \Exception("Cannot delete delivery order that is in delivery or delivered");
                }

                // Hapus instruksi pengiriman terkait
                $deliveryInstructionD->delete();
            }

            foreach ($deliveryOrder->qurbanDeliveryOrderD as $detail) {
                // Hapus detail surat jalan
                $detail->delete();
            }

            $deliveryOrder->delete();

            // Commit transaksi
            DB::commit();


        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'error' => $error
        ];
    }

}
