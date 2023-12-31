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
  <h1>Barcha testlar</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Bosh sahifa</a></li>
      <li class="breadcrumb-item active">Testlar</li>
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
        <h5 class="card-title">Testlar</h5>
      <thead>
        <tr>
          <th>
            <b>Nomi</b>
          </th>
          <th>Fan nomi</th>
          <th>Test turi</th>
          <th>Savollar soni</th>
          <th>O'zgartirish / o'chirish</th>
        </tr>
      </thead>
      <tbody>
      @foreach($tests as $item)  
      <?php
      ?>
        <tr>
          <td>{{ $item->test_name }}</td>
          <td>{{ $item->subj_name }}</td>
          <td>{{ $item->cat_name }}</td>
          <td>25</td>
          <td></td>
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
