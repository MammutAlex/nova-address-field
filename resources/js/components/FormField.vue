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
              address: '',
              locality: '',
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
            this.addressData.locality = addressData.locality;
            this.addressData.country = addressData.country;
            this.addressData.administrative_area_level_1 = addressData.administrative_area_level_1;
            this.addressData.administrative_area_level_2 = addressData.administrative_area_level_2;
            this.addressData.postal_code = addressData.postal_code;
            this.addressData.route = addressData.route;
            this.addressData.formatted_address = placeResultData.formatted_address;
            this.$emit('addressChanged', this.addressData)
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
