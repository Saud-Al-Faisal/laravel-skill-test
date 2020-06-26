@if($upazilas)
    <div class="form-group mb-0">
        <select class="form-control upazila" name="upazila">
       <option value="" selected> Select Upazila</option>
        @foreach($upazilas as $k=>$upa)
            <option value="{{ $upa->id ?? '' }}"> {{$upa->upazila_name ?? ''}} </option>
        @endforeach
        </select>
    </div>
@endif