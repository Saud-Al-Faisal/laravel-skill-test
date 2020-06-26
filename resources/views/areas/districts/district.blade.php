@extends('layouts.master')

@section('content')

<div class="container">
  <div class="card">
    <div class="card-header ">
       <div class="d-flex align-items-center justify-content-between">
           <span> All Districts</span>
       </div>
    </div>
     <div class="card-body">
         <div class="input-group mb-3">
         <input type="text" class="form-control mr-3" id="pageLimit" placeholder="Page Limit" style="max-width:110px;">
             <input type="text" class="form-control" id="searchField" placeholder="Type Here To Search">
             <div class="input-group-append">
                 <button class="btn btn-secondary" id="searchBtn" type="button"> Search </button>
                </div>
        </div>
        <div id="message">
        </div>
        <div class="districts">
            @include('areas.districts.district-table')
        </div>
        <input type="hidden" name="hidden_page_no" id="hidden_page_no" value="1" />
    </div>
  </div>
</div>
@endsection
@section('script-content')
<script>
    $(document).ready(function(){
        var _CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   
    $(document).on('click', '#add', function(){
        var district_name = $('#district_name').text().trim();
        var division = $('#division').children("option:selected").val().trim();
       
        if(district_name != '' && division!='')
        {
        $.ajax({
            url:"{{route('district.insert')}}",
            method:"POST",
            data:{district_name:district_name, division:division, _token:_CSRF_TOKEN},
            success:function(data)
            {
                if(data[0]==1){
                    $('#district_name').text('')
                    $('#division').prop('selectedIndex',0);
                    show_success_msg(data[1])
                    var page = $('#hidden_page_no').val();
                     getData(page,filter_query='');
               
                //         html =`<tr>
                //             <td>
                //                 <div class="form-check">
                //                     <label class="form-check-label">
                //                         <input type="checkbox" class="select_checkbox" data-check_row="${data[2].id}" name="checked_row" class="form-check-input" value="${data[2].id}">
                //                     </label>
                //                 </div>

                //             </td>
                //             <td><div class="form-group mb-0">
                //                                       <select class="form-control" class="division" name="division">`;

                //             var divisions = @json($divisions);

                //                      if(divisions.length>0){
                //                             for( var i = 0; i < divisions.length; i++) {
                //                             if(data[2].division_id == divisions[i].id){
                //                                 html+=`<option selected value="${divisions[i].id}"> ${divisions[i].division_name} </option>`;
                //                             }else{
                //                                  html+=`<option value="${divisions[i].id}"> ${divisions[i].division_name} </option>`;
                //                                 }
                //                             }   
                //                      }

                //              html+=` </select>
                //                                         </div>
                //                                     </div>     </td>
                //             <td>${data[2].id}</td>
                //             <td contenteditable class="column_name" data-column_name="district_name" data-id="${data[2].id}">${data[2].district_name}</td>
                //             <td> 
                //                 <button class="btn btn-danger btn-sm delete" data-id="${data[2].id}"> Delete</button>

                //             </td>
                //         </tr>`;
                
                // $('#add_row').after(html)
                }
            },
            error: function(errors)
             {
                error_handle(errors.responseJSON.errors)
            }
        });
        }
        else
        {
            if(division=='')
        $('#message').html("<div class='alert alert-danger'>Division   Field is required</div>");
            if(district_name=='')
        $('#message').html("<div class='alert alert-danger'>District Name Field is required</div>");
        }
        });

        $('#searchBtn').on('click',function(event) {
            event.preventDefault();
            var filter_query = $('#searchField').val();
            if(filter_query==''){
                alert('Please type for searching');
                return false
            }
            var page = $('#hidden_page_no').val();
            getData(page,filter_query);
        });
        $(document).on('click', '.pagination a',function(event) {
            event.preventDefault();
          
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
          
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
            var filter_query = $('#searchField').val();
            $('#hidden_page_no').val(page)
            getData(page,filter_query);
        });
        function getData(page,filter_query) {
            var pageLimit= $('#pageLimit').val()
            var url = `?page=${page}&limit=${pageLimit}`
            var url = `?page=${page}`
            if(filter_query!=''){
                url+=`&filterby=${filter_query}`
            }
            $.ajax({
                    type: 'get',
                    url: url,
                    success:function(response)
                    {
                        $('.districts').html(response);
                            location.hash = page;
                    },
                    error: function(errors)
                    {
                        alert('District Not Found.');
                    }
                });
        }


        // $(document).on('click', '.select_division', function(e){
        //     e.preventDefault()
        //     var divisions = @json($divisions);
    
        //    var div_id = $(this).data('id')    
        //     html='';

        //          if(divisions.length>0){
        //                 html+=`<div class="form-group mb-0">
        //                           <select class="form-control" class="division" name="division">`;
        //                            for( var i = 0; i < divisions.length; i++) {
        //                             if(div_id===divisions[i].id){
        //                                 html+=`<option selected value="${divisions[i].id}"> ${divisions[i].division_name} </option>`;
        //                             }else{
        //                                 html+=`<option value="${divisions[i].id}"> ${divisions[i].division_name} </option>`;
        //                             }
        //                             }   
        //                         html+=`</select>
        //                             </div>
        //                         </div>`;
        //              $(this).html(html)
        //          }
            
        // })
        // $(document).on('change', '.division', function(e){
        //     e.preventDefault()
        //    var column_value =  $(this).children("option:selected").val();
       
        //    var column_name = $(this).parent().parent('td').data('column_name')
        //    var id =$(this).parent().parent('td').data('id')
        // });

        $(document).on('blur', '.column_name', function(e){
            e.preventDefault()
            var column_value =null
            column_name = $(this).data("column_name").trim();

          

            if($(this).children().find('select').length > 0){
             column_value = $(this).children().find('select').children("option:selected").val()
            }else{
                column_value = $(this).text().trim();
            }

             
            var id = $(this).data("id");
            if(column_value != '')
            {
                callAjaxToUpdate(column_name,column_value,id)
            }
            else
            {
            $('#message').html("<div class='alert alert-danger'>Enter some value</div>");
            }
            });
      
      function callAjaxToUpdate(column_name,column_value,id){
            $.ajax({
                url:"{{ route('district.update') }}",
                method:"PUT",
                data:{column_name:column_name, column_value:column_value, id:id, _token: _CSRF_TOKEN},
                success:function(data)
                {
                show_success_msg(data)
                },
                error:function(errors){
                    error_handle(errors.responseJSON.errors)
                }
            })
        }
      
        $(document).on('click', '.delete', function(){
        var id = $(this).data("id");
        var tr = $(this).parent().parent('tr')
        if(confirm("Are you sure you want to delete this records?"))
        {
        $.ajax({
            url:"{{ route('district.delete') }}",
            method:"DELETE",
            data:{id:id, _token:_CSRF_TOKEN},
            success:function(data)
            {
            show_success_msg(data)
            $(tr).remove();
            },
            error:function(errors){
                error_handle(errors.responseJSON.errors)
            }
        });
        }
        });


        $(document).on("change","input.select_checkbox", function(e) {
            e.preventDefault();
            var on_state_row = $(this).data('check_row');
            var checked_items_key = 'checked-district-rows';
            var stored_items = getLocalStorageItems(checked_items_key)
             if(!stored_items){
                stored_items=[];
             }

            if(this.checked) {
                    stored_items.push($(this).data('check_row'));
                    setLocalStorageItems (checked_items_key,stored_items)
            }else{

                for( var i = 0; i < stored_items.length; i++) { 
                    if ( stored_items[i]['id'] === on_state_row['id']) {
                            stored_items.splice(i, 1);
                          }
                }
                setLocalStorageItems (checked_items_key,stored_items)
            }
            if(stored_items.length==0){
                localStorage.removeItem(checked_items_key)
            }
        });

        function getLocalStorageItems(key){
           return JSON.parse( localStorage.getItem(key));
        }
        function setLocalStorageItems(key,stored_items){
            localStorage.setItem(key,JSON.stringify(stored_items))
        }

        function error_handle(errors){
            var errs = Object.values(errors);
            var msg= "";
            $.each(errs,function(i1,v1){
                $.each(v1,function(i,v){
                msg +=`  ${v}  <br>`;
            })
            })
            $('#message').html(`
                     <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Oops! ${msg} </strong> 
                    </div>
                `);
        }
        function show_success_msg(msg){
            $('#message').html(`
                     <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success! ${msg} </strong> 
                    </div>
                `);
        }
 
    });
</script>
@endsection
