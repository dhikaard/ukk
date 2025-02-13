<template>
  <div class="surface-section">
    <!-- Hero Section -->
    <div class="hero-section relative">
      <div class="w-full min-h-screen flex align-items-center" >
      <div class="px-4 py-8 md:px-6 lg:px-8 w-full">
          <div class="text-center">
            <h1 class="text-white font-bold text-6xl mb-4">Amfibi Outdoor</h1>
            <p class="text-white font-medium text-2xl mb-6">Perlengkapan Outdoor Berkualitas untuk Petualanganmu</p>
            <Button 
              label="Lihat Produk Kami" 
              icon="pi pi-arrow-down" 
              class="p-button-lg" 
              @click="scrollToProducts" 
            />
          </div>
        </div>
      </div>
      <WaveDivider fill-color="var(--surface-card)" />
    </div>
  
    <!-- Featured Products -->
    <div id="featured-products" class="surface-card relative">
      <div class="py-8 px-4 md:px-6 lg:px-8">
        <div class="text-center mb-6">
          <h2 class="text-900 font-bold text-4xl mb-3">Produk Unggulan</h2>
          <p class="text-600 font-medium text-xl">Peralatan Terbaik untuk Kegiatan Outdoor Anda</p>
        </div>
        
        <div class="grid">
          <div v-for="product in featuredProducts" :key="product.id" class="col-12 md:col-4 p-3">
            <div class="surface-card p-4 border-round shadow-2 cursor-pointer transition-colors transition-duration-300 hover:surface-hover"
                 @click="showDetail(product)">
              <img :src="product.image" :alt="product.name" class="w-full border-round mb-3" style="height: 200px; object-fit: cover;">
              <div class="text-900 font-medium text-xl mb-2">{{ product.name }}</div>
              <div class="text-600 mb-3">{{ product.category }}</div>
              <div class="flex align-items-center justify-content-between">
                <span class="text-900 font-medium text-xl">{{ toCurrencyLocale(product.price) }}/hari</span>
                <Tag :value="product.inventoryStatus === 'INSTOCK' ? 'Tersedia' : 'Kosong'"
                     :severity="getSeverity(product)" />
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex justify-content-center mt-4">
          <Button label="Lihat Semua Produk" icon="pi pi-arrow-right" outlined @click="router.push('/rent')" />
        </div>
      </div>
    </div>
  
      <!-- Benefits Section -->
      <div class="benefits-section relative">
      <WaveDivider fill-color="var(--surface-card)" flip />
      <div class="py-8 px-4 md:px-6 lg:px-8">
        <div class="text-center mb-6">
          <h2 class="text-white font-bold text-4xl mb-3">Mengapa Sewa di Kami?</h2>
          <p class="text-white font-medium text-xl">Keuntungan Menyewa Peralatan di Amfibi Outdoor</p>
        </div>
        
        <div class="grid">
          <div v-for="(benefit, index) in benefits" :key="index" class="col-12 md:col-3 p-3">
            <div class="surface-card p-4 border-round shadow-2 h-full hover:shadow-8 transition-all transition-duration-300">
              <i :class="benefit.icon + ' text-4xl text-primary mb-3'"></i>
              <div class="text-900 font-medium text-xl mb-3">{{ benefit.title }}</div>
              <p class="text-600 line-height-3">{{ benefit.description }}</p>
            </div>
          </div>
        </div>
      </div>
      <WaveDivider fill-color="var(--surface-ground)" />
    </div>
  
    
    <!-- Terms Section -->
    <div class="surface-ground relative">
      
      <div class="surface-ground py-8 px-4 md:px-6 lg:px-8">
        <WaveDivider fill-color="var(--surface-card)" />
        <div class="text-center mb-6">
          <h2 class="text-900 font-bold text-4xl mb-3">Syarat & Ketentuan</h2>
          <p class="text-600 font-medium text-xl">Informasi Penting untuk Penyewaan</p>
        </div>
        
        <div class="grid">
          <div v-for="(term, index) in terms" :key="index" class="col-12 md:col-4 p-3">
            <div class="surface-card p-4 border-round shadow-2 h-full">
              <i :class="term.icon + ' text-4xl text-primary mb-3'"></i>
              <div class="text-900 font-medium text-xl mb-3">{{ term.title }}</div>
              <p class="text-600 line-height-3">{{ term.description }}</p>
            </div>
          </div>
        </div>
    
        <div class="flex justify-content-center mt-4">
          <Button label="Lihat Detail Ketentuan" icon="pi pi-external-link" text @click="router.push('/terms')" />
        </div>
      </div>
    </div>
    
    <!-- Location Section -->
    <div class="surface-card relative">
      <WaveDivider fill-color="var(--surface-card)" />
      <div class="py-8 px-4 md:px-6 lg:px-8">
        <div class="text-center mb-6">
          <h2 class="text-900 font-bold text-4xl mb-3">Kunjungi Toko Kami</h2>
        </div>
        
        <div class="surface-card p-4 border-round shadow-2">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4058310.9964663917!2d105.93011345000001!3d-6.622766185472307!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b944ca13d91%3A0xd632b5339993b2b1!2sSewa%20Tenda%20Semarang%20Akasa%20Adv%20Rental%20Alat%20Outdoor%20Ngaliyan!5e0!3m2!1sid!2sid!4v1738499856253!5m2!1sid!2sid"
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
    
        <div class="mt-4 text-center">
          <p class="text-600 mb-3">Jl. Bukit Beringin Bar., Tambakaji, Kec. Ngaliyan, Kota Semarang</p>
          <Button label="Petunjuk Arah" icon="pi pi-map-marker" @click="openMaps" />
        </div>
      </div>
    </div>
  </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import { useRentViewStore } from '@/stores/rent-view.store';
  import { toCurrencyLocale } from '@/utils/currency';
  import WaveDivider from '@/components/WaveDivider.vue';
  
  const router = useRouter();
  const store = useRentViewStore();
  const featuredProducts = ref([]);
  
  const benefits = [
    {
      icon: 'pi pi-money-bill',
      title: 'Hemat Biaya',
      description: 'Tidak perlu membeli peralatan mahal untuk kegiatan outdoor Anda'
    },
    {
      icon: 'pi pi-check-circle',
      title: 'Peralatan Berkualitas',
      description: 'Semua peralatan terawat dan selalu dicek sebelum disewakan'
    },
    {
      icon: 'pi pi-sync',
      title: 'Fleksibel',
      description: 'Sewa sesuai kebutuhan, tidak perlu menyimpan peralatan'
    },
    {
      icon: 'pi pi-users',
      title: 'Konsultasi Gratis',
      description: 'Tim kami siap membantu memilih peralatan yang tepat'
    }
  ];
  
  
  const terms = [
    {
      icon: 'pi pi-clock',
      title: 'Durasi Sewa',
      description: 'Minimum sewa 1 hari dengan pengembalian maksimal pukul 22:00 WIB.'
    },
    {
      icon: 'pi pi-id-card',
      title: 'Persyaratan',
      description: 'Kartu identitas (KTP/SIM) dan jaminan untuk proses penyewaan.'
    },
    {
      icon: 'pi pi-money-bill',
      title: 'Pembayaran',
      description: 'Pembayaran dilakukan di awal dengan minimal DP 50% dari total sewa.'
    }
  ];
  
  onMounted(async () => {
    // Get featured products (maybe first 3 products)
    await store.getProducts();
    featuredProducts.value = store.products.slice(0, 3);
  });
  
  const showDetail = (product) => {
    const slug = product.name
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '');
  
    router.push({
      name: 'detailProduct',
      params: {
        code: product.code,
        slug: slug
      }
    });
  };
  
  const openMaps = () => {
    window.open('https://www.google.com/maps/dir//Sewa+Tenda+Semarang+Akasa+Adv+Rental+Alat+Outdoor+Ngaliyan+Gg.+Ciremai+RT.02%2FRW.01,+Kedungpane+Kec.+Ngaliyan,+Kota+Semarang,+Jawa+Tengah+50189/@-7.0207142,110.3377604,9z/data=!4m5!4m4!1m0!1m2!1m1!1s0x2e708b944ca13d91:0xd632b5339993b2b1', '_blank');
  };
  
  const getSeverity = (product) => {
    return product.inventoryStatus === 'INSTOCK' ? 'success' : 'danger';
  };
  
  const scrollToProducts = () => {
    document.getElementById('featured-products').scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    });
  };
  </script>
  
  <style scoped>
  .benefits-section {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                url('../assets/img/mountains-views2.jpg') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    position: relative;
    z-index: 1;
    margin-top: -2px;
    margin-bottom: -2px;
  }
  
  .benefits-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-900);
    opacity: 0.1;
    z-index: -1;
  }
  
  .hero-section {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                url('../assets/img/mountains-views1.jpg') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    position: relative;
    z-index: 1;
    margin-top: -2px;
    margin-bottom: -2px;
  }
  
  .surface-card, .surface-ground {
    position: relative;
    z-index: 0;
  }
  
  /* Add more custom styles as needed */
  

  </style>
  