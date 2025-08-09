@php($farmId = request()->route('farm_id'))
<div class="px-8 py-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Detail Jadwal Treatment Individu</h1>
        <p class="mt-1 text-sm text-gray-500">Ringkasan transaksi, ternak, obat/tindakan, serta catatan.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.care-livestock.treatment-schedule-individu.edit', ['farm_id' => $farmId, 'id' => $treatmentScheduleIndividu->id]) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
           Edit
        </a>
        <a href="{{ route('admin.care-livestock.treatment-schedule-individu.index', ['farm_id' => $farmId]) }}"
           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
           Kembali
        </a>
    </div>
</div>

@if (session('success'))
    <div class="px-8">
        <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="px-8 py-4 grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg border p-4 space-y-3">
            <div>
                <div class="text-xs text-gray-500">Tgl Transaksi</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($treatmentScheduleIndividu->treatmentSchedule)->transaction_date
                        ? \Carbon\Carbon::parse($treatmentScheduleIndividu->treatmentSchedule->transaction_date)->format('d M Y')
                        : '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Tgl Jadwal</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ $treatmentScheduleIndividu->schedule_date
                        ? \Carbon\Carbon::parse($treatmentScheduleIndividu->schedule_date)->format('d M Y')
                        : '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Eartag</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{
                        optional($treatmentScheduleIndividu->livestock)->eartag
                        ?? optional($treatmentScheduleIndividu->livestock)->eartag_number
                        ?? optional($treatmentScheduleIndividu->livestock)->ear_tag
                        ?? optional($treatmentScheduleIndividu->livestock)->tag
                        ?? optional($treatmentScheduleIndividu->livestock)->code
                        ?? optional($treatmentScheduleIndividu->livestock)->rfid_number
                        ?? '-'
                    }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Nama Ternak</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{
                        optional($treatmentScheduleIndividu->livestock)->name
                        ?? optional($treatmentScheduleIndividu->livestock)->nama
                        ?? optional($treatmentScheduleIndividu->livestock)->display_name
                        ?? optional($treatmentScheduleIndividu->livestock)->nickname
                        ?? '-'
                    }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Catatan</div>
                <div class="text-sm text-gray-800">
                    {{ $treatmentScheduleIndividu->notes ?: '-' }}
                </div>
            </div>

            <form action="{{ route('admin.care-livestock.treatment-schedule-individu.destroy', ['farm_id' => $farmId, 'id' => $treatmentScheduleIndividu->id]) }}"
                  method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" class="pt-2">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold rounded-lg px-4 py-2 text-sm transition-all">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
{{-- Obat (opsional) --}}
<div class="bg-white rounded-lg border overflow-hidden">
    <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Obat</div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Obat</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Satuan</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Qty/Unit</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(
                    !empty($treatmentScheduleIndividu->medicine_name) ||
                    !empty($treatmentScheduleIndividu->medicine_unit) ||
                    !is_null($treatmentScheduleIndividu->medicine_qty_per_unit)
                )
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $treatmentScheduleIndividu->medicine_name ?: '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $treatmentScheduleIndividu->medicine_unit ?: '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 text-right">
                            {{ !is_null($treatmentScheduleIndividu->medicine_qty_per_unit)
                                ? number_format($treatmentScheduleIndividu->medicine_qty_per_unit, 2, ',', '.')
                                : '-' }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">Tidak ada data obat.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


        {{-- Tindakan (opsional) --}}
        <div class="bg-white rounded-lg border overflow-hidden">
            <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Tindakan</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(!empty($treatmentScheduleIndividu->treatment_name))
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $treatmentScheduleIndividu->treatment_name }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="px-4 py-6 text-center text-gray-500">Tidak ada tindakan.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
