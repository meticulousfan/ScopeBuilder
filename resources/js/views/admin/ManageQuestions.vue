<template>
  <div>
    <header class="header">
      <div class="container">
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
              <h1 class="page-caption" style="margin: 0px !important">
                <a
                  style="font-size: 13px"
                  href="/admin/questionnaires/display"
                  class="mr-2"
                >
                  &larr; {{ locale.back }}</a
                >

                {{ locale.manage_questions }}
              </h1>
              <button
                v-if="showCreate"
                class="btn btn-primary"
                href="javascript:void(0)"
                style="
                  padding: 10px 20px;
                  min-height: 54px;
                  border: 1px solid var(--btn-bg);
                  border-radius: var(--r);
                  background: var(--btn-bg);
                  color: var(--btn-color);
                "
                @click="showCreate = false"
              >
                {{ locale.show_questions_list }}
              </button>
              <button
                v-else
                class="btn btn-primary"
                href="javascript:void(0)"
                style="
                  padding: 10px 20px;
                  min-height: 54px;
                  border: 1px solid var(--btn-bg);
                  border-radius: var(--r);
                  background: var(--btn-bg);
                  color: var(--btn-color);
                "
                @click="showCreate = true"
              >
                {{ locale.create_new_question }}
              </button>
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
              <div v-show="showCreate">
                <div
                  id="fbuilder-editor"
                  style="padding: 0; margin: 10px 0; background: #f2f2f2"
                ></div>
                <div class="row">
                  <div class="col-9">
                    <el-select
                      placeholder="Select step you want to attach to your questions"
                      class="form-select w-100"
                      v-model="selected_step"
                    >
                      <el-option
                        v-for="item in questionnaire.step"
                        :key="item"
                        :value="item"
                        :label="'step ' + item"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="col-3">
                    <button
                      @click="handleSubmitQuestion()"
                      class="btn btn-primary"
                      style="
                        padding: 8px 20px;
                        border: 1px solid var(--btn-bg);
                        border-radius: var(--r);
                        background: var(--btn-bg);
                        color: var(--btn-color);
                      "
                    >
                      Submit
                    </button>
                  </div>
                </div>
              </div>
              <section v-show="!showCreate">
                <button
                  @click="incrementStep()"
                  class="btn my-3"
                  style="
                    padding: 10px 20px;
                    min-height: 54px;
                    border: 1px solid var(--btn-bg);
                    border-radius: var(--r);
                    background: var(--btn-bg);
                    color: var(--btn-color);
                  "
                >
                  {{ locale.create_new_step }}
                </button>
                <el-tabs
                  v-model="selected_step"
                  closable
                  @tab-remove="removeTab"
                >
                  <el-tab-pane
                    v-for="(question_array, index) in question_array_by_step"
                    :key="index"
                    :label="'Step ' + index"
                    :name="index"
                  >
                    <div class="table-wrapper">
                      <table class="table clients_table">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Label</th>
                            <th>Type</th>
                            <th>
                              {{ locale.action }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(question, indx) in question_array"
                            :key="indx"
                          >
                            <td>
                              {{ indx + 1 }}
                            </td>
                            <td>
                              {{ question.fields.label }}
                            </td>
                            <td>
                              {{ question.fields.type }}
                            </td>

                            <td>
                              <a
                                :href="`/admin/question/${question.id}/update`"
                                class="editeType"
                              >
                                <img
                                  src="/ui_assets/img/icons/vuesax-bulk-document-text.svg"
                                />
                              </a>
                              <button
                                class="client-act-btn deleteType"
                                @click="deleteQuestion(question.id)"
                              >
                                <svg class="btn-icon">
                                  <use
                                    xlink:href="/ui_assets/img/icons-sprite.svg#trash"
                                  ></use>
                                </svg>
                              </button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </el-tab-pane>
                </el-tabs>

                <el-card class="my-5">
                  <h2 class="text-center">Full preview</h2>
                  <div
                    v-for="(question_array, index) in question_array_by_step"
                    :key="index"
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
                                class="form-check-input"
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
                                class="form-check-input"
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
                            <input type="date" class="form-control" />
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
                              v-model.number="question.fields.value"
                              type="number"
                              class="form-control"
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
                                v-for="(child, child_index) in question.fields
                                  .value"
                                :key="child_index"
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
                            <select class="custom-select">
                              <option
                                v-for="(val, ival) in question.fields.values"
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
                              <button class="add-btn" type="button">
                                <svg
                                  style="width: 30px; height: 30px"
                                  class="btn-icon"
                                >
                                  <use
                                    :xlink:href="'/ui_assets/img/icons-sprite.svg#plus'"
                                  ></use>
                                </svg>
                              </button>
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
                              <input class="form-control border-0" />
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
                  <div
                    class="
                      question-block-footer
                      py-5
                      px-3
                      mt-5
                      d-flex
                      flex-wrap
                    "
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
                        @click="previewed_step += 1"
                        :disabled="
                          previewed_step == questionnaire.step ? true : false
                        "
                        type="button"
                        class="btn btn-lg step-btn"
                      >
                        Next
                      </button>
                    </div>
                  </div>
                </el-card>
              </section>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script>
import lang from "../../../lang/en.json";
const base_path = window.location.origin;
var formBuilder;
export default {
  props: {
    id: String,
  },
  data: () => ({
    selected_step: "1",
    previewed_step: 1,
    locale: lang,
    showCreate: false,
    loading: false,
    questionnaire: {},
    question_array_by_step: {},
  }),
  mounted() {
    this.fetchQuestionnaire();
    require("/js/form-builder.min.js");
    jQuery(function ($) {
      var fbEditor = document.getElementById("fbuilder-editor");

      var options = {
        showActionButtons: false,
        disableFields: [
          "autocomplete",
          "button",
          "header",
          "hidden",
          "paragraph",
          "starRating",
          "file",
        ],
        disabledAttrs: ["subtype", "access", "name"],
      };
      formBuilder = $(fbEditor).formBuilder(options);
    });
  },
  methods: {
    async fetchQuestionnaire() {
      try {
        this.loading = true;
        const res = await window.axios.get(
          `${base_path}/client/questionnaires/${this.id}`
        );
        this.questionnaire = res.data.questionnaire;
        let question_array_by_step = {};
        for (let index = 1; index < res.data.questionnaire.step + 1; index++) {
          if (res.data.questions[index]) {
            const element = res.data.questions[index].map((element) => {
              return {
                ...element,
                fields: element.fields,
              };
            });

            question_array_by_step[index] = element;
          }
        }
        this.question_array_by_step = question_array_by_step;
      } catch (error) {
        alert(String(error));
      } finally {
        this.loading = false;
      }
    },
    async deleteQuestion(id) {
      try {
        this.loading = true;
        await window.axios.delete(`${base_path}/admin/question/${id}/delete`);
        this.fetchQuestionnaire();
      } catch (error) {
        alert(String(error));
      } finally {
        this.loading = false;
      }
    },
    async incrementStep() {
      try {
        this.loading = true;
        const res = await window.axios.post(
          `${base_path}/admin/questionnaires/${this.questionnaire.id}/add-step`
        );
        this.fetchQuestionnaire();
      } catch (error) {
        alert(String(error));
      } finally {
        this.loading = false;
      }
    },
    async removeTab(targetName) {
      try {
        this.loading = true;
        const res = await window.axios.post(
          `${base_path}/admin/questionnaires/${this.questionnaire.id}/remove-step`,
          {
            step_id: targetName,
          }
        );
        this.fetchQuestionnaire();
      } catch (error) {
        alert(String(error));
      } finally {
        this.loading = false;
      }
    },
    async handleSubmitQuestion() {
      try {
        let final_payload = JSON.parse(formBuilder.actions.getData("json"));
        if (final_payload.length < 1) {
          return;
        }
        if (this.selected_step == null || this.selected_step == 0) {
          alert("Please select a step number");
          return;
        }
        this.loading = true;
        const res = await window.axios.post(
          `${base_path}/admin/question/create`,
          {
            questionnaire_id: this.questionnaire.id,
            fields: JSON.stringify(final_payload),
            step: this.selected_step,
          }
        );

        window.location.reload();
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
