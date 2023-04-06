<template>
    <div class="section-inner">
        <div class="buttons" v-if="project.is_draft == 1">
            <button @click="saveLater()" :disabled="disableSubmitButton" value="Save For Later" >{{saving ? "Submitting..." : "Save For Later"}}</button>
        </div>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(saveFourth)">
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
                                        How many pages will this project have?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please enter the number of different
                                            pages for this project.
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
                                                name="Number of Pages"
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
                                                            Number of Pages
                                                        </div>
                                                        <select
                                                            v-model="
                                                                project.number_of_pages
                                                            "
                                                            @change="
                                                                onChange($event)
                                                            "
                                                        >
                                                            <option value="1">
                                                                1 Page
                                                            </option>
                                                            <option value="2">
                                                                2 Pages
                                                            </option>
                                                            <option
                                                                value="3"
                                                            >
                                                                3 Pages
                                                            </option>
                                                            <option value="4">
                                                                4 Pages
                                                            </option>
                                                            <option value="5">
                                                                5 Pages
                                                            </option>
                                                            <option value="6">
                                                                6 Pages
                                                            </option>
                                                            <option value="7">
                                                                7 Pages
                                                            </option>
                                                            <option value="8">
                                                                8 Pages
                                                            </option>
                                                            <option value="9">
                                                                9 Pages
                                                            </option>
                                                            <option value="10">
                                                                10 Pages
                                                            </option>
                                                            <option value="11">
                                                                11 Pages
                                                            </option>
                                                            <option value="12">
                                                                12 Pages
                                                            </option>
                                                            <option value="13">
                                                                13 Pages
                                                            </option>
                                                            <option value="14">
                                                                14 Pages
                                                            </option>
                                                            <option value="15">
                                                                15 Pages
                                                            </option>
                                                            <option value="16">
                                                                16 Pages
                                                            </option>
                                                            <option value="17">
                                                                17 Pages
                                                            </option>
                                                            <option value="18">
                                                                18 Pages
                                                            </option>
                                                            <option value="19">
                                                                19 Pages
                                                            </option>
                                                            <option value="20">
                                                                20 Pages
                                                            </option>
                                                            <option value="21">
                                                                21 Pages
                                                            </option>
                                                            <option value="22">
                                                                22 Pages
                                                            </option>
                                                            <option value="23">
                                                                23 Pages
                                                            </option>
                                                            <option value="24">
                                                                24 Pages
                                                            </option>
                                                            <option value="25">
                                                                25 Pages
                                                            </option>
                                                            <option value="26">
                                                                26 Pages
                                                            </option>
                                                            <option value="27">
                                                                27 Pages
                                                            </option>
                                                            <option value="28">
                                                                28 Pages
                                                            </option>
                                                            <option value="29">
                                                                29 Pages
                                                            </option>
                                                            <option value="30">
                                                                30 Pages
                                                            </option>
                                                            <option value="31">
                                                                31 Pages
                                                            </option>
                                                            <option value="32">
                                                                32 Pages
                                                            </option>
                                                            <option value="33">
                                                                33 Pages
                                                            </option>
                                                            <option value="34">
                                                                34 Pages
                                                            </option>
                                                            <option value="35">
                                                                35 Pages
                                                            </option>
                                                            <option value="36">
                                                                36 Pages
                                                            </option>
                                                            <option value="37">
                                                                37 Pages
                                                            </option>
                                                            <option value="38">
                                                                38 Pages
                                                            </option>
                                                            <option value="39">
                                                                39 Pages
                                                            </option>
                                                            <option value="40">
                                                                40 Pages
                                                            </option>
                                                            <option value="41">
                                                                41 Pages
                                                            </option>
                                                            <option value="42">
                                                                42 Pages
                                                            </option>
                                                            <option value="43">
                                                                43 Pages
                                                            </option>
                                                            <option value="44">
                                                                44 Pages
                                                            </option>
                                                            <option value="45">
                                                                45 Pages
                                                            </option>
                                                            <option value="46">
                                                                46 Pages
                                                            </option>
                                                            <option value="47">
                                                                47 Pages
                                                            </option>
                                                            <option value="48">
                                                                48 Pages
                                                            </option>
                                                            <option value="49">
                                                                49 Pages
                                                            </option>
                                                            <option value="50">
                                                                50 Pages
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <b-form-invalid-feedback
                                                    id="number-of-pages-live-feedback"
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
                                        Define the pages of your project.
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Based on your selection above,
                                            please define the names for the
                                            different pages of this project.
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
                                        <div class="form-fields columns-2">
                                            <div
                                                class="form-field"
                                                v-for="(
                                                    value, index
                                                ) in project.pages"
                                                v-bind:key="index"
                                            >
                                                <validation-provider
                                                    name="All Page Name"
                                                    :rules="{
                                                        required: true,
                                                    }"
                                                    v-slot="validationContext"
                                                >
                                                    <div class="input-field">
                                                        <div
                                                            class="field-label"
                                                        >
                                                            Page
                                                            {{ index + 1 }} Name
                                                        </div>
                                                        <input
                                                            type="text"
                                                            :id="index + 'page'"
                                                            v-model="value.page"
                                                        />
                                                    </div>
                                                    <b-form-invalid-feedback
                                                        :id="
                                                            index +
                                                            'page-live-feedback'
                                                        "
                                                        style="color: red"
                                                        >{{
                                                            validationContext
                                                                .errors[0]
                                                        }}
                                                    </b-form-invalid-feedback>
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
                        <li v-for="(value, index) in project.steps" :key="index" :class="index == 3 ? 'active' : '' ">
                          <span class="percentage">{{ Math.round(100/project.steps * 4) }}%</span>
                          <span class="dots"></span>
                        </li>
                      </ul>
                      <p>{{ project.steps-4 }} of {{ project.steps }} Steps Remaining</p>
                    </div>
                    <div class="footer-block">
                        <button
                            class="btn btn-dark"
                            type="button"
                            @click="goToThird()"
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
    name: "Fourthstep",

    props: ["project", "base_path", "saving"],

    data() {
        return {
            pages_count: 0,
        };
    },

    created() {
        if (this.project.pages.length == 0) {
            this.pages_count = this.project.number_of_pages;
            for (let index = 0; index < this.pages_count; index++) {
                this.project.pages.push({
                    page: "",
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

        saveFourth() {
            let values = [];
            let hasSameValue = false;
            for (
                let index = 0;
                index < this.project.pages.length;
                index++
            ) {
                const element = this.project.pages[index].page;
                if (values.indexOf(element) !== -1) {
                    hasSameValue = true;
                }
                values.push(element);
            }
            if (hasSameValue) {
                alert("Please use different names.");
            } else {
                EventBus.$emit("edit-fourth-step-submitted");
            }
        },

        goToThird() {
            EventBus.$emit("edit-goToThirdForm-clicked");
        },

        onChange(event) {
            let diff = 0
            this.pages_count = event.target.value;
            this.pages_count = this.project.number_of_pages;
            if(this.project.pages.length < this.pages_count)
            {
                diff = this.pages_count - this.project.pages.length
                for (let index = 0; index < diff; index++) {
                    this.project.pages.push({
                        page: "",
                    });
                }    
            }
            else
            {
                diff = this.project.pages.length - this.pages_count
                for (let index = 0; index < diff; index++) {
                    for(let i = this.project.pages.length-1; i >= this.pages_count; i--) {
                        this.project.pages.splice(i, 1)
                    }

                    // Remove Page from Pages Information
                    if(this.project.pages_information.length > 0)
                    {
                        for (let i = 0; i < this.project.pages_information.length; i++) {
                            const element = this.project.pages_information[i];
                            for (let k = 0; k < this.project.pages.length; k++) {
                                const page = this.project.pages[k];
                                if(element.page != page.page)
                                {
                                    this.project.pages_information.splice(i, 1)
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
