<template>
  <div class="mb-5">
    <div class="wrap">
      <form>
        <div class="mb-5 last:mb-0">
          <h2 class="mb-1 text-2xl">Multilanguage</h2>
          <table class="form-table" role="presentation">
            <tbody>
              <tr>
                <th scope="row">
                  <label for="multilanguage">Enable</label>
                </th>
                <td>
                  <input
                    type="checkbox"
                    name="multilanguage"
                    id="multilanguage"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </form>
    </div>

    <!-- Submit -->
    <p class="submit">
      <input
        type="submit"
        name="submit-contact-settings"
        id="submit"
        class="button button-primary"
        value="Save settings"
      />
    </p>
  </div>
</template>

<script>
export default {
  components: {},
  computed: {},
  data() {
    return {};
  },
  methods: {},
  mounted() {},
};
</script>

