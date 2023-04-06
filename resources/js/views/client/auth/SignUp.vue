<template>
  <div>
    <div class="fixed-top top-notif-wrap">
        <div class="top-notif">
            <span v-if="can_signup">
              <a href="javascript:void(0)" @click="dialogSignupFromVisible = true">{{ locale.signup }}</a>
              &nbsp; or &nbsp;
            </span>
            <a href="javascript:void(0)" @click="dialogLoginFromVisible = true">{{ locale.login }}</a>
            &nbsp;
            {{ locale.tocontinue_and_save_later }}
        </div>
    </div>
    <div class="sign-in">
      <el-dialog
        :title="locale.signup"
        @close="resetPayload"
        :show-close="false"
        :visible.sync="dialogSignupFromVisible"
      >
        <div>
          <div class="form">
            <div v-if="errors" class="alert alert-danger">
              <span>{{ errors }}</span>
            </div>
            <div class="auth-block">
              <div class="block-caption">
                  <div class="bc-icon">
                      <img :src="root_path+'/ui_assets/img/icons/user.svg'" alt="">
                  </div>
                  <h3 class="bc-title">Create An Account</h3>
                  <p class="bc-subtitle">Hello there! Please create a new account to <br class="sm-hidden">continue
                      using the application.</p>
              </div>
            </div>

            <div class="input-field setting-form-field">
              <div class="field-label">
                Full Name
              </div>
              <input
                type="text"
                v-model="payload.name"
                id="type_name"
                name="type_name"
                placeholder="Enter Full Name"
              />
            </div>
            <div class="input-field setting-form-field">
              <div class="field-label">
                Email
              </div>
              <input
                type="email"
                v-model="payload.email"
                id="email"
                name="email"
                placeholder="Enter Email"
              />
            </div>
            <div class="input-field setting-form-field">
              <div class="field-label">
                Password
              </div>
              <input
                type="password"
                v-model="payload.password"
                id="password"
                name="password"
                placeholder=""
              />
            </div>
            <div class="input-field setting-form-field">
              <div class="field-label">
                Confirm Password
              </div>
              <input
                type="password"
                v-model="payload.password_confirmation"
                id="confirm_password"
                name="confirm_password"
                placeholder=""
              />
            </div>
          </div>

          <div
            class="modal-footer"
            style="flex-wrap: inherit; padding: 0px; border: 0px"
          >
            <el-button
              :loading="loading"
              class="btn"
              @click="submitSignUp"
              style="
                padding: 10px 20px;
                width: 100%;
                border: 1px solid var(--btn-bg);
                border-radius: var(--r);
                background: var(--btn-bg);
                color: var(--btn-color);
                margin-top: 15px;
              "
            >
              {{ locale.create_account }}
            </el-button>
          </div>
        </div>
      </el-dialog>

      <el-dialog
        :title="locale.signin"
        @close="resetPayload"
        :show-close="false"
        :visible.sync="dialogLoginFromVisible"
      >
        <div>
          <div v-if="message != ''" class="alert alert-success">
                {{ message }}
          </div>
          <div class="form">
            <div v-if="errors" class="alert alert-danger">
              <span>{{ errors }}</span>
            </div>
            <div class="auth-block">
              <div class="block-caption">
                  <div class="bc-icon">
                      <img :src="root_path+'/ui_assets/img/icons/user.svg'" alt="">
                  </div>
                  <h3 class="bc-title">Sign In</h3>
                  <p class="bc-subtitle">Welcome back! Please enter your credentials <br class="sm-hidden">to sign in to
                      your account.</p>
              </div>
            </div>

            <div class="input-field setting-form-field">
              <div class="field-label">
                Email
              </div>
              <input
                type="email"
                v-model="payload.email"
                id="email"
                name="email"
                placeholder="Enter Email"
              />
            </div>
            <div class="input-field setting-form-field">
              <div class="field-label">
                Password
              </div>
              <input
                type="password"
                v-model="payload.password"
                id="password"
                name="password"
                placeholder=""
              />
            </div>
          </div>

          <div
            class="modal-footer"
            style="flex-wrap: inherit; padding: 0px; border: 0px"
          >
            <el-button
              :loading="loading"
              class="btn"
              @click="submitLogin"
              style="
                padding: 10px 20px;
                width: 100%;
                border: 1px solid var(--btn-bg);
                border-radius: var(--r);
                background: var(--btn-bg);
                color: var(--btn-color);
                margin-top: 15px;
              "
            >
              {{ locale.signin }}
            </el-button>
          </div>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import lang from "../../../../lang/en.json";
const base_path = window.location.origin;

export default {
  name: 'SignUp',
  props: {
    id: String
  },
  data: () => ({
    rowData: [],
    loading: false,
    dialogSignupFromVisible: false,
    dialogLoginFromVisible: false,
    payload: {
      id: 0,
      name: "",
      email: "",
      password: "",
      password_confirmation: ""
    },
    errors: null,
    locale: lang,
    root_path: base_path,
    message: "",
    can_signup: true
  }),
  methods: {
    resetPayload() {
      this.payload.id = 0;
      this.payload.email = '';
      this.payload.password = '';
      this.payload.password_confirmation = '';
      this.payload.name = "";
      this.errors = null;
      this.message = "";
    },
    async submitSignUp() {
      try {
        this.errors = null;
        this.message = "";
        this.loading = true;
        let urlParams = new URLSearchParams(window.location.search);
        this.payload.referral_token = urlParams.get('ref');
        this.payload.project = "";
        if(localStorage.project) {
          this.payload.project = localStorage.project
        }
        if(localStorage.anonymous_client) {
          this.payload.anonymous_client = localStorage.anonymous_client
        }
        
        const response = await window.axios.post(
          `${base_path}/client/anonymous/register_api`,
          this.payload
        );

        if(response.data.success) {
          this.dialogSignupFromVisible = false;
          this.dialogLoginFromVisible = true;
          this.message = response.data.message;
          // localStorage.removeItem('anonymous_client');
          this.can_signup = false;
        }
      } catch (error) {
        alert("And error occured. Check that all the fields are correct.");
      } finally {
        this.loading = false;
      }
    },
    async submitLogin() {
      try {
        this.errors = null;
        this.message = "";
        this.loading = true;

        let urlParams = new URLSearchParams(window.location.search);
        this.payload.referral_token = urlParams.get('ref');
        if(localStorage.anonymous_client) {
          this.payload.anonymous_client = localStorage.anonymous_client
        }

        const response = await window.axios.post(
          `${base_path}/client/login_api`,
          this.payload
        );

        if(response.data.success) {
          this.dialogSignupFromVisible = false;
          this.dialogLoginFromVisible = false;
          this.message = response.data.message;
          
          localStorage.removeItem('anonymous_client');
          window.location.href = `${base_path}/client/projects/${this.id}/questions`;
          // this.$parent.openPaymentDialogue();
          // window.location.href = `${base_path}/client/projects`;
        } else {
          this.errors = response.data.message;
        }
      } catch (error) {
        alert("And error occured. Check that all the fields are correct.");
      } finally {
        this.loading = false;
      }
    },
    openSignupOrLogin() {
      if(this.can_signup) {
        this.opneSignupDialogue();
      } else {
        this.openLoginDialogue();
      }
    },
    opneSignupDialogue() {
      this.dialogSignupFromVisible = true;
      this.dialogLoginFromVisible = false;
    },
    openLoginDialogue() {
      this.dialogSignupFromVisible = false;
      this.dialogLoginFromVisible = true;
    },
  }
  
}
</script>

<style>
  .el-dialog {
    max-width: 400px;
    border-radius: 10px;
  }
  .auth-block {
    padding-top: 0;
  }
</style>