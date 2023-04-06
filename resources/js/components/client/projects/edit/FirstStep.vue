<template>
    <div class="section-inner">
        <validation-observer ref="observer" v-slot="{ handleSubmit }">
            <b-form @submit.stop.prevent="handleSubmit(saveFirst)">
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
                                        What is the name of the project?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please enter a suitable name for
                                            your project.
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
                                <validation-provider
                                    name="Project Name"
                                    :rules="{ required: true, min: 3 }"
                                    v-slot="validationContext"
                                >
                                    <div class="block-content">
                                        <div class="block-form form">
                                            <div class="form-fields">
                                                <div class="form-field">
                                                    <div class="input-field">
                                                        <div
                                                            class="field-label"
                                                        >
                                                            Project Name
                                                        </div>
                                                        <input
                                                            type="text"
                                                            id="project-name"
                                                            v-model="
                                                                project.name
                                                            "
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <b-form-invalid-feedback
                                        id="project-name-live-feedback"
                                        style="color: red"
                                        >{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback
                                    >
                                </validation-provider>
                            </div>

                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        What is your project description?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please enter a suitable description
                                            for your project.
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
                                <validation-provider
                                    name="Project Description"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                    <div class="block-content">
                                        <div class="block-form form">
                                            <div class="form-fields">
                                                <div class="form-field">
                                                    <div class="input-field">
                                                        <div
                                                            class="field-label"
                                                        >
                                                            Project Description
                                                        </div>
                                                        <textarea
                                                            id="description"
                                                            v-model="
                                                                project.description
                                                            "
                                                        >
                                                        </textarea>
                                                        <!-- <div
                                                            class="
                                                                field-allowed-count
                                                            "
                                                            data-max="500"
                                                        ></div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <b-form-invalid-feedback
                                        id="description-live-feedback"
                                        style="color: red"
                                        >{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback
                                    >
                                </validation-provider>
                            </div>

                            <div class="question-block">
                                <div class="block-top">
                                    <h3 class="block-caption">
                                        Do you have any example projects you
                                        would like to share?
                                    </h3>
                                    <div class="block-note">
                                        <p>
                                            Please use the form below to add
                                            links to projects you want to share.
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
                                <validation-provider
                                    name="Example Projects"
                                    :rules="{ required: true, min: 7 }"
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
                                                            <div class="item" v-for="(value,index) in project.example_projects" v-bind:key="index">
                                                                <div
                                                                    class="
                                                                        input-field
                                                                    "
                                                                    style="
                                                                        width: -webkit-fill-available;
                                                                    "
                                                                    v-show="value.project != null"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        id="example-projects"
                                                                        v-model="value.project"
                                                                    />
                                                                </div>
                                                                <button
                                                                    class="
                                                                        action-btn
                                                                    "
                                                                    v-show="index || ( !index && project.example_projects_count > 1)" type="button" @click="remove(index, 1)"
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
                                                            id="example-projects-live-feedback"
                                                            style="color: red"
                                                            >{{
                                                                validationContext
                                                                    .errors[0]
                                                            }}</b-form-invalid-feedback
                                                        >
                                                        <button class="add-btn"
                                                            @click="addExampleProject()"
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
                    </div>
                </div>

                <div class="section-footer">
                    <div class="footer-progress">
                      <ul>
                        <li v-for="(value, index) in project.steps" :key="index" :class="index == 0 ? 'active' : '' ">
                          <span class="percentage">{{ Math.round(100/project.steps) }}%</span>
                          <span class="dots"></span>
                        </li>
                      </ul>
                      <p>{{ project.steps-1 }} of {{ project.steps }} Steps Remaining</p>
                    </div>
                    <div class="footer-block">
                        <a :href="base_path + 'client/projects'" class="btn btn-dark">Cancel</a>
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
import EventBus from '../../../../vue-asset'
export default {
    name: "FirstStep",
    props: ["project", "base_path"],
    data() {
        return {};
    },

    mounted() {},

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

        addExampleProject() {
            this.project.example_projects.push({
                project: "",
            });
            this.project.example_projects_count =
                this.project.example_projects_count + 1;
        },
        remove(index) {
            this.project.example_projects.splice(index, 1);
            this.project.example_projects_count =
                this.project.example_projects_count - 1;
        },

        saveFirst() {
            EventBus.$emit('edit-first-step-submitted')
        },
    },
};
</script>

<style lang="scss" scoped></style>
