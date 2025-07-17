document.addEventListener("DOMContentLoaded", function () {
    const hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const bulan = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    new AirDatepicker("#tanggal-airdatepicker", {
        autoClose: true,
        position: "top left",
        locale: {
            days: hari,
            daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
            daysMin: ["Mg", "Sn", "Sl", "Rb", "Km", "Jm", "Sb"],
            months: bulan,
            monthsShort: bulan.map((b) => b.substring(0, 3)),
            today: "Hari ini",
            clear: "Clear",
            dateFormat: "yyyy-MM-dd",
            timeFormat: "HH:mm",
            firstDay: 1,
        },
        onShow: function () {
            setTimeout(() => {
                const sel = document.querySelector(".air-datepicker-cell.-year-.-selected-");
                if (sel) sel.scrollIntoView({ block: "center" });
            }, 50);
        },
    });

    // === Kalkulasi Harga per Kepala (tanpa format) ===
    const weightInput = document.querySelector('input[name="weight"]');
    const pricePerKgInput = document.querySelector('input[name="price_per_kg"]');
    const pricePerHeadInput = document.querySelector('input[name="price_per_head"]');

    function cleanNumber(str) {
        return parseFloat(str.replace(/[^0-9.]/g, "")) || 0;
    }

    function updatePricePerHead() {
        const weight = parseFloat(weightInput.value) || 0;
        const perKg = cleanNumber(pricePerKgInput.value);
        const total = weight * perKg;
        pricePerHeadInput.value = total > 0 ? Math.round(total) : "";
    }

    weightInput?.addEventListener("input", updatePricePerHead);
    pricePerKgInput?.addEventListener("input", updatePricePerHead);

    if (pricePerHeadInput) {
        pricePerHeadInput.readOnly = true;
        pricePerHeadInput.classList.add("bg-gray-100");
    }
});
