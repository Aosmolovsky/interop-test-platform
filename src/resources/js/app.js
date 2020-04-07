import Vue from 'vue';
import '@fancyapps/fancybox';
$.fancybox.defaults.touch = false;

import {
    AlertPlugin,
    DropdownPlugin,
    NavPlugin,
    NavbarPlugin,
    CollapsePlugin,
    ProgressPlugin,
    VBTooltipPlugin,
} from 'bootstrap-vue';

import hljs from 'highlight.js';
import Clipboard from 'clipboard';

Vue.use(AlertPlugin);
Vue.use(DropdownPlugin);
Vue.use(NavPlugin);
Vue.use(NavbarPlugin);
Vue.use(CollapsePlugin);
Vue.use(ProgressPlugin);
Vue.use(VBTooltipPlugin);

Vue.component('confirm-button', () =>
    import(/* webpackChunkName: "confirm" */ './components/ConfirmButton.vue'),
);
Vue.component('chart', () =>
    import(/* webpackChunkName: "chart" */ './components/Chart.vue'),
);
Vue.component('web-editor', () =>
    import(/* webpackChunkName: "editor" */ './components/WebEditor.vue'),
);
Vue.component('flow-chart', () =>
    import(/* webpackChunkName: "flow-chart" */ './components/FlowChart.vue'),
);
Vue.component('notification', () =>
    import(
        /* webpackChunkName: "notification" */ './components/Notification.vue'
    ),
);
Vue.component('json-tree', () =>
    import(/* webpackChunkName: "json-tree" */ './components/JsonTree.vue'),
);

const app = new Vue({
    el: '#app',
    methods: {
        toggleCheckboxes(e) {
            const btn = e.target;
            const closestParentList = btn.closest('.list-group-item');
            const checkboxes = Array.from(
                closestParentList.querySelectorAll('input[type="checkbox"]'),
            );
            const isChecked = checkboxes.every(
                (checkbox) => checkbox.checked === true,
            );

            checkboxes.forEach((checkbox) => {
                checkbox.checked = !isChecked;
            });
        },
        handleSessionComponentsSelect(e) {
            const select = e.target;
            const selectValue = select.value;
            const closestFormGroup = select.closest('.form-group');
            const input = closestFormGroup.querySelector('input.form-control');

            const isSutSelected = selectValue === '';

            input.value = selectValue;
            input.disabled = !isSutSelected;
        },
    },
});

hljs.initHighlightingOnLoad();

new Clipboard('[data-clipboard-target]');
