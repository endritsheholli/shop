<template>
  <main class="main">
    <!-- <nav aria-label="breadcrumb" class="breadcrumb-nav">
      <div class="container">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.html">
              <i class="fa fa-home" />
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Të dhënat e përdoruesit</li>
        </ol>
      </div>
    </nav> -->
    <div class="container">
      <div class="row">
        <div class="col-12">
          <ul class="checkout-progress-bar">
            <li class="active">
              <span>Përdoruesi</span>
            </li>
            <li>  
              <span>Rishikimi &amp; Pagesa</span>
            </li>
          </ul>
        </div>
        <div class="col-lg-7">
          <!-- if its not logged in -->
          <ul class="checkout-steps">
            <li>
              <h2 class="step-title">Kyçu</h2>
              <form class="full-width-form" action="#">
                <div class="form-group required-field">
                  <label>E-mail</label>
                  <div class="form-control-tooltip">
                    <input type="email" class="form-control" required />
                    <span
                      class="input-tooltip"
                      data-toggle="tooltip"
                      title="Në këtë e-mail do ju dërgojm letër konfirmuese"
                      data-placement="right"
                    >
                      <i class="icon-question-circle" />
                    </span>
                  </div>
                  <!-- End .form-control-tooltip -->
                </div>
                <!-- End .form-group -->

                <div class="form-group required-field">
                  <label>Fjalëkalimi</label>
                  <input type="password" class="form-control" required />
                </div>
                <!-- End .form-group -->
                <div class="row">
                  <div class="col-12 col-md-6" style="margin-top: 10px;">
                    <a href="forgot-password.html" class="forget-pass">
                      <small>Keni harruar fjalekalimin?</small>
                    </a>
                  </div>
                  <div class="col-12 col-md-6 text-right">
                    <button type="submit" class="btn btn-primary">Kyçu</button>
                  </div>
                  <div class="col-12">
                    <hr style="margin:20px 0;" />
                    <router-link
                      :to="{name: 'checkout-review'}"
                      style="text-decoration: underline;"
                    >Nuk jam i regjistruar</router-link>
                  </div>
                </div>
                <!-- End .form-footer -->
              </form>
            </li>
          </ul>
        </div>
        <!-- End .col-lg-7 -->

        <div class="col-lg-5">
          <div class="order-summary">
            <h3>Fatura permbledhese</h3>
            <div id="order-cart-section" class="collapse show">
              <table class="table table-mini-cart">
                <tbody>
                  <tr>
                    <td class="product-col">
                      <figure class="product-image-container">
                        <a href="product.html" class="product-image">
                          <img src="../../../img/270x/product3.png" alt="product" />
                        </a>
                      </figure>
                      <div>
                        <h2 class="product-title">
                          <a href="product.html">Ore per meshkuj</a>
                        </h2>
                      </div>
                    </td>
                    <td class="price-col">
                      <small>17.90 &euro;</small>
                    </td>
                  </tr>
                  <tr>
                    <td class="product-col">
                      <figure class="product-image-container">
                        <a href="product.html" class="product-image">
                          <img src="../../../img/270x/product3.png" alt="product" />
                        </a>
                      </figure>
                      <div>
                        <h2 class="product-title">
                          <a href="product.html">Adapter</a>
                        </h2>
                      </div>
                    </td>
                    <td class="price-col">
                      <small>2.90 &euro;</small>
                    </td>
                  </tr>
                  <tr>
                    <td>Totali</td>
                    <td>20.80 &euro;</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  layout: "nosidebar",

  metaInfo() {
    return { title: this.$t("checkout") };
  }
};
</script>
