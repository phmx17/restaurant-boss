@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <!-- create category button -->
      <div class="col-md-8"><i class="fas fa-chair"></i> Table
      <a class="btn btn-success btn-sm float-right" href="/management/table/create"><i class="fas fa-plus"></i> Create</a>
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
            <th scope="col">Table</th>
            <th scope="col">Status</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          @foreach($tables as $table)
          <tr>
            <td>{{$table->id}}</td>
            <td>{{$table->name}}</td>
            <td>{{$table->status}}</td>
            <td><a href="/management/table/{{$table->id}}/edit" class="btn btn-warning">Edit</a></td>
            <td>
              <form action="/management/table/{{$table->id}}" method="POST">
                @csrf  
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger" />
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