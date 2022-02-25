@extends('layout.master')

@section('title')
    Transaction Purchase
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }
        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }
        .table-pembelian tbody tr:last-child {
            display: none;
        }
        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaction Purchase</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Supplier</td>
                            <td>: {{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>: {{ $supplier->phone }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>: {{ $supplier->address }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">

                    <form class="form-product">
                        @csrf
                        <div class="form-group row">
                            <label for="code_product" class="col-lg-2">Code Product</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_purchase" id="id_purchase" value="{{ $id_purchase }}">
                                    <input type="hidden" name="id_product" id="id_product">
                                    <input type="text" class="form-control" name="code_product" id="code_product">
                                    <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-pembelian">
                        <thead>
                        <th width="5%">No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th width="15%">Sum</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="tampil-bayar bg-primary"></div>
                            <div class="tampil-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('purchase.store') }}" class="form-pembelian" method="post">
                                @csrf
                                <input type="hidden" name="id_purchase" value="{{ $id_purchase }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-lg-2 control-label">Discount</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="discount" id="discount" class="form-control" value="{{ $discount }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="bayarrp" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('purchase_detail.product')
@endsection

@push('scripts')
    <script>
        let table, table2;
        $(function () {
            $('body').addClass('sidebar-collapse');
            table = $('.table-pembelian').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('purchase_detail.data', $id_purchase) }}',
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'code_product'},
                    {data: 'product_name'},
                    {data: 'purchas_price'},
                    {data: 'sum'},
                    {data: 'subtotal'},
                    {data: 'Added', searchable: false, sortable: false},
                ],
                dom: 'Brt',
                bSort: false,
                paginate: false
            })
                .on('draw.dt', function () {
                    loadForm($('#discount').val());
                });
            table2 = $('.table-produk').DataTable();
            $(document).on('input', '.quantity', function () {
                let id = $(this).data('id');
                let sum = parseInt($(this).val());
                if (sum < 1) {
                    $(this).val(1);
                    alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }
                if (sum > 10000) {
                    $(this).val(10000);
                    alert('Jumlah tidak boleh lebih dari 10000');
                    return;
                }
                $.post(`{{ url('/purchase_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'sum': sum
                })
                    .done(response => {
                        $(this).on('mouseout', function () {
                            table.ajax.reload(() => loadForm($('#discount').val()));
                        });
                    })
                    .fail(errors => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            });
            $(document).on('input', '#discount', function () {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                loadForm($(this).val());
            });
            $('.btn-simpan').on('click', function () {
                $('.form-pembelian').submit();
            });
        });
        function tampilProduk() {
            $('#modal-produk').modal('show');
        }
        function hideProduk() {
            $('#modal-produk').modal('hide');
        }
        function pilihProduk(id, kode) {
            $('#id_product').val(id);
            $('#code_product').val(kode);
            hideProduk();
            tambahProduk();
        }
        function tambahProduk() {
            $.post('{{ route('purchase_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#code_product').focus();
                    table.ajax.reload(() => loadForm($('#discount').val()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }
        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#discount').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }
        function loadForm(discount = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());
            $.get(`{{ url('/purchase_detail/loadform') }}/${discount}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('NIS. '+ response.totalrp);
                    $('#bayarrp').val('NIS. '+ response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('NIS. '+ response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
