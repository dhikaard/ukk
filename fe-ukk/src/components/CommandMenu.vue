<template>
    <Dialog :visible="visible" @update:visible="onHide" dismissableMask modal :showHeader="false" class="border-round-lg"
        :breakpoints="{ '960px': '75vw', '640px': '80vw' }" :style="{ width: '50vw', minWidth: '300px' }"
        contentClass="border-round-lg p-0" appendTo="body">
        <div class="p-3">
            <!-- Search Input -->
            <div class="mb-3 surface-100 border-round p-2">
                <div class="flex align-items-center w-full">
                    <IconField iconPosition="left" class="w-full surface-border">
                        <InputIcon :class="['pi', searchStore.loading['search'] ? 'pi-spin pi-spinner' : 'pi-search']"></InputIcon>
                        <InputText 
                            type="text" 
                            placeholder="Ketik untuk mencari..." 
                            autofocus
                            v-model="search" 
                            class="w-full border-none surface-100" 
                            @input="handleSearch" 
                        />
                    </IconField>
                </div>
            </div>

            <!-- Quick Actions -->
            <div v-if="!search" class="quick-actions">
                <h5 class="text-sm font-semibold text-700 mb-2">Menu Cepat</h5>
                <div v-for="action in context.articles" 
                     :key="action.name"
                     class="action-item"
                     @click="handleArticleClick(action)">
                    <div class="flex align-items-center">
                        <i :class="[action.iconClass, 'mr-2']"></i>
                        <div>
                            <span class="font-medium">{{ action.name }}</span>
                            <small class="text-600 block">{{ action.description }}</small>
                        </div>
                    </div>
                    <small class="text-600">{{ action.iconEnd }}</small>
                </div>
            </div>

            <!-- Search Results -->
            <template v-else>
                <!-- Items -->
                <div v-if="searchStore.results.items?.length" class="search-section">
                    <h5 class="text-sm font-semibold text-700 mb-2">Barang</h5>
                    <div v-for="item in searchStore.results.items" 
                         :key="item.title"
                         class="result-item"
                         @click="navigateToItem(item)">
                        <div class="flex align-items-center">
                            <i class="pi pi-box mr-2"></i>
                            <div>
                                <span class="font-medium">{{ item.title }}</span>
                                <small class="text-600 block">
                                    {{ item.details.Kategori }} | {{ item.details.Harga }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions -->
                <div v-if="searchStore.results.transactions?.length" class="search-section">
                    <h5 class="text-sm font-semibold text-700 mb-2">Transaksi</h5>
                    <div v-for="trx in searchStore.results.transactions" 
                         :key="trx.title"
                         class="result-item"
                         @click="navigateToTransaction(trx)">
                        <div class="flex align-items-center">
                            <i class="pi pi-receipt mr-2"></i>
                            <div>
                                <span class="font-medium">{{ trx.title }}</span>
                                <small class="text-600 block">
                                    Status: {{ trx.details.Status }} | {{ trx.details.Total }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Results -->
                <div v-if="search && !searchStore.loading['search'] && !hasResults" 
                     class="p-4 text-center text-500">
                    <i class="pi pi-search-minus text-xl mb-2"></i>
                    <p class="m-0">Tidak ada hasil untuk "{{ search }}"</p>
                </div>
            </template>
        </div>
    </Dialog>
</template>

<style scoped>
.command-menu-dialog {
    max-width: 90vw;
}

.action-item,
.result-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.action-item:hover,
.result-item:hover {
    background-color: var(--surface-100);
}

.search-section {
    margin-bottom: 1.5rem;
}

:deep(.p-dialog-content) {
    padding: 0 !important;
}
</style>

<script setup>
import { computed, ref, watch, onMounted, onUnmounted } from 'vue';
import { useCommandMenuStore } from '@/stores/command-menu.store';
import { useSearchStore } from '@/stores/search.store';
import { useRouter } from 'vue-router';

const context = useCommandMenuStore();
const searchStore = useSearchStore();
const router = useRouter();
const search = ref('');

// Debounced search
watch(search, async (newValue) => {
    if (newValue.length >= 2) {
        await searchStore.search(newValue);
    } else {
        searchStore.results = { 
            items: [], 
            transactions: []  // Make sure to clear both
        };
    }
}, { debounce: 300 });

function navigateToItem(item) {
    router.push({ 
        name: 'item-detail', 
        params: { code: item.details.Kode } 
    });
    context.visible = false;
}

function navigateToTransaction(trx) {
    router.push({ 
        name: 'history',
        query: { trx: trx.title }
    });
    context.visible = false;
}

const filteredArticles = computed(() => {
  return context.articles.filter(article =>
    article.name.toLowerCase().includes(search.value.toLowerCase()) ||
    article.description.toLowerCase().includes(search.value.toLowerCase())
  );
});

function handleArticleClick(article) {
  if (article.action && typeof context[article.action] === 'function') {
    context[article.action]();
  } else {
    context.navigateTo(article.route);
  }
}

function handleKeyDown(event) {
  if (event.ctrlKey) {
    event.preventDefault();

    switch (event.key.toLowerCase()) {
      case 's':
        context.navigateTo('home'); 
        break;
      case 'h':
        context.navigateTo('history'); 
        break;
      case 'p':
        context.navigateTo(''); 
        break;
      case 'c':
        context.navigateTo('terms'); 
        break;
    }
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});

// Add computed for better readability
const hasResults = computed(() => {
    return searchStore.results.items?.length > 0 || 
           searchStore.results.transactions?.length > 0;
});
</script>

