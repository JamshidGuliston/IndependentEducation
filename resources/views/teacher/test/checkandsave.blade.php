@extends('layouts.basic')

@section('header')
    @include('teacher.header'); 
@endsection

@section('leftmenu')
    @include('teacher.leftmenu'); 
@endsection

@section('content')
<main id="main" class="main">

<div class="pagetitle">
  <h1>Yangi test</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Bosh sahifa</a></li>
      <li class="breadcrumb-item"><a href="/teacher/page_creating_test">Test yaratish</a></li>
      <li class="breadcrumb-item active">Testni ko'rish</li>
    </ol>
  </nav>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div style="height: 15px;">
                    <!-- <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="50"></div> -->
                </div>
              <!-- No Labels Form -->
              <form class="row g-3" method="POST" action="correct_test" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="title" value="{{ $aboutTest['title'] }}" />
                <input type="hidden" name="category" value="{{ $aboutTest['category'] }}" />
                <input type="hidden" name="subject" value="{{ $aboutTest['subject'] }}" />
                <input type="hidden" name="count" value="{{ $aboutTest['count'] }}" />
                <input type="hidden" name="timer" value="{{ $aboutTest['timer'] }}" />
                <div class="col-md-12">
                @foreach ($paragraphs as $paragraph) 
                    @if(($loop->iteration % 5 == 1 and $aboutTest['category'] == 1) or $aboutTest['category'] != 1)
                    <div class="alert border-primary alert-dismissible fade show">
                        <p>{{ ($aboutTest['category'] == 1) ? ceil($loop->iteration / 5) : $loop->iteration }}-savol</p>
                        <p class="mb-0"><input type="hidden" name="data[]" value="{{$paragraph}}">{!! $paragraph !!}</p>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
                    </div>
                    @elseif($loop->iteration % 5 == 2)
                        <div class="alert alert-success alert-dismissible fade show" >
                            <p class="mb-0"><input type="hidden" name="data[]" value="{{$paragraph}}">{!! $paragraph !!}</p>
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
                        </div>
                    @else
                        <div class="alert border-info alert-dismissible fade show">
                            <p class="mb-0"><input type="hidden" name="data[]" value="{{$paragraph}}">{!! $paragraph !!}</p>
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
                        </div>
                    @endif
                @endforeach
                </div>
    
                <div class="text-center">
                <button type="submit" name="delete" class="btn btn-danger ml-3 mr-3">Qayta yuklash </button>
                @if((count($paragraphs) % 5 == 0 and count($paragraphs) != 0) or $aboutTest['category'] != 1)
                    <button type="submit" name="save" class="btn btn-primary">Tasdiqlash</button>
                @else
                    <span>Testda xatolik bor. Ortiqcha qator qo'shilgan. iltimos qayta yuklang</span>
                @endif
                </div>
              </form>

            </div>
        </div>
    </div>

</main>

@endsection

@section('footer')
    @include('teacher.footer'); 
@endsection
