<template>
  <div
    class="max-w-2xl p-10 mx-auto mt-20 bg-white border border-gray-400 rounded-md shadow-sm"
  >
    <transition name="fade" mode="out-in">
      <div class="text-center" v-if="totalSteps[0] === 1" key="1">
        <i
          class="mb-12 text-6xl leading-none text-blue-500 dashicons dashicons-admin-page"
        ></i>
        <p class="mb-4 text-3xl font-light">Welcome to the page editor</p>
        <p class="text-base text-gray-600">
          Build your own SweepBright website with ease by using a library of
          pre-made building blocks. Our intuitive visual editor allows you to
          design and personalize a website tailored to your taste.
        </p>
      </div>

      <div v-if="totalSteps[1] === 1" key="2">
        <p class="mb-6 text-3xl font-light text-center">SEO</p>

        <div class="form-group">
          <label id="description">Meta description</label>
          <input
            type="text"
            id="description"
            v-model="onboarding.description"
          />
        </div>
      </div>

      <div v-if="totalSteps[2] === 1" key="3">
        <p class="mb-6 text-3xl font-light text-center">SweepBright</p>

        <div class="form-group">
          <label id="sweepbright_client_id">Client ID</label>
          <input
            type="text"
            id="sweepbright_client_id"
            v-model="onboarding.client_id"
          />
        </div>

        <div class="form-group">
          <label id="sweepbright_client_secret">Client Secret</label>
          <input
            type="text"
            id="sweepbright_client_secret"
            v-model="onboarding.client_secret"
          />
        </div>
      </div>

      <div v-if="totalSteps[3] === 1" key="4">
        <p class="mb-6 text-3xl font-light text-center">Google reCaptcha</p>

        <div class="form-group">
          <label id="recaptcha_site_key">Site key</label>
          <input
            type="text"
            id="recaptcha_site_key"
            v-model="onboarding.recaptcha_site_key"
          />
        </div>

        <div class="form-group">
          <label id="recaptcha_secret_key">Secret key</label>
          <input
            type="text"
            id="recaptcha_secret_key"
            v-model="onboarding.recaptcha_secret_key"
          />
        </div>
      </div>

      <div class="text-center" v-if="totalSteps[4] === 1" key="5">
        <i class="mb-4 text-6xl text-blue-500 fad fa-check"></i>
        <p class="mb-6 text-3xl font-light">You're all set!</p>
        <p class="text-base text-gray-600">
          You're now ready to start using the visual web editor for SweepBright.
        </p>
      </div>
    </transition>

    <div class="flex items-center justify-between mt-10">
      <div class="w-1/3">
        <button
          class="btn btn-default"
          @click="back"
          v-if="totalSteps[0] !== 1"
        >
          Back
        </button>
      </div>
      <div class="flex justify-center w-1/3">
        <ul class="flex space-x-2">
          <li
            v-for="(step, index) in totalSteps"
            :key="index"
            @click="navigate(index)"
            :class="
              step === 1
                ? 'bg-blue-500 hover:bg-blue-600'
                : 'bg-gray-300 hover:bg-gray-400'
            "
            class="w-3 h-3 transition duration-200 rounded-full cursor-pointer"
          ></li>
        </ul>
      </div>
      <div class="w-1/3 text-right">
        <button class="btn btn-primary" @click="next">
          <template v-if="totalSteps[0] === 1">Get started</template>
          <template v-else-if="totalSteps[totalSteps.length - 1] === 1"
            >Finish</template
          >
          <template v-else>Next</template>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import bus from "../../../js/pages/bus.js";

export default {
  components: {},
  computed: {},
  data() {
    return {
      onboarding: {
        description: "",
        client_id: "",
        client_secret: "",
        recaptcha_site_key: "",
        recaptcha_secret_key: "",
      },
      totalSteps: [1, 0, 0, 0, 0],
    };
  },
  methods: {
    back() {
      const index = this.totalSteps.indexOf(1);
      this.totalSteps = new Array(this.totalSteps.length).fill(0);
      this.$set(this.totalSteps, index - 1, 1);
    },
    next() {
      if (this.totalSteps[this.totalSteps.length - 1] !== 1) {
        const index = this.totalSteps.indexOf(1);
        this.totalSteps = new Array(this.totalSteps.length).fill(0);
        this.$set(this.totalSteps, index + 1, 1);
      } else {
        bus.$emit("onboardingComplete", this.onboarding);
      }
    },
    navigate(index) {
      this.totalSteps = new Array(this.totalSteps.length).fill(0);
      this.$set(this.totalSteps, index, 1);
    },
  },
  mounted() {
    bus.$once("settingsLoaded", (settings) => {
      this.onboarding.description = settings.description;
      this.onboarding.client_id = settings.client_id;
      this.onboarding.client_secret = settings.client_secret;
      this.onboarding.recaptcha_site_key = settings.recaptcha_site_key;
      this.onboarding.recaptcha_secret_key = settings.recaptcha_secret_key;
    });
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  @apply transition-all duration-200;
}

.fade-enter,
.fade-leave-to {
  @apply opacity-0 transform translate-y-1;
}
</style>
