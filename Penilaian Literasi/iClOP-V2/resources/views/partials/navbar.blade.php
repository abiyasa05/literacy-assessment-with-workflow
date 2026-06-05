<style>
  .btn-google {
    text-decoration: none;
    border: 1px solid #1466C2;
    color: #1466C2;
    display: inline-block;
    padding: 10px 100px;
    border-radius: 5px;
  }

  .btn-google:hover {
    background-color: #0056b3;
    /* Warna latar saat di-hover */
    color: white;
    /* Warna teks saat di-hover */
  }
</style>
<nav class="navbar navbar-expand-lg" style="background-color: #fff; ">
  <div class="container-fluid">
    <!-- <a class="navbar-brand" href="#">Navbar</a> -->
    <img src="./images/logo.png" alt="logo" width="104" height="65">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <div class="mx-auto">
        <ul class="navbar-nav mb-2 mb-lg-0 justify-content-center">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Tutorials</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Contact Us</a>
          </li>
        </ul>
      </div>

      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal"
        style="border-radius: 20px; margin-right: 10px; width: 100px; height: 35px;">Sign In</button>
      <button class="btn btn-primary custom-button-sign-up" onclick="window.location.href='{{ route('signup') }}'">Sign
        Up</button>
    </div>
  </div>
</nav>

<!-- MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header border-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="row align-items-center">

          <!-- IMAGE SECTION -->
          <div class="col-12 col-lg-6 text-center mb-4 mb-lg-0">
            <img src="./images/sign-in.png" alt="Illustration" class="img-fluid" style="max-height: 400px;">
          </div>

          <!-- FORM SECTION -->
          <div class="col-12 col-lg-6 px-4 px-lg-5">
            <!-- TITLE -->
            <p class="sign-in-modal fs-3 fw-bold mb-1">Sign In</p>
            <p class="welcome-modal text-muted mb-4">Welcome back! Please enter your details.</p>

            <!-- FORM -->
            <form action="{{ route('login') }}" method="post">
              @csrf

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
              </div>

              <!-- Password -->
              <div class="mb-3">
                <label for="password" class="form-label fw-medium">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                  required>
              </div>

              <!-- Sign In Button -->
              <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Sign In</button>
              </div>
            </form>

            <!-- Google Sign In -->
            <div class="d-grid mb-3">
              <a href="{{ route('google.redirect') }}"
                class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                <img src="./images/google.png" alt="Google Icon" style="width: 16px; height: 16px;">
                Sign In with Google
              </a>
            </div>

            <!-- Sign Up Link -->
            <p class="text-center mb-0">
              Don’t have an account?
              <a href="{{ route('signup') }}" class="text-primary text-decoration-none">Sign Up</a>
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>