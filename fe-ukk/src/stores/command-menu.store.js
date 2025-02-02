import { defineStore } from 'pinia';
import { useRouter } from 'vue-router';

export const useCommandMenuStore = defineStore(
  'command-menu.store', {
  state: () => ({
    search: '',
    isDarkMode: false,
    articles: [
      { name: 'Sewa Barang', description: 'Cari barang untuk disewa', iconClass: 'pi pi-home', route: 'home', iconEnd:'Ctrl + S' },
      { name: 'Riwayat Sewa', description: 'Lihat riwayat barang', iconClass: 'pi pi-receipt', route: 'history', iconEnd:'Ctrl + H' },
      { name: 'Pengaturan', description: 'Ubah data pribadi', iconClass: 'pi pi-cog', route: 'settings', iconEnd:'Ctrl + S' },
      { name: 'Ketentuan', description: 'Lihat ketentuan layanan', iconClass: 'pi pi-info-circle', route: 'terms', iconEnd:'Ctrl + T' },
      { name: 'Tema', description: 'Ubah tema tampilan aplikasi', iconClass: 'pi pi-palette', action: 'switchTheme'}
    ],
    router: useRouter()
  }),

  actions: {
    navigateTo(routeName) {
      this.router.push({ name: routeName });
    },

    switchTheme() {
      this.isDarkMode = !this.isDarkMode;
      const themeLink = document.getElementById('theme-link');
      const primeflexLink = document.getElementById('primeflex-link');

      if (this.isDarkMode) {
        themeLink.href = import.meta.env.VITE_THEME_DARK;
        primeflexLink.href = import.meta.env.VITE_PRIMEFLEX_DARK;
        localStorage.setItem('theme', 'dark');
      } else {
        themeLink.href = import.meta.env.VITE_THEME_LIGHT;
        primeflexLink.href = import.meta.env.VITE_PRIMEFLEX_LIGHT;
        localStorage.setItem('theme', 'light');
      }
    }
  }
});
