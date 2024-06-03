<header class="header"> 
 
<style>
.button {
  display: inline-block;
  padding: 10px 20px;
  font-size: 14px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  color: #fff;
  background-color: #ff0000;
  border: none;
  border-radius: 15px;
}

.button:hover {background-color: #C51F1A}

.button:active {
  background-color: #C51F1A;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}
</style>


      <nav class="navbar navbar-expand-lg">
        <div class="search-panel">
          <div class="search-inner d-flex align-items-center justify-content-center">
            <div class="close-btn">Close <i class="fa fa-close"></i></div>
            <form id="searchForm" action="#">
              <div class="form-group">
                <input type="search" name="search" placeholder="What are you searching for...">
                <button type="submit" class="submit">Search</button>
              </div>
            </form>
          </div>
        </div>
        <div class="container-fluid d-flex align-items-center justify-content-between">
          <div class="navbar-header">
            <!-- Navbar Header--><a href="" class="navbar-brand">
              <div class="brand-text brand-big visible text-uppercase" style="color:white">HALAMAN ADMIN</div>
              <div class="brand-text brand-sm"><strong class="text-primary">HA</strong></div></a>
            <!-- Sidebar Toggle Btn-->
            <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
          </div>
          
            <!-- Log out -->
            <div class="list-inline-item logout">                   

            <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <input type="submit" value="LOGOUT" class="button">
                        </form>
          </div>
        </div>
      </nav>
    </header>