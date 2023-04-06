<template>
    <div class="section-inner">
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(updateSeventh)">
                <input
                    type="hidden"
                    id="project-step"
                    v-model="project.step"
                />
                <div class="page-tab">
                    <div class="tab-content">
                        <div class="column" v-if="project.type == 'web' || project.type == 'both' ">
                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        Web - What frameworks would you prefer?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please choose from the list provided
                                            the frameworks.
                                        </p>
                                    </div>

                                    <div class="popover-block">
                                        <button class="block-opener">
                                            <svg class="btn-icon">
                                                <use
                                                    :xlink:href="base_path + 'ui_assets/img/icons-sprite.svg#info'"
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
                                <validation-provider
                                    name="Web Frameworks"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <div class="fields-group">
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="React JS"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >React JS</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Angular JS"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Angular JS</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="WordPress"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >WordPress</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Laravel"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Laravel</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Vue JS"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Vue JS</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="BigCommerce"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >BigCommerce</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Ruby on Rails"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Ruby on Rails</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value=".Net"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >.Net</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Django"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Django</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="PHP"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >PHP</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Node JS"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Node JS</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Python"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Python</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="HTML & CSS"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >HTML & CSS</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Tailwind"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Tailwind</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Squarespace"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Squarespace</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Shopify"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Shopify</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Webflow"
                                                            v-model="project.web_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Webflow</span
                                                        >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <b-form-invalid-feedback
                                        id="web-frameworks-live-feedback"
                                        style="color: red"
                                        >{{
                                            validationContext
                                                .errors[0]
                                        }}</b-form-invalid-feedback
                                    >
                                </validation-provider>
                            </div>
                        </div>
                        <div class="column" v-if="project.type == 'mobile' || project.type == 'both' ">
                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        Mobile - What frameworks would you
                                        prefer?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please choose from the list provided
                                            the frameworks.
                                        </p>
                                    </div>

                                    <div class="popover-block">
                                        <button class="block-opener">
                                            <svg class="btn-icon">
                                                <use
                                                    :xlink:href="base_path + 'ui_assets/img/icons-sprite.svg#info'"
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
                                <validation-provider
                                    name="Mobile Frameworks"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <div class="fields-group">
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="React Native"
                                                            v-model="project.mobile_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >React Native</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Flutter"
                                                            v-model="project.mobile_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Flutter</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Swift"
                                                            v-model="project.mobile_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Swift</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Java"
                                                            v-model="project.mobile_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Java</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Kotlin"
                                                            v-model="project.mobile_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Kotlin</span
                                                        >
                                                    </label>
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            class="
                                                                visually-hidden
                                                            "
                                                            value="Xamarin"
                                                            v-model="project.mobile_frameworks"
                                                        />
                                                        <span class="fake-label"
                                                            >Xamarin</span
                                                        >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <b-form-invalid-feedback
                                        id="mobile-frameworks-live-feedback"
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
                <div class="section-footer">
                    <div class="footer-progress">
                      <ul>
                        <li v-for="(value, index) in project.steps" :key="index" :class="index == project.steps - 1 ? 'active' : '' ">
                          <span class="percentage">100%</span>
                          <span class="dots"></span>
                        </li>
                      </ul>
                      <p>0 of {{ project.steps }} Steps Remaining</p>
                    </div>
                    <div class="footer-block">
                        <button class="btn btn-dark" type="button" @click="goToSixth()">
                            Go Back
                        </button>
                    </div>
                    <div class="footer-block">
                        <button class="btn" :disabled="disableSubmitButton" value="Update" type="submit">{{saving ? "Submitting..." : "Update"}}</button>
                    </div>
                </div>
            </b-form>
        </validation-observer>
    </div>
</template>

<script>
import EventBus from "../../../../vue-asset";
export default {
    name: "Seventhstep",

    props: ["project", "base_path", "data", "saving"],

    data() {
        return {
            notificationSystem: {
                options: {
                    success: {
                        overlay: true,
                        position: "center",
                        timeout: 3000,
                        class: 'complete_notification'
                    },
                    error: {
                        position: "center",
                        timeout: 4000,
                        class: 'error_notification'
                    },
                    completed: {
                        position: 'center',
                        timeout: 1000,
                        class: 'complete_notification'
                    },
                    info: {
                        overlay: true,
                        zindex: 999,
                        position: 'center',
                        timeout: 3000,
                        class: 'info_notification',
                    }
                }
            },
        };
    },

    computed: {
        disableSubmitButton() {
            return this.saving ? true: false
        }
    },

    created() {
        if(this.project.type == 'mobile')
        {
            this.project.web_frameworks = []
        }
        
        if(this.project.type == 'web')
        {
            this.project.mobile_frameworks = []
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

        updateSeventh() {
            EventBus.$emit("edit-updateForm-clicked");
        },

        goToSixth() {
            EventBus.$emit("edit-goToSixthForm-clicked");
        },

        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },
    },
};
</script>

<style lang="scss" scoped></style>
