@if($districts)
    <div class="form-group mb-0">
        <select class="form-control district" name="district">
       <option value="" selected> Select District</option>
        @foreach($districts as $k=>$dist)
            <option value="{{ $dist->id ?? '' }}"> {{$dist->district_name ?? ''}} </option>
        @endforeach
        </select>
    </div>
@endif