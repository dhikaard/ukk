<template>
    <Dialog :visible="visible" @update:visible="onHide" dismissableMask modal :showHeader="false" class="border-round-lg"
        :breakpoints="{ '960px': '75vw', '640px': '80vw' }" :style="{ width: '50vw', minWidth: '300px' }"
        contentClass="border-round-lg p-0" appendTo="body">
        <div class="p-2">
            <!-- <div class="flex w-full surface-section align-items-center justify-content-between">
                <span class="p-input-icon-left w-full">
                    <IconField iconPosition="left" class="w-full">
                        <InputIcon class="pi pi-search"> </InputIcon>
                        <InputText ref="input1" type="text" placeholder="Cari sesuatu..."
                            v-model="search" class="w-full border-none shadow-none outline-none" @input="handleSearch" />
                    </IconField>
                </span>
            </div> -->
            <section class="mb-2 border-1 surface-border border-round">
                <div class="flex w-full align-items-center justify-content-between surface-overlay border-round">
                    <IconField iconPosition="left" class="w-full surface-border ">
                        <InputIcon class="pi pi-search"> </InputIcon>
                        <InputText ref="input1" type="text" placeholder="Cari sesuatu..."
                            v-model="search" class="w-full border-none shadow-none outline-none" @input="handleSearch" />
                        </IconField>
                    </div>
            </section>
            <article v-for="article in filteredArticles" :key="article.name"
                class="flex w-full justify-content-between align-items-center select-none cursor-pointer surface-border hover:surface-hover border-round-lg px-2 py-2 text-800 hover:text-primary "
                @click="handleArticleClick(article)">
                <div class="flex align-items-center">
                    <span class="border-circle w-2rem h-2rem flex align-items-center justify-content-center surface-100">
                        <i :class="article.iconClass"></i>
                    </span>
                    <div class="ml-2 flex flex-column">
                        <p class="font-semibold text-sm mt-0 mb-1">{{ article.name }}</p>
                        <div class="flex  justify-content-between">
                            <p class="font-normal text-xs text-600 mt-0 mb-0">{{ article.description }}</p>
                        </div>
                    </div>
                </div>
                <Tag v-if="article.iconEnd" class="border-1 p-1 surface-border border-round surface-100 text-600 text-xs ml-3" severity="secondary" :value="article.iconEnd"></Tag>
            </article>
        </div>
    </Dialog>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useCommandMenuStore } from '@/stores/command-menu.store';

const context = useCommandMenuStore();
const search = ref('');

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
</script>

