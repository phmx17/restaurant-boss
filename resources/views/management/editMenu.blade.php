@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <div class="col-md-8"><i class="fas fa-hamburger"></i> Edit a Menu  
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
      <form action="/management/menu/{{$menu->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="menuName">Menu Name</label>
          <input type="text" name="name" id="menuName" class="form-control" value="{{old('name', $menu->name)}}">        
        </div>

        <label for="menuPrice">Price</label>
        <div class="input-group mb-3"><!-- forms a line with $ as prepend and .00 as append -->
          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
          <input type="text" name="price" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{$menu->price}}">          
        </div>

        <label for="menuImage">Image</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend"><span class="input-group-text">Upload</span></div>
          <div class="custom-file">
            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>
            <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">
          </div>
        </div> 
        
        <div class="form-group">
          <label for="description">Description</label>
          <input type="text" name="description" class="form-control" value="{{old('description', $menu->description)}}">
        </div>

        <div class="form-group">
          <label for="category">Category</label>
          <select class="form-control" name="category_id" id="category">
            @foreach ($categories as $category)
              <option value="{{ $category->id}}" {{$menu->category_id === $category->id ? 'selected' : ''}} >{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <a href="/management/menu" class="btn btn-secondary float-right">Cancel</a>
        <button type="submit" class="btn btn-warning">Update</button>
      </form>
  
      </div>
    </div>
  </div>
@endsection