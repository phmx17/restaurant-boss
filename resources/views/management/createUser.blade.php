@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <div class="col-md-8"><i class="fas fa-user"></i> Create a User
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
      <form action="/management/user" method="POST">
        @csrf
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Name..." value="{{old('name')}}">        
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Email..." value="{{old('email')}}">        
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Password...">        
        </div>

        <div class="form-group">
          <label for="role">Role</label>
          <select name="role" id="role" class="form-control">
            <option value="cashier">Cashier</option>
            <option value="admin">Admin</option>
          </select>          
        </div>

        <!-- cancel and submit buttons -->
        <a href="/management/user" class="btn btn-secondary float-right">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>
  
      </div>
    </div>
  </div>
@endsection