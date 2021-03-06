<table class="table table-striped" id="table_div">
          <thead class="thead-dark">
            <tr>
                <th>Select</th>
                <th>Designation ID</th>
                <th>Designation Name</th>
                <th>Action</th>
            </tr>
         </thead>
            <tbody>

                    <tr id="add_row">
                        <td></td>
                        <td>
                          
                        </td>
                        <td contenteditable  id="designation_name"></td>
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
                                    <input type="checkbox" class="select_checkbox" data-check_row="{{$d}}" name="checked_row" class="form-check-input" value="{{$d->id ?? ''}}">
                                </label>
                            </div>
                        </td>
                        <td>{{$d->id ?? ''}}</td>
                        <td contenteditable class="column_name" data-column_name="designation_name" data-id="{{$d->id ?? ''}}">{{$d->designation_name ?? ''}}</td>
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