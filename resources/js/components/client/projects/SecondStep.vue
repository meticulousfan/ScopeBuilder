<template>
    <div class="section-inner">
        <div class="buttons">
            <button @click="saveLater()" :disabled="disableSubmitButton" value="Save For Later" >{{saving ? "Submitting..." : "Save For Later"}}</button>
        </div>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(saveSecond)">
                <input
                    type="hidden"
                    id="project-step"
                    v-model="project.step"
                />
                <div class="page-tab">
                    <div class="tab-content">
                        <div class="column">
                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        What is the type of your project?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please choose whether this will be a
                                            mobile based project, a web based
                                            project or both.
                                        </p>
                                    </div>

                                    <div class="popover-block">
                                        <button class="block-opener">
                                            <svg class="btn-icon">
                                                <use
                                                    :xlink:href="
                                                        base_path +
                                                        'ui_assets/img/icons-sprite.svg#info'
                                                    "
                                                ></use>
                                            </svg>
                                        </button>
                                        <div class="block-hidden-content">
                                            <p>
                                                <strong>Lorem ipsum</strong> -
                                                dolor sit amet, consectetur
                                                adipisicing elit. Facilis
                                                doloremque vitae, nisi. Mollitia
                                                asperiores saepe pariatur
                                                repellat ullam voluptates neque!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <validation-provider
                                                    name="Project Type"
                                                    :rules="{
                                                        required: true,
                                                        min: 3,
                                                    }"
                                                    v-slot="validationContext"
                                                >
                                                    <div class="fields-group">
                                                        <label class="radio">
                                                            <input
                                                                type="radio"
                                                                name="project-type"
                                                                v-model="
                                                                    project.type
                                                                "
                                                                value="mobile"
                                                                checked
                                                                class="
                                                                    visually-hidden
                                                                "
                                                            />
                                                            <span
                                                                class="
                                                                    fake-label
                                                                "
                                                                >Mobile
                                                                Project</span
                                                            >
                                                        </label>
                                                        <label class="radio">
                                                            <input
                                                                type="radio"
                                                                name="project-type"
                                                                v-model="
                                                                    project.type
                                                                "
                                                                value="web"
                                                                class="
                                                                    visually-hidden
                                                                "
                                                            />
                                                            <span
                                                                class="
                                                                    fake-label
                                                                "
                                                                >Web
                                                                Project</span
                                                            >
                                                        </label>
                                                        <label class="radio">
                                                            <input
                                                                type="radio"
                                                                name="project-type"
                                                                v-model="
                                                                    project.type
                                                                "
                                                                value="both"
                                                                class="
                                                                    visually-hidden
                                                                "
                                                            />
                                                            <span
                                                                class="
                                                                    fake-label
                                                                "
                                                                >Both</span
                                                            >
                                                        </label>
                                                    </div>
                                                    <b-form-invalid-feedback
                                                        id="project-type-live-feedback"
                                                        style="color: red"
                                                        >{{
                                                            validationContext
                                                                .errors[0]
                                                        }}</b-form-invalid-feedback
                                                    >
                                                </validation-provider>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-footer">
                    <div class="footer-progress">
                      <ul>
                        <li v-for="(value, index) in project.steps" :key="index" :class="index == 1 ? 'active' : '' ">
                          <span class="percentage">{{ Math.round(100/project.steps * 2) }}%</span>
                          <span class="dots"></span>
                        </li>
                      </ul>
                      <p>{{ project.steps-2 }} of {{ project.steps }} Steps Remaining</p>
                    </div>
                    <div class="footer-block">
                        <button class="btn btn-dark" type="button" @click="goToFirst()">Go Back</button>
                    </div>
                    <div class="footer-block">
                        <button class="btn" type="submit">Next</button>
                    </div>
                </div>
            </b-form>
        </validation-observer>
    </div>
</template>

<script>
import EventBus from "../../../vue-asset";
export default {
    name: "SecondStep",
    props: ["project", "base_path", "saving"],
    data() {
        return {};
    },

    mounted() {},

    computed: {
        disableSubmitButton() {
            return this.saving ? true: false
        }
    },

    methods: {
        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        },

        saveSecond() {
            EventBus.$emit("second-step-submitted");
        },

        goToFirst() {
            EventBus.$emit('goToFirstForm-clicked')
        },

        saveLater()
        {
            EventBus.$emit("save-for-later-clicked")
        }
    },
};
</script>

<style lang="scss" scoped></style>
