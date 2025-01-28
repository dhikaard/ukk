// light theme
import './assets/main.css'
// import 'primeflex/themes/primeone-light.css'
import 'primeflex/primeflex.css'
import 'primeicons/primeicons.css'
import 'primevue/resources/primevue.min.css'
// import 'primevue/resources/themes/aura-light-blue/theme.css'

// dark theme
// import 'primevue/resources/themes/aura-dark-blue/theme.css'
// import 'primeflex/themes/primeone-dark.css'

// components
import PrimeVue from 'primevue/config';
import Ripple from 'primevue/ripple';
import StyleClass from 'primevue/styleclass';
import FocusTrap from 'primevue/focustrap';
import Badge from 'primevue/badge';
import Tooltip from 'primevue/tooltip';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

// app.use
const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(ToastService);

app.use(PrimeVue, {
    ripple: true,
    locale: {
        startsWith: 'Dimulai dengan',
        contains: 'Mengandung',
        notContains: 'Tidak mengandung',
        endsWith: 'Diakhiri dengan',
        equals: 'Sama dengan',
        notEquals: 'Tidak sama dengan',
        noFilter: 'Tidak ada filter',
        lt: 'Kurang dari',
        lte: 'Kurang dari atau sama dengan',
        gt: 'Lebih besar dari',
        gte: 'Lebih besar dari atau sama dengan',
        dateIs: 'Tanggal adalah',
        dateIsNot: 'Tanggal bukan',
        dateBefore: 'Tanggal sebelum',
        dateAfter: 'Tanggal setelah',
        clear: 'Bersihkan',
        apply: 'Terapkan',
        matchAll: 'Cocokkan semua',
        matchAny: 'Cocokkan salah satu',
        addRule: 'Tambahkan aturan',
        removeRule: 'Hapus aturan',
        accept: 'Ya',
        reject: 'Tidak',
        choose: 'Pilih',
        upload: 'Unggah',
        cancel: 'Batal',
        dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        dayNamesShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        dayNamesMin: ['M', 'S', 'S', 'R', 'K', 'J', 'S'],
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
        today: 'Hari ini',
        weekHeader: 'Minggu',
        firstDayOfWeek: 1,
        dateFormat: 'dd/mm/yy',
        weak: 'Lemah',
        medium: 'Sedang',
        strong: 'Kuat',
        passwordPrompt: 'Masukkan kata sandi',
        emptyFilterMessage: 'Tidak ditemukan',
        emptyMessage: 'Data tidak tersedia'
    }
});

app.component('Toast', Toast);

// app.directive
app.directive('badge', Badge);
app.directive('styleclass', StyleClass);
app.directive('ripple', Ripple);
app.directive('focustrap', FocusTrap);
app.directive('tooltip', Tooltip);

app.mount('#app')
