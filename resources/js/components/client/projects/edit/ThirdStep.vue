<template>
    <div class="section-inner">
        <div class="buttons" v-if="project.is_draft == 1">
            <button @click="saveLater()" :disabled="disableSubmitButton" value="Save For Later" >{{saving ? "Submitting..." : "Save For Later"}}</button>
        </div>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(saveThird)">
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
                                        How many types of users will this
                                        project have?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please enter the number of different
                                            user types this project will have.
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
                                            <validation-provider
                                                name="Number of User Types"
                                                :rules="{
                                                    required: true,
                                                }"
                                                v-slot="validationContext"
                                            >
                                                <div class="form-field">
                                                    <div
                                                        class="
                                                            input-field
                                                            select-field
                                                        "
                                                    >
                                                        <div
                                                            class="field-label"
                                                        >
                                                            Number of User Types
                                                        </div>
                                                        <select
                                                            id="user-type"
                                                            v-model="
                                                                project.number_of_user_types
                                                            "
                                                            @change="
                                                                onChange($event)
                                                            "
                                                        >
                                                            <option value="1">
                                                                1 User Type
                                                            </option>
                                                            <option value="2">
                                                                2 User Types
                                                            </option>
                                                            <option
                                                                value="3"
                                                            >3 User Types
                                                            </option>
                                                            <option value="4">
                                                                4 User Types
                                                            </option>
                                                            <option value="5">
                                                                5 User Types
                                                            </option>
                                                            <option value="6">
                                                                6 User Types
                                                            </option>
                                                            <option value="7">
                                                                7 User Types
                                                            </option>
                                                            <option value="8">
                                                                8 User Types
                                                            </option>
                                                            <option value="9">
                                                                9 User Types
                                                            </option>
                                                            <option value="10">
                                                                10 User Types
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <b-form-invalid-feedback
                                                    id="user-type-live-feedback"
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

                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        Define the user types of your project.
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Based on your selection above,
                                            please define the different user
                                            types of this project.
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
                                            <div
                                                class="form-field"
                                                v-for="(
                                                    value, index
                                                ) in project.user_types"
                                                v-bind:key="index"
                                            >
                                                <validation-provider
                                                    name="User types"
                                                    :rules="{
                                                        required: true,
                                                        min: 3,
                                                    }"
                                                    v-slot="validationContext"
                                                >
                                                    <div class="input-field">
                                                        <div
                                                            class="field-label"
                                                        >
                                                            User Type
                                                            {{ index + 1 }}
                                                        </div>
                                                        <input
                                                            type="text"
                                                            name="user-types"
                                                            value=""
                                                            v-model="value.type"
                                                        />
                                                    </div>
                                                    <b-form-invalid-feedback
                                                        id="user-types-live-feedback"
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
                        <li v-for="(value, index) in project.steps" :key="index" :class="index == 2 ? 'active' : '' ">
                          <span class="percentage">{{ Math.round(100/project.steps * 3) }}%</span>
                          <span class="dots"></span>
                        </li>
                      </ul>
                      <p>{{ project.steps-3 }} of {{ project.steps }} Steps Remaining</p>
                    </div>
                    <div class="footer-block">
                        <button
                            class="btn btn-dark"
                            type="button"
                            @click="goToSecond()"
                        >
                            Go Back
                        </button>
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
import EventBus from "../../../../vue-asset";
export default {
    name: "Thirdstep",

    props: ["project", "base_path", "saving"],

    data() {
        return {
            user_types_count: 0,
        };
    },

    created() {
        if (this.project.user_types.length == 0) {
            this.user_types_count = this.project.number_of_user_types;
            for (let index = 0; index < this.user_types_count; index++) {
                this.project.user_types.push({
                    type: "",
                });
            }
        }
    },

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

        saveThird() {
            let values = [];
            let hasSameValue = false
            for (
                let index = 0;
                index < this.project.user_types.length;
                index++
            ) {
                const element = this.project.user_types[index].type;
                if (values.indexOf(element) !== -1) {
                    hasSameValue = true
                }
                values.push(element);
            }
            if(hasSameValue)
            {
                alert('Please use different names.')
            }
            else
            {
                EventBus.$emit("edit-third-step-submitted");
            }
        },

        goToSecond() {
            EventBus.$emit("edit-goToSecondForm-clicked");
        },

        onChange(event) {
            let diff = 0
            this.user_types_count = event.target.value;
            if(this.project.user_types.length < this.user_types_count)
            {
                diff = this.user_types_count - this.project.user_types.length
                for (let index = 0; index < diff; index++) {
                    this.project.user_types.push({
                        type: "",
                    });
                }    
            }
            else
            {
                diff = this.project.user_types.length - this.user_types_count
                for (let index = 0; index < diff; index++) {
                    for(let i = this.project.user_types.length-1; i >= this.user_types_count; i--) {
                        this.project.user_types.splice(i, 1)
                    }

                    // Remove Users from Pages Information
                    if(this.project.pages_information.length > 0)
                    {
                        for (let i = 0; i < this.project.pages_information.length; i++) {
                            const element = this.project.pages_information[i];
                            for (let j = 0; j < element.users.length; j++) {
                                const user = element.users[j];
                                for (let k = 0; k < this.project.user_types.length; k++) {
                                    const type = this.project.user_types[k];
                                    if(user.user != type.type)
                                    {
                                        element.users.splice(j, 1)
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },

        saveLater()
        {
            EventBus.$emit("edit-save-for-later-clicked")
        }
    },
};
</script>

<style lang="scss" scoped></style>
