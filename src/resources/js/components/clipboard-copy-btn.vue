<template>
    <button
        type="button"
        class="btn btn-secondary"
        data-clipboard-btn
        v-b-tooltip.hover.topright.viewport
        :title="title"
        :data-clipboard-target="target"
    >
        <icon v-if="showCopyIcon" name="copy" class="m-0" />
        <a v-if="copyButtonText">{{ copyButtonText }}</a>
    </button>
</template>

<script>
import Clipboard from 'clipboard';

export default {
    props: {
        showCopyIcon: {
            type: Boolean,
            default: true,
        },
        copyButtonText: {
            type: String,
        },
        data: {
            type: String,
        },
        target: {
            type: String,
        },
        title: {
            type: String,
            default: 'Copy',
        },
    },
    mounted() {
        new Clipboard(this.$el, {
            container: this.$el.parentElement,
            text: () => !this.target && this.data,
        });
    },
};
</script>
