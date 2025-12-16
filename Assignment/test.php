<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>My Store</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
  /* Reset some default styles */
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: 'Helvetica Neue', sans-serif;
    background-color: #f4f4f4;
    color: #333;
  }

  /* Header */
  header {
    background-color: #222;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 999;
  }

  header h1 {
    color: #fff;
    font-size: 24px;
  }

  nav {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  nav a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background 0.3s;
  }

  nav a:hover {
    background-color: #444;
  }

  /* Buttons for login/register */
  .btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
    text-decoration: none;
  }

  .btn-login {
    background-color: transparent;
    color: #fff;
    border: 2px solid #fff;
  }

  .btn-login:hover {
    background-color: #fff;
    color: #222;
  }

  .btn-register {
    background-color: #ff6f61;
    color: #fff;
  }

  .btn-register:hover {
    background-color: #e55b50;
  }

  /* Search Bar */
  .search-box {
    display: flex;
    align-items: center;
  }

  .search-box input {
    padding: 8px 12px;
    border: none;
    border-radius: 4px 0 0 4px;
    outline: none;
    width: 200px;
  }

  .search-box button {
    padding: 8px 14px;
    border: none;
    background-color: #ff6f61;
    color: #fff;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
  }

  /* Hero Banner */
  .banner {
    background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-4.0.1&auto=format&fit=crop&w=1600&q=80');
    background-size: cover;
    background-position: center;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
    font-size: 36px;
    font-weight: bold;
    padding: 0 20px;
    text-align: center;
  }

  /* Main Content */
  .container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
  }

  /* Filters & Sorting */
  .filters {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .filters .filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
  }

  /* Product Grid */
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }

  /* Product Card */
  .card {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    transition: transform 0.2s;
  }
  .card:hover {
    transform: translateY(-3px);
  }

  .card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }

  .card-body {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .product-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
  }

  .price {
    color: #ff6f61;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 12px;
  }

  .actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
  }

  .btn-add {
    background-color: #ff6f61;
    color: #fff;
  }

  .btn-add:hover {
    background-color: #e55b50;
  }

  /* Footer */
  footer {
    background-color: #222;
    color: #fff;
    padding: 30px 20px;
    margin-top: 50px;
  }

  footer p {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
  }
</style>
</head>
<body>

<header>
  <h1>My Store</h1>
  <nav>
    <a href="#">Home</a>
    <a href="#">Shop</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
    <!-- Login/Register Buttons -->
    <a href="#" class="btn btn-login">Login</a>
    <a href="#" class="btn btn-register">Register</a>
    <!-- Search inside nav -->
    <div class="search-box" style="margin-left:auto;">
      <input type="text" placeholder="Search products..." />
      <button>Search</button>
    </div>
  </nav>
</header>

<!-- Hero Banner -->
<div class="banner">
  Discover Our Latest Collection
</div>

<!-- Main Content -->
<div class="container">
  <!-- Filters & Sorting -->
  <div class="filters">
    <div class="filter-group">
      <label for="category">Category:</label>
      <select id="category">
        <option>All</option>
        <option>Clothing</option>
        <option>Electronics</option>
        <option>Home</option>
      </select>
    </div>
    <div class="filter-group">
      <label for="sort">Sort by:</label>
      <select id="sort">
        <option>Price: Low to High</option>
        <option>Price: High to Low</option>
        <option>Newest</option>
      </select>
    </div>
  </div>

  <!-- Product Grid -->
  <div class="product-grid">
    <!-- Example Product Card -->
    <div class="card">
      <img src="/photos/692bb9e0d7537.jpg" alt="Product 1"/>
      <div class="card-body">
        <div class="product-name">Stylish T-Shirt</div>
        <div class="price">$19.99</div>
        <div class="actions">
          <button class="btn btn-add">Add to Cart</button>
        </div>
      </div>
    </div>
    <!-- Repeat product cards as needed -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1614952037744-1f14f2c6eae4?ixlib=rb-4.0.1&auto=format&fit=crop&w=800&q=80" alt="Product 2"/>
      <div class="card-body">
        <div class="product-name">Wireless Headphones</div>
        <div class="price">$59.99</div>
        <div class="actions">
          <button class="btn btn-add">Add to Cart</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <p>&copy; 2024 My Store. All rights reserved.</p>
</footer>

</body>
</html>