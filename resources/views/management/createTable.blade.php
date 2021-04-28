@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <div class="col-md-8"><i class="fas fa-chair"></i> Create a Table
      <hr>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
          </ul>
        </div>
      @endif    
      <form action="/management/table" method="POST">
        @csrf
        <div class="form-group">
          <label for="tableName">Table Name</label>
          <input type="text" name="name" id="tableName" class="form-control @error('name') @enderror" placeholder="table...">
          @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
          @enderror
        </div>
        <a href="/management/table" class="btn btn-secondary float-right">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>
  
      </div>
    </div>
  </div>
@endsection