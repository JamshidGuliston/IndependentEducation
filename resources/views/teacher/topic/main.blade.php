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
                      <input type="hidden" name="plan_id" value="{{ $data['plan_id'] }}">
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
                    <th scope="col">Mavzular</th>
                    <th scope="col">Tahrirlash/O'chirsh</th>
                  </tr>
                </thead>
                <tbody>

@foreach($lesson as $row)

                  <tr>
                    <th scope="row">1</th>
                    <td>{{$row->topicname}}</td>
                    <td> 
                      
                    <form method="POST" action="{{route('teacher.pageedittopic', ['data'=>$data, 'edite_les_id'=> $row->topic_id])}}">
                        @csrf
                        <a class="m-2" href="{{$row->topic_id}}" onclick="event.preventDefault();
                                                this.closest('form').submit()">    
                          <i class="bi bi-pen-fill"></i>
                        </a>
                    </form>
                      <form method="POST" action="{{route('teacher.deletetopic', ['data'=>$data, 'del_les_id'=> $row->topic_id])}}">
                        @csrf
                            <input type="hidden" name="del_les_id" value="{{$row->topic_id}}">

                        <a class="text-danger m-2"  onclick="event.preventDefault();
                                                this.closest('form').submit()"> 
  
                        <i class="bi bi-trash-fill"></i>
                    
                        </a> 
                      </form>                 
                  </td>
                  </tr>

                  @endforeach
                 
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