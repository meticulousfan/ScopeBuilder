<template>
  <div>
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
                <a
                  style="font-size: 13px"
                  :href="'/admin/questionnaire/'+parsed_question.questionnaire_id+'/questions/manage'"
                  class="mr-2"
                >
                  &larr; {{ locale.back }}</a
                >
                {{ locale.edit_question }}
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
              <div>
                <div
                  id="fbuilder-editor"
                  style="padding: 0; margin: 10px 0"
                ></div>
                <div>
                  <div  v-if="parsed_question.fields.type == 'number'" class="my-3">
                    <label> Conditions</label>
    
                    <el-select
                      placeholder="Conditions"
                      v-model="constraints"
                      class="form-select w-100 mb-3"
                      multiple
                    >
                      <!-- <el-option
                        value="can_add_multiple_child"
                        label="user can add multiple child for this field"
                      >
                      </el-option> -->
                      <el-option
                      v-if="parsed_question.fields.type == 'number'"
                        value="can_add_multiple_child_based_value"
                        label="show child input based on input value"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="mb-3">
                    <label> Question position</label>
                    <el-select
                      placeholder="Position"
                      v-model="parsed_question.position"
                      class="w-100"
                    >
                      <el-option
                        v-for="pos in Number(count)"
                        :key="pos"
                        :value="pos"
                        :label="pos"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="mb-3">
                    <label>Field description</label>
                    <el-input v-model="desc" class="w-100" ></el-input>
                  </div>
                  <button
                    @click="submitQuestion"
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
    question: String,
    count: String,
  },
  data: () => ({
    loading: false,
    parsed_question: {},
    constraints: [],
    desc: "",
    locale: lang,
    selected_step: null,
  }),
  mounted() {
    require("/js/form-builder.min.js");
    var parsed_question = JSON.parse(this.question);
    this.parsed_question = parsed_question;
    this.constraints = parsed_question.fields.constraints;
    jQuery(function ($) {
      var fbEditor = document.getElementById("fbuilder-editor");
      var options = {
        showActionButtons: false,
        disabledFieldButtons: {
          text: ["remove", "copy"],
          select: ["edit", "copy"],
          "checkbox-group": ["remove", "copy"],
          date: ["remove", "copy"],
          number: ["remove", "copy"],
          "radio-group": ["remove", "copy"],
          textarea: ["remove", "copy"],
        },
        disableFields: [
          "autocomplete",
          "button",
          "checkbox-group",
          "date",
          "file",
          "header",
          "hidden",
          "number",
          "paragraph",
          "radio-group",
          "select",
          "starRating",
          "text",
          "textarea",
        ],
        defaultFields: [parsed_question.fields],
        disabledAttrs: ["subtype", "access", "name"],
      };
      formBuilder = $(fbEditor).formBuilder(options);
    });
  },
  methods: {
    async submitQuestion() {
      let data = JSON.parse(formBuilder.formData);
      let field = data[0];
      field.constraints = this.constraints ?? [];
      field.desc = this.desc;
      try {
        this.loading = true;
        await window.axios.put(
          `${base_path}/admin/question/${this.id}/edit`,
          {
            fields: JSON.stringify(field),
            position: this.parsed_question.position,
          }
        );
        window.location.href = `/admin/questionnaire/${this.parsed_question.questionnaire_id}/questions/manage`;
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
