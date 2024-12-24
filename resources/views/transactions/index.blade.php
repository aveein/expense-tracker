@extends('layouts.app')

@section('title','Transaction Management')

@push('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                    <label for="category_id" class="form-label">Select Categories</label>
                    <select name="category_id" class="form-select" id="category_id" aria-label="Default select example">
                      <option value="" selected>Select Category</option>
                      @foreach ($categories as $category )

                      <option value="{{$category->id}}">{{$category->title}}</option>
                      @endforeach

                    </select>
                  </div>

                  <div class="mb-4">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="text" name="amount" class="form-control" id="amount" placeholder="Enter Amount">
                  </div>
                  <div class="mb-4">
                    <label for="type" class="form-label">Select Type</label>
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
                            id="type"
                            value="option2" />
                          <label class="form-check-label" for="type">Expense</label>
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



        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Transaction Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="modal-body" class="text-center">
                ...
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="margin-right: 5px !important" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-8">

            <div class="card">
                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="card-header">Transaction List</h5>
                    <div class="card-header">

                        <button onclick="window.location.href='{{route('transactions.index')}}'" class="btn btn-primary btn-sm">Add Transaction</button>
                    </div>
                </div>


                    <div class="card-header">
                        <form action="{{route('transactions.date-wise')}}" method="GET" id="date_range">
                            <div class="col-md-12 d-flex align-items-center">
                                <div class="col-2 mt-3">
                                    <p class="fw-bold">Date Range (Bar)</p>
                                </div>

                                <div class="col-3 p-2">
                                    <select name="type" class="form-select" id="category_filter_id">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category )
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-4">

                                    <input type="text" name="dates" class="form-control" id="date">
                                </div>

                                <div class="col-2 p-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>

                            </div>
                        </form>


                    </div>



                <div class="table-responsive text-nowrap">
                    <div class="card-body p-4">
                        <table id="example" class="datatables-ajax display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Amount</th>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="../assets/js/pages-account-settings-account.js"></script>


<script>
    $('input[name="dates"]').daterangepicker();
</script>

<script>


   var filter = false;


   var datatable = new DataTable('#example', {
        ajax: {
        url: '{{route("transactions.data")}}',
        type: 'POST',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    },
    columns: [
        { data: 'id' },
        { data: 'image',name:'id' },
        { data: 'category_id' },
        { data: 'type' },
        { data: 'amount' },

        { data: 'created_at' },
        { data: 'action',name:'id' }
    ],
    processing: true,
    serverSide: true,
    search: {
        "regex": false
      }
});

$('#date_range').on('submit',function(e){
        e.preventDefault();
        datatable.destroy()
        datatable = new DataTable('#example', {
                ajax: {
                url: '{{route("transactions.data")}}',
                data:{
                    date:$('#date').val(),
                    category_id:$('#category_filter_id').val(),
                    filter:filter
                },
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            },
            columns: [
                { data: 'id' },
                { data: 'image',name:'id' },
                { data: 'category_id' },
                { data: 'type' },
                { data: 'amount' },
                { data: 'created_at' },
                { data: 'action',name:'id' }
            ],
            processing: true,
            serverSide: true,
            search: {
                "regex": false
            }
        });


    })


$(document).on('click','.view',function(){
    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
    keyboard: false
    })
    myModal.show()
    var url = "{{route('transactions.edit',':id')}}"
    url =url.replace(':id',$(this).data('id'))
    $.ajax({
        url: url,
        success:function(data){
            $('#modal-body').html('');

            if(data.image){
                let image = `{{ asset('storage/${data.image}')}}`
                $('#modal-body').append(`<img src="${image}" width="100%">`)
            }
            $('#modal-body').append(`<p class="ml-3 fw-bold"> Amount: ${data.amount} </p>`)
            $('#modal-body').append(`<p class="ml-3 fw-bold"> Type: ${data.type} </p>`)


        }
    });

})
$(document).on('click','.edit',function(){
    $('#update_title').text('Update Transaction')
    var url = "{{route('transactions.edit',':id')}}"

    url =url.replace(':id',$(this).data('id'))

    let update_url = "{{route('transactions.update',':id')}}"
    update_url =update_url.replace(':id',$(this).data('id'))

    $('#form_submit').attr('action',update_url)
    $('#form_submit').attr('method','post')
    $('#form_submit').append('@method("put")')

    $.ajax({
        url: url,
        success:function(data){

           $('#amount').val(data.amount);
           data.type === 'income' ? $('#inlineRadio1').attr('checked',true) : $('#type').attr('checked',true);

           $(`#category_id option[value="${data.category_id}"]`).prop('selected',"selected").trigger('change');

           data.image ? $('#uploadedAvatar').attr('src',`{{ asset('storage/${data.image}') }}`)  :  $('#uploadedAvatar').attr('src','./assets/img/elements/12.jpg')
        }
    });
});

$(document).on('click','.delete',function(){
    var url = "{{route('transactions.destroy',':id')}}"

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
    e.preventDefault();
    var form = $(this);
    $('.invalid-feedback').text('')
    var formData = new FormData(this);


    $.ajax({
        url:$(this).attr('action'),
        method:$(this).attr('method'),
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:formData,
        cache: false,
        contentType: false,
        processData: false,
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
                    ele.closest('.mb-4').append(`<div class="invalid-feedback">${value}</span>`)
                    console.log( index + ": " + value );
                });
            }
        }
    });
})

</script>
@endpush
