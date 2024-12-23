@extends('layouts.app')

@section('title','Category Management')

@push('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
@endpush
@section('content')
    <div class="row">

        <div class="col-md-4">
            <div class="card">
              <h5 class="card-header" id="update_title">Add Transaction</h5>
              <div class="card-body">
                <form action="{{route('transactions.store')}}" method="POST" id="form_submit" enctype="multipart/form-data">
                  @csrf
                <div class="mb-4">
                    <label for="exampleFormControlSelect1" class="form-label">Select Categories</label>
                    <select name="category_id" class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                      <option selected>Select Category</option>
                      @foreach ($categories as $category )

                      <option value="{{$category->id}}">{{$category->title}}</option>
                      @endforeach

                    </select>
                  </div>

                  <div class="mb-4">
                    <label for="title" class="form-label">Amount</label>
                    <input type="text" name="amount" class="form-control" id="title" placeholder="Enter Amount">
                  </div>
                  <div class="mb-4">
                    <label for="exampleFormControlSelect2" class="form-label">Select Type</label>
                    <div class="col-md">
                        <small class="text-light fw-medium d-block">(Income or Expense)</small>
                        <div class="form-check form-check-inline mt-3">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="type"
                            value="income"
                            id="inlineRadio1"
                            value="option1" checked />
                          <label class="form-check-label" for="inlineRadio1">Income</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="type"
                            value="expense"
                            id="inlineRadio2"
                            value="option2" />
                          <label class="form-check-label" for="inlineRadio2">Expense</label>
                        </div>

                      </div>
                  </div>
                  <small class="text-light fw-medium d-block">(Invoice Image)</small>
                  <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                    <img
                      src="../assets/img/elements/12.jpg"
                      alt="user-avatar"
                      class="d-block w-px-100 h-px-100 rounded"
                      id="uploadedAvatar" />
                    <div class="button-wrapper">
                      <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                        <span class="d-none d-sm-block">Select Image</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                        <input
                          type="file"
                          name="image"
                          id="upload"
                          class="account-file-input"
                          hidden
                          accept="image/png, image/jpeg" />
                      </label>
                      <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                        <i class="bx bx-reset d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Reset</span>
                      </button>

                      <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                    </div>
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
<script src="../assets/js/pages-account-settings-account.js"></script>
<script>

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
            datatable.ajax.reload();
        }
    });
});

$('#form_submit').on('submit',function(e){
    e.preventDefault();
    var form = $(this);

    $.ajax({
        url:$(this).attr('action'),
        method:$(this).attr('method'),
        data:new FormData(this),
        processData: false,
        contentType: false,
        success:function(e){
            form.trigger('reset');
            datatable.ajax.reload();
        },
        error:function(e){
            if(e.responseJSON.errors){
                console.log(e.responseJSON.errors)

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
