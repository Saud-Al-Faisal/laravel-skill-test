@if($unions)
    <div class="form-group mb-0">
        <select class="form-control union" name="union">
       <option value="" selected> Select Union</option>
        @foreach($unions as $k=>$uni)
            <option value="{{ $uni->id ?? '' }}"> {{$uni->union_name ?? ''}} </option>
        @endforeach
        </select>
    </div>
@endif