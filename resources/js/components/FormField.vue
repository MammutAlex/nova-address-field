<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">

            <vue-google-autocomplete
                ref="address"
                :id="field.attribute"
                :dusk="field.attribute"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                :country="field.countries"
                v-on:placechanged="getAddressData">
            </vue-google-autocomplete>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import VueGoogleAutocomplete from 'vue-google-autocomplete'

export default {

    components: { VueGoogleAutocomplete },

    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: function () {
        return {
            address: '',
            addressData: {
              latitude: this.field.lat || '',
              longitude: this.field.lng || '',
              address: ''
            },
            marker: null,
            geocoder: new google.maps.Geocoder,
            showLngLat: this.field.withLatLng || false,
        }
    },

    methods: {
        getAddressData: function (addressData, placeResultData, id) {
            this.addressData.latitude = addressData.latitude;
            this.addressData.longitude = addressData.longitude;
            this.addressData.formatted_address = placeResultData.formatted_address;
            this.$emit('addressChanged', this.addressData)
        },

        geocode(latLng) {
            let _this = this
            this.geocoder.geocode({'location': latLng}, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        _this.addressData.latitude = parseFloat(latLng.lat().toFixed(6))
                        _this.addressData.longitude = parseFloat(latLng.lng().toFixed(6))
                        _this.addressData.formatted_address = results[0].formatted_address
                        _this.$refs.address.update(results[0].formatted_address);
                        _this.$emit('addressChanged', _this.addressData)
                    } else {
                        //window.alert('No results found');
                    }
                } else {
                    _this.addressData.latitude = null
                    _this.addressData.longitude = null
                    _this.addressData.formatted_address = null
                    _this.$refs.address.update('');
                    _this.$emit('addressChanged', _this.addressData)
                    console.log(status);
                }
            });
        },

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
            if(this.value) {
                this.addressData = JSON.parse(this.value)
                this.$refs.address.update(this.addressData.formatted_address);
            }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },

    watch: {
        'addressData' : {
            handler: function (newAddressData) {
                this.value = JSON.stringify(newAddressData)
            },
            deep: true
        }
    }
}
</script>
