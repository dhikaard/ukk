// Style
import './assets/main.css'
// import 'primeflex/themes/primeone-light.css'
import 'primeflex/primeflex.css'
import 'primeicons/primeicons.css'
import 'primevue/resources/primevue.min.css'
// import 'primevue/resources/themes/aura-light-blue/theme.css'

// Components
import PrimeVue from 'primevue/config';
import Ripple from 'primevue/ripple';
import StyleClass from 'primevue/styleclass';
import FocusTrap from 'primevue/focustrap';
import BadgeDirective from 'primevue/badgedirective';
import Tooltip from 'primevue/tooltip';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';


import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

// app.use
const app = createApp(App)
app.use(createPinia());
app.use(router);
app.use(ToastService);
app.use(PrimeVue, { ripple: true });


// app.directive
app.component('Toast', Toast);
app.directive('badge', BadgeDirective);
app.directive('styleclass', StyleClass);
app.directive('ripple', Ripple);
app.directive('focustrap', FocusTrap);
app.directive('tooltip', Tooltip);

app.mount('#app')
