<template>
  <div>
    <header class="header">
      <div class="container-fluid">
        <div class="header-inner">
          <div class="header-block with-menu-opener w-100">
            <button
              class="menu-opener lg-visible-flex"
              aria-label="Show navigation"
            >
              <span class="bar"></span>
              <span class="bar"></span>
              <span class="bar"></span>
            </button>
            <span
              class="w-100"
              style="
                display: flex;
                align-items: center;
                justify-content: space-between;
              "
            >
              <h1
                class="page-caption font-weight-bold"
                style="margin: 0px !important"
              >
                Questionnaire

                <span class="text-uppercase">
                  {{ questionnaire.name }}
                </span>
              </h1>
            </span>
          </div>
        </div>
      </div>
    </header>
    <main class="page-main" v-loading="loading">
      <div class="page-content">
        <section class="default-section">
          <div class="container-fluid">
            <input
              type="hidden"
              name="response"
              :value="JSON.stringify(question_array_by_step)"
            />

            <div class="card shadow-sm border-0 pt-4 pb-0 px-3 mb-5">
              <div class="card-body pt-3 pb-0 px-2">
                <div class="project-type-container">
                  <div class="col-md-7 px-0">
                    <div class="question-block">
                      <div
                        v-for="(
                          question_array, index
                        ) in question_array_by_step"
                        :key="index"
                        class="projectforms form"
                      >
                        <div v-if="previewed_step == index">
                          <div
                            v-for="(question, indx) in question_array"
                            :key="indx"
                          >
                            <div class="row block-top mb-3">
                              <div class="col">
                                <h3 class="block-caption">
                                  {{ question.fields.label }}
                                </h3>
                              </div>
                              <div v-if="question.fields.description" class="col-auto">
                                <div class="popover-block">
                                  <span class="block-opener">
                                    <svg class="btn-icon">
                                      <use
                                        xlink:href="/ui_assets/img/icons-sprite.svg#info"
                                      ></use>
                                    </svg>
                                  </span>
                                  <div class="block-hidden-content">
                                    <p>
                                      {{ question.fields.description }}
                                    </p>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-12">
                                <p>
                                  {{ question.fields.desc }}
                                </p>
                              </div>

                              <!-- radiosGroup -->
                              <div
                                v-if="question.fields.type == 'radio-group'"
                                class="col-md-form-group col-md-12"
                              >
                                <div
                                  v-for="(val, ival) in question.fields.values"
                                  :key="ival"
                                  class="form-check"
                                  :class="{
                                    'form-check-inline':
                                      question.fields.inline && true,
                                  }"
                                >
                                  <input
                                    @change="sendResponse(question)"
                                    class="form-check-input"
                                    v-model="question.fields.value"
                                    type="radio"
                                    :name="indx"
                                    :value="val.value"
                                  />
                                  <label class="form-check-label">{{
                                    val.label
                                  }}</label>
                                </div>
                              </div>
                              <!-- checkbox -->
                              <div
                                v-if="question.fields.type == 'checkbox-group'"
                                class="col-md-form-group col-md-12"
                              >
                                <div
                                  v-for="(val, ival) in question.fields.values"
                                  :key="ival"
                                  class="form-check"
                                  :class="{
                                    'form-check-inline':
                                      question.fields.inline && true,
                                  }"
                                >
                                  <input
                                    @change="sendResponse(question)"
                                    class="form-check-input"
                                    v-model="question.fields.value"
                                    type="checkbox"
                                    :name="indx"
                                    :value="val.value"
                                  />
                                  <label class="form-check-label">{{
                                    val.label
                                  }}</label>
                                </div>
                              </div>
                              <!-- date -->
                              <div
                                v-if="question.fields.type == 'date'"
                                class="col-md-form-group col-md-12"
                              >
                                <input
                                  @blur="sendResponse(question)"
                                  v-model="question.fields.value"
                                  type="date"
                                  class="form-control"
                                />
                                <div
                                  class="invalid-feedback"
                                  id="descriptionError"
                                ></div>
                              </div>
                              <!-- number -->
                              <div
                                v-if="question.fields.type == 'number'"
                                class="col-md-form-group col-md-12"
                              >
                                <input
                                  @blur="sendResponse(question)"
                                  v-model.number="question.fields.value"
                                  type="number"
                                  class="form-control"
                                  @input="
                                    question.fields.value > 0
                                      ? (question.fields.child_values = [])
                                      : 0
                                  "
                                />
                                <div
                                  class="invalid-feedback"
                                  id="descriptionError"
                                ></div>
                                <div
                                  v-if="
                                    question.fields.constraints ==
                                    'can_add_multiple_child_based_value'
                                  "
                                  class="ml-5"
                                >
                                  <input
                                    v-for="(child, child_index) in question
                                      .fields.value"
                                    :key="child_index"
                                    v-model.number="
                                      question.fields.child_values[child_index]
                                    "
                                    type="text"
                                    class="form-control my-1"
                                  />
                                </div>
                              </div>
                              <!-- select -->
                              <div
                                v-if="question.fields.type == 'select'"
                                class="col-md-form-group col-md-12"
                              >
                                <select
                                  @change="sendResponse(question)"
                                  v-model="question.fields.value"
                                  class="custom-select"
                                >
                                  <option
                                    v-for="(val, ival) in question.fields
                                      .values"
                                    :key="ival"
                                    :value="val.value"
                                    :selected="val.selected"
                                  >
                                    {{ val.label }}
                                  </option>
                                </select>
                                <div
                                  class="invalid-feedback"
                                  id="descriptionError"
                                ></div>
                              </div>
                              <!-- text -->
                              <div
                                v-if="question.fields.type == 'text'"
                                class="form-group col-md-12"
                              >
                                <div
                                  v-if="
                                    question.fields.constraints &&
                                    question.fields.constraints.length > 0 &&
                                    question.fields.constraints.includes(
                                      'can_add_multiple_child'
                                    )
                                  "
                                >
                                  <!-- <button class="add-btn" type="button">
                          <svg
                            style="width: 30px; height: 30px"
                            class="btn-icon"
                          >
                            <use
                              :xlink:href="'/ui_assets/img/icons-sprite.svg#plus'"
                            ></use>
                          </svg>
                        </button> -->
                                </div>
                                <div
                                  v-else
                                  class="
                                    inner
                                    custom-field-design
                                    border
                                    rounded
                                    px-2
                                    pt-2
                                  "
                                >
                                  <label for="name" class="small">{{
                                    question.fields.placeholder
                                  }}</label>
                                  <input
                                    @blur="sendResponse(question)"
                                    v-model="question.fields.value"
                                    class="form-control border-0"
                                  />
                                  <div
                                    class="invalid-feedback"
                                    id="descriptionError"
                                  ></div>
                                </div>
                              </div>
                              <!-- textarea -->
                              <div
                                v-if="question.fields.type == 'textarea'"
                                class="form-group col-md-12"
                              >
                                <div
                                  class="
                                    inner
                                    custom-field-design
                                    border
                                    rounded
                                    px-2
                                    pt-2
                                  "
                                >
                                  <label for="name" class="small">{{
                                    question.fields.placeholder
                                  }}</label>
                                  <textarea
                                    @blur="sendResponse(question)"
                                    v-model="question.fields.value"
                                    class="form-control border-0"
                                    rows="3"
                                    name="description"
                                    id="descriptionInput"
                                  ></textarea>
                                  <div
                                    class="invalid-feedback"
                                    id="descriptionError"
                                  ></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="question-block-footer py-5 px-3 mt-5 d-flex flex-wrap"
                >
                  <div class="col-auto">
                    <button
                      :disabled="previewed_step <= 1 ? true : false"
                      id="backbutton_projects"
                      class="btn btn-primary btn-cancel btn-lg step-btn-back"
                      @click="previewed_step -= 1"
                    >
                      Back
                    </button>
                  </div>
                  <div class="footer-progress mx-auto">
                    <ul>
                      <li
                        @click="previewed_step = step"
                        v-for="(step, sidx) in questionnaire.step"
                        :key="sidx"
                        :class="{
                          active: step == previewed_step,
                        }"
                      >
                        <span class="percentage">
                          {{
                            Math.floor(previewed_step / questionnaire.step) *
                            100
                          }}
                          %</span
                        ><span class="dots"></span>
                      </li>
                    </ul>

                    <p id="steptext">
                      {{ previewed_step }} of {{ questionnaire.step }} Steps
                      Remaining
                    </p>
                  </div>
                  <div class="col-auto">
                    <button
                      type="submit"
                      v-if="previewed_step == questionnaire.step"
                      class="btn btn-lg step-btn"
                    >
                      Submit
                    </button>
                    <button
                      @click="previewed_step += 1"
                      v-else
                      type="button"
                      class="btn btn-lg step-btn"
                    >
                      Next
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script>
const base_path = window.location.origin;
export default {
  props: {
    id: String,
    questions: String,
    is_connected: String,
    _questionnaire: String,
  },
  data: () => ({
    loading: false,
    question_array_by_step: {},
    questionnaire: {},
    previewed_step: 1,
  }),
  mounted() {
    this.question_array_by_step = JSON.parse(this.questions);
    this.questionnaire = JSON.parse(this._questionnaire);
  },
  methods: {
    async sendResponse(question) {
      if (this.is_connected == true) {
        await window.axios.post(
          `${base_path}/client/questionnaires/${this.questionnaire.id}/response/${question.id}`,
          { responses: JSON.stringify(question) }
        );
      }
    },
    async handleSubmit() {
      const response = await window.axios.post(
        `${base_path}/client/questionnaires/${this.questionnaire.id}/response`,
        { responses: JSON.stringify(this.question_array_by_step) }
      );
      setTimeout(() => {
        const link = document.createElement("a");
        link.href = response.url;
        document.body.appendChild(link);
        link.click();
        // window.location.href = response.url;
      }, 1000);
    },
  },
};
</script>

<style></style>
