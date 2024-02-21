import $ from 'jquery'

import './glide.css'

import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster'

import Vue from 'vue'
import List from './components/List.vue'

import SweepBright from '../../../../../dist/wp-sweepbright-public'
import WPSweepBright from '../../../_shared/WPSweepBright'

Vue.use(SweepBright)
Vue.use(WPSweepBright)

$('.list-default').each((index, el) => {
  Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyDeE8uomuOzvqJ43ULf_gJLrVj3vJWb7uo',
      libraries: 'places',
    },
  })
  Vue.component('GmapCluster', GmapCluster)

  Vue.config.productionTip = false
  Vue.config.devtools = false

  new Vue({
    el: `.${el.className}`,
    render: (h) =>
      h(List, {
        props: {
          component: $(el).data('component'),
        },
      }),
  })
})
