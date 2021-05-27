<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="sub-title m-0">Slider</h2>
            <div class="checkbox">
              <input type="checkbox" checked value="" /><label>Active</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-5 col-12 ml-auto">
          <div class="d-flex">
            <input
              type="text"
              name="Search"
              class="form-control custom-input"
              value=""
              placeholder="Search"
              id=""
            />
            <button class="btn btn-primary">Search</button>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-12">
          <div class="table-responsive">
            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Name of product</th>
                  <th scope="col">Description of product</th>
                  <th scope="col">Price of product</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Maic Nike</td>
                  <td>
                    <p>Maic pambuku nike</p>
                  </td>
                  <td>
                    <p>45 &euro;</p>
                  </td>
                  <td>
                    <button class="remove-btn-style delete-btn">
                      <i class="fa fa-trash" />
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>Maic Nike 2</td>
                  <td>
                    <p>Maic pambuku nike 2</p>
                  </td>
                  <td>
                    <p>45 &euro;</p>
                  </td>
                  <td>
                    <button class="remove-btn-style delete-btn">
                      <i class="fa fa-trash" />
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>