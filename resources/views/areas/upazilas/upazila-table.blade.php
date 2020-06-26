<table class="table table-striped" id="table_div">
          <thead class="thead-dark">
            <tr>
                <th>Select</th>
                <th>Upazila Id</th>
                <th>Division</th>
                <th>District</th>
                <th>Upazila Name</th>
                <th>Action</th>
            </tr>
         </thead>
            <tbody>

                    <tr id="add_row">
                        <td></td>
                        <td></td>
                        <td class="division-td">
                             @if($divisions)
                                    <div class="form-group">
                                     
                                        <select class="form-control division" name="division">
                                        <option value=""> Select Division </option>
                                        @foreach($divisions as $k=>$div)
                                            <option value="{{ $div->id ?? '' }}"> {{$div->division_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                              @endif
                        </td>
                        <td class="district-td">

                               
                          
                        
                        </td>
                      
                        <td contenteditable  id="upazila_name"> </td>
                        <td> 
                            <button class="btn btn-primary btn-sm" id="add"> Add</button>
                        </td>
                    </tr>
                @if($data)
                 @foreach($data as $k=>$d)
                    <tr>
                        <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="select_checkbox"
                                     data-check_row="{{$d}}" name="checked_row" class="form-check-input"
                                     value="{{$d->id ?? ''}}">
                                </label>
                            </div>
                        </td>
                        <td>{{$d->id ?? ''}}</td>
                        <td class="select_division column_name division-td"  data-column_name="division_id" 
                             data-id="{{$d->id ?? ''}}" >
                             @if($divisions)
                                    <div class="form-group mb-0">
                                        <select class="form-control division" name="division">
                                        @foreach($divisions as $k=>$div)
                                            <option value="{{ $div->id ?? '' }}" {{$div->id==$d->district->division_id ? 'selected' :''}}> {{$div->division_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                              @endif
                            
                        </td>
                        <td class="select_district column_name district-td"  data-column_name="district_id" 
                             data-id="{{$d->id ?? ''}}">
                             @if($districts)
                                    <div class="form-group mb-0">
                                        <select class="form-control district" name="district">
                                        @foreach($districts->where('division_id','=', $d->district->division_id) as $k=>$dist)
                                            <option value="{{ $dist->id ?? '' }}" {{$dist->id==$d->district_id ? 'selected' :''}}> {{$dist->district_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                              @endif
                              
                        </td>
                       
                        <td contenteditable class="column_name" data-column_name="upazila_name" data-id="{{$d->id ?? ''}}">{{$d->upazila_name ?? ''}}</td>
                        <td> 
                            <button class="btn btn-danger btn-sm delete" data-id="{{$d->id ?? ''}}"> Delete</button>

                        </td>
                    </tr>
                 @endforeach
                @endif
              
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
           {{ $data->render() }}
           </div>