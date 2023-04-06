<template>
    <section>
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
                            <h1
                                class="page-caption"
                                style="margin: 0px !important"
                            >
                                {{ locale.questionnaires }}
                            </h1>
                            <div
                                style="
                                    width: 47px;
                                    height: 26px;
                                    margin: 1px 16px;
                                    background: #1890ff 0% 0% no-repeat
                                        padding-box;
                                    border-radius: 13px;
                                    opacity: 1;
                                    text-align: center;
                                    align-items: center;
                                    display: grid;
                                "
                            >
                                <div style="font-size: 13px; color: white">
                                    {{ rowData.length }}
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-main">
            <div class="page-content">
                <section class="default-section">
                    <div class="container">
                        <div>
                            <button
                                class="btn setting-btn"
                                type="button"
                                style="float: right"
                                @click="handleNewQuestionnaire"
                            >
                                {{ locale.new_questionnaire }}
                            </button>

                            <button
                                class="client-act-btn deleteType d-none"
                                id="remove_id"
                                data-modal="#remove-questionnaire-modal"
                            ></button>
                        </div>

                        <div class="section-inner">
                            <div class="success-message" role="alert">
                                {{ this.message }}
                            </div>
                            <div class="table-wrapper">
                                <table
                                    v-loading="loading"
                                    class="table clients_table"
                                >
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>{{ locale.name }}</th>
                                            <th>{{ locale.project_type }}</th>
                                            <th>{{ locale.skills }}</th>
                                            <th>{{ locale.step_number }}</th>
                                            <th>{{ locale.active_status }}</th>
                                            <th>{{ locale.action }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(item, idx) in rowData"
                                            :key="item.id"
                                        >
                                            <td>{{ idx + 1 }}</td>
                                            <td>
                                                <a
                                                    :href="`/admin/questionnaire/${item.id}/questions/manage`"
                                                >
                                                    {{ item.name }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ item.project_types.name }}
                                            </td>
                                            <td>
                                                <span
                                                    v-for="(
                                                        skill, id
                                                    ) in item.skills"
                                                    :key="id"
                                                    class="mr-1"
                                                    >{{ skill.name }}
                                                </span>
                                            </td>
                                            <td>{{ item.step }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input
                                                        @change="
                                                            changeStatus(item)
                                                        "
                                                        :data-id="item.id"
                                                        class="toggle-class"
                                                        type="checkbox"
                                                        data-onstyle="success"
                                                        data-offstyle="danger"
                                                        data-toggle="toggle"
                                                        data-on="Active"
                                                        data-off="InActive"
                                                        :checked="item.status"
                                                    />
                                                    <span
                                                        class="slider round"
                                                    ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <button
                                                    class="editeType"
                                                    @click="
                                                        handleEditQuestionnaire(
                                                            item
                                                        )
                                                    "
                                                >
                                                    <img
                                                        src="/ui_assets/img/icons/vuesax-bulk-document-text.svg"
                                                    />
                                                </button>

                                                <button
                                                    class="client-act-btn deleteType"
                                                    @click="
                                                        handleDelete(item.id)
                                                    "
                                                >
                                                    <svg class="btn-icon">
                                                        <use
                                                            xlink:href="/ui_assets/img/icons-sprite.svg#trash"
                                                        ></use>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-auto pagination-wrap">
                                    <div class="float-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <div class="modal" id="remove-questionnaire-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <button
                        class="modal-close"
                        aria-label="Close modal"
                    ></button>
                    <div class="modal-caption">
                        <div class="mc-icon">
                            <img src="/ui_assets/img/icons/trash.svg" alt="" />
                        </div>
                        <h3 class="mc-title">
                            <strong>Delete Questionnaire</strong>
                        </h3>
                        <p class="mc-subtitle">
                            Are you sure you wish to delete this Questionnaire ?
                            This action will&nbsp;remove the Questionnaire and
                            can not be undone.
                        </p>
                    </div>

                    <div
                        class="modal-footer"
                        style="flex-wrap: inherit; padding: 0px; border: 0px"
                    >
                        <button
                            class="btn btn-light js-modal-close"
                            id="cancel-delete"
                            style="
                                padding: 10px 20px;
                                min-height: 54px;
                                border: 1px solid var(--btn-bg);
                                border-radius: var(--r);
                                background: var(--btn-bg);
                                color: var(--btn-color);
                            "
                        >
                            Cancel
                        </button>
                        <button
                            class="btn"
                            @click="deleteQuestionnaire()"
                            style="
                                padding: 10px 20px;
                                min-height: 54px;
                                border: 1px solid var(--btn-bg);
                                border-radius: var(--r);
                                background: var(--btn-bg);
                                color: var(--btn-color);
                            "
                        >
                            Delete Questionnaire
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <el-dialog
            :title="payload.id > 0 ? 'Edit questionnaire' : 'New questionnaire'"
            @close="resetPayload"
            :show-close="false"
            :visible.sync="dialogFormVisible"
        >
            <div>
                <div class="form">
                    <div v-if="errors" class="alert alert-danger">
                        <span>{{ errors }}</span>
                    </div>
                    <div class="input-field setting-form-field">
                        <div class="field-label">
                            {{ locale.questionnaire_name }}
                        </div>
                        <input
                            type="text"
                            v-model="payload.name"
                            id="type_name"
                            name="type_name"
                            placeholder="Enter Project Name"
                        />
                    </div>
                    <div class="input-field setting-form-field">
                        <div class="field-label">
                            {{ locale.project_type }}
                        </div>
                        <el-select
                            style="width: 100%"
                            v-model="payload.parent_type_id"
                            placeholder="--Please choose an project type option--"
                            @change="handleParentType"
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
                    <div class="input-field setting-form-field">
                        <div class="field-label">
                            {{ locale.specialty }}
                        </div>
                        <el-select
                            style="width: 100%"
                            v-model="payload.sub_type_id"
                            placeholder="--Please choose an specialty option--"
                        >
                            <el-option
                                v-for="item in sub_project_types_data"
                                :key="item.id"
                                :value="item.id"
                                :label="item.name"
                            >
                            </el-option>
                        </el-select>
                    </div>
                    <div class="input-field">
                        <div class="field-label">{{ locale.skills }}</div>
                        <el-select
                            v-model="payload.skills_ids"
                            multiple
                            style="width: 100%"
                            placeholder="--Please choose an option--"
                        >
                            <el-option
                                v-for="item in skills_data"
                                :label="item.name"
                                :key="item.id"
                                :value="item.id"
                            >
                            </el-option>
                        </el-select>
                    </div>
                    <div class="input-field setting-form-field">
                        <div class="field-label">
                            {{ locale.helptext1 }}
                        </div>
                        <el-switch v-model="payload.is_default"> </el-switch>
                    </div>
                </div>

                <div
                    class="modal-footer"
                    style="flex-wrap: inherit; padding: 0px; border: 0px"
                >
                    <el-button
                        :disabled="loading"
                        @click="dialogFormVisible = false"
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
                        {{ locale.cancel }}
                    </el-button>
                    <el-button
                        v-if="payload.id <= 0"
                        :loading="loading"
                        class="btn"
                        @click="submitNewQuestionnaire"
                        style="
                            padding: 10px 20px;
                            min-height: 54px;
                            border: 1px solid var(--btn-bg);
                            border-radius: var(--r);
                            background: var(--btn-bg);
                            color: var(--btn-color);
                        "
                    >
                        {{ locale.add_questionnaire }}
                    </el-button>
                    <el-button
                        v-if="payload.id > 0"
                        :loading="loading"
                        class="btn"
                        @click="submitUpdateQuestionnaire"
                        style="
                            padding: 10px 20px;
                            min-height: 54px;
                            border: 1px solid var(--btn-bg);
                            border-radius: var(--r);
                            background: var(--btn-bg);
                            color: var(--btn-color);
                        "
                    >
                        {{ locale.edit_questionnaire }}
                    </el-button>
                </div>
            </div>
        </el-dialog>
    </section>
</template>

<script>
import lang from "../../../lang/en.json";
const base_path = window.location.origin;

export default {
    data: () => ({
        rowData: [],
        loading: false,
        dialogFormVisible: false,
        deleted_question_id: 0,
        initial_project_types_data: [],
        project_types_data: [],
        sub_project_types_data: [],
        locale: lang,
        skills_data: [],
        payload: {
            id: 0,
            type_id: null,
            sub_type_id: null,
            parent_type_id: null,
            is_default: false,
            skills_ids: [],
            name: "",
        },
        errors: null,
        message: "",
    }),
    created() {
        this.fetchQuestionnaires();
        this.fetchProjetsType();
        this.fetchSkills();
    },
    methods: {
        resetPayload() {
            this.payload.id = 0;
            this.payload.type_id = null;
            this.payload.is_default = false;
            this.payload.skills_ids = [];
            this.payload.name = "";
            this.errors = null;
        },
        async fetchQuestionnaires() {
            try {
                this.loading = true;
                const res = await window.axios.get(
                    `${base_path}/admin/questionnaires/list`
                );
                this.rowData = res.data.questionnaires;
                this.loading = false;
            } catch (error) {
                alert(String(error));
            } finally {
                this.loading = false;
            }
        },
        async fetchProjetsType() {
            try {
                const res = await window.axios.get(
                    `${base_path}/admin/project-types/list`
                );
                this.initial_project_types_data = res.data.project_types;
                this.project_types_data = res.data.project_types;
                if (this.project_types_data.length) {
                    this.sub_project_types_data =
                        this.project_types_data[0].project_types;
                }
            } catch (error) {
                alert(String(error));
            }
        },
        async fetchSkills() {
            try {
                const res = await window.axios.get(
                    `${base_path}/admin/skills/list`
                );
                this.skills_data = res.data.skills;
            } catch (error) {
                alert(String(error));
            }
        },
        async submitNewQuestionnaire() {
            try {
                this.errors = null;
                this.loading = true;
                this.payload.type_id = this.payload.sub_type_id;
                const response = await window.axios.post(
                    `${base_path}/admin/questionnaires/create`,
                    this.payload
                );
                if (response.data.errors) {
                    this.errors = response.data.errors;
                } else {
                    this.dialogFormVisible = false;
                    this.resetPayload();
                    this.fetchQuestionnaires();
                    this.fetchProjetsType();

                    this.message = response.data.message;
                    setTimeout(() => {
                        this.message = "";
                    }, 5000);
                }
            } catch (error) {
                alert(
                    "And error occured. Check that all the fields are correct."
                );
            } finally {
                this.loading = false;
            }
        },
        async submitUpdateQuestionnaire() {
            try {
                this.errors = null;
                this.loading = true;
                const response = await window.axios.put(
                    `${base_path}/admin/questionnaires/${this.payload.id}/edit`,
                    this.payload
                );
                if (response.data.errors) {
                    this.errors = response.data.errors;
                } else {
                    this.dialogFormVisible = false;
                    this.resetPayload();
                    this.fetchQuestionnaires();
                    this.fetchProjetsType();

                    this.message = response.data.message;
                    setTimeout(() => {
                        this.message = "";
                    }, 5000);
                }
            } catch (error) {
                alert(
                    "And error occured. Check that all the fields are correct."
                );
            } finally {
                this.loading = false;
            }
        },
        async changeStatus(item) {
            try {
                await window.axios.post(
                    `${base_path}/admin/questionnaire/changeStatus`,
                    { questionnaire_id: item.id, status: !item.status }
                );
                this.fetchQuestionnaires();
            } catch (error) {
                alert(
                    "An error occured. Check that all the fields are correct."
                );
            }
        },
        handleNewQuestionnaire() {
            this.project_types_data = [...this.initial_project_types_data];
            this.dialogFormVisible = true;
        },
        handleEditQuestionnaire(item) {
            this.project_types_data = [...this.initial_project_types_data];
            var type = this.project_types_data.filter(
                (type) => type.id == item.project_types.id
            );
            if (type.length == 0) {
                this.project_types_data.push(item.project_types);
            }
            this.payload.id = item.id;
            this.payload.type_id = item.project_types.id;
            this.payload.is_default = item.is_default ? true : false;
            this.payload.skills_ids = item.skills.map((ele) => ele.id);
            this.payload.name = item.name;
            this.dialogFormVisible = true;
        },
        handleDelete(id) {
            this.deleted_question_id = id;
            document.getElementById("remove_id").click();
        },
        async deleteQuestionnaire() {
            try {
                this.loading = true;
                await window.axios.delete(
                    `${base_path}/admin/questionnaires/${this.deleted_question_id}/delete`
                );
                // window.location.reload();
                let delete_index = null;
                this.rowData.forEach((data, index) => {
                    if (data.id == this.deleted_question_id) {
                        delete_index = index;
                    }
                });
                this.rowData.splice(delete_index, 1);
                document.getElementById("cancel-delete").click();
                this.message = this.locale.questionnairesDeleted;
                setTimeout(() => {
                    this.message = "";
                }, 5000);
            } catch (error) {
                this.loading = false;
                alert("And error occured");
            } finally {
                this.loading = false;
            }
        },
        handleParentType() {
            if (this.payload.parent_type_id) {
                let sub_types = this.project_types_data.find(
                    (item) => item.id == this.payload.parent_type_id
                );
                this.sub_project_types_data = [...sub_types.project_types];
            }
        },
    },
};
</script>

<style>
.success-message {
    color: #1890ff !important;
    font-size: 20px;
    background-color: transparent !important;
    border-color: unset;
    padding: 0;
    padding-bottom: 10px;
}
</style>
