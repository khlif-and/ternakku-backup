function breedDropdown() {
    return {
        open: false,
        selected: 'Pilih Ras',
        items: [],
        oldSelected: null,

init() {
    console.log('[breedDropdown] init jalan');
    this.oldSelected = this.$el.getAttribute('data-old');

    // Coba ambil dari radio
    let typeId = document.querySelector('input[name="livestock_type_id"]:checked')?.value;

    if (!typeId) {
        typeId = window.oldLivestockTypeId;
        console.log('[breedDropdown] ambil typeId dari old():', typeId);
    }

    if (typeId) {
        this.fetchRas(typeId);
    }

    // Tambah event listener global!
    document.addEventListener('livestock-type-changed', (e) => {
        this.fetchRas(e.detail);
    });
},


        async fetchRas(typeId) {
            this.selected = 'Memuat...';
            this.items = [];

            try {
                const res = await fetch(`https://feedmill.ternakku.com/api/data-master/livestock/breed?livestock_type_id=${typeId}`);
                const json = await res.json();
                this.items = json?.data || [];

                if (this.oldSelected && this.items.length) {
                    const found = this.items.find(b => b.id == this.oldSelected);
                    if (found) {
                        this.selected = found.name;
                        console.log('[breedDropdown] selected ras:', found.name);
                    } else {
                        this.selected = 'Pilih Ras';
                    }
                } else {
                    this.selected = 'Pilih Ras';
                }
            } catch (e) {
                console.error('[breedDropdown] Gagal fetch ras:', e);
                this.selected = 'Gagal memuat';
            }
        }
    };
}

document.addEventListener("alpine:init", () => {
    Alpine.data("breedDropdown", breedDropdown);
});
