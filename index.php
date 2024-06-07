<?php
session_start();

$sqluser = "root";
$sqlpassword = "";
$sqldatabase = "login";

if (!isset($_SESSION['uname']) && isset($_COOKIE['uname']) && isset($_COOKIE['pass'])) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=" . $sqldatabase, $sqluser, $sqlpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
    $st = $pdo->prepare('SELECT * FROM list WHERE user_name=?');
    $st->execute(array($_COOKIE['uname']));
    $r = $st->fetch();
    if ($r != null && password_verify($_COOKIE['pass'], $r["password"])) {
        $_SESSION["uname"] = $_COOKIE['uname'];
        $_SESSION["fname"] = $r["first_name"];
    }
}

if (!isset($_SESSION['uname'])) {
    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kopi Kenangan Senja</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet">

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- My Style -->
  <link rel="stylesheet" href="css/style.css">

  <!-- alpineJs -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="src/app.js"></script>
  
  <!-- Custom Style for Sidebar -->
  <style>

    .navbar-extra a#settings-button {
       margin-left: 0.8rem; 
    }


    .settings-sidebar {
      position: fixed;
      top: 40px;
      right: -300px;
      width: 300px;
      height: 100%;
      background: #333;
      color: white;
      transition: right 0.3s;
      z-index: 1000;
      padding: 20px;
    }

    .settings-sidebar.active {
      right: 0;
    }

    .settings-sidebar .close-btn {
      display: block;
      text-align: right;
      margin-top: 3rem;
      
    }

    .settings-sidebar .close-btn i {
      cursor: pointer;
      
    }

    .settings-sidebar ul {
      list-style-type: none;
      padding: 0;
    }

    .settings-sidebar ul li {
      margin: 20px 0;
    }

    .settings-sidebar ul li a {
      color: white;
      text-decoration: none;
      font-size: 1.2em;
    }

    .settings-sidebar ul li a:hover {
      color: #d3a27f;
    }

    .settings-sidebar button {
      background: none; border: none; color: white; font-size: 1.2em; cursor:pointer;
    }

    .settings-sidebar button:hover{
      color: #d3a27f;
    }


  </style>
</head>

<body>

  <!-- Navbar start -->
  <nav class="navbar" x-data>
    <a href="#" class="navbar-logo">kenangan<span>senja</span>.</a>

    <div class="navbar-nav">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#menu">Menu</a>
      <a href="#products">Produk</a>
      <a href="#contact">Kontak</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="search-button"><i data-feather="search"></i></a>
      <a href="#" id="shopping-cart-button">
        <i data-feather="shopping-cart"></i>
        <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
      </a>
      <a href="#" id="settings-button"><i data-feather="settings"></i></a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

    <!-- Search Form start -->
    <div class="search-form">
      <input type="search" id="search-box" placeholder="search here...">
      <label for="search-box"><i data-feather="search"></i></label>
    </div>
    <!-- Search Form end -->

    <!-- Shopping Cart start -->
    <div class="shopping-cart">
      <template x-for="(item, index) in $store.cart.items" x-keys="index">
        <div class="cart-item">
        <img :src="`img/products/${item.img}`" :alt="item.name">
        <div class="item-detail">
          <h3 x-text="item.name"></h3>
          <div class="item-price">
            <span x-text="rupiah(item.price)"></span> &times;
            <button id="remove"  @click="$store.cart.remove(item.id)">&minus;</button>
            <span x-text="item.quantity"></span>
            <button id="add" @click="$store.cart.add(item)">&plus;</button>&equals;
            <span x-text="rupiah(item.total)"></span>
          </div>
        </div>
      </div>
    </template>
    <h4 x-show="!$store.cart.items.length" style="margin-top: 1rem;">Cart is Empty</h4>
    <h4 x-show="$store.cart.items.length">Total : <span x-text="rupiah($store.cart.total)"></span></h4>


    <div class="form-container" x-show="$store.cart.items.length">
      <form action="" id="checkoutForm">

        <h5>Customer Detail</h5>

        <label for="name">
          <span>Name</span>
          <input type="text" name="name" id="name">
        </label>

        <label for="email">
          <span>Email</span>
          <input type="email" name="email" id="email">
        </label>

        <label for="phone">
          <span>Phone</span>
          <input type="number" name="phone" id="phone" autocomplete="off">
        </label>

        <button class="checkout-button" type="submit" id="checkout-button" value="checkout">Checkout</button>

      </form>
    </div>
    </div>
    <!-- Shopping Cart end -->

  </nav>
  <!-- Navbar end -->

  <!-- Settings Sidebar start -->
  <div class="settings-sidebar" id="settings-sidebar">
    <div class="close-btn"><i data-feather="x"></i></div>
    <ul>
      <li><a href="changepassword.php">Ganti Password</a></li>
      <li>
        <form method="post" action="logout.php">
          <button type="submit">
            Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
  <!-- Settings Sidebar end -->

  <!-- Hero Section start -->
  <section class="hero" id="home">
    <div class="mask-container">
      <main class="content">
        <h1>Mari Nikmati Secangkir <span>Kopi</span></h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, enim.</p>
      </main>
    </div>
  </section>
  <!-- Hero Section end -->

  <!-- About Section start -->
  <section id="about" class="about">
    <h2><span>Tentang</span> Kami</h2>

    <div class="row">
      <div class="about-img">
        <img src="img/tentang-kami.jpg" alt="Tentang Kami">
      </div>
      <div class="content">
        <h3>Kenapa memilih kopi kami?</h3>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur ducimus voluptatum dolor. Et, voluptatum
          accusantium!</p>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Hic deserunt iure amet facilis eos a quo cum
          voluptates molestias nihil.</p>
      </div>
    </div>
  </section>
  <!-- About Section end -->

  <!-- Menu Section start -->
  <section id="menu" class="menu">
    <h2><span>Menu</span> Kami</h2>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Expedita, repellendus numquam quam tempora voluptatum.
    </p>

    <div class="row">
      <div class="menu-card">
        <img src="img/menu/1.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Espresso -</h3>
        <p class="menu-card-price">IDR 15K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/1.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Capuccino -</h3>
        <p class="menu-card-price">IDR 25K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/1.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Latte -</h3>
        <p class="menu-card-price">IDR 20K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/1.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Espresso -</h3>
        <p class="menu-card-price">IDR 15K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/1.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Espresso -</h3>
        <p class="menu-card-price">IDR 15K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/1.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Espresso -</h3>
        <p class="menu-card-price">IDR 15K</p>
      </div>
    </div>
  </section>
  <!-- Menu Section end -->

  <!-- Products Section start -->
  <section class="products" id="products" x-data="products">
    <h2><span>Produk Unggulan</span> Kami</h2>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo unde eum, ab fuga possimus iste.</p>

    <div class="row">
      <template x-for="(item, index) in items" x-key="index">
      <div class="product-card">
        <div class="product-icons">
        <a href="#" @click.prevent="$store.cart.add(item)">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#shopping-cart" />
                            </svg>
                        </a>
                        <a href="#" class="item-detail-button">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#eye" />
                            </svg>
                        </a>
        </div>
        <div class="product-image">
          <img :src="`img/products/${item.img}`" :alt="item.name">
        </div>
        <div class="product-content">
          <h3 x-text="item.name"></h3>
          <div class="product-stars">
            <svg
            width="24"
            height="24"
            fill="currentColor"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <use href="/img/feather-sprite.svg#star" />
          </svg>
          <svg
            width="24"
            height="24"
            fill="currentColor"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <use href="/img/feather-sprite.svg#star" />
          </svg>
          <svg
            width="24"
            height="24"
            fill="currentColor"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <use href="/img/feather-sprite.svg#star" />
          </svg>
          <svg
            width="24"
            height="24"
            fill="currentColor"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <use href="/img/feather-sprite.svg#star" />
          </svg>
          <svg
            width="24"
            height="24"
            fill="currentColor"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <use href="/img/feather-sprite.svg#star" />
          </svg>
          </div>
          <div class="product-price"><span x-text="rupiah(item.price)"></span></div>
        </div>
      </template>
      </div>
    </div>
  </section>
  <!-- Products Section end -->

  <!-- Contact Section start -->
  <section id="contact" class="contact">
    <h2><span>Kontak</span> Kami</h2>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, provident.
    </p>

    <div class="row">
      <iframe
        src="https://maps.google.com/maps?q=Yogyakarta&t=&z=13&ie=UTF8&iwloc=&output=embed"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>

      <form action="">
        <div class="input-group">
          <i data-feather="user"></i>
          <input type="text" placeholder="nama">
        </div>
        <div class="input-group">
          <i data-feather="mail"></i>
          <input type="text" placeholder="email">
        </div>
        <div class="input-group">
          <i data-feather="phone"></i>
          <input type="text" placeholder="no hp">
        </div>
        <button type="submit" class="btn">kirim pesan</button>
      </form>

    </div>
  </section>
  <!-- Contact Section end -->

  <!-- Footer start -->
  <footer>
    <div class="socials">
      <a href="#"><i data-feather="instagram"></i></a>
      <a href="#"><i data-feather="twitter"></i></a>
      <a href="#"><i data-feather="facebook"></i></a>
    </div>

    <div class="links">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#menu">Menu</a>
      <a href="#contact">Kontak</a>
    </div>

  </footer>
  <!-- Footer end -->

  <!-- Modal Box Item Detail start -->
  <div class="modal" id="item-detail-modal">
    <div class="modal-container">
      <a href="#" class="close-icon"><i data-feather="x"></i></a>
      <div class="modal-content">
        <img src="img/products/1.jpg" alt="Product 1">
        <div class="product-content">
          <h3>Product 1</h3>
          <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Provident, tenetur cupiditate facilis obcaecati
            ullam maiores minima quos perspiciatis similique itaque, esse rerum eius repellendus voluptatibus!</p>
          <div class="product-stars">
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star"></i>
          </div>
          <div class="product-price">IDR 30K <span>IDR 55K</span></div>
          <a href="#"><i data-feather="shopping-cart"></i> <span>add to cart</span></a>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Box Item Detail end -->

  <!-- Feather Icons -->
  <script>
    feather.replace()

    // Sidebar control
    document.getElementById('settings-button').addEventListener('click', function() {
        document.getElementById('settings-sidebar').classList.toggle('active');
    });

    document.querySelector('.close-btn').addEventListener('click', function() {
        document.getElementById('settings-sidebar').classList.remove('active');
    });
    document.addEventListener('click', function(event) {
        var isClickInside = document.getElementById('settings-sidebar').contains(event.target) || 
                            document.getElementById('settings-button').contains(event.target);

        if (!isClickInside) {
            document.getElementById('settings-sidebar').classList.remove('active');
        }
    });

  </script>

  <!-- My Javascript -->
  <script src="js/script.js"></script>
</body>

</html>
