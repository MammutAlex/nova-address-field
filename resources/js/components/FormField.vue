<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">

      <vue-google-autocomplete
        :id="field.attribute"
        :dusk="field.attribute"
        class="w-full form-control form-input form-input-bordered"
        :class="errorClasses"
        :value="addressData.name"
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
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import VueGoogleAutocomplete from 'vue-google-autocomplete'

export default {

  components: {VueGoogleAutocomplete},

  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data: function () {
    return {
      address: '',
      addressData: {
        lat: this.field.lat || '',
        lon: this.field.lng || '',
        name: this.field.name || '',
        country: this.field.country || '',
        city: this.field.city || '',
      },
      marker: null,
      geocoder: new google.maps.Geocoder,
      showLngLat: this.field.withLatLng || false,
    }
  },
  created() {
    console.log(this.value);
    this.addressData = {
      lat: this.value.lat || '',
      lon: this.value.lng || '',
      name: this.value.name || '',
      country: this.value.country || '',
      city: this.value.city || '',
    }
  },
  methods: {
    getAddressData: function (addressData, placeResultData, id) {
      this.addressData.lat = addressData.latitude;
      this.addressData.lon = addressData.longitude;
      this.addressData.city = addressData.locality;
      this.addressData.country = addressData.country;
      this.addressData.administrative_area_level_1 = addressData.administrative_area_level_1;
      this.addressData.administrative_area_level_2 = addressData.administrative_area_level_2;
      this.addressData.postal_code = addressData.postal_code;
      this.addressData.route = addressData.route;
      this.addressData.name = placeResultData.formatted_address;
      this.$emit('addressChanged', this.addressData)
      this.value = JSON.stringify(this.addressData)
    },
  },
}
</script>
