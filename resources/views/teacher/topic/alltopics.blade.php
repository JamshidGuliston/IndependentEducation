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
  <h1></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Bosh sahifa</a></li>
      <li class="breadcrumb-item active">Mavzular</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">
  <div class="col-lg-12">

<div class="card">
  <div class="card-body">

    <!-- Table with stripped rows -->
    <table class="table datatable">
        <h5 class="card-title">Mavzular</h5>
      <thead>
        <tr>
          <th>
            <b>Mavzu nomi</b>
          </th>
          <th>Fan nomi</th>
          <th>Test nomi</th>
          <th>ko'rish / o'zgartirish / o'chirish</th>
        </tr>
      </thead>
      <tbody>
      @foreach($topics as $item)  
      <?php
      ?>
        <tr>
          <td>{{ $item->topicname }}</td>
          <td>{{ $item->subj_name }}</td>
          <td>{{ $item->task_name }}</td>
          <td style="text-align: end;"><button type="button" class="btn btn-success"><i class="bi bi-eye"></i></button> <button type="button" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>  <button type="button" class="btn btn-danger"><i class="bi bi-trash"></i></button></td>
        </tr>
      @endforeach
        
      </tbody>
    </table>
    <!-- End Table with stripped rows -->

    </div>
    </div>
  </div>
</section>

</main><!-- End #main -->


@endsection

@section('footer')
    @include('teacher.footer'); 
@endsection
