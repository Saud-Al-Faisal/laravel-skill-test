@extends('layouts.master')

@section('content')

<div class="container">
  <div class="card">
    <div class="card-header ">
       <div class="d-flex align-items-center justify-content-between">
           <span> All User</span>
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
        <div class="users">
            @include('users.users.area-user-table')
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

        $(document).on('change', 'select.district', function(e){
            e.preventDefault()
           var dist_id =  $(this).children("option:selected").val();
           var thisz = $(this).parent().parent('td.district-td')
           var designation_name =$(this).closest('tr').find('td.designation-td').children().find('select.designation').children("option:selected").text();
           var designation_id =$(this).closest('tr').find('td.designation-td').children().find('select.designation').children("option:selected").val();
            if(designation_id==''){
                alert('Please selest designation first')
                return false
            }
           if( designation_name.trim()=="District Head" || designation_name.trim()=="Division Head"){
                return false
           }
           if(dist_id != ''){
            $.ajax({
                url:"{{route('upazila.by.district')}}",
                method:"get",
                data:{district_id:dist_id},
                success:function(data)
                {
                    thisz.next('.upazila-td').html(data);
                },
                error:function(errors)
                {
                    console.log(errors)
                }
            });
           }
        });

        $(document).on('change', 'select.upazila', function(e){
            e.preventDefault()
           var upa_id =  $(this).children("option:selected").val();
           var thisz = $(this).parent().parent('td.upazila-td')
           var designation_name =$(this).closest('tr').find('td.designation-td').children().find('select.designation').children("option:selected").text();
           var designation_id =$(this).closest('tr').find('td.designation-td').children().find('select.designation').children("option:selected").val();
      
            if(designation_id==''){
                alert('Please selest designation first')
                return false
            }
           if( designation_name.trim()!="Union Head"){
                return false
           }
           if(upa_id != ''){
            $.ajax({
                url:"{{route('union.by.upazila')}}",
                method:"get",
                data:{upazila_id:upa_id},
                success:function(data)
                {
                    
                    thisz.next('.union-td').html(data);
                },
                error:function(errors)
                {
                    console.log(errors)
                }
            });
           }

        });
        $(document).on('change', 'select.designation', function(e){
            e.preventDefault()
           var designation_id =  $(this).children("option:selected").val();
           var designation_name =  $(this).children("option:selected").text();
           var thisz = $(this).parent().parent('td.upazila-td')
           if(designation_name == 'Divisional Head'){
                
           }

        });
    $(document).on('click', '#add', function(e){
        e.preventDefault()
        var user_name = $('#user_name').text().trim();
        var user_email = $('#user_email').text().trim();
        var upazilaTag=$(this).closest('tr').find('td.upazila-td').children().find('select.upazila')
        var divisionTag=$(this).closest('tr').find('td.division-td').children().find('select.division')
        var districtTag=$(this).closest('tr').find('td.district-td').children().find('select.district')
        var unionTag=$(this).closest('tr').find('td.union-td').children().find('select.union')
        var DesignationTag=$(this).closest('tr').find('td.designation-td').children().find('select.designation')
        var upazila = upazilaTag.children("option:selected").val();
        var division = divisionTag.children("option:selected").val();
        var district = districtTag.children("option:selected").val();
        var union = unionTag.children("option:selected").val();
        var designation = DesignationTag.children("option:selected").val();
        var designation_name = DesignationTag.children("option:selected").text();
        var password_confirmation = $('#password_confirmation').text().trim()
        var password = $('#password').text().trim()
        if(user_name != '' && user_email!='')
        {
        $.ajax({
            url:"{{route('user.insert')}}",
            method:"POST",
            data:{
                user_name:user_name,
                user_email:user_email,
                division:division,
                district:district,
                union:union,
                designation:designation,
                upazila:upazila,
                password:password,
                password_confirmation:password_confirmation,
                designation_name:designation_name,
              _token:_CSRF_TOKEN},
            success:function(data)
            {
                if(data[0]==1){
                    $('#union_name').text('')
                    $(upazilaTag).prop('selectedIndex',0);
                    show_success_msg(data[1])
                    var page = $('#hidden_page_no').val();
                    getData(page,filter_query='');
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
        if(user_email=='')
        $('#message').html("<div class='alert alert-danger'>User Email Field is required</div>");
            if(user_name=='')
        $('#message').html("<div class='alert alert-danger'>User Name Field is required</div>");
        }
        });
   
   
   
   
    $(document).on('click', '.update_data', function(e){
        e.preventDefault();

        var id = $(this).data('id');
        
        var designation = $('tr#user_tr_'+id).find('td.designation-td select.designation').children("option:selected").val();
        var designation_name = $('tr#user_tr_'+id).find('td.designation-td select.designation').children("option:selected").text();

        var division = $('tr#user_tr_'+id).find('td.division-td select.division').children("option:selected").val();
        var district = $('tr#user_tr_'+id).find('td.district-td select.district').children("option:selected").val();
        var upazila = $('tr#user_tr_'+id).find('td.upazila-td select.upazila').children("option:selected").val();
        var union = $('tr#user_tr_'+id).find('td.union-td select.union').children("option:selected").val();

        $.ajax({
            url:"{{route('user.update.info')}}",
            method:"put",
            data:{
                division:division,
                district:district,
                union:union,
                designation_name:designation_name,
                designation:designation,
                upazila:upazila,
                id:id,
              _token:_CSRF_TOKEN
              },
          
            success:function(data)
            {
                show_success_msg(data)
            },
            error: function(errors)
             {
                error_handle(errors.responseJSON.errors)
            }
        });
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
            if(filter_query!=''){
                url+=`&filterby=${filter_query}`
            }
            $.ajax({
                    type: 'get',
                    url: url,
                    success:function(response)
                    {
                        $('.users').html(response);
                            location.hash = page;
                    },
                    error: function(errors)
                    {
                        alert('upazila Not Found.');
                    }
                });
        }



        $(document).on('blur', '.column_name', function(e){
            e.preventDefault()
            var column_value =null
            column_name = $(this).data("column_name").trim();
            if($(this).children().find('select.division').length > 0){
                $('#message').html("<div class='alert alert-danger'>Please Select District</div>");
             return false
            }
            else if($(this).children().find('select.district').length > 0){
                $('#message').html("<div class='alert alert-danger'>Please Select Upazila</div>");
             return false
            }
           else{
                if($(this).children().find('select.upazila').length > 0){
                column_value = $(this).children().find('select.upazila').children("option:selected").val()

                }
                else{
                    column_value = $(this).text().trim();
                }
            }

            var id = $(this).data("id");
            if(column_value != '')
            {
                callAjaxToUpdate(column_name,column_value,id)
            }
            else
            {
            $('#message').html("<div class='alert alert-danger'>Please enter some value</div>");
            }
            });

      function callAjaxToUpdate(column_name,column_value,id){
            $.ajax({
                url:"{{ route('user.update') }}",
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
            url:"{{ route('user.delete') }}",
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
