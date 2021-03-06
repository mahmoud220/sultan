@extends('layout.master')
@section('title')
Products
@endsection
@section('breadcrumb')
@parent
<li class="active">Products</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
            <button onclick="addForm('{{ route('products.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Add</button>
            <button onclick="deleteSelected('{{ route('product.delete_selected') }}')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Delete</button>
            <button onclick="printBarcode('{{ route('product.print_barcode') }}')" class="btn btn-info btn-xs btn-flat"><i class="fa fa-barcode"></i> Barcode</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-product">
                    @csrf
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">
                           <input type="checkbox" name="select_all" id="select_all">
                        </th>
                        <th width="5%">No</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Purchas Price</th>
                        <th>Sell Price</th>
                        <th>Discount</th>
                        <th>Stock</th>
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
      @includeIf('products.form')
@endsection
@push('scripts')
<script>
    let table;
    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('product.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'code_product'},
                {data: 'product_name'},
                {data: 'category_name'},
                {data: 'brand'},
                {data: 'purchas_price'},
                {data: 'sell_price'},
                {data: 'discount'},
                {data: 'stock'},
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
        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });
    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Product');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=product_name]').focus();
    }
    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Product');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=product_name]').focus();
        $.get(url)
            .done((response) => {
                $('#modal-form [name=product_name]').val(response.product_name);
                $('#modal-form [name=id_category]').val(response.id_category);
                $('#modal-form [name=brand]').val(response.brand);
                $('#modal-form [name=purchas_price]').val(response.purchas_price);
                $('#modal-form [name=sell_price]').val(response.sell_price);
                $('#modal-form [name=discount]').val(response.discount);
                $('#modal-form [name=stock]').val(response.stock);
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

    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            if (confirm('Are you sure you want to delete the selected data?')) {
                $.post(url, $('.form-product').serialize())
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Unable to delete data');
                        return;
                    });
            }
        } else {
            alert('Select the data to be deleted');
            return;
        }
    }

    function printBarcode(url) {
        if ($('input:checked').length < 1) {
            alert('Select the data to be printed');
            return;
        } else if ($('input:checked').length < 3) {
            alert('Choose at least 3 data to print');
            return;
        } else {
            $('.form-product')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }
</script>
@endpush
