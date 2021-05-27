<template>
  <table style="width: 100%; font-size: 16px; margin-bottom: 40px; border: 1px solid #111;">
    <thead>
      <tr>
        <th>
          <h4
            style="margin-top: 1rem;font-size: 15px; font-weight: 600; text-align: center; width: 100%; "
          >Dy-Po - Fatura {{confirmedOrder.id}}</h4>
        </th>
      </tr>
    </thead>
    <tr>
      <td style="padding: 10px;">
        <table
          style="width: 100%; font-size: 14px; margin-bottom: 0px; border: 1px solid #111; border-collapse: separate;
  border-spacing: 1px;"
        >
          <tr>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Postieri:
              <span
                class="font-weight-bold float-right"
              >{{ confirmedOrder.postman ? confirmedOrder.postman.user_details.name : '' }}</span>
            </td>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Pesha:
              <span
                class="font-weight-bold float-right"
              >{{ confirmedOrder.weight ? confirmedOrder.weight : '' }}</span>
            </td>
            <td rowspan="3" colspan="2" style="text-align: center; ">
              <qrcode-vue
                :value="`${location}/platform/order/${confirmedOrder.id}`"
                :size="size"
                level="H"
                :renderAs="'svg'"
              ></qrcode-vue>
              <!-- <img style="width: 169px; float: right;" src="/images/bc.png" alt="Barcode" /> -->
            </td>
          </tr>
          <tr>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Sasia:
              <span
                class="font-weight-bold float-right"
              >{{ confirmedOrder.quantity ? confirmedOrder.quantity : '' }}</span>
            </td>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Qmimi:
              <span
                class="font-weight-bold float-right"
              >{{ confirmedOrder.price ? confirmedOrder.price+"€" : '' }}</span>
            </td>
          </tr>
          <tr>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Mund të kthehet:
              <span
                class="font-weight-bold float-right"
              >{{ confirmedOrder.reversible == 1 ? "PO" : 'JO' }}</span>
            </td>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Mund të hapet:
              <span
                class="font-weight-bold float-right"
              >{{ confirmedOrder.openable == 1 ? "PO" : 'JO' }}</span>
            </td>
          </tr>
          <tr>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Data regjistrimit:
              <span
                class="font-weight-bold float-right"
              >{{ convertDate(confirmedOrder.created_at) }}</span>
            </td>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Referenca:
              <span
                class="font-weight-bold float-right"
              >{{ confirmedOrder.reference != 'Null' ? confirmedOrder.reference : "" }}</span>
            </td>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Numri i porosisë:
              <span class="font-weight-bold float-right">{{ confirmedOrder.id }}</span>
            </td>
            <td style=" border: 1px solid #111; padding: 4px 8px;">
              Emri i produktit:
              <span class="font-weight-bold float-right">{{ confirmedOrder.name }}</span>
            </td>
          </tr>
          <tr>
            <td style=" border: 1px solid #111; padding: 4px 8px;" colspan="2">
              <p>Shitësi:</p>
              <strong>{{ confirmedOrder.creator ? confirmedOrder.creator.user_details ? confirmedOrder.creator.user_details.name +" ("+confirmedOrder.creator.user_details.phone_number+") ":"" : '' }}</strong>
              {{ confirmedOrder.city ?confirmedOrder.city.name : "" }}
              <span
                class="float-right"
              >
                {{ confirmedOrder.seller_location ? confirmedOrder.seller_location + ","+(confirmedOrder.creator ? confirmedOrder.creator.role.role_name != 'admin' ?(confirmedOrder.creator.user_details.city ? confirmedOrder.creator.user_details.city.name+'':"" ): "":"" ) : '' }}
                <br />
              </span>
            </td>
            <td style=" border: 1px solid #111; padding: 4px 8px;" colspan="2">
              <p>Blerësi:</p>
              <span
                class="font-weight-bold"
              >{{ confirmedOrder.buyer_name+"("+confirmedOrder.buyer_phone+")" }}</span>
              <span
                class="float-right"
              >{{ confirmedOrder.buyer_location ? confirmedOrder.buyer_location+ ","+ (confirmedOrder.city_name ?confirmedOrder.city_name : "") : '' }}</span>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</template>

<script>
import QrcodeVue from "qrcode.vue";

export default {
  name: "PdfInvoice",

  props: {
    confirmedOrder: Object
  },
  computed: {},
  data() {
    return {
      size: 140,
      location: window.location.hostname
    };
  },
  methods: {
    convertDate(date) {
      // var status = date + "";
      // var jun = moment(status)
      //   .add(9, "hours")
      //   .format("YYYY-MM-DD hh:mm:ss");
      // return jun;
      var d = date;
      d = d.split(" ")[0];
      return d;
    }
  },
  components: {
    QrcodeVue
  }
};
</script>
