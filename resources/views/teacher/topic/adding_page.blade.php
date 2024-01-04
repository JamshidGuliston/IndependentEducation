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
                <h5 class="card-title">Mavzu yaratish</h5>
            <form class="row g-3" method="POST" action="create_topic">
            @csrf
                <div class="col-12">
                    <div class="card-body">
                        <label for="selectsubject" class="form-label">Fan</label>
                        <select id="selectsubject" name="subject" class="form-select" onchange="changeFunc();" required>
                            <option selected="">-Tanlash-</option>
                            @foreach($subjects as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-body">
                        <label for="inputNanme4" class="form-label">Mavzu nomi:</label>
                        <input type="text" class="form-control" id="inputNanme4" name="title" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-body">
                        <h5>Mavzu matni</h5>
                        <textarea class="tinymce-editor" name="fulltext" required>
                        </textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-body">
                        <label for="inputAddress" class="form-label">Topshiriq</label>
                        <div class="taskoptions">
                    </div>  
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script>
        function changeFunc() {
            var selectBox = document.getElementById("selectsubject");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            
            var div = $('.taskoptions');
            var route = "{{ route('teacher.select_tests') }}";
            $.ajax({
                method: "GET",
                url: route,
                data: {
                    'subject_id': selectedValue,
                },
                success: function(data) {
                    div.html(data);
                }
            });
        }
    </script>
@endsection