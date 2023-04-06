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
                            <h1
                                class="page-caption"
                                style="margin: 0px !important"
                            >
                                <a
                                    style="font-size: 13px"
                                    href="/client/projects"
                                    class="mr-2"
                                >
                                    &larr; {{ locale.back }}</a
                                >

                                {{ locale.create_project }}
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
                        <div class="">
                            <section v-show="!showCreate">
                                <el-card class="mb-5 question-block">
                                    <!-- <h2 class="text-center">Questions</h2> -->
                                    <div
                                        v-for="(
                                            question_array, index
                                        ) in question_array_by_step"
                                        :key="index"
                                        class=""
                                    >
                                        <div v-if="previewed_step == index">
                                            <div
                                                v-for="(
                                                    question, indx
                                                ) in question_array"
                                                :key="indx"
                                                class=""
                                            >
                                                <div class="row block-top mb-3">
                                                    <div class="col">
                                                        <h3
                                                            class="block-caption"
                                                        >
                                                            {{
                                                                question.fields
                                                                    .label
                                                            }}
                                                        </h3>
                                                    </div>

                                                    <div
                                                        v-if="
                                                            question.fields
                                                                .description
                                                        "
                                                        class="col-auto"
                                                    >
                                                        <div
                                                            class="popover-block"
                                                        >
                                                            <span
                                                                class="block-opener"
                                                            >
                                                                <svg
                                                                    class="btn-icon"
                                                                >
                                                                    <use
                                                                        xlink:href="/ui_assets/img/icons-sprite.svg#info"
                                                                    ></use>
                                                                </svg>
                                                            </span>
                                                            <div
                                                                class="block-hidden-content"
                                                            >
                                                                <p>
                                                                    {{
                                                                        question
                                                                            .fields
                                                                            .description
                                                                    }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-7">
                                                        <p>
                                                            {{
                                                                question.fields
                                                                    .desc
                                                            }}
                                                        </p>
                                                    </div>

                                                    <!-- radiosGroup -->
                                                    <div
                                                        v-if="
                                                            question.fields
                                                                .type ==
                                                            'radio-group'
                                                        "
                                                        class="col-md-form-group col-md-7"
                                                    >
                                                        <div
                                                            v-for="(
                                                                val, ival
                                                            ) in question.fields
                                                                .values"
                                                            :key="ival"
                                                            class="form-check"
                                                            :class="{
                                                                'form-check-inline':
                                                                    question
                                                                        .fields
                                                                        .inline &&
                                                                    true,
                                                            }"
                                                        >
                                                            <input
                                                                class="form-check-input"
                                                                type="radio"
                                                                v-model="
                                                                    question.value
                                                                "
                                                                :name="indx"
                                                                :value="
                                                                    val.value
                                                                "
                                                            />
                                                            <label
                                                                class="form-check-label"
                                                                >{{
                                                                    val.label
                                                                }}</label
                                                            >
                                                        </div>
                                                    </div>
                                                    <!-- checkbox -->
                                                    <div
                                                        v-if="
                                                            question.fields
                                                                .type ==
                                                            'checkbox-group'
                                                        "
                                                        class="col-md-form-group col-md-7"
                                                    >
                                                        <div
                                                            v-for="(
                                                                val, ival
                                                            ) in question.fields
                                                                .values"
                                                            :key="ival"
                                                            class="form-check"
                                                            :class="{
                                                                'form-check-inline':
                                                                    question
                                                                        .fields
                                                                        .inline &&
                                                                    true,
                                                            }"
                                                        >
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                v-model="
                                                                    question.value
                                                                "
                                                                :value="
                                                                    val.value
                                                                "
                                                            />
                                                            <label
                                                                class="form-check-label"
                                                                >{{
                                                                    val.label
                                                                }}</label
                                                            >
                                                        </div>
                                                    </div>
                                                    <!-- date -->
                                                    <div
                                                        v-if="
                                                            question.fields
                                                                .type == 'date'
                                                        "
                                                        class="col-md-form-group col-md-7"
                                                    >
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            v-model="
                                                                question.value
                                                            "
                                                        />
                                                        <div
                                                            class="invalid-feedback"
                                                            id="descriptionError"
                                                        ></div>
                                                    </div>
                                                    <!-- number -->
                                                    <div
                                                        v-if="
                                                            question.fields
                                                                .type ==
                                                            'number'
                                                        "
                                                        class="col-md-form-group col-md-7"
                                                    >
                                                        <input
                                                            v-model="
                                                                question.value
                                                            "
                                                            type="number"
                                                            class="form-control"
                                                        />
                                                        <div
                                                            class="invalid-feedback"
                                                            id="descriptionError"
                                                        ></div>
                                                        <div
                                                            v-if="
                                                                question.fields
                                                                    .constraints ==
                                                                'can_add_multiple_child_based_value'
                                                            "
                                                            class="ml-5"
                                                        >
                                                            <input
                                                                v-for="(
                                                                    child,
                                                                    child_index
                                                                ) in question
                                                                    .fields
                                                                    .value"
                                                                :key="
                                                                    child_index
                                                                "
                                                                v-model="
                                                                    question.value
                                                                "
                                                                type="text"
                                                                class="form-control my-1"
                                                            />
                                                        </div>
                                                    </div>
                                                    <!-- select -->
                                                    <div
                                                        v-if="
                                                            question.fields
                                                                .type ==
                                                            'select'
                                                        "
                                                        class="col-md-form-group col-md-7"
                                                    >
                                                        <select
                                                            class="custom-select"
                                                            v-model="
                                                                question.value
                                                            "
                                                        >
                                                            <option
                                                                v-for="(
                                                                    val, ival
                                                                ) in question
                                                                    .fields
                                                                    .values"
                                                                :key="ival"
                                                                :value="
                                                                    val.value
                                                                "
                                                                :selected="
                                                                    val.selected
                                                                "
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
                                                        v-if="
                                                            question.fields
                                                                .type == 'text'
                                                        "
                                                        class="form-group col-md-7"
                                                    >
                                                        <div
                                                            v-if="
                                                                question.fields
                                                                    .constraints &&
                                                                question.fields
                                                                    .constraints
                                                                    .length >
                                                                    0 &&
                                                                question.fields.constraints.includes(
                                                                    'can_add_multiple_child'
                                                                )
                                                            "
                                                        >
                                                            <button
                                                                class="add-btn"
                                                                type="button"
                                                            >
                                                                <svg
                                                                    style="
                                                                        width: 30px;
                                                                        height: 30px;
                                                                    "
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
                                                            class="inner custom-field-design border rounded px-2 pt-2"
                                                        >
                                                            <label
                                                                for="name"
                                                                class="small"
                                                                >{{
                                                                    question
                                                                        .fields
                                                                        .placeholder
                                                                }}</label
                                                            >
                                                            <input
                                                                class="form-control border-0"
                                                                v-model="
                                                                    question.value
                                                                "
                                                            />
                                                            <div
                                                                class="invalid-feedback"
                                                                id="descriptionError"
                                                            ></div>
                                                        </div>
                                                    </div>
                                                    <!-- textarea -->
                                                    <div
                                                        v-if="
                                                            question.fields
                                                                .type ==
                                                            'textarea'
                                                        "
                                                        class="form-group col-md-7"
                                                    >
                                                        <div
                                                            class="inner custom-field-design border rounded px-2 pt-2"
                                                        >
                                                            <label
                                                                for="name"
                                                                class="small"
                                                                >{{
                                                                    question
                                                                        .fields
                                                                        .placeholder
                                                                }}</label
                                                            >
                                                            <textarea
                                                                class="form-control border-0"
                                                                rows="3"
                                                                name="description"
                                                                id="descriptionInput"
                                                                v-model="
                                                                    question.value
                                                                "
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
                                        class="question-block-footer py-5 px-3 mt-5 d-flex flex-wrap"
                                    >
                                        <div class="col-auto">
                                            <button
                                                :disabled="
                                                    previewed_step <= 1
                                                        ? true
                                                        : false
                                                "
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
                                                    @click="
                                                        previewed_step = step
                                                    "
                                                    v-for="(
                                                        step, sidx
                                                    ) in questionnaire.step"
                                                    :key="sidx"
                                                    :class="{
                                                        active:
                                                            step ==
                                                            previewed_step,
                                                    }"
                                                >
                                                    <span class="percentage">
                                                        {{
                                                            Math.floor(
                                                                previewed_step /
                                                                    questionnaire.step
                                                            ) * 100
                                                        }}
                                                        %</span
                                                    ><span class="dots"></span>
                                                </li>
                                            </ul>

                                            <p id="steptext">
                                                {{ previewed_step }} of
                                                {{ questionnaire.step }} Steps
                                                Remaining
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <button
                                                v-if="
                                                    previewed_step !=
                                                    questionnaire.step
                                                "
                                                @click="nextStep()"
                                                :disabled="
                                                    previewed_step ==
                                                    questionnaire.step
                                                        ? true
                                                        : false
                                                "
                                                type="button"
                                                class="btn btn-lg step-btn"
                                            >
                                                Next
                                            </button>
                                            <button
                                                v-if="
                                                    previewed_step ==
                                                    questionnaire.step
                                                "
                                                @click="saveAndPay()"
                                                type="button"
                                                class="btn btn-lg step-btn"
                                            >
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </el-card>

                                <el-dialog
                                    :title="locale.pay"
                                    @close="resetPayload"
                                    :show-close="false"
                                    class="text-center payment-dialog"
                                    :visible.sync="dialogPaymentVisible"
                                >
                                    <div class="">
                                        <div class="form">
                                            <h3 class="mc-title">
                                                Project Questionnaire Completed
                                            </h3>
                                            <h4>
                                                <b>Cost: </b>${{
                                                    cost.toFixed(2)
                                                }}
                                            </h4>
                                        </div>

                                        <div class="form">
                                            <p class="mc-subtitle">
                                                Congratulations on completing
                                                your project scope. In order to
                                                generate your project document,
                                                ScopeBuilder requires that you
                                                pay the payment fees of ${{
                                                    cost.toFixed(2)
                                                }}
                                            </p>
                                        </div>

                                        <div
                                            class="mt-3"
                                            style="
                                                flex-wrap: inherit;
                                                padding: 0px;
                                                border: 0px;
                                            "
                                        >
                                            <!-- <el-button
                      :disabled="loading"
                      @click="proceedToPay"
                      class="btn btn-light js-modal-close"
                      style="
                        padding: 10px 20px;
                        min-height: 54px;
                        border: 1px solid var(--btn-bg);
                        border-radius: var(--r);
                        background: var(--btn-bg);
                        color: var(--btn-color);
                      "
                      >
                        {{ locale.proceed_to_pay }}
                      </el-button> -->

                                            <Stripe
                                                :project="project"
                                                :cost="cost"
                                                :user="user"
                                                :stripeKeys="stripeKeys"
                                                @cancel="cancelPayment"
                                            />
                                        </div>
                                    </div>
                                </el-dialog>
                                <el-dialog
                                    :title="Tips"
                                    width="60%"
                                    :show-close="false"
                                    @close="handleClose"
                                    :visible.sync="dialogVisible"
                                >
                                    <div class="form">
                                        <h3 class="mc-title text-center">
                                            Project store success!
                                        </h3>
                                    </div>
                                    <template #footer>
                                        <div
                                            class="flex justify-content-center"
                                        >
                                            <span
                                                class="dialog-footer justify-"
                                            >
                                                <el-button
                                                    type="primary"
                                                    @click="
                                                        dialogVisible = false
                                                    "
                                                >
                                                    Confirm
                                                </el-button>
                                            </span>
                                        </div>
                                    </template>
                                </el-dialog>
                            </section>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>

<script>
import lang from "../../../../lang/en.json";
import Stripe from "./Stripe.vue";
const base_path = window.location.origin;
var formBuilder;
export default {
    props: {
        id: String,
    },
    components: {
        Stripe,
    },
    data: () => ({
        selected_step: "1",
        previewed_step: 1,
        locale: lang,
        showCreate: false,
        loading: false,
        questionnaire: {},
        question_array_by_step: {},
        project: {},
        dialogPaymentVisible: false,
        cost: 0,
        settings: {},
        user: null,
        stripeKeys: {},
        dialogVisible: false,
    }),
    mounted() {
        this.fetchQuestionnaire();
        this.fetchSettings();
        this.fetchAuthUser();
        this.getStripeKeys();
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
        async nextStep() {
            try {
                this.loading = true;
                this.save();
                this.previewed_step += 1;
            } catch (error) {
                alert(String(error));
            } finally {
                this.loading = false;
            }
        },
        async save() {
            let left_step = this.previewed_step + 1;
            if (this.previewed_step == this.questionnaire.step) {
                left_step = 1;
            }
            const payload = await window.axios.post(
                `${base_path}/client/project/${this.id}/questions/store`,
                {
                    question_array_by_step: this.question_array_by_step,
                    left_step: left_step,
                }
            );
            if (payload.status == 200 && payload.data.success) {
                if (this.project.status == 2) this.dialogVisible = true;
            }
        },
        saveAndPay() {
            this.save();
            this.calculatePrice();
            if (localStorage.anonymous_client) {
                this.$parent.openSignup();
            } else {
                if (this.project.status != 2) this.openPaymentDialogue();
            }
        },
        openPaymentDialogue() {
            if (this.user && this.user.id == this.project.user_id) {
                this.dialogPaymentVisible = true;
            }
        },
        calculatePrice() {
            let question_response = 0;
            if (Object.keys(this.settings).length > 0) {
                Object.values(this.question_array_by_step).map(
                    (question_array) => {
                        Object.values(question_array).map((question) => {
                            if (question.value) {
                                question_response += 1;
                            }
                        });
                    }
                );
                this.cost =
                    this.settings.defaultQuestionPrice * question_response;
            }
        },
        async fetchQuestionnaire() {
            try {
                this.loading = true;
                const res = await window.axios.get(
                    `${base_path}/client/project/${this.id}/questions/details`
                );
                this.questionnaire = res.data.questionnaire;
                this.project = res.data.project;
                if (
                    this.project.left_step != 0 &&
                    this.project.left_step <= res.data.questionnaire.step
                ) {
                    this.previewed_step = parseInt(this.project.left_step);
                }
                let question_array_by_step = {};
                for (
                    let index = 1;
                    index < res.data.questionnaire.step + 1;
                    index++
                ) {
                    if (res.data.questions[index]) {
                        const element = res.data.questions[index].map(
                            (element) => {
                                if (element.fields.type == "checkbox-group") {
                                    if (element.value) {
                                        element.value = JSON.parse(
                                            element.value
                                        );
                                    } else {
                                        element.value = [];
                                    }
                                }
                                return {
                                    ...element,
                                    fields: element.fields,
                                };
                            }
                        );
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
                await window.axios.delete(
                    `${base_path}/admin/question/${id}/delete`
                );
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
                let final_payload = JSON.parse(
                    formBuilder.actions.getData("json")
                );
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
        async proceedToPay() {
            try {
                this.loading = true;
                const res = await window.axios.post(
                    `${base_path}/client/project/${this.project.id}/pay`,
                    {
                        amount: this.cost,
                    }
                );
                if (res.data.success) {
                    this.dialogPaymentVisible = false;
                    window.location.href = `${base_path}/client/projects`;
                }
            } catch (error) {
                alert(String(error));
            } finally {
                this.loading = false;
            }
        },
        resetPayload() {
            this.calculatePrice();
        },
        async fetchSettings() {
            try {
                this.loading = true;
                const res = await window.axios.get(`${base_path}/settings`);
                this.settings = res.data.settings;
            } catch (error) {
                alert(String(error));
            } finally {
                this.loading = false;
            }
        },
        async fetchAuthUser() {
            try {
                this.loading = true;
                const res = await window.axios.get(`${base_path}/auth/user`);
                this.user = res.data.user;
            } catch (error) {
                alert(String(error));
            } finally {
                this.loading = false;
            }
        },
        async getStripeKeys() {
            try {
                this.loading = true;
                const res = await window.axios.get(`${base_path}/stripe/keys`);
                this.stripeKeys.key = res.data.key;
                this.stripeKeys.secret = res.data.secret;
            } catch (error) {
                alert(String(error));
            } finally {
                this.loading = false;
            }
        },
        cancelPayment() {
            this.dialogPaymentVisible = false;
        },
        handleClose() {
            console.log("success mdoal");
        },
    },
};
</script>

<style>
.payment-dialog .el-dialog {
    max-width: 550px;
}
.form .mc-subtitle {
    word-break: break-word;
}
</style>
