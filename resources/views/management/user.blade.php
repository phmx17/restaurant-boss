@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <!-- create category button -->
      <div class="col-md-8"><i class="fas fa-user"></i> User
      <a class="btn btn-success btn-sm float-right" href="/management/user/create"><i class="fas fa-plus"></i> Create User</a>
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
            <th scope="col">Name</th>
            <th scope="col">Role</th>
            <th scope="col">Email</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody> 
          @foreach($users as $user)
            <tr>
              <td>{{$user->id}}</td>
              <td>{{$user->name}}</td>
              <td>{{$user->role}}</td>
              <td>{{$user->email}}</td>
              <td><a href="/management/user/{{$user->id}}/edit" class="btn btn-warning">Edit</a></td>
              <td><!-- delete action -->
                <form action="/management/user/{{$user->id}}" method="POST">
                  @csrf
                  @method('DELETE')                  
                  <input type="submit" value="Delete" class="btn btn-danger">
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      </div>
    </div>
  </div>
@endsection