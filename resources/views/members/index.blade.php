@extends('layout.master')
@section('title')
    Members
@endsection
@section('breadcrumb')
@parent
<li class="active">Members</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
            <button onclick="addForm('{{ route('members.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Add</button>
                <button onclick="printMember('{{ route('member.print_member') }}')" class="btn btn-info btn-xs btn-flat"><i class="fa fa-id-card"></i> Print Member</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">
                            <input type="checkbox" name="select_all" id="select_all">
                        </th>
                        <th width="5%">No</th>
                        <th>Code Member</th>
                        <th>Member Name</th>
                        <th>Address</th>
                        <th>Phone</th>
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
      @includeIf('members.form')
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
                url: '{{ route('member.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'code_member'},
                {data: 'name'},
                {data: 'address'},
                {data: 'phone'},
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
        $('#modal-form .modal-title').text('Add Member');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }
    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Member');
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
    function printMember(url) {
        if ($('input:checked').length < 1) {
            alert('Select the data to be printed');
            return;
        } else {
            $('.form-member')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }
</script>
@endpush
