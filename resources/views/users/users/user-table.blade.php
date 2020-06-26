<table class="table table-striped" id="table_div">
          <thead class="thead-dark">
            <tr>
                <th>Select</th>
                <th> Name</th>
                <th>Email</th>
                <th>Password</th>
                <!-- <th>Password Confirm</th> -->

                <th>Designation</th>
                <th>Division</th>
                <th>District</th>
                <th>Upazila</th>
                <th>Union</th>
                <th>Action</th>
            </tr>
         </thead>
            <tbody>

                    <tr id="add_row">
                        <td></td>
                        <td contenteditable  id="user_name"> </td>
                        <td contenteditable  id="user_email"> </td>
                        <td contenteditable  id="password"> </td>
                        <!-- <td contenteditable  id="password_confirmation"> </td> -->
                        <td class="designation-td">
                                @if($designations)
                                    <div class="form-group mb-0">
                                        <select class="form-control designation" name="designation">
                                        <option value=""> Select Designation </option>
                                        @foreach($designations as $k=>$deg)
                                            <option value="{{ $deg->id ?? '' }}"> {{$deg->designation_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                 @endif


                        </td>
                        <td class="division-td">
                             @if($divisions)
                                    <div class="form-group mb-0">
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
                        <td class="upazila-td">

                        </td>
                      
                        <td class="union-td">


                        </td>
                        <td> 
                            <button class="btn btn-primary btn-sm" id="add"> Add</button>
                         
                        </td>
                    </tr>
                @if($data)
               
                 @foreach($data as $k=>$d)
               
                    <tr id="user_tr_{{$d->id}}">
                        <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="select_checkbox"
                                     data-check_row="{{$d}}" name="checked_row" class="form-check-input"
                                     value="{{$d->id ?? ''}}">
                                </label>
                            </div>
                        </td>
                        <td contenteditable class="column_name" data-column_name="name"  data-id="{{$d->id ?? ''}}"> {{$d->name ?? ''}} </td>
                        <td contenteditable class="column_name" data-column_name="email"  data-id="{{$d->id ?? ''}}"> {{$d->email ?? ''}} </td>
                        <td contenteditable class="column_name" data-column_name="password"  data-id="{{$d->id ?? ''}}"> </td>
                        <!-- <td  contenteditable data-column_name="password_confirmation"  data-id="{{$d->id ?? ''}}"></td> -->

                        <td class="select_designation designation-td" data-column_name="designation_id" 
                             data-id="{{$d->id ?? ''}}" >
                                @if($designations)
                                    <div class="form-group mb-0">
                                        <select class="form-control designation" name="designation">
                                        <option value=""> Select Designation </option>
                                        @foreach($designations as $k=>$deg)
                                            <option value="{{ $deg->id ?? '' }}" {{ $d->designation_id && $deg->id==$d->designation_id ? 'selected' :''}}> {{$deg->designation_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                 @endif


                        </td>


                        <td class="select_division division-td"  data-column_name="division_id" 
                             data-id="{{$d->id ?? ''}}" >
                             @if($divisions)
                                    <div class="form-group mb-0">
                                        <select class="form-control division" name="division">
                                        <option value=""> Select Division </option>
                                        @foreach($divisions as $k=>$div)
                                            <option value="{{ $div->id ?? '' }}" {{ $d->division_id && $div->id==$d->division_id ? 'selected' :''}}> {{$div->division_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                              @endif
                            
                        </td>
                        <td class="select_district district-td"  data-column_name="district_id" 
                             data-id="{{$d->id ?? ''}}">
                             @if($districts)
                             @if($d->district_id && $d->division_id)
                                    <div class="form-group mb-0">
                                        <select class="form-control district" name="district">
                                        <option value=""> Select District</option>
                                    
                                            @foreach($districts->where('division_id','=', $d->division_id) as $k=>$dist)
                                                <option value="{{ $dist->id ?? '' }}" {{$dist->id==$d->district_id ? 'selected' :''}}> {{$dist->district_name ?? ''}} </option>
                                            @endforeach
                                   
                                        </select>
                                    </div>
                                    @endif
                              @endif
                        </td>
                        <td class="select_upazila upazila-td"  data-column_name="upazila_id" 
                             data-id="{{$d->id ?? ''}}">
                             @if($upazilas)
                             @if($d->district_id && $d->division_id && $d->upazila_id)
                                    <div class="form-group mb-0">
                                        <select class="form-control upazila" name="upazila">
                                        <option value=""> Select Upazila </option>
                                        @foreach($upazilas->where('district_id','=', $d->district_id) as $k=>$upa)
                                            <option value="{{ $upa->id ?? '' }}" {{$upa->id==$d->upazila_id ? 'selected' :''}}> {{$upa->upazila_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                @endif
                              @endif
                              
                        </td>
                        <td class="select_union  union-td"  data-column_name="union_id" 
                             data-id="{{$d->id ?? ''}}">
                             @if($unions)
                             @if($d->district_id && $d->division_id && $d->upazila_id && $d->union_id)
                                    <div class="form-group mb-0">
                                        <select class="form-control union" name="union">
                                        <option value=""> Select Union </option>
                                        @foreach($unions->where('upazila_id','=', $d->upazila_id) as $k=>$uni)
                                            <option value="{{ $uni->id ?? '' }}" {{$uni->id==$d->union_id ? 'selected' :''}}> {{$uni->union_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                @endif
                              @endif

                        </td>
                        <td> 
                            <button class="btn btn-danger btn-sm delete" data-id="{{$d->id ?? ''}}"> Delete</button>
                            <button class="btn btn-info btn-sm update_data" data-id="{{$d->id ?? ''}}"> Update</button>

                        </td>
                    </tr>
                 @endforeach
                @endif
           
              
            </tbody>
        </table>
           
           <div class="d-flex justify-content-center">
           {{ $data->render() }}
           </div>