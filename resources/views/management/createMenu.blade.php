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
      <div class="col-md-8"><i class="fas fa-hamburger"></i> Create a Menu  
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
      <form action="/management/menu" method="POST">
        @csrf
        <div class="form-group">
          <label for="menuName">Menu Name</label>
          <input type="text" name="name" id="menuName" class="form-control" placeholder="name...">        
        </div>

        <label for="menuPrice">Price</label>
        <div class="input-group mb-3"><!-- forms a line with $ as prepend and .00 as append -->
          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
          <input type="text" name="price" class="form-control" aria-label="Amount (to the nearest dollar)">
          <div class="input-group-append"><span class="input-group-text">.00</span></div>
        </div>

        <label for="menuImage">Image</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend"><span class="input-group-text">Upload</span></div>
          <div class="custom-file">
            <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">
            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>
          </div>
        </div> 
        
        <div class="form-group">
          <label for="description">Description</label>
          <input type="text" name="description" class="form-control" placeholder="description...">
        </div>

        <div class="form-group">
          <label for="category">Category</label>
          <select class="form-control" name="category_id" id="">
            
          </select>
        </div>



        <a href="/management/menu" class="btn btn-secondary float-right">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>
  
      </div>
    </div>
  </div>
@endsection