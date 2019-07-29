<template>
    <transition name="slide">
        <div class="alert-flash" :class="'alert-'+level" v-show="show" role="alert" v-text="body"></div>
    </transition>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },
        created() {
            if (this.message) {
                this.flash();
            }

            window.Event.$on('flash', data => this.flash(data));
        },

        methods: {
            flash(data) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                }
 
                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(() => {
                   this.show = false;
                }, 4000);
            }
        }
    };
</script>

<style scoped>
    .alert-flash {
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
        position: fixed;
        right: 2.5rem;
        top: 2rem;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .alert-info {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }
    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    .slide-enter, .slide-leave-to {
        opacity: .2;
        transform: translateX(25rem);
    }
    .slide-enter-active, .slide-leave-active {
        transition: all .3s ease-in-out;
    }
</style>
