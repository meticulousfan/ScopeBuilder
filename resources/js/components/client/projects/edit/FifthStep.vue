<template>
    <div class="section-inner">
        <div class="buttons" v-if="project.is_draft == 1">
            <button @click="saveLater()" :disabled="disableSubmitButton" value="Save For Later" >{{saving ? "Submitting..." : "Save For Later"}}</button>
        </div>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(saveFifth)">
                <input
                    type="hidden"
                    id="project-step"
                    v-model="project.step"
                />
                <div class="page-tab">
                    <div v-for="(value, index) in project.pages" :key="index" class="tab-content" :style="index > 0 ? 'margin-top: 3%;' : ''">
                        <div class="column" style="width: 120%;">
                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        Page {{ index + 1 }} -
                                        {{
                                            project.pages[index].page
                                        }}
                                        Page
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please choose which users will have
                                            access to this page.
                                        </p>
                                    </div>
                                    <div class="popover-block" v-if="project.pages_information[index].myusers.length < project.user_types.length">
                                        <label
                                            class="checkbox"
                                        >
                                            <input
                                                type="checkbox"
                                                class="
                                                    visually-hidden
                                                "
                                                @click="selectAll(index)"
                                            />
                                            <span
                                                class="
                                                    fake-label
                                                "
                                                style="border: none;"
                                                >Check All</span
                                            >
                                        </label>
                                        <!-- <button class="block-opener">
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
                                        </div> -->
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <validation-provider
                                                    name="User Type"
                                                    :rules="{
                                                        required: true,
                                                    }"
                                                    v-slot="validationContext"
                                                >
                                                    <div class="fields-group">
                                                        <label
                                                            class="checkbox"
                                                            v-for="(
                                                                value, index1
                                                            ) in project.user_types"
                                                            :key="index1"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                v-model="
                                                                    project.pages_information[index].myusers
                                                                "
                                                                :value="
                                                                    value.type
                                                                "
                                                                class="
                                                                    visually-hidden
                                                                "
                                                                @change="onChange($event, index)"
                                                            />
                                                            <span
                                                                class="
                                                                    fake-label
                                                                "
                                                                >{{
                                                                    value.type
                                                                }}</span
                                                            >
                                                        </label>
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-footer">
                    <div class="footer-progress">
                      <ul>
                        <li v-for="(value, index) in project.steps" :key="index" :class="index == 4 ? 'active' : '' ">
                          <span class="percentage">{{ Math.round(100/project.steps * 5) }}%</span>
                          <span class="dots"></span>
                        </li>
                      </ul>
                      <p>{{ project.steps-5 }} of {{ project.steps }} Steps Remaining</p>
                    </div>
                    <div class="footer-block">
                        <button class="btn btn-dark" type="button" @click="goToFourth()">
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
    name: "Fifthstep",

    props: ["project", "base_path", "page_index", "saving"],

    data() {
        return {
            pages_index: 0,
            users: []
        };
    },

    created() {
        var _this = this;
        let steps = 0
        _this.pages_index = _this.project.pages.length;
        _this.project.page_step = 0
        for (let index = 0; index < this.project.pages.length; index++) {
            const element = this.project.pages[index];
            if(_this.project.pages_information.length <= index)
            {
                _this.project.pages_information.push({
                    page: _this.project.pages[index].page,
                    users: [],
                    myusers: []
                })
            }
            if(_this.project.pages_information[index].users.length > 0)
            {
                for (let i = 0; i < _this.project.pages_information[index].users.length; i++) {
                    const element = _this.project.pages_information[index].users[i];
                    if(element.user)
                    {
                        _this.project.pages_information[index].myusers.push(element.user)
                        steps = steps + 1
                    }
                }
               
            }
        }
        if(_this.project.steps <= 6)
        {
            _this.project.steps = _this.project.steps + steps
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

        saveFifth() {
            this.users = []
            for (let index = 0; index < this.project.pages_information.length; index++) {
                const element = this.project.pages_information[index];
                this.users = element.myusers
                element.myusers = []
                console.log(this.users.length)
                for(let i = 0; i < this.users.length; i++)
                {
                    const el = this.users[i]
                    // element.users.push({
                        // user: el,
                        // information: [{
                        //     text: ''
                        // }],
                        // actions: [{
                        //     text: ''
                        // }]
                    // })
                }
                this.users = [] 
            }
            EventBus.$emit("edit-fifth-step-submitted", 0);
        },

        goToFourth() {
            EventBus.$emit("edit-goToFourthForm-clicked");
        },

        selectAll(page) {
            for (let index = 0; index < this.project.user_types.length; index++) {
                const element = this.project.user_types[index];
                this.project.pages_information[page].myusers.push(element.type)
            }
        },

        onChange(event, page)
        {
            if(!event.target.checked)
            {
                for (let index = 0; index < this.project.pages_information[page].users.length; index++) {
                    const element = this.project.pages_information[page].users[index];
                    if(event.target.value == element.user)
                    {
                        this.project.pages_information[page].users.splice(index, 1)
                    }
                }
                this.project.steps = this.project.steps - 1
            }
            else
            {
                this.project.pages_information[page].users.push({
                    user: event.target.value,
                    information: [{
                        text: ''
                    }],
                    actions: [{
                        text: ''
                    }]
                })
                this.project.steps = this.project.steps + 1
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
