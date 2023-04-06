<template>
  <section>
    <header class="header">
      <div class="container">
        <div class="header-inner">
          <div class="header-block with-menu-opener">
            <button
              class="menu-opener lg-visible-flex"
              aria-label="Show navigation"
            >
              <span class="bar"></span>
              <span class="bar"></span>
              <span class="bar"></span>
            </button>
            <span style="display: flex; align-items: center">
              <h1 class="page-caption" style="margin: 0px !important">
                Questionnaires
              </h1>
            </span>
          </div>
        </div>
      </div>
    </header>

    <main class="page-main" v-loading="loading">
      <div class="page-content">
        <section class="default-section">
          <div class="container">
            <div class="section-inner">
              <div class="row">
                <div v-for="(item, idx) in rowData" :key="idx" class="col-4">
                  <div class="project-card">
                    <div class="bg-white p-3">
                      <div
                        class="
                          d-flex
                          align-items-center
                          justify-content-between
                        "
                      >
                        <div class="card-image">
                          <img src="/ui_assets/img/icons/folder.svg" alt="" />
                        </div>

                        <div class="card-content">
                          <h3 class="card-caption text-uppercase">
                            {{ item.name }}
                          </h3>
                        </div>
                      </div>
                      <div class="d-flex justify-content-center flex-wrap">
                        <p
                          v-for="(skill, idx) in item.skills"
                          :key="idx"
                          class="card-type m-1"
                        >
                          {{ skill.name }}
                        </p>
                      </div>
                    </div>

                    <a
                      :href="`/client/questionnaires/${item.id}/show`"
                      class="w-100 btn btn-primary mt-3"
                      style="
                        border: 1px solid var(--btn-bg);
                        border-radius: var(--r);
                        background: var(--btn-bg);
                      "
                      >View Questionnaire</a
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
   
  </section>
</template>

<script>
const base_path = window.location.origin;

export default {
  data: () => ({
    loading: true,
    errorMessage: "",
    rowData: [],
  }),
  created() {
    this.fetchQuestionnaires();
  },
  methods: {
    async fetchQuestionnaires() {
      try {
        this.loading = true;
        const res = await window.axios.get(
          `${base_path}/client/questionnaires`
        );
        this.rowData = res.data.questionnaires;
      } catch (error) {
        alert(String(error));
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style></style>
