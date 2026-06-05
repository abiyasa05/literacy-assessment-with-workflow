<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.header')
  <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
</head>

<body>
  @include('partials.navbar')

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-6 content-left">
        <p class="welcome-to-iclop">Welcome To iCLOP</p>
        <p class="where-education" style="line-height:60px;">Where Your <span class="education">Education</span> Has No Limit</p>
        <p class="subtext">iCLOP (intelligent computer assisted programming learning platform)</p>
        <p class="subtext">With our easy-to-follow tutorials and examples, you can learn to code in no time...</p>
      </div>
      <div class="col-lg-6 content-right">
        <img src="{{ asset('images/Edeucation.png') }}" alt="Illustration" class="img-fluid">
      </div>
    </div>
  </div>

  <div class="container mt-3">
    <div class="row">
      <div class="col-lg-6 content-left">
        <img src="{{ asset('images/online_virtual_machine.png') }}" alt="VM" class="img-fluid">
      </div>
      <div class="col-lg-6 content-right">
        <p class="where-education" style="font-size: 40px;">Online Virtual Machine</p>
        <p style="font-size: 20px; margin-top: 35px;">Make learning an easy process...</p>
      </div>
    </div>
  </div>

  <div class="container py-5" style="background-color: #FAFAFA;">
    <p class="where-education text-center" style="font-size: 35px;">Choose What You Want To Study</p>
    <p class="popular-languages text-center" style="font-size: 20px;">Begin By Studying Some of The Most Popular Programming Languages</p>

    <div class="row gx-4 gy-4 justify-content-center mt-4">
      @foreach ($cards as $card)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex justify-content-center">
          <div class="card p-0 shadow-sm" style="width: 100%; max-width: 305px; height: 375px;">
            <img src="{{ $card['image'] }}" class="card-img-top" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ $card['title'] }}</h5>
              <div class="row align-items-start">
                <div class="col-1">
                  <img src="{{ asset('images/book.png') }}" style="width: 13px; height: 16px;">
                </div>
                <div class="col">
                  <p>{{ $card['topics'] }}</p>
                </div>
              </div>
              <div class="mt-auto">
                <a href="#" class="btn btn-primary">Start Learning</a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="text-center mt-4">
      <button class="btn btn-primary custom-button-sign-up" style="width: 252px; height: 42px;">Load More</button>
    </div>
  </div>

  <div class="container text-center mt-5">
    <p class="where-education" style="font-size: 40px;">Our Services</p>
    <p style="font-size: 25px; color: #636363;">Make Your Learning Experience<br>Extraordinary With The Services We Provide</p>
  </div>

  <div class="container text-center mt-5">
    <div class="row gx-4 gy-4 justify-content-center">
      @foreach ($cardsData as $card)
        <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
          <div class="card shadow-sm" style="width: 100%; max-width: 430px; height: 440px;">
            <div class="card-body">
              <img src="{{ $card['image'] }}" alt="icon" style="height: 102px; margin-top: 20px;">
              <p style="font-size: 22px; font-weight: 600; color: #34364A; margin-top: 24px;">{{ $card['title'] }}</p>
              <p style="font-size: 18px;">{{ $card['description'] }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="container text-center mt-4">
    <div class="row gx-4 gy-4 justify-content-center">
      @foreach ($cardsData2 as $card)
        <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
          <div class="card shadow-sm" style="width: 100%; max-width: 430px; height: 440px;">
            <div class="card-body">
              <img src="{{ $card['image'] }}" alt="icon" style="height: 102px; margin-top: 20px;">
              <p style="font-size: 22px; font-weight: 600; color: #34364A; margin-top: 24px;">{{ $card['title'] }}</p>
              <p style="font-size: 18px;">{{ $card['description'] }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <script src="{{ asset('script.js') }}"></script>

  @include('partials.footer')
</body>
</html>