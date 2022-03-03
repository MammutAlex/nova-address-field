<template>
    <span class="text-90">{{addressData.formatted_address}}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    computed: {
        addressData: function () {
            return JSON.parse(this.field.value)
        }
    }
}
</script>
