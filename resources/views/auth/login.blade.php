@extends('layouts.basic')

@section('content')
<main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                  <!-- <img src="assssets/img/logo.png" alt=""> -->
              <div class="card mb-3">
                  <div class="progress" style="height: 5px;">
                    <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                <div class="card-body">
                  <div class="d-flex justify-content-center">
                    <!-- <h3 class="card-title text-center pb-0 fs-3">Kirish</h3> -->
                    <img src="assets/img/logo.png" alt="" width="40%">
                  </div>
                  <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Hemis ID</label>
                      <div class="input-group has-validation">
                        <input type="text" class="form-control" name="email" :value="old('login')" autofocus autocomplete="username" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required autocomplete="current-password" >
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                  </form>
                </div>
            </div>

            </div>
          </div>
        </div>
      </section>
    </div>
</main><!-- End #main -->
@endsection