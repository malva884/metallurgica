<!DOCTYPE html>
<html>
<head>
    <title>Laravel Yajra Datatables Export to Excel Button Example - ItSolutionStuff.com</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="/vendor/datatables/buttons.server-side.js"></script>
</head>
<body>

<div class="container">
    <h1>Laravel Yajra Datatables Export to Excel Button Example - ItSolutionStuff.com</h1>

    {!! $dataTable->table() !!}
</div>

</body>

{!! $dataTable->scripts() !!}

</html>


<script>
    $(function () {
        $('#table').DataTable({
            responsive: true,
            fixedColumns: true,
            fixedHeader: true,
            dom: 'Bfrtip',
            buttons: ['colvis', 'csv', 'excel', 'print', {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }],
            processing: true,
            serverSide: true,
            ajax: '{!! route('banks.data') !!}',
            columns: [{data: 'action', name: 'action', orderable: false, searchable: false}, {
                data: 'BANK_COUNTRY',
                name: 'BANK_COUNTRY'
            }, {data: 'BANK_NAME', name: 'BANK_NAME'}, {
                data: 'ALTERNATE_BANK_NAME',
                name: 'ALTERNATE_BANK_NAME'
            }, {data: 'SHORT_BANK_NAME', name: 'SHORT_BANK_NAME'}, {
                data: 'BANK_NUMBER',
                name: 'BANK_NUMBER'
            }, {data: 'DESCRIPTION', name: 'DESCRIPTION'}, {
                data: 'TAX_PAYER_ID',
                name: 'TAX_PAYER_ID'
            }, {data: 'TAX_REGISTRATION_NUMBER', name: 'TAX_REGISTRATION_NUMBER'}, {
                data: 'INACTIVE_ON',
                name: 'INACTIVE_ON'
            }, {data: 'CONTEXT_VALUE', name: 'CONTEXT_VALUE'}, {data: 'ADDRESS', name: 'ADDRESS'}, {
                data: 'CONTACT',
                name: 'CONTACT'
            }, {data: 'CREATION_DATE', name: 'CREATION_DATE'}],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement('input');
                    $(input).appendTo($(column.footer()).empty()).on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search($(this).val(), false, false, true).draw();
                    });
                });
            }
        });
    }); </script>