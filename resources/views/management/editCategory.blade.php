@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="list-group">
          <a class="list-group-item list-group-item-action" href="/management/category"><i class="fas fa-align-justify"></i> Category</a>
          <a class="list-group-item list-group-item-action" href=""><i class="fas fa-hamburger"></i> Menu</a>
          <a class="list-group-item list-group-item-action" href=""><i class="fas fa-chair"></i> Table</a>
          <a class="list-group-item list-group-item-action" href=""><i class="fas fa-users-cog"></i> User</a>

        </div>
      </div>
      <div class="col-md-8"><i class="fas fa-align-justify"></i> Edit a Category  
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
      <form action="/management/category/{{ $category->id }}" method="POST">
        @method('PUT')
        @csrf
        <div class="form-group">
          <label for="categoryName">Category Name</label>
          <input value="{{ $category->name }}" type="text" name="name" id="categoryName" class="form-control @error('name') @enderror" placeholder="yummy...">
          @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary float-right">Update</button>
        <a href="/management/category" type="button" class="btn btn-secondary">Cancel</a>
      </form>
  
      </div>
    </div>
  </div>
@endsection