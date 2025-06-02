<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .bordered th,
        .bordered td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .no-border {
            border: none;
        }

        .signature td {
            height: 60px;
            text-align: center;
            vertical-align: bottom;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td style="width: 60%;">
                <div class="text-bold"><h3 style="margin-bottom:5px">{{ $deliveryOrder->farm->name }}</h3></div>
                <div>{{  $deliveryOrder->farm->farmDetail->region->regency_name }}</div>
            </td>
        </tr>
    </table>

    <h2 style="margin-top: 20px;">Surat Jalan</h2>

    <table style="margin-bottom: 10px;">
        <tr>
            <td style="width: 10%;">No. Surat Jalan</td>
            <td style="width: 1%;">:</td>
            <td style="width: 60%;">{{ $deliveryOrder->transaction_number }}</td>

            <td style="width: 29%;">Kepada Yth. :</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{  $deliveryOrder->transaction_date }}</td>
            <td>Bapak/Ibu {{ $deliveryOrder->qurbanSaleLivestockH?->qurbanCustomer?->user?->name }}</td>
        </tr>
        <tr>
            <td>Kendaraan</td>
            <td>:</td>
            <td>
                {{ $deliveryOrder->qurbanDeliveryInstructionD?->qurbanDeliveryInstructionH?->fleet?->name }} / 
                {{ $deliveryOrder->qurbanDeliveryInstructionD?->qurbanDeliveryInstructionH?->fleet?->police_number }} / 
                {{ $deliveryOrder->qurbanDeliveryInstructionD?->qurbanDeliveryInstructionH?->driver?->name }}
            </td>

            <td></td>
        </tr>
    </table>

    <table class="bordered">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 25%;">Identitas Ternak</th>
                <th style="width: 25%;">Jenis Ternak</th>
                <th style="width: 20%;">Penerima</th>
                <th style="width: 25%;">Alamat</th>
            </tr>
        </thead>

        @foreach ($deliveryOrder->qurbanDeliveryOrderD as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->livestock?->eartag_number }}</td>
                <td>{{ $item->livestock?->livestockType?->name }} - {{ $item->livestock?->livestockBreed?->name }} -   {{ $item->livestock?->livestockSex?->name }}</td>
                <td>{{ $item->livestock?->qurbanSaleLivestockD?->qurbanCustomerAddress?->name }}</td>
                <td>{{ $item->livestock?->qurbanSaleLivestockD?->qurbanCustomerAddress?->fullAddress() }}</td>
            </tr>
        @endforeach

    </table>

    <br><br>

    <table class="signature">
        <tr>
            <td style="width: 33%;">( ..... ..... ..... )<br>Admin</td>
            <td style="width: 33%;">( ..... ..... ..... )<br>Pengemudi</td>
            <td style="width: 33%;">( ..... ..... ..... )<br>Penerima</td>
        </tr>
    </table>

</body>
</html>
