<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Export PDF Orders</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
 <style>
   .container{
    padding: 5%;
   } 
</style>
</head>
<body>l
<div class="container">
    <div class="row">
        <div class="col-12">
           <a href="{{ url('pdf') }}" class="btn btn-success mb-2">Export PDF</a>
          <table class="table table-bordered" id="laravel_crud">
           <thead>
              <tr>
                 <th>Id</th>
                 <th>Product ID</th>
                 <th>User ID</th>
                 <th>Status</th>
                 <th>Order type</th>
              </tr>
           </thead>
           <tbody>
              @foreach($orders as $order)
              <tr>
                 <td>{{ $order->id }}</td>
                 <td>{{ $order->product_id }}</td>
                 <td>{{ $order->buyer_id }}</td>
                 <td>{{ $order->user_id }}</td>
                 <td>{{ $order->status }}</td>
                 <td>{{ $order->order_type }}</td>
                 <td>{{ date('d m Y', strtotime($order->date)) }}</td>
              </tr>
              @endforeach
           </tbody>
          </table>
       </div> 
   </div>
</div>
</body>
</html>