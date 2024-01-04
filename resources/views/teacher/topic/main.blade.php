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
                <div class="col-md-12">
                    <h5 class="card-title"><span class="text-danger">Fan nomi:</span> {{ $data["subject_name"] }} <span class="text-danger">Guruh:</span> {{ $data["group_name"] }}</h5>
                </div>
                <div class="col-md-12 my-auto">
                    <form method="POST" action="{{ route('teacher.plantopic') }}">
                      @csrf
                      <input type="hidden" name="group_id" value="{{ $data['group_id'] }}">
                      <input type="hidden" name="semester_id" value="{{ $data['semester_id'] }}">
                      <input type="hidden" name="subject_id" value="{{ $data['subject_id'] }}">
                      <input type="hidden" name="subject_name" value="{{ $data['subject_name'] }}">
                      <input type="hidden" name="group_name" value="{{ $data['group_name'] }}">
                      <input type="hidden" name="plan_id" value="{{ $data['plan_id'] }}">
                      <div class="row">
                        <div class="col-md-8">
                            <select name="topicid" class="form-select" required>
                                <option selected="">-Tanlash-</option>
                                @foreach($topics as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['topicname'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus"></i> qo'shish</button>
                        </div>
                      </div>
                      @if ($errors->has('myFile'))
                          <div {{ $errors->first('myFile') }}</div>
                      @endif
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif
                    </form>
                </div>
              </div>
              <h3></h3>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Mavzular</th>
                    <th scope="col">O'chirsh</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($lesson as $row)
                  <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{$row->topicname}}</td>
                    <td> 
                      <form method="POST" action="{{route('teacher.deletetopic', ['data'=>$data, 'del_les_id'=> $row->topic_id])}}">
                        @csrf
                        <input type="hidden" name="del_les_id" value="{{$row->topic_id}}">
                        <a class="text-danger m-2"  onclick="event.preventDefault();
                                                this.closest('form').submit()"> 
                        <button type="button" class="btn btn-danger"><i class="bi bi-trash-fill"></i></button>
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