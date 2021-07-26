<template>
  <div class="relative">
    <div class="aspect-ratio-16/9"></div>
    <GmapMap
      ref="gmap"
      :options="options"
      class="top-0 left-0 w-full h-full"
      :center="center"
      :zoom="7"
      @click="closeEstate"
    >
      <GmapCluster
        :zoom-on-click="true"
        :styles="clusterStyle"
        v-if="markerCluster"
      >
        <gmap-marker
          ref="marker"
          :key="index"
          v-for="(m, index) in markerLocations"
          :position="m.position"
          :icon="m.icon"
          @click="openEstate(m)"
        ></gmap-marker>
      </GmapCluster>

      <gmap-info-window
        :options="infoOptions"
        :position="infoWindowPos"
        :opened="infoWinOpen"
        v-if="infoWinOpen"
        @closeclick="closeEstate"
      >
        <a
          href="#"
          v-if="infoWindowData.location"
          @click.prevent="openWindow(infoWindowData)"
          class="block max-w-xs"
        >
          <div class="relative">
            <p
              v-if="
                infoWindowData.status === 'sold' ||
                infoWindowData.status === 'rented' ||
                infoWindowData.status === 'option' ||
                infoWindowData.status === 'under_contract'
              "
              class="absolute top-0 right-0 z-10 px-2 py-1 m-2 text-sm tracking-widest text-white uppercase bg-black"
            >
              {{ data.locale[lang].status[infoWindowData.status] }}
            </p>
            <div class="aspect-ratio-16/9"></div>
            <img
              :src="infoWindowData.image"
              class="absolute top-0 left-0 object-cover object-center w-full h-full"
            />
          </div>
          <div class="p-5 text-white bg-black">
            <p class="font-heading">
              {{ infoWindowData.location.street }},
              {{ infoWindowData.location.city }}
            </p>
            <p
              class="mt-1 font-medium"
              v-if="infoWindowData.status === 'available'"
            >
              {{ $getUnits(lang).currency }}
              {{
                $formatNumber(
                  parseInt(infoWindowData.price),
                  $getNumberFormat(lang)
                )
              }}
            </p>
          </div>
        </a>
      </gmap-info-window>
    </GmapMap>
  </div>
</template>

<script>
/* global google */

export default {
  props: ["markerData", "component"],
  components: {},
  watch: {
    markerData() {
      this.renderMarkers();
    },
  },
  data() {
    return {
      theme: window.theme,
      data: window[this.component],
      lang: window.lang,
      markerLocations: [],
      markerActive: `/wp-content/plugins/wp-sweepbright/blocks/sweepbright/properties/default/${require("../images/marker-active.svg")}`,
      markerInactive: `/wp-content/plugins/wp-sweepbright/blocks/sweepbright/properties/default/${require("../images/marker-inactive.svg")}`,
      markerCluster: true,
      infoWinOpen: true,
      infoWindowPos: null,
      infoWindowData: {},
      infoOptions: {
        pixelOffset: new google.maps.Size(0, -55),
      },
      clusterStyle: [
        {
          textColor: "white",
          url: `/wp-content/plugins/wp-sweepbright/blocks/sweepbright/properties/default/${require("../images/cluster.svg")}`,
          height: 30,
          width: 30,
          textSize: 14,
          fontFamily: "Helvetica, Arial, sans-serif",
          fontWeight: "500",
        },
      ],
      options: {
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false,
        disableDefaultUi: true,
        styles: [
          {
            featureType: "water",
            elementType: "geometry",
            stylers: [{ color: "#e9e9e9", }, { lightness: 17, }],
          },
          {
            featureType: "landscape",
            elementType: "geometry",
            stylers: [{ color: "#f5f5f5", }, { lightness: 20, }],
          },
          {
            featureType: "road.highway",
            elementType: "geometry.fill",
            stylers: [{ color: "#ffffff", }, { lightness: 17, }],
          },
          {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [{ color: "#ffffff", }, { lightness: 29, }, { weight: 0.2, }],
          },
          {
            featureType: "road.arterial",
            elementType: "geometry",
            stylers: [{ color: "#ffffff", }, { lightness: 18, }],
          },
          {
            featureType: "road.local",
            elementType: "geometry",
            stylers: [{ color: "#ffffff", }, { lightness: 16, }],
          },
          {
            featureType: "poi",
            elementType: "geometry",
            stylers: [{ color: "#f5f5f5", }, { lightness: 21, }],
          },
          {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{ color: "#dedede", }, { lightness: 21, }],
          },
          {
            elementType: "labels.text.stroke",
            stylers: [
              { visibility: "on", },
              { color: "#ffffff", },
              { lightness: 16, },
            ],
          },
          {
            elementType: "labels.text.fill",
            stylers: [
              { saturation: 36, },
              { color: "#333333", },
              { lightness: 40, },
            ],
          },
          { elementType: "labels.icon", stylers: [{ visibility: "off", }], },
          {
            featureType: "transit",
            elementType: "geometry",
            stylers: [{ color: "#f2f2f2", }, { lightness: 19, }],
          },
          {
            featureType: "administrative",
            elementType: "geometry.fill",
            stylers: [{ color: "#fefefe", }, { lightness: 20, }],
          },
          {
            featureType: "administrative",
            elementType: "geometry.stroke",
            stylers: [{ color: "#fefefe", }, { lightness: 17, }, { weight: 1.2, }],
          },
        ],
      },
      center: {
        lat: 50.0564426,
        lng: 3.7328107,
      },
    };
  },
  methods: {
    openWindow(data) {
      if (data.status === "available") {
        window.location.href = data.permalink;
      } else {
        this.$bus.$emit("openModal", data);
      }
    },
    openEstate(marker) {
      this.infoWinOpen = true;
      this.infoWindowPos = {
        lat: Number(marker.position.lat),
        lng: Number(marker.position.lng),
      };
      this.infoWindowData = marker.data;
      this.setMarkerIcon(marker);
    },
    closeEstate() {
      this.infoWinOpen = false;
      this.resetMarkers();
    },
    resetMarkers() {
      this.markerLocations.forEach((marker) => {
        marker.icon = this.markerInactive;
      });
    },
    setMarkerIcon(marker) {
      this.resetMarkers();
      marker.icon = this.markerActive;
    },
    resetMap() {
      this.infoWinOpen = false;
      if (this.markerData.length === 0) {
        this.$refs.gmap.$mapPromise.then(() => {
          this.$refs.gmap.$mapObject.setCenter({
            lat: this.center.lat,
            lng: this.center.lng,
          });
          this.$refs.gmap.$mapObject.setZoom(7);
        });
      } else {
        this.markerCluster = false;
        this.$nextTick(() => {
          this.markerCluster = true;
        });
      }
    },
    centerMap() {
      this.$refs.gmap.$mapPromise.then(() => {
        const bounds = new google.maps.LatLngBounds();
        for (const m of this.markerLocations) {
          bounds.extend(m.position);
        }
        this.$refs.gmap.$mapObject.fitBounds(bounds);
      });
    },
    drawMarkers() {
      this.markerLocations = [];
      this.markerData.forEach((marker) => {
        this.markerLocations.push({
          position: {
            lat: Number(marker.location.latitude),
            lng: Number(marker.location.longitude),
          },
          icon: this.markerInactive,
          data: marker,
        });
      });
    },
    renderMarkers() {
      this.drawMarkers();
      if (this.markerData.length > 0) {
        this.centerMap();
      }
      this.resetMap();
    },
  },
  mounted() {
    this.renderMarkers();

    this.$bus.$on("mode", (mode) => {
      if (mode === "map") {
        this.renderMarkers();
      }
    });
  },
};
</script>

<style>
.vue-map-container {
  position: absolute;
}

.gm-style .gm-style-iw-d {
  @apply overflow-hidden !important;
}

.gm-style .gm-style-iw-c {
  @apply p-0 bg-transparent rounded-none shadow-none text-base;
}

.gm-style .gm-style-iw-c button {
  @apply hidden !important;
}

.gm-style .gm-style-iw-t::after {
  margin-top: -2px !important;
  background: black;
  @apply shadow-none !important;
}
</style>
