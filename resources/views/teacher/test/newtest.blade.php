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
      <li class="breadcrumb-item active">Test yaratish</li>
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
              <form class="row g-3" method="POST" action="create_test" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" name="title" required>
                        <label for="floatingInput">Test nomi</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="inputNanme4" class="form-label">Test turi</label>
                    <select id="inputState" name="category" class="form-select" required>
                        <option selected="">-Tanlash-</option>
                        @foreach($testcategories as $item)
                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                        @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                    <label for="inputNanme4" class="form-label">Fan</label>
                    <select id="inputState" name="subject" class="form-select" required>
                        <option selected="">-Tanlash-</option>
                        @foreach($lessons as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="inputNanme4" class="form-label">Test faylini yuklash <span style="color: red;">(fayl .docx formatda  bo'lishi kerak)</span></label>
                    <input type="file" name="file" class="form-control" placeholder="Address" accept=".docx" required><br>
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
                </div>

                <div class="col-md-6">
                <div class="form-group">
    <label for="exampleInputEmail1">Tushadigan Savollar soni</label>
    <input type="number" name="count" class="form-control" id="exampleInputEmail1" placeholder="raqam">
    <small id="emailHelp" class="form-text text-muted">.</small>
  </div>
                </div>

                <div class="col-md-6">
                <div class="form-group">
    <label for="exampleInputEmail1">Test vaqtini minut ko'rinishida yozing misol uchun 60 </label>
    <input type="number" name="timer" class="form-control" id="exampleInputEmail1" placeholder="raqam">
    <small id="emailHelp" class="form-text text-muted">.</small>
  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Saqlash</button>
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
