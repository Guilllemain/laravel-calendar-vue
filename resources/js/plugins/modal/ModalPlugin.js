import Component from './Component.vue'

const Plugin = {
    install: function(Vue, options = {}) {
        Vue.component(options.name || 'modal', Component)
        
        this.EventBus = new Vue()

        Vue.prototype.$modal = {
            show(params) {
                Plugin.EventBus.$emit('show', params)
            },
            hide(params) {
                Plugin.EventBus.$emit('hide', params)
            }
        }
    }
}

export default Plugin