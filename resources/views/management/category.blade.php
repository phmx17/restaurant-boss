@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <!-- create category button -->
      <div class="col-md-8"><i class="fas fa-align-justify"></i> Category
      <a class="btn btn-success btn-sm float-right" href="/management/category/create"><i class="fas fa-plus"></i> Create</a>
      <hr>
      <!-- success message -->
      @if(Session()->has('status'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert"><i class="fas fa-check" style="color: green;" ></i></button>
          {{ Session()->get('status') }}
        </div>
      @endif
      <hr>
      <!-- show list of categories in table -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Category</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories->all() as $category)
          <tr>
            <th scope="row">{{ $category->id }}</th>
            <td scope="row">{{ $category->name }}</td>
            <td scope="row"><a href="/management/category/{{ $category->id }}/edit" class="btn btn-warning">Edit</a></td>
            <td scope="row">
              <!-- must make into a form -->
              <form action="/management/category/{{ $category->id }}" method="POST">
                @method('DELETE')
                @csrf                
                <button class="btn btn-danger">Delete</button>
              </form>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $categories->links()}}      
      </div>
    </div>
  </div>
@endsection