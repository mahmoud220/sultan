@extends('layout.master')
@section('title')
    Expenses
@endsection
@section('breadcrumb')
@parent
<li class="active">Expenses</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
            <button onclick="addForm('{{ route('expenses.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Add</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-expense">
                    @csrf
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Nominal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
                </form>
            </div>
            <!-- ./box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
      <!-- /.row (main row) -->
      @includeIf('expenses.form')
@endsection
@push('scripts')
<script>
    let table;
    $(function () {
        table = $('.table').DataTable({
           // responsive: true,
            processing: true,
           // serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('expense.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'created_at'},
                {data: 'desc'},
                {data: 'nominal'},
                {data: 'Added', searchable: false, sortable: false},
            ]
        });
        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Faild! Some data is not correct');
                        return;
                    });
            }
        });
    });
    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Expense');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=desc]').focus();
    }
    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Expense');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=desc]').focus();
        $.get(url)
            .done((response) => {
                $('#modal-form [name=desc]').val(response.desc);
                $('#modal-form [name=nominal]').val(response.nominal);
            })
            .fail((errors) => {
                alert('Faild to show data!');
                return;
            });
    }
    function deleteData(url) {
        if (confirm('Are you sure to delete that?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Faild to delete data!');
                    return;
                });
        }
    }

</script>
@endpush
