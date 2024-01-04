@extends('layouts.student')

@section('header')
    @include('student.header'); sssss
@endsection

@section('leftmenu')
    @include('student.leftmenu'); 
@endsection

@section('content')
<div class="modal fade" id="beforstart" tabindex="-1" data-bs-backdrop="false" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Diqqat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Test jarayoniga o'tishga tayyormisiz? Agar tayyor bo'lsangiz Ha aks holda Yo'q tugmasini bosing. 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yo'q</button>
        <button type="button" class="btn btn-primary">Ha</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="fullscreenModal" tabindex="-1" aria-hidden="true" style="display: none; ">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div>
        <div class="progress">
          <div class="progress-bar progress-bar-striped" id="timeprogress" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
      <div class="modal-header" style="background-color: mintcream;">
        <h5 class="modal-title"><i class="bi bi-arrow-left-circle-fill" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></i> ortga</h5>
        <div id="timer">
            <span id="time-left">-:-:-</span>
        </div>
      </div>
      <div class="container progress-bar progress-bar-striped"><br><br><br><br>
        <button id="start" class="btn btn-outline-info">Start</button>
      </div>
      <div class="container alert alert-secondary modal-body all-questions " id="modal-body" style="display: none;">
            <button class="btn btn-primary" type="button" disabled="">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
      </div>
      <div class="modal-footer" id="modal-footer" style="display: none;">
        <button type="button" class="btn btn-success">Natijani yuborish</button>
      </div>
    </div>
  </div>
</div>
<main id="main" class="main">

<div class="pagetitle">
  <h1>Talaba</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active">Fan nomi</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">
      <div class="card-body">
        <h5 class="list-group-item d-flex justify-content-between align-items-center card-title"><p>Mavzular</p><p>Mavzuga oid topshiriqlar</p></h5>

        <!-- Default List group -->
        <ul class="list-group">
          @foreach($topics as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center"><a href="#" class="card-title" data-bs-toggle="modal" data-bs-target="#disabledText{{ $loop->iteration }}">{{ $loop->iteration .".". $item->topicname }}</a> <button type="button" class="btn btn-success question" data-bs-toggle="modal" data-bs-target="#fullscreenModal" data-test-id="{{ $item->task_id }}"><i class="bi bi-ui-radios"></i> Boshlash</button></li>
            <div class="modal fade" id="disabledText{{ $loop->iteration }}" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">{{ $item->topicname }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      {!! $item->text !!}
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
          @endforeach
        </ul><!-- End Default List group -->

      </div>

      </div>
    </div><!-- End Left side columns -->

  </div>
</section>

</main><!-- End #main -->

@endsection

@section('footer')
  @include('teacher.footer'); 
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script>

    $(".question").click(function(){
      var testid = $(this).attr('data-test-id');
      var div = $('.all-questions');
      var route = "{{ route('student.takequestions') }}";
      $.ajax({
          method: "GET",
          url: route,
          data: {
              'testid': testid,
          },
          beforeSend: function(){
            // sleep(0.2);
          },
          success: function(data) {
              div.html(data);
          }
      });
    });


    // Countdown Timer logic
    let countdown;
    const timeLeftDisplay = document.getElementById('time-left');
    const timeprogressDisplay = document.getElementById('timeprogress');
    const startButton = document.getElementById('start');

    // Function to start the countdown
    function startCountdown() {
        var qdiv = document.getElementById("modal-body");
        var fdiv = document.getElementById("modal-footer");
        qdiv.style.display = "block";
        fdiv.style.display = "block";
        startButton.style.display = "none";
        const hours = 0;
        const minutes = 30;
        const seconds = 0;
        const totalTime = hours * 3600 + minutes * 60 + seconds;
        
        let remainingTime = totalTime;

        countdown = setInterval(() => {
            const hoursLeft = Math.floor(remainingTime / 3600);
            const minutesLeft = Math.floor((remainingTime % 3600) / 60);
            const secondsLeft = remainingTime % 60;

            const timeString = `${hoursLeft.toString().padStart(2, '0')}:${minutesLeft.toString().padStart(2, '0')}:${secondsLeft.toString().padStart(2, '0')}`;

            timeLeftDisplay.textContent = timeString;
            timeprogressDisplay.textContent = `${Math.floor((remainingTime/totalTime)*100)}%`;
            timeprogressDisplay.style.width = `${(remainingTime/totalTime)*100}%`;
            if (remainingTime === 0) {
                clearInterval(countdown);
            } else {
                remainingTime--;
            }
        }, 1000);
    }

    startButton.addEventListener('click', startCountdown);

  </script>
@endsection