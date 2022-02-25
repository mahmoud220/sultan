@extends('layout.master')
@section('title')
    Purchases
@endsection
@section('breadcrumb')
@parent
<li class="active">Purchases</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
            <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> New Transaction </button>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-supplier">
                    @csrf
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Price</th>
                        <th>Discount</th>
                        <th>Total Buy</th>
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
      @includeIf('purchase.supplier')
@endsection
@push('scripts')
<script>
    let table;
    $(function () {
        table = $('.table').DataTable({
            {{--responsive: true,--}}
            {{--processing: true,--}}
            {{--serverSide: true,--}}
            {{--autoWidth: false,--}}
            {{--ajax: {--}}
            {{--    url: '{{ route('supplier.data') }}',--}}
            {{--},--}}
            {{--columns: [--}}
            {{--    {data: 'DT_RowIndex', searchable: false, sortable: false},--}}
            {{--    {data: 'name'},--}}
            {{--    {data: 'address'},--}}
            {{--    {data: 'phone'},--}}
            {{--    {data: 'Added', searchable: false, sortable: false},--}}
            {{--]--}}
        });
    });
    function addForm() {
        $('#modal-supplier').modal('show');
    }
    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Supplier');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=name]').focus();
        $.get(url)
            .done((response) => {
                $('#modal-form [name=name]').val(response.name);
                $('#modal-form [name=phone]').val(response.phone);
                $('#modal-form [name=address]').val(response.address);
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
