@extends('layouts.app')

@section('title','Category Management')

@push('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
@endpush
@section('content')
    <div class="row">

        <div class="col-md-4">
            <div class="card">
              <h5 class="card-header" id="update_title">Add Category</h5>
              <div class="card-body">
                <form action="{{route('categories.store')}}" method="POST" id="form_submit">
                  @csrf
                <div class="mb-4">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title">
                </div>
                <div class="form-check form-switch mb-2">
                    <label class="form-check-label fw-4" for="flexSwitchCheckDefault"
                    >Status</label
                    >
                    <input class="form-check-input" name="status" type="checkbox" value="1" id="flexSwitchCheckDefault" checked />

                  </div>
                <div class="mt-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
              </div>
            </div>
        </div>


        <div class="col-md-8">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="card-header">Category List</h5>
                    <div class="card-header">

                        <button onclick="window.location.href='{{route('categories.index')}}'" class="btn btn-primary btn-sm">Add Category</button>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <div class="card-body p-4">
                        <table id="example" class="datatables-ajax display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created Date.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection
@push('js')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script>

  // Bootstrap toasts example
  // --------------------------------------------------------------------






   var datatable = new DataTable('#example', {
        ajax: {
        url: '{{route("categories.data")}}',
        type: 'POST',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    },
    columns: [
        { data: 'id' },
        { data: 'title' },
        { data: 'status' },
        { data: 'created_at' },
        { data: 'action',name:'title' }
    ],
    processing: true,
    serverSide: true,
    search: {
        "regex": false
      }
});

$(document).on('click','.edit',function(){
    $('#update_title').text('Update Category')
    var url = "{{route('categories.edit',':id')}}"

    url =url.replace(':id',$(this).data('id'))

    let update_url = "{{route('categories.update',':id')}}"
    update_url =update_url.replace(':id',$(this).data('id'))

    $('#form_submit').attr('action',update_url)
    $('#form_submit').attr('method','put')

    $.ajax({
        url: url,
        success:function(data){

            $('#title').val(data.title)
            data.status ? $('#flexSwitchCheckDefault').attr('checked', 'checked') : $('#flexSwitchCheckDefault').removeAttr('checked') ;
        }
    });
});

$(document).on('click','.delete',function(){
    var url = "{{route('categories.destroy',':id')}}"

    url =url.replace(':id',$(this).data('id'))

    $.ajax({

        url: url,
        method:'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(data){
            success()
            datatable.ajax.reload();
        }
    });
});

$('#form_submit').on('submit',function(e){
    $('.invalid-feedback').text('')
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url:$(this).attr('action'),
        method:$(this).attr('method'),
        data:$( this ).serialize(),
        success:function(e){
            if(e.reset){

            form.trigger('reset');
            }
            success()
            datatable.ajax.reload();
        },
        error:function(e){
            if(e.responseJSON.errors){
                console.log(e.responseJSON.errors)
                error()
                $.each( e.responseJSON.errors, function( index, value ) {
                    var ele = $('#'+index);
                    ele.addClass('is-invalid')
                    ele.parent().append(`<div class="invalid-feedback">${value}</span>`)
                    console.log( index + ": " + value );
                });
            }
        }
    });
})

</script>
@endpush
