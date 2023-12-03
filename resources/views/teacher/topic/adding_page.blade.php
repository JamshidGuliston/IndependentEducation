@extends('layouts.basic')

@section('header')
    @include('teacher.header'); 
@endsection

@section('leftmenu')
    @include('teacher.leftmenu'); 
@endsection

@section('content')
<main id="main" class="main">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title"><span class="text-danger">Fan nomi:</span> {{ $data["subject_name"] }} <span class="text-danger">Guruh:</span> {{ $data["group_name"] }}</h5>
              <!-- Vertical Form -->
              <form class="row g-3" method="POST" action="create_topic">
                @csrf
                <input type="hidden" name="group_id" value="{{ $data['group_id'] }}">
                <input type="hidden" name="semester_id" value="{{ $data['semester_id'] }}">
                <input type="hidden" name="subject_id" value="{{ $data['subject_id'] }}">
                <input type="hidden" name="subject_name" value="{{ $data['subject_name'] }}">
                <input type="hidden" name="group_name" value="{{ $data['group_name'] }}">
                <div class="col-12">
                    <div class="card-body">
                        <label for="inputNanme4" class="form-label">Mavzu nomi:</label>
                        <input type="text" class="form-control" id="inputNanme4" name="title">
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-body">
                        <h5 class="card-title">Mavzu matni</h5>
                        <textarea class="tinymce-editor" name="fulltext">
                        </textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-body">
                        <label for="inputAddress" class="form-label">Baholash</label>
                        <input type="text" name="test_id" class="form-control" id="inputAddress" placeholder="Qidirish">
                    </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-success">Saqlash</button>
                </div>
              </form><!-- Vertical Form -->

            </div>
        </div>
    </div>
</main>
@endsection

@section('footer')
    @include('teacher.footer'); 
@endsection