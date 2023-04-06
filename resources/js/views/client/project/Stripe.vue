<template>
  <div class="form-group">
    <div v-show="showPaymentCard" class="card-body">
      <div class="big-modal-icon">
          <img src="/ui_assets/img/icons/wallet.svg" alt="">
      </div>
      <label for="card-element"> Enter your credit card information </label>
      <div id="card-element" class="form-control pt-2">
        <!-- A Stripe Element will be inserted here. -->
      </div>

      <!-- Used to display form errors. -->
      <div id="card-errors" role="alert"></div>
      <input type="hidden" name="questionnaire" value="" />
      <input type="hidden" name="user_questionnaire" value="" />
      <input type="hidden" name="payment_method" class="payment-method" />

      <el-button
        :disabled="loading"
        @click="cancelPayment"
        class="btn btn-light js-modal-close"
        style="
          padding: 10px 20px;
          min-height: 54px;
          border: 1px solid var(--btn-bg);
          border-radius: var(--r);
          background: var(--btn-bg);
          color: var(--btn-color);
          margin-top: 15px;
        "
      >
        {{ locale.cancel }}
      </el-button>

      <el-button
        :disabled="loading"
        @click="proceedToPay"
        class="btn btn-primary js-modal-close"
        style="
          padding: 10px 20px;
          min-height: 54px;
          border: 1px solid var(--btn-bg);
          border-radius: var(--r);
          background: var(--btn-bg);
          color: var(--btn-color);
          margin-top: 15px;
        "
      >
        {{ locale.proceed_to_pay }}
      </el-button>
    </div>

    <div v-if="paymentLoading" id="paymentLoading" class="calling-modal-3 p-3 text-center">
      <div class="wallet-icon-block">
        <div id="loading"></div>
      </div>
      <div class="custom-heading-text custom-heading-lg mb-3">
        {{ locale.loadingPayment }}
      </div>
      <div class="seconary-paragraph mb-4">
        {{ locale.loadingPaymentMessage }}
      </div>
    </div>

    <div v-if="paymentSuccess" id="paymentSuccess" class="calling-modal-3 p-3 text-center">
      <div class="wallet-icon-block">
        <div class="wallet-icon-cont pay-success">
          <svg class="btn-icon">
            <use
              xlink:href="/ui_assets/img/icons-sprite.svg#wallet"
            ></use>
          </svg>
        </div>
      </div>
      <div class="custom-heading-text custom-heading-lg mb-3">
        {{ locale.paymentSuccess }}
      </div>
      <div class="seconary-paragraph mb-4">
        {{ locale.paymentSuccessMessage }}
      </div>
      <div class="seconary-paragraph">
        {{ locale.pdfRedirect }}
      </div>
      <div class="button-block">
        <el-button
          :disabled="loading"
          @click="generatePdf"
          class="btn btn-primary js-modal-close"
          style="
            padding: 10px 20px;
            min-height: 54px;
            border: 1px solid var(--btn-bg);
            border-radius: var(--r);
            background: var(--btn-bg);
            color: var(--btn-color);
            margin-top: 15px;
          "
        >
          {{ locale.generatePdf }}
        </el-button>
      </div>
    </div>

    <div v-if="paymentFailed" id="paymentFailed" class="calling-modal-3 p-3 text-center ">
      <div class="wallet-icon-block">
        <div class="wallet-icon-cont pay-failed">
          <svg class="btn-icon">
            <use
              xlink:href="/ui_assets/img/icons-sprite.svg#wallet"
            ></use>
          </svg>
        </div>
      </div>
      <div class="custom-heading-text custom-heading-lg mb-3">
        {{ locale.paymentFailed }}
      </div>
      <div class="seconary-paragraph mb-4">
        {{ locale.paymentFailedMessage }}
      </div>
      <div class="button-block">
        <el-button
          :disabled="loading"
          @click="showPayment"
          class="btn btn-light js-modal-close"
          style="
            padding: 10px 20px;
            min-height: 54px;
            border: 1px solid var(--btn-bg);
            border-radius: var(--r);
            background: var(--btn-bg);
            color: var(--btn-color);
            margin-top: 15px;
          "
        >
          {{ locale.goBack }}
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
const base_path = window.location.origin;
import lang from "../../../../lang/en.json";

export default {
  props: {
    project: Object,
    user: Object,
    stripeKeys: Object,
    cost: Number,
  },
  data() {
    return {
      stripe: null,
      card: null,
      loading: false,
      locale: lang,
      key: "",
      secret: "",
      paymentLoading: false,
      paymentSuccess: false,
      paymentFailed: false,
      showPaymentCard: true
    };
  },
  mounted() {
    this.stripe = Stripe(this.stripeKeys.key);

    // Create an instance of Elements.
    var elements = this.stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
      base: {
        color: "#32325d",
        lineHeight: "18px",
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#aab7c4",
        },
      },
      invalid: {
        color: "#fa755a",
        iconColor: "#fa755a",
      },
    };

    // Create an instance of the card Element.
    var card = elements.create("card", {
      style: style,
      hidePostalCode: true,
      // hideIcon: true,
      iconStyle: "solid",
    });
    this.card = card;
    // Add an instance of the card Element into the `card-element` <div>.
    card.mount("#card-element");

    // showPaymentLoading();
  },
  methods: {
    async proceedToPay() {

      const { paymentMethod, error } = await this.stripe.createPaymentMethod(
        "card",
        this.card,
        {
          billing_details: {
            name: this.user.name,
          },
        }
      );

      if (error) {
        // Display "error.message" to the user...
        // console.log(error);
      } else {
        // The card has been verified successfully...
        try {
          this.showPaymentLoading();
          const res = await window.axios.post(
            `${base_path}/client/project/${this.project.id}/pay`,
            {
              paymentMethod: paymentMethod,
              amount: this.cost,
            }
          );
          if (res.data.success) {
            this.showPaymentSuccess();
            setTimeout(() => {
              this.generatePdf();
            }, 5000);
          }
        } catch(error) {
          this.showPaymentFailed();
        }
      }
    },
    gotoProjects() {
      window.location.href = `${base_path}/client/projects`;
    },
    generatePdf() {
      window.location.href = `${base_path}/client/projects/generate-pdf/${this.project.uuid}`
    },
    showPaymentLoading() {
      this.showPaymentCard = false;
      this.paymentLoading = true;
    },
    showPaymentSuccess() {
      this.showPaymentCard = false;
      this.paymentLoading = false;
      this.paymentFailed = false;
      this.paymentSuccess = true;
    },
    showPaymentFailed() {
      this.showPaymentCard = false;
      this.paymentLoading = false;
      this.paymentFailed = true;
    },
    showPayment() {
      this.showPaymentCard = true;
      this.paymentLoading = false;
      this.paymentFailed = false;
      this.paymentSuccess = false;
    },
    cancelPayment() {
      this.$emit("cancel", "cancel payment");
    }
  },
};
</script>

<style>
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