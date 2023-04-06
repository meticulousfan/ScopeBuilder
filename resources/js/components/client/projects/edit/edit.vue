<template>
    <div class="container">
        
        <div class="section-inner" v-if="showProgress">
            <div id="loading"></div>
        </div>
        <!-- Create Project Step 1 -->
        <first-step
            :project="project"
            :base_path="base_path"
            v-if="showStep1"
        ></first-step>

        <!-- Create Project Step 2 -->
        <second-step :project="project" :base_path="base_path" v-if="showStep2" :saving="saving">
        </second-step>

        <!-- Create Project Step 3 -->
        <third-step :project="project" :base_path="base_path" v-if="showStep3" :saving="saving">
        </third-step>

        <!-- Create Project Step 4 -->
        <fourth-step
            :project="project"
            :base_path="base_path"
            v-if="showStep4"
            :saving="saving"
            >

        </fourth-step>

        <!-- Create Project Step 5 -->
        <fifth-step
            :project="project"
            :base_path="base_path"
            :page_index="page_index"
            v-if="showStep5"
            :saving="saving"
            >
        </fifth-step>

        <!-- Create Project Step 6 -->
        <sixth-step
            :project="project"
            :base_path="base_path"
            :page_index="page_index"
            :user_index="user_index"
            v-if="showStep6"
            :saving="saving"
            >
        </sixth-step>

        <!-- Create Project Step 7 -->
        <seventh-step
            :project="project"
            :base_path="base_path"
            v-if="showStep7"
            :data="data"
            :saving="saving"
            >
        </seventh-step>

        <!-- Complete Project Modal -->
        <div :class="submitted == true ? 'modal visible' : 'modal'" id="questionnaire-completed-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <button class="modal-close" aria-label="Close modal"></button>

                    <div class="modal-caption">
                        <h3 class="mc-title">Project Questionnaire Completed</h3>
                        <p class="mc-subtitle">Congratulations on completing your questionnaire. In order <br class="sm-hidden">to add this project to your dashboard, Scope Builder requires a <br class="sm-hidden">small payment fees of $10.00.</p>
                    </div>

                    <div class="big-modal-icon">
                        <img :src="base_path + 'ui_assets/img/icons/wallet.svg' " alt="">
                    </div>

                    <div class="modal-footer">
                        <a :href="base_path + 'client/projects'" class="btn">Proceed To Payment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EventBus from "../../../../vue-asset";
import FirstStep from "./FirstStep.vue";
import SecondStep from "./SecondStep.vue";
import ThirdStep from "./ThirdStep.vue";
import FourthStep from "./FourthStep.vue";
import FifthStep from "./FifthStep.vue";
import SixthStep from "./SixthStep.vue"
import SeventhStep from './SeventhStep.vue'

export default {
    name: "edit-project",

    props: ['data'],

    components: {
        FirstStep,
        SecondStep,
        ThirdStep,
        FourthStep,
        FifthStep,
        SixthStep,
        SeventhStep,
    },

    data() {
        return {
            base_path: "",
            project: {
                name: "",
                description: "",
                example_projects: [
                    {
                        project: "",
                    },
                ],
                example_projects_count: 1,
                type: "mobile",
                number_of_user_types: 1,
                user_types: [],
                number_of_pages: 1,
                pages: [],
                pages_information: [],
                web_frameworks: [],
                mobile_frameworks: [],
                steps: 6,
                page_step: 0,
                is_draft: 0,
                step: 0
            },
            page_index: 0,
            user_index: 0,
            showProgress: false,
            showStep1: true,
            showStep2: false,
            showStep3: false,
            showStep4: false,
            showStep5: false,
            showStep6: false,
            showStep7: false,
            saving: false,
            submitted: false,
        };
    },

    created() {
        var _this = this;
        _this.getData();
        EventBus.$on("edit-first-step-submitted", function () {
            _this.showStep1 = false;
            _this.project.step = 2;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep2 = true;
            }, 500);
        });

        EventBus.$on("edit-goToFirstForm-clicked", function () {
            _this.showStep2 = false;
            _this.project.step = 1;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep1 = true;
            }, 500);
        });

        // Second Step
        EventBus.$on("edit-second-step-submitted", function () {
            _this.showStep2 = false;
            _this.project.step = 3;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep3 = true;
            }, 500);
        });

        EventBus.$on("edit-goToSecondForm-clicked", function () {
            _this.showStep3 = false;
            _this.project.step = 2;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep2 = true;
            }, 500);
        });

        // Third Step
        EventBus.$on("edit-third-step-submitted", function () {
            _this.showStep3 = false;
            _this.project.step = 4;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep4 = true;
            }, 500);
        });

        EventBus.$on("edit-goToThirdForm-clicked", function () {
            _this.showStep4 = false;
            _this.project.step = 3;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep3 = true;
            }, 500);
        });

        // Fourth Step
        EventBus.$on("edit-fourth-step-submitted", function () {
            _this.showStep4 = false;
            _this.project.step = 5;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep5 = true;
            }, 500);
        });

        EventBus.$on("edit-goToFourthForm-clicked", function () {
            _this.showStep5 = false;
            _this.project.step = 4;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep4 = true;
            }, 500);
        });

        // Fifth Step
        EventBus.$on("edit-fifth-step-submitted", function (index) {
            _this.page_index = index;
            _this.showStep5 = false;
            _this.project.step = 6;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        });

        EventBus.$on("edit-goToFifthForm-clicked", function () {
            _this.showStep6 = false;
            _this.project.step = 5;
            _this.showProgress = true;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep5 = true;
            }, 500);
        });

        // Sixth Step
        EventBus.$on("edit-sixth-step-submitted", function () {
            _this.showStep6 = false;
            _this.showProgress = true;
            _this.project.step = 7;
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep7 = true;
            }, 500);
        });

        EventBus.$on("edit-goToSixthForm-clicked", function () {
            _this.showStep7 = false;
            _this.project.step = 6;
            _this.showProgress = true;
            if(_this.project.page_step > 0)
            {
                _this.project.page_step = _this.project.page_step - 1
            }
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        });

        // Repeat Sixth Step
        EventBus.$on("edit-repeat-sixth-step", function (index, duplicate) {
            let actions = []
            let information = []
            _this.user_index = index + 1
            _this.showStep6 = false;
            _this.showProgress = true;
            if(duplicate)
            {
                actions = _this.project.pages_information[_this.page_index].users[index].actions
                information = _this.project.pages_information[_this.page_index].users[index].information
                
                _this.project.pages_information[_this.page_index].users[_this.user_index].actions = actions
                _this.project.pages_information[_this.page_index].users[_this.user_index].information = information
            }
            _this.project.page_step = _this.project.page_step + 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        });

        EventBus.$on("edit-repeat-sixth-step-for-next-page", function(page) {
            _this.page_index = page + 1
            _this.user_index = 0
            _this.showStep6 = false;
            _this.showProgress = true;
            _this.project.page_step = _this.project.page_step + 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        })

        EventBus.$on("edit-previous-sixth-step", function (index) {
            _this.user_index = index - 1;
            _this.showStep6 = false;
            _this.showProgress = true;
            _this.project.page_step = _this.project.page_step - 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        });

        EventBus.$on("edit-previous-sixth-step-for-page", function (page, user) {
            _this.page_index = page - 1;
            _this.user_index = user
            _this.showStep6 = false;
            _this.showProgress = true;
            _this.project.page_step = _this.project.page_step - 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        });

        EventBus.$on('previous-sixth-step-from-fifth', function() {
            _this.page_index = _this.page_index - 1
            _this.user_index = _this.project.pages_information[_this.page_index].users.length - 1
            _this.showStep5 = false;
            _this.showProgress = true;
            _this.project.page_step = _this.project.page_step - 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        })

        EventBus.$on("edit-updateForm-clicked", function() {
            _this.project.is_draft = 0
            _this.update()
        })
        
        EventBus.$on("edit-save-for-later-clicked", function() {
            _this.project.is_draft = 1
            _this.update()
        })

        EventBus.$on("edit-skip-user", function(user) {
            _this.user_index = user + 1
            _this.showStep6 = false;
            _this.showProgress = true;
            _this.project.page_step = _this.project.page_step + 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        })

        EventBus.$on("edit-skip-page", function(page) {
            _this.page_index = page + 1
            _this.user_index = 0
            _this.showStep6 = false;
            _this.showProgress = true;
            _this.project.page_step = _this.project.page_step + 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep6 = true;
            }, 500);
        })
    
        EventBus.$on("edit-skip-last", function() {
            _this.showStep6 = false;
            _this.showProgress = true;
            _this.project.page_step = _this.project.page_step + 1
            setTimeout(() => {
                _this.showProgress = false;
                _this.showStep7 = true;
            }, 500);
        })
    },

    mounted() {
        this.base_path = base_url;
    },

    methods: {
        getData()
        {
            this.showProgress = true
            axios.get( base_url + 'client/projects/get/' + this.data.id).then(response => {
                this.project.name = this.data.name
                this.project.description = this.data.description
                this.example_projects_count = this.data.example_projects_count
                this.project.example_projects = response.data.example_projects
                this.project.type = this.data.type
                this.project.number_of_user_types = this.data.number_of_user_types
                this.project.user_types = response.data.user_types
                this.project.number_of_pages = this.data.number_of_pages
                this.project.pages = response.data.pages
                this.project.pages_information = response.data.pages_information
                this.project.web_frameworks = response.data.web_frameworks == false ? [] : response.data.web_frameworks
                this.project.mobile_frameworks = response.data.mobile_frameworks == false ? [] : response.data.mobile_frameworks
                this.project.is_draft = this.data.is_draft
                this.project.step = response.data.step
                this.showProgress = false

                if(this.data.is_draft == 1) {
                    this.showStep1 = (response.data.step == 1) ? true : false
                    this.showStep2 = (response.data.step == 2) ? true : false
                    this.showStep3 = (response.data.step == 3) ? true : false
                    this.showStep4 = (response.data.step == 4) ? true : false
                    this.showStep5 = (response.data.step == 5) ? true : false
                    this.showStep6 = (response.data.step == 6) ? true : false
                    this.showStep7 = (response.data.step == 7) ? true : false
                }
                console.log(this)
                console.log(response)
            });
        },

        update() {
            this.saving = true
            if(this.project.type == 'mobile')
            {
                this.project.web_frameworks = []
            }
            
            if(this.project.type == 'web')
            {
                this.project.mobile_frameworks = []
            }
            axios
            .post(base_url + "client/projects/update/" + this.data.id, this.project)
            .then(response => {
                this.saving = false
                if(this.data.is_draft == 1)
                {
                    window.location.href = this.base_path + 'client/projects'
                    //document.getElementById("questionnaire-completed-modal").setAttribute("style","display: block;")
                }
                else
                {
                    this.submitted = true
                    document.getElementById("questionnaire-completed-modal").setAttribute("style","display: block;")
                    //window.location.href = this.base_path + 'client/projects'
                }
            })
            .catch(err => {
                if (err.response) {
                    this.saving = false
                    // this.submitted = true
                    alert(err.response.data.message)
                    this.showMessage(err.response.data)
                }
            });
        },
    },
};
</script>

<style scoped>
#loading {
    margin: 0 auto;
    margin-top: 20%;
    display: inline-block;
    width: 70px;
    height: 70px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #002766;
    animation: spin 1s ease-in-out infinite;
    -webkit-animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
    }
}
@-webkit-keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
    }
}
</style>
