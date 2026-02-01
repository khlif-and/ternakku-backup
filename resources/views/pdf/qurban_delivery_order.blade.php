<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengirimakn Ternak Qurban</title>
    <x-pdf.style />
</head>

<body>

    <x-pdf.header 
        :regencyName="$deliveryOrder->farm->farmDetail->region->regency_name ?? ''"
        :farmName="$deliveryOrder->farm->name"
        :farmAddress="$deliveryOrder->farm->farmDetail->address_line ?? 'Alamat Farm Belum Diisi'"
    />

    {{-- Judul Khusus Qurban --}}
    <div class="title-container">
        <div class="title-text">SURAT JALAN / PENGIRIMAN HEWAN QURBAN</div>
        <div class="title-number">Nomor : {{ $deliveryOrder->transaction_number }}</div>
    </div>

    <x-pdf.details-table 
        title="Yang bertanda tangan di bawah ini :"
        :data="[
            'Nama Pengemudi' => $deliveryOrder->qurbanDeliveryInstructionD?->qurbanDeliveryInstructionH?->driver?->name ?? '.........................',
            'No. Polisi' => $deliveryOrder->qurbanDeliveryInstructionD?->qurbanDeliveryInstructionH?->fleet?->police_number ?? '-',
            'Jenis Kendaraan' => $deliveryOrder->qurbanDeliveryInstructionD?->qurbanDeliveryInstructionH?->fleet?->name ?? '-'
        ]"
    />

    <x-pdf.recipient-details 
        :name="$deliveryOrder->qurbanSaleLivestockH?->qurbanCustomer?->user?->name ?? '-'"
        :destination="$deliveryOrder->qurbanCustomerAddress ? ($deliveryOrder->qurbanCustomerAddress->region ? 'Kec. ' . $deliveryOrder->qurbanCustomerAddress->region->district_name . ' - ' . $deliveryOrder->qurbanCustomerAddress->region->regency_name : '-') : '-'"
        :policeNumber="'-'" 
        :date="\Carbon\Carbon::parse($deliveryOrder->transaction_date)->translatedFormat('l / d F Y')"
    />

    <x-pdf.livestock-table :items="$deliveryOrder->qurbanDeliveryOrderD" />

    <x-pdf.notes 
        :content="[
            'Ternak tersebut berasal dari ' . $deliveryOrder->farm->name . '.',
            'Ternak tersebut untuk keperluan Ibadah Qurban.'
        ]"
    />

    <x-pdf.signature 
        :location="$deliveryOrder->farm->farmDetail->region->regency_name ?? 'Tempat'"
        :date="date('d F Y')"
        :signerName="$deliveryOrder->farm->name"
    />

</body>

</html>