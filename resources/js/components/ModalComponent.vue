<template>
    <transition name="fade" @after-enter="showContent = true">
        <div v-if="isShowing" class="modal" @click="showContent = false">
            <transition name="scale" @after-leave="hideModal">
                <div v-if="showContent" :style="{ width: contentWidth }" @click.stop class="modal__content">
                    <span class="modal__close" @click="showContent = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon-close">
                            <use href="/svg/icons.svg#close" />
                        </svg>
                    </span>
                    <slot></slot>
                </div>
            </transition>
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
    data() {
        return {
            showContent: false
        }
    },
    methods: {
        hideModal() {
            this.$emit("hideModal");
        }
    }
};
</script>

<style scoped>
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
    background-color: rgba(0, 0, 0, 0.75);
    transition: all 0.4s ease-in-out;
}

.modal__content {
    background-color: white;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    position: relative;
    border-radius: 3px;
}

.modal__close {
    position: absolute;
    top: 1rem;
    right: 1.2rem;
    opacity: 0.8;
    cursor: pointer;
    z-index: 20;
    transition: all 0.2s;
}

.modal__close:hover,
.modal__close:focus {
    opacity: 1;
}

.modal__close:hover {
    transform: scale(1.05);
}

.icon-close {
    height: 1.3rem;
    width: 1.3rem;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity .2s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.scale-enter-active {
    animation: scaleIn 0.2s ease-in-out;
}
.scale-leave-active {
    animation: scaleIn 0.2s ease-in-out reverse;
}
@keyframes scaleIn {
    0% {
        opacity: 0;
        visibility: hidden;
        transform: scale(0.75);
    }
    100% {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }
}
</style>

