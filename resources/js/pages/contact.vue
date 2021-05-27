<template>
  <div>
    <div class="conatiner-fluid p-0">
      <div class="map">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2933.2132080157076!2d21.168808330088613!3d42.67802581646716!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x13549f3eeed991e5%3A0x4ebd91efef16c52f!2sMentor%20Ratkoceri%2C%20Prishtin%C3%AB!5e0!3m2!1sen!2s!4v1609096063722!5m2!1sen!2s"
          width="100%"
          height="450"
          frameborder="0"
          style="border: 0"
          allowfullscreen=""
          aria-hidden="false"
          tabindex="0"
        ></iframe>
        <div class="contact-info">
          <div class="contact-content">
            <div class="container">
              <div class="row">
                <div class="col-12 col-md-4">
                  <div class="ci-content">
                    <h1>Phone</h1>
                    <p>+41 123 123 123 12</p>
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="ci-content">
                    <h1>Email</h1>
                    <p>support@eblejta.com</p>
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="ci-content">
                    <h1>Location</h1>
                    <p>
                      Berlin, Street 2394 <br />
                      Germany
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container mt-10">
      <div class="row">
        <div class="col-md-8 mx-auto mt-8">
          <h2 class="title">Contact Form</h2>
          <form method="get" @submit.prevent="contactus" class="header-form custom-form">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group required-field">
                  <label for="name">Name</label>
                  <input
                    id="name"
                    type="text"
                    class="form-control custom-input"
                    name="name"
                    v-model="name"
                    required
                  />
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group required-field">
                  <label for="email">E-mail</label>
                  <input
                    id="email"
                    type="email"
                    class="form-control custom-input"
                    name="email"
                    v-model="email"
                    required
                  />
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="phone">Numri i telefonit</label>
                  <input
                    id="phone"
                    type="tel"
                    class="form-control custom-input"
                    name="phone"
                    v-model="phone"
                  />
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="company">Company</label>
                  <input
                    id="company"
                    type="text"
                    class="form-control custom-input"
                    name="company"
                    v-model="company"
                  />
                </div>
              </div>
              <div class="col-12">
                <div class="form-group required-field">
                  <label for="message">Mesazhi</label>
                  <textarea
                    id="message"
                    cols="30"
                    rows="1"
                    class="form-control custom-input"
                    name="message"
                    v-model="message"
                    required
                  />
                </div>
              </div>
              <div class="col-12">
                <div class="pull-right">
                  <button type="submit" class="btn btn-primary">Dergo</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="mb-8"></div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "basic",
  components: {},
  metaInfo() {
    return {
      title: this.$t("home"),
    };
  },
  data() {
    return {
      name: "",
      phone: "",
      email: "",
      message: "",
      company: "",
    };
  },
  methods: {
    contactus() {
      let data = {
        name: this.name,
        phone: this.phone,
        email: this.email,
        message: this.message,
        company: this.company,
      };
      this.axios.post(`/api/contactUs/contactForm`, data).then((result) => {
        if (result.data.status === "success") {
          swal({
            type: "success",
            title: "You have subscribed succesfully",
            confirmButtonText: "ok",
          });
        } else {
          if (result.data.status === "failed") {
            console.log("error", result);
            swal({
              type: "error",
              title: "Error",
              text: result.data.message.email[0],
              reverseButtons: true,
              confirmButtonText: "ok",
            });
          }
        }
      });
    },
  },
};
</script>
