<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-primary" :no-body="true">
          <div class="card-body pb-0">
            <h4 class="mb-0">
              1.423
            </h4>
            <p>Porosi te derguara</p>
          </div>
          <card-line1-chart-example class="chart-wrapper px-3" style="height:70px;" height="70" />
        </b-card>
      </div>
      <!--/.col-->
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-info" :no-body="true">
          <div class="card-body pb-0">
            <h4 class="mb-0">
              4.823
            </h4>
            <p>Shpallje te postuara</p>
          </div>
          <card-line2-chart-example class="chart-wrapper px-3" style="height:70px;" height="70" />
        </b-card>
      </div>
      <!--/.col-->
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-warning" :no-body="true">
          <div class="card-body pb-0">
            <h4 class="mb-0">
              525
            </h4>
            <p>Dergesa ne pritje</p>
          </div>
          <card-line3-chart-example class="chart-wrapper" style="height:70px;" height="70" />
        </b-card>
      </div>
      <!--/.col-->
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-danger" :no-body="true">
          <div class="card-body pb-0">
            <h4 class="mb-0">
              4.823
            </h4>
            <p>Followers</p>
          </div>
          <card-bar-chart-example class="chart-wrapper px-3" style="height:70px;" height="70" />
        </b-card>
      </div>
      <!--/.col-->
    </div>
    <!--/.row-->
  </div>
</template>

<script>
import CardLine1ChartExample from '../dashboard/CardLine1ChartExample'
import CardLine2ChartExample from '../dashboard/CardLine2ChartExample'
import CardLine3ChartExample from '../dashboard/CardLine3ChartExample'
import CardBarChartExample from '../dashboard/CardBarChartExample'
import MainChartExample from '../dashboard/MainChartExample'
import Callout from '~/components/Callout'

export default {
  metaInfo () {
    return {
      title: 'Dashboard'
    }
  },
  name: 'Dashboard',
  middleware: 'auth',
  layout: 'default',
  components: {
    CardLine1ChartExample,
    CardLine2ChartExample,
    CardLine3ChartExample,
    CardBarChartExample
  },
  data: function () {
    return {}
  },
  methods: {
    variant (value) {
      let $variant
      if (value <= 25) {
        $variant = 'info'
      } else if (value > 25 && value <= 50) {
        $variant = 'success'
      } else if (value > 50 && value <= 75) {
        $variant = 'warning'
      } else if (value > 75 && value <= 100) {
        $variant = 'danger'
      }
      return $variant
    }
  }
}
</script>
