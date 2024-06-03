<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')

    <style type="text/css">

        table
        {
            border:2px solid white;
            text-align: center;
        }

        th
        {
            background-color: green;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            color: white;
        }

        td
        {
            color: white;
            padding: 10px;
            border: 1px solid white;
        }

        .table_center
        {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1
        {
            color: white;
            text-align: center;
            padding : 10px;
        }

    </style>

  </head>
  <body>
   
    @include('admin.header')

    @include('admin.sidebar')
      <!-- Sidebar Navigation end-->
      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">

          <h1>Halaman Pemesanan</h1>
            
            <div class="table_center">

          <table>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Status</th>
                <th>Change Status</th>
            </tr>

            @foreach($data as $data)

            <tr>
                <td>{{$data->name}}</td>
                <td>{{$data->rec_address}}</td>
                <td>{{$data->phone}}</td>
                <td>{{$data->product->title}}</td>
                <td>{{$data->product->price}}</td>
                <td>

                    <img width="150" src="products/{{$data->product->image}}">

                </td>

                <td>

                    @if($data->status == 'in progress')

                    <span style="color:red">{{$data->status}}</span>

                    @elseif($data->status == 'Dalam Perjalanan')

                    <span style="color:skyblue">{{$data->status}}</span>

                    @else

                    <span style="color:yellow">{{$data->status}}</span>

                    @endif

                </td>

                <td>

                    <a class="btn btn-primary" href="{{url('on_the_way', $data->id)}}">Dalam Perjalanan</a>

                    <a class="btn btn-success" href="{{url('delivered', $data->id)}}">Diterima</a>

                </td>

            </tr>

            @endforeach

          </table>


            
            </div>
      </div>
    </div>
    <!-- JavaScript files-->
    @include('admin.js')
  </body>
</html>