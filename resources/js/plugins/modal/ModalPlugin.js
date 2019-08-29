import Component from './Component.vue'

const Plugin = {
    install: function(Vue, options = {}) {
        Vue.component(options.name || 'modal', Component)
        
        Plugin.EventBus = new Vue()

        Vue.prototype.$modal = {
            show(name) {
                Plugin.EventBus.$emit('show', name)
            },
            hide(name) {
                Plugin.EventBus.$emit('hide', name)
            }
        }
    }
}

export default Plugin