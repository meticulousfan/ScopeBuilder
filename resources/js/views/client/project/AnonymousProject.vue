<template>
    <div>
        <div class="">
            <Sidebar />
        </div>
        <div v-if="showQuesions">
            <sign-up :id="project_id" ref="signupChild" />
        </div>
        <div v-if="!showQuesions" class="project-box">
            <div>
                <div class="modal-caption">
                    <h5 class="modal-title">Add Your Project</h5>
                </div>
                <div class="modal-form form">
                    <div class="form-fields">
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">
                                    {{ locale.project_name }}
                                </div>
                                <input
                                    type="text"
                                    v-model="payload.name"
                                    id="type_name"
                                    name="type_name"
                                    placeholder="Enter Project Name"
                                />
                            </div>
                        </div>
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">
                                    {{ locale.project_type }}
                                </div>
                                <el-select
                                    style="width: 100%"
                                    v-model="payload.type_id"
                                    placeholder="--Please choose an option--"
                                    @change="handleSelect"
                                >
                                    <el-option
                                        v-for="item in project_types_data"
                                        :key="item.id"
                                        :value="item.id"
                                        :label="item.name"
                                    >
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">
                                    {{ locale.specialty }}
                                </div>
                                <el-select
                                    style="width: 100%"
                                    v-model="payload.sub_type_id"
                                    placeholder="--Please choose an specialty--"
                                    @change="handleSpecialtySelect"
                                >
                                    <el-option
                                        v-for="item in specialty"
                                        :key="item.id"
                                        :value="item.id"
                                        :label="item.name"
                                    >
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                        <div class="form-field">
                            <div class="input-field">
                                <div class="field-label">
                                    {{ locale.skills }}
                                </div>
                                <el-select
                                    v-model="payload.skills_ids"
                                    multiple
                                    style="width: 100%"
                                    placeholder="--Please choose an option--"
                                >
                                    <el-option
                                        v-for="item in skills_option"
                                        :label="item.name"
                                        :key="item.id"
                                        :value="item.id"
                                    >
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        id=""
                        class="btn"
                        type="submit"
                        @click="submitProject"
                    >
                        Create Project
                    </button>
                </div>
            </div>
        </div>
        <div v-if="showQuesions">
            <Questions :id="project_id" ref="questionChild" />
        </div>
    </div>
</template>

<script>
import lang from "../../../../lang/en.json";
const base_path = window.location.origin;
import Questions from "../project/Questions.vue";
import SignUp from "../auth/SignUp.vue";
import Sidebar from "../anonymous/layouts/Sidebar.vue";

export default {
    data: () => ({
        project_types_data: [],
        sub_project_types_data: [],
        specialty: [],
        locale: lang,
        skills_data: [],
        skills_option: [],
        payload: {
            id: 0,
            type_id: null,
            sub_type_id: null,
            is_default: false,
            skills_ids: [],
            name: "",
        },
        project_id: 0,
        errors: {},
        showQuesions: false,
    }),
    name: "AnonymousProject",
    props: {
        id: String,
    },
    components: {
        Questions,
        SignUp,
        Sidebar,
    },
    created() {
        this.fetchProjetsType();
        this.fetchSkills();

        if (localStorage.anonymous_client) {
            this.getProject(localStorage.anonymous_client);
        }
    },
    mounted() {
        $(".select-skills").select2();
    },
    methods: {
        async fetchProjetsType() {
            try {
                const res = await window.axios.get(
                    `${base_path}/client/project-types/list`
                );
                this.project_types_data = res.data.project_types;
                this.sub_project_types_data = res.data.sub_project_types;
                this.project_types_data.length &&
                    this.changeSpeciality(this.project_types_data[0].id);
            } catch (error) {
                alert(String(error));
            }
        },
        async fetchSkills() {
            try {
                const res = await window.axios.get(
                    `${base_path}/client/skills/list`
                );
                this.skills_data = res.data.skills;
            } catch (error) {
                alert(String(error));
            }
        },
        async submitProject() {
            try {
                this.errors = null;
                this.loading = true;
                const anonymous_id = new Date().valueOf();
                this.payload.anonymous_id = anonymous_id;
                const response = await window.axios.post(
                    `${base_path}/client/projects/anonymous/store`,
                    this.payload
                );
                // console.log(response.data)
                if (response.data.success) {
                    localStorage.anonymous_client =
                        response.data.project.anonymous_user_id;
                    this.project_id = String(response.data.project.uuid);
                    this.showQuesions = true;
                }
            } catch (error) {
                alert(
                    "And error occured. Check that all the fields are correct."
                );
            } finally {
                this.loading = false;
            }
        },
        async getProject(anonymous_user_id) {
            try {
                const response = await window.axios.get(
                    `${base_path}/client/project/${anonymous_user_id}`
                );
                if (response.data.success) {
                    this.project_id = String(response.data.project.uuid);
                    this.showQuesions = true;
                }
            } catch (error) {
                alert(
                    "And error occured. Check that all the fields are correct."
                );
            }
        },
        changeSpeciality(type_id) {
            this.reset();
            this.specialty = [];
            this.sub_project_types_data.forEach((item) => {
                if (item.parent_id == type_id) {
                    return this.specialty.push(item);
                }
            });
        },
        changeSkillValue() {
            this.skills_option = this.skills_data;
            this.payload.skills_ids = [];
            const currentSpecialty = this.sub_project_types_data.find(
                (item) => item.id === this.payload.sub_type_id
            );
            let skillIds = JSON.parse(currentSpecialty.skills).split(",");
            skillIds.forEach((item) => {
                this.payload.skills_ids.push(Number(item));
            });
        },
        reset() {
            this.payload.skills_ids = [];
            this.payload.sub_type_id = null;
            this.skills_option = [];
        },
        handleSpecialtySelect() {
            this.changeSkillValue();
        },
        handleSelect() {
            this.changeSpeciality(this.payload.type_id);
        },
        openSignup() {
            this.$refs.signupChild.openSignupOrLogin();
        },
        openPaymentDialogue() {
            this.$refs.questionChild.openPaymentDialogue();
        },
        opneSignupDialogue() {
            this.$refs.signupChild.opneSignupDialogue();
        },
        openLoginDialogue() {
            this.$refs.signupChild.openLoginDialogue();
        },
    },
};
</script>

<style>
.project-box {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-form {
    min-width: 450px;
}
</style>
