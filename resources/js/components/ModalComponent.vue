<template>
    <transition name="fade">
        <div v-if="isShowing" class="modal" @click="hideModal">
            <div :style="{ width: contentWidth }" @click.stop class="relative">
                <span class="close__icon" @click="hideModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon__svg">
                        <use class="text-grey fill-current" href="/svg/icons.svg#close" />
                    </svg>
                </span>
                <slot></slot>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        contentWidth: {
            required: false,
            type: String,
            default: "30rem"
        },
        isShowing: {
            required: true,
            type: Boolean,
            default: false
        } 
    },
    methods: {
        hideModal() {
            this.$emit("hideModal");
        }
    }
};
</script>

<style>
.modal {
    position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.85);
    transition: all 0.4s ease-in-out;
}
.modal__content {
    visibility: hidden;
    opacity: 0;
    background-color: white;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    animation: scaleIn 0.2s ease-in-out forwards;
}

.close__icon {
    position: absolute;
    top: 1rem;
    right: 1.2rem;
    opacity: 0.8;
    cursor: pointer;
    z-index: 20;
    transition: all 0.2s;
}

.close__icon:hover,
.close__icon:focus {
    opacity: 1;
}

.close__icon:hover {
    transform: scale(1.05);
}

.icon__svg {
    height: 1.3rem;
    width: 1.3rem;
}

.fade-enter-active {
    transition: opacity .3s;
}
.fade-leave-active {
    transition: opacity .3s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.scale-enter-active {
    animation: scaleIn 0.2s ease-in-out forwards;
}
@keyframes scaleIn {
    0% {
        transform: scale(0.75);
    }
    100% {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }
}
</style>

