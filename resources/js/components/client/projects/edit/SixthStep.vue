<template>
    <div class="section-inner">
        <div class="buttons">
            <button @click="skipUser()">Skip</button>
            <button v-if="project.is_draft == 1" @click="saveLater()" :disabled="disableSubmitButton" value="Save For Later" >{{saving ? "Submitting..." : "Save For Later"}}</button>
        </div>
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(saveSixth)">
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
                                        Page {{ page_index + 1 }} -
                                        {{
                                            project.pages[page_index].page
                                        }} 
                                    </h3>
                                    <h3 class="page-user">
                                    User : {{ project.pages_information[page_index].users[current_index].user }}
                                    </h3>
                                    <h3 class="page-user_info">Information Displayed</h3>
                                    <div class="block-note">
                                        <p>
                                            Please define what information is
                                            displayed to this user.
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
                                    name="Infomation"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                    <div class="block-content">
                                        <div class="block-form form">
                                            <div class="form-fields">
                                                <div class="form-field">
                                                    <div
                                                        class="values-component"
                                                    >
                                                        <div
                                                            class="values-list"
                                                        >
                                                            <div class="item" v-for="(value,index) in project.pages_information[page_index].users[user_index].information" v-bind:key="index">
                                                                <div
                                                                    class="
                                                                        input-field
                                                                    "
                                                                    style="
                                                                        width: -webkit-fill-available;
                                                                    "
                                                                    v-show="value.text != null"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        id="informaion"
                                                                        v-model="value.text"
                                                                    />
                                                                </div>
                                                                <button
                                                                    class="
                                                                        action-btn
                                                                    "
                                                                    v-show="index || ( !index && information_count > 1)" type="button" @click="removeInformation(index, 1)"
                                                                >
                                                                    <svg
                                                                        class="
                                                                            btn-icon
                                                                        "
                                                                    >
                                                                        <use
                                                                            :xlink:href="
                                                                                base_path +
                                                                                'ui_assets/img/icons-sprite.svg#trash'
                                                                            "
                                                                        ></use>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <b-form-invalid-feedback
                                                            id="informaion-live-feedback"
                                                            style="color: red"
                                                            >{{
                                                                validationContext
                                                                    .errors[0]
                                                            }}</b-form-invalid-feedback
                                                        >
                                                        <button class="add-btn"
                                                            @click="addInformation()"
                                                            type="button"
                                                            >
                                                            <svg
                                                                class="btn-icon"
                                                            >
                                                                <use
                                                                    :xlink:href="
                                                                        base_path +
                                                                        'ui_assets/img/icons-sprite.svg#plus'
                                                                    "
                                                                ></use>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </validation-provider>
                            </div>
                        </div>
                        <div class="column">
                            <div class="question-block">
                                <div class="block-top">
                                  <h3 class="block-caption">
                                        Page {{ page_index + 1 }} -
                                        {{
                                            project.pages[page_index].page
                                        }}                                       
                                    </h3>
                                     <h3 class="page-user">
                                        User : {{ project.pages_information[page_index].users[current_index].user }}
                                    </h3>
                                    <h3 class="page-user_info">Performable Actions</h3>
                                    <div class="block-note">
                                        <p>
                                            Please define what information is
                                            displayed to this user.
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
                                    name="Perfomable Actions"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                    <div class="block-content">
                                        <div class="block-form form">
                                            <div class="form-fields">
                                                <div class="form-field">
                                                    <div
                                                        class="values-component"
                                                    >
                                                        <div
                                                            class="values-list"
                                                        >
                                                            <div class="item" v-for="(value,index) in project.pages_information[page_index].users[user_index].actions" v-bind:key="index">
                                                                <div
                                                                    class="
                                                                        input-field
                                                                    "
                                                                    style="
                                                                        width: -webkit-fill-available;
                                                                    "
                                                                    v-show="value.text != null"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        id="example-projects"
                                                                        v-model="value.text"
                                                                    />
                                                                </div>
                                                                <button
                                                                    class="
                                                                        action-btn
                                                                    "
                                                                    v-show="index || ( !index && actions_count > 1)" type="button" @click="removeAction(index, 1)"
                                                                >
                                                                    <svg
                                                                        class="
                                                                            btn-icon
                                                                        "
                                                                    >
                                                                        <use
                                                                            :xlink:href="
                                                                                base_path +
                                                                                'ui_assets/img/icons-sprite.svg#trash'
                                                                            "
                                                                        ></use>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <b-form-invalid-feedback
                                                            id="actions-live-feedback"
                                                            style="color: red"
                                                            >{{
                                                                validationContext
                                                                    .errors[0]
                                                            }}</b-form-invalid-feedback
                                                        >
                                                        <button class="add-btn"
                                                            @click="addAction()"
                                                            type="button"
                                                            >
                                                            <svg
                                                                class="btn-icon"
                                                            >
                                                                <use
                                                                    :xlink:href="
                                                                        base_path +
                                                                        'ui_assets/img/icons-sprite.svg#plus'
                                                                    "
                                                                ></use>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </validation-provider>
                            </div>
                        </div>
                        <div class="column" v-if="current_index < (project.pages_information[page_index].users.length - 1)">
                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        Duplicate Information
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please select whether you'd like to
                                            duplicate this information for the
                                            next user.
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
                                <div class="block-content">
                                    <div class="block-form form">
                                        <div class="form-fields">
                                            <div class="form-field">
                                                <div class="fields-group">
                                                    <label class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            v-model="duplicate"
                                                            class="
                                                                visually-hidden
                                                            "
                                                        />
                                                        <span class="fake-label"
                                                            >Duplicate
                                                            Information</span
                                                        >
                                                    </label>
                                                </div>
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
                        <li v-for="(value, index) in project.steps" :key="index" :class="index == (5 + project.page_step) ? 'active' : '' ">
                          <span class="percentage">{{ Math.round(100/project.steps * (6 + project.page_step ) ) }}%</span>
                          <span class="dots"></span>
                        </li>
                      </ul>
                      <p>{{ project.steps- (6 + project.page_step ) }} of {{ project.steps }} Steps Remaining</p>
                    </div>
                    <div class="footer-block">
                        <button class="btn btn-dark" type="button" @click="goToFifth()">
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
    name: "Sixthstep",

    props: ["project", "base_path", "page_index", "user_index", "saving"],

    data() {
        return {
            current_index: 0,
            information_count: 1,
            actions_count: 1,
            duplicate: false
        };
    },

    created() {
        var _this = this
        _this.current_index = _this.user_index
       let values = []
        let count = 0
        for (
            let j = _this.project.pages_information[_this.page_index].users.length - 1;
            j >= 0;
            j--
        ) {
            const el = _this.project.pages_information[_this.page_index].users[j]
            if(el.user)
            {
                const element = el.user;
                let information = []
                let actions = []
                if (values.indexOf(element) !== -1) {
                    let index = values.indexOf(element)
                    information = _this.project.pages_information[_this.page_index].users[index].information
                    actions = _this.project.pages_information[_this.page_index].users[index].actions
                    _this.project.pages_information[_this.page_index].users.splice(index, 1)
                    count = count + 1
                    let users = _this.project.pages_information[_this.page_index].users.length - count
                    if(_this.project.pages_information[_this.page_index].users[users])
                    {
                        if(_this.project.pages_information[_this.page_index].users[users].information && _this.project.pages_information[_this.page_index].users[users].actions)
                        {
                            _this.project.pages_information[_this.page_index].users[users].information = information
                            _this.project.pages_information[_this.page_index].users[users].actions = actions
                        }
                    }
                }
                values.push(element);
            }
            _this.information_count = _this.project.pages_information[_this.page_index].users[_this.user_index].information.length
            _this.actions_count = _this.project.pages_information[_this.page_index].users[_this.user_index].actions.length
        }

        const user = _this.project.pages_information[_this.page_index].users[_this.user_index]
        for (let index = 0; index < user.information.length; index++) {
            const element = user.information[index];
            if(element.text == null)
            {
                element.text = ''
            }
        }

        for (let index = 0; index < user.actions.length; index++) {
            const element = user.actions[index];
            if(element.text == null)
            {
                element.text = ''
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

        addInformation() {
            this.project.pages_information[this.page_index].users[this.user_index].information.push({
                text: "",
            });
            this.information_count =
                this.information_count + 1;
        },

        addAction() {
            this.project.pages_information[this.page_index].users[this.user_index].actions.push({
                text: "",
            });
            this.actions_count =
                this.actions_count + 1;
        },

        removeInformation(index) {
            this.project.pages_information[this.page_index].users[this.user_index].information.splice(index, 1);
            this.information_count =
                this.information_count - 1;
        },

        removeAction(index) {
            this.project.pages_information[this.page_index].users[this.user_index].actions.splice(index, 1);
            this.actions_count =
                this.actions_count - 1;
        },

        saveSixth() {
            if(this.project.pages_information[this.page_index].users.length-1 >= this.current_index + 1 ) {
                EventBus.$emit('edit-repeat-sixth-step', this.current_index, this.duplicate)    
            }
            else if(this.current_index == (this.project.pages_information[this.page_index].users.length - 1)) {
                if(this.page_index < (this.project.number_of_pages - 1)) 
                {
                    this.current_index = 0   
                    EventBus.$emit('edit-repeat-sixth-step-for-next-page', this.page_index)
                }
                else
                {
                    EventBus.$emit("edit-sixth-step-submitted");    
                }
            }
            else
            {
                EventBus.$emit("edit-sixth-step-submitted");    
            }
        },

        goToFifth() {
            if( this.current_index > 0 && (this.project.pages_information[this.page_index].users.length-1) >= (this.current_index) ) {
                EventBus.$emit('edit-previous-sixth-step', this.current_index)    
            }
            else if(this.page_index > 0 && this.page_index <= this.project.pages_information.length)
            {
                EventBus.$emit('edit-previous-sixth-step-for-page', this.page_index, this.current_index)    
            }
            else
            {
                EventBus.$emit("edit-goToFifthForm-clicked");
            }
        },

        saveLater()
        {
            EventBus.$emit("edit-save-for-later-clicked")
        },

        skipUser() {
            if(this.project.pages_information[this.page_index].users.length-1 >= this.current_index + 1 ) {
                EventBus.$emit('edit-skip-user', this.current_index)    
            }
            else if(this.current_index == (this.project.pages_information[this.page_index].users.length - 1)) {
                if(this.page_index < (this.project.number_of_pages - 1)) 
                {
                    EventBus.$emit('edit-skip-page', this.page_index)
                }
                else {
                    EventBus.$emit('edit-skip-last')
                }
            }
            else {
                // console.log('works')
                EventBus.$emit('skip-last')
            }
        },
    },
};
</script>

<style lang="scss" scoped></style>
