require('./bootstrap');

import { createApp, h } from 'vue';
import {createInertiaApp, Link} from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import VueSignaturePad from "vue-signature-pad";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

let app = createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin).use(VueSignaturePad)
            .component('inertia-link', Link)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
