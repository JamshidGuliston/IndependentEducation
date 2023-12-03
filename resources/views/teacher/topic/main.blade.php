@extends('layouts.basic')

@section('header')
    @include('teacher.header'); 
@endsection

@section('leftmenu')
    @include('teacher.leftmenu'); 
@endsection

@section('content')
<main id="main" class="main">
<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                @if(!isset($data['group_id']))
                  <?php
                      $data = $data['data'];
                  ?>
                @endif
                <div class="col-md-10">
                    <h5 class="card-title"><span class="text-danger">Fan nomi:</span> {{ $data["subject_name"] }} <span class="text-danger">Guruh:</span> {{ $data["group_name"] }}</h5>
                </div>
                <div class="col-md-2 my-auto">
                    <form method="GET" action="{{ route('teacher.pageaddingtopic') }}">
                      @csrf
                      <input type="hidden" name="group_id" value="{{ $data['group_id'] }}">
                      <input type="hidden" name="semester_id" value="{{ $data['semester_id'] }}">
                      <input type="hidden" name="subject_id" value="{{ $data['subject_id'] }}">
                      <input type="hidden" name="subject_name" value="{{ $data['subject_name'] }}">
                      <input type="hidden" name="group_name" value="{{ $data['group_name'] }}">
                      <h5>
                      <button type="submit" class="btn btn-light "><span class="text-success"><i class="bi bi-plus-circle"> Mavzu qo'shish</i></span></button>
                      </h5>
                    </form>
                </div>
              </div>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Age</th>
                    <th scope="col">Start Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Brandon Jacob</td>
                    <td>Designer</td>
                    <td>28</td>
                    <td>2016-05-25</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Bridie Kessler</td>
                    <td>Developer</td>
                    <td>35</td>
                    <td>2014-12-05</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>Ashleigh Langosh</td>
                    <td>Finance</td>
                    <td>45</td>
                    <td>2011-08-12</td>
                  </tr>
                  <tr>
                    <th scope="row">4</th>
                    <td>Angus Grady</td>
                    <td>HR</td>
                    <td>34</td>
                    <td>2012-06-11</td>
                  </tr>
                  <tr>
                    <th scope="row">5</th>
                    <td>Raheem Lehner</td>
                    <td>Dynamic Division Officer</td>
                    <td>47</td>
                    <td>2011-04-19</td>
                  </tr>
                </tbody>
              </table>
              <!-- End Default Table Example -->
            </div>
          </div>
        </div>
      </div>
</section>
</main>
@endsection

@section('footer')
    @include('teacher.footer'); 
@endsection