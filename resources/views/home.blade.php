@extends('layouts.master')

@section('content')
  <div class="card">
    <div class="card-header">Header</div>
     <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
           {{ session('status') }}
        </div>
         @endif
        You are logged in!
    </div> 
  </div>
@endsection
@section('script-content')
<script>
    $(document).ready(function(){

    });
</script>
@endsection
