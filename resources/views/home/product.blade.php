<style>
  .custom-bg {
    background-color: white; /* Ganti dengan warna yang Anda inginkan */
}

hr.custom-divider {
    border: none;
    border-top: 3px solid skyblue;
    width: 80%;
    margin: 20px auto;
  }

</style>

<section class="shop_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">

      <p class="card-text font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;">
                      Selamat datang di pusat perbelanjaan kami yang menyediakan solusi lengkap untuk budidaya ikan gurami. 
                      Temukan telur bibit berkualitas tinggi dan benih ikan gurami terbaik untuk memulai atau meningkatkan 
                      usaha akuakultur Anda. Dengan telur bibit yang dipilih secara hati-hati dan benih yang sehat, Anda dapat 
                      memastikan pertumbuhan yang optimal dan hasil panen yang memuaskan. Dukung keberhasilan bisnis Anda dalam 
                      budidaya ikan gurami dengan produk-produk unggulan kami.</p>

      


      <h2>
          TEMPAT PEMIJAHAN
      </h2>

      <hr class="custom-divider">
      
      <div style="margin-bottom: 50px;">
      
      <video width="640" height="480" controls>
      <source src="images/kolam.mp4" type="video/mp4">
      Your browser does not support the video tag.
      </video>

      </div>

      

        <h2>
          TERSEDIA
        </h2>

        <hr class="custom-divider">

      </div>

      


      <div class="row justify-content-center">

        @foreach($product as $products)

        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="box rounded text-center">
            
              <div class="img-box rounded custom-bg mx-auto">
                <img src="products/{{$products->image}}" alt="">
              </div>
              <div class="detail-box" style="padding: 10px">
                <h6>{{$products->title}}</h6>
                <h6>
                  Harga
                  <span>
                    Rp.{{$products->price}}
                  </span>
                </h6>
              </div>
            
              <div style="padding: 5px">
                <a class="btn btn-danger" style="color:white" href="{{url('product_details', $products->id)}}">Detail</a>

                <a class="btn btn-primary" style="color:white" href="{{url('add_cart', $products->id)}}">Tambah</a>

              </div>

          </div>
        </div>
        
        @endforeach
        
      </div>
      
    </div>
  </section>