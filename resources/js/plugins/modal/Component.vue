<template>
        <transition name="fade" @after-enter="showContent = true">
            <div v-if="isVisible" class="modal" @click="showContent = false">
                <transition name="scale" @after-leave="hide">
                    <div v-if="showContent" :style="{ width: contentWidth }" @click.stop class="modal__content">
                        <span class="modal__close" @click="showContent = false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-close">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                <path d="M0 0h24v24H0z" fill="none"/>
                            </svg>
                        </span>
                        <slot></slot>
                    </div>
                </transition>
            </div>
        </transition>
</template>

<script>
import Modal from './ModalPlugin';

export default {
    props: {
        name: {
          required: true,
          type: String
        },
        contentWidth: {
            required: false,
            type: String,
            default: "30rem"
        }
    },
    data() {
        return {
            isVisible: false,
            showContent: false
        }
    },
    beforeMount() {
        Modal.EventBus.$on('show', (name) => {
            if(name !== this.name) return
            this.show()
        })
        Modal.EventBus.$on('hide', (name) => {
            if(name !== this.name) return
            this.hide()
        })
    },
    methods: {
        hide() {
            this.isVisible = false
        },
        show() {
            this.isVisible = true
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

