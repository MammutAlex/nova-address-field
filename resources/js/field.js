Nova.booting((Vue, router) => {
    Vue.component('index-address-field', require('./components/IndexField').default);
    Vue.component('detail-address-field', require('./components/DetailField').default);
    Vue.component('form-address-field', require('./components/FormField').default);
})
