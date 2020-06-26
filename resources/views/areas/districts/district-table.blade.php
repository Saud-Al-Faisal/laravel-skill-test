<table class="table table-striped" id="table_div">
          <thead class="thead-dark">
            <tr>
                <th>Select</th>
                <th>Division</th>
                <th>District ID</th>
                <th>District Name</th>
                <th>Action</th>
            </tr>
         </thead>
            <tbody>

                    <tr id="add_row">
                        <td></td>
                        <td>
                          <!-- Division -->
                             @if($divisions)
                                <div>
                                    <div class="form-group">
                                     
                                        <select class="form-control" id="division" name="division">
                                        <option value=""> Select Division </option>
                                        @foreach($divisions as $k=>$div)
                                            <option value="{{ $div->id ?? '' }}"> {{$div->division_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                              @endif
                        </td>
                        <td></td>
                        <td contenteditable  id="district_name"></td>
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
                        <td class="select_division column_name"  data-column_name="division_id" 
                             data-id="{{$d->id ?? ''}}">
                             @if($divisions)
                                    <div class="form-group mb-0">
                                        <select class="form-control division" name="division">
                                        @foreach($divisions as $k=>$div)
                                            <option value="{{ $div->id ?? '' }}" {{$div->id==$d->division_id ? 'selected' :''}}> {{$div->division_name ?? ''}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                              @endif
                              <!-- {{$d->division->division_name ?? ''}} -->
                        </td>
                        <td>{{$d->id ?? ''}}</td>
                        <td contenteditable class="column_name" data-column_name="district_name" data-id="{{$d->id ?? ''}}">{{$d->district_name ?? ''}}</td>
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