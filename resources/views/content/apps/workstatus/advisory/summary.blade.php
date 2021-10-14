@extends('layouts/contentLayoutMaster')

@section('title', 'Riepilogo')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/ag-grid/ag-grid.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/ag-grid/ag-theme-material.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/tables/table-ag-grid.css')) }}">
@endsection

@section('content')
    <!-- Basic example section start -->
    <section id="basic-examples">
        <div class="card ">
            <div class="card-body">
                <div class="row col-12">
                    <div class="col-7">
                        <div class="ag-grid-btns d-flex justify-content-between flex-wrap mb-1">
                            <div class="dropdown sort-dropdown mb-1 mb-sm-0">
                                <button
                                        class="btn filter-btn dropdown-toggle border text-dark"
                                        type="button"
                                        id="dropdownMenuButton6"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                >
                                    1 - 20 of 500
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton6">
                                    <a class="dropdown-item" href="javascript:void(0);">20</a>
                                    <a class="dropdown-item" href="javascript:void(0);">50</a>
                                    <a class="dropdown-item" href="javascript:void(0);">100</a>
                                    <a class="dropdown-item" href="javascript:void(0);">150</a>
                                </div>
                            </div>
                            <div class="ag-btns d-flex flex-wrap">
                                <input
                                        type="text"
                                        class="ag-grid-filter form-control w-50 mr-1 mb-1 mb-sm-0"
                                        id="filter-text-box"
                                        placeholder="Search...."
                                />
                                <div class="btn-export">
                                    <button class="btn btn-primary ag-grid-export-btn">Export as CSV</button>
                                </div>
                            </div>
                        </div>
                        <div id="myGrid" class="aggrid ag-theme-material"></div>
                    </div>
                    <div class="col-5">
                        <div class="row" id="table-borderless">
                            <div class="col-11">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Totali Consultivo</h4>
                                    </div>
                                    <div class="table-responsive">


                                        <table class="table table-bordered">
                                            <thead>

                                            </thead>
                                            <tbody>
                                            <tr class="table-secondary">
                                                <td>Totale Reale </td>
                                                <td>Totale Teorico </td>
                                                <td> </td>
                                            </tr>
                                            <tr class="table-success">
                                                <td>€ {{number_format($head->total_real,2)}}</td>
                                                <td>€ {{number_format($head->total_theoretical,2)}}</td>
                                                <td>Valore finale</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="col-6" id="basic-examples">
        <div class="card">
            <div class="card-body">
                <div class="row">

                </div>

            </div>
        </div>
    </section>
    <!-- // Basic example section end -->

@endsection
@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/ag-grid/ag-grid-community.min.noStyle.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script>

        $(function () {
            'use strict';

            function cloneObject(obj) {
                return JSON.parse(JSON.stringify(obj));
            }

            /*** COLUMN DEFINE ***/
            var columnDefs = [
                {
                    headerName: 'Order',
                    field: 'order',
                    editable: false,
                    cellStyle: params => {
                        if (params.data.order) {
                            return {color: 'black', backgroundColor: 'aqua'};
                        }
                        return null;
                    }
                },
                {
                    headerName: 'Totale Reale',
                    field: 'total_real',
                    editable: false,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return '€ 0.00'
                        }else{
                            var x = params.value.toString();
                            var pattern = /(-?\d+)(\d{3})/;
                            while (pattern.test(x))
                                x = x.replace(pattern, "$1,$2");
                            return '€ '+x;
                        }
                    }

                },
                {
                    headerName: 'Totale Teorico',
                    field: 'total_theoretical',
                    editable: false,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return '€ 0.00'
                        }else{
                            var x = params.value.toString();
                            var pattern = /(-?\d+)(\d{3})/;
                            while (pattern.test(x))
                                x = x.replace(pattern, "$1,$2");
                            return '€ '+x;
                        }
                    }

                },

                {
                    headerName: 'Motivo correzione',
                    field: 'note',
                    editable: true,
                    cellEditor: 'mySimpleCellEditor'
                },

            ];

            /*** GRID OPTIONS ***/
            var gridOptions = {
                columnDefs: columnDefs,
                pagination: true,
                paginationPageSize: 60,
                rowHeight: 20,

                pivotPanelShow: 'always',
                colResizeDefault: 'shift',
                animateRows: true,
                resizable: true,
                rowSelection: 'single',
                enableRangeSelection: true,
                onSelectionChanged: onSelectionChanged,
                components: {
                    mySimpleCellEditor: MySimpleCellEditor
                },
                onCellValueChanged: function ($asd) {
                    $.ajax({
                        url: "{{route('workstatus.edit_summary')}}",
                        type: "post",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {
                            column: $asd['colDef']['field'],
                            id: $asd['data']['id'],
                            value: $asd['newValue']
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (response) {

                        },
                        error: function (blob) {
                            console.log(blob);
                        }
                    })

                }
            };

            let KEY_BACKSPACE = 8;
            let KEY_F2 = 113;
            let KEY_DELETE = 46;


            function MySimpleCellEditor() {
            }

            MySimpleCellEditor.prototype.init = function (params) {
                this.gui = document.createElement('input');
                this.gui.type = 'text';
                this.gui.classList.add('my-simple-editor');

                this.params = params;

                var startValue;

                let keyPressBackspaceOrDelete =
                    params.keyPress === KEY_BACKSPACE
                    || params.keyPress === KEY_DELETE;

                if (keyPressBackspaceOrDelete) {
                    startValue = '';
                } else if (params.charPress) {
                    startValue = params.charPress;
                } else {
                    startValue = params.value;
                    if (params.keyPress !== KEY_F2) {
                        this.highlightAllOnFocus = true;
                    }
                }

                if (startValue !== null && startValue !== undefined) {
                    this.gui.value = startValue;
                }

                this.gui.addEventListener('focusout', function (event) {
                    //alert("inside focus out");
                    //if (event.preventDefault) event.preventDefault();
                });
            };

            MySimpleCellEditor.prototype.getGui = function () {
                return this.gui;
            };

            MySimpleCellEditor.prototype.getValue = function () {
                return this.gui.value;
            };

            MySimpleCellEditor.prototype.afterGuiAttached = function () {
                this.gui.focus();
            };

            MySimpleCellEditor.prototype.myCustomFunction = function () {
                return {
                    rowIndex: this.params.rowIndex,
                    colId: this.params.column.getId()
                };
            };

            function onSelectionChanged() {
                var selectedRows = gridOptions.api.getSelectedRows();

            }

            // setup the grid after the page has finished loading
            document.addEventListener('DOMContentLoaded', function () {
                var gridDiv = document.querySelector('#myGrid');
                new agGrid.Grid(gridDiv, gridOptions);
            });

            setInterval(function () {
                var instances = gridOptions.api.getCellEditorInstances();
                if (instances.length > 0) {
                    var instance = instances[0];
                    if (instance.myCustomFunction) {
                        var result = instance.myCustomFunction();
                        //console.log('found editing cell: row index = ' + result.rowIndex + ', column = ' + result.colId + '.');
                    } else {
                        //console.log('found editing cell, but method myCustomFunction not found, must be the default editor.');
                    }
                } else {
                    //console.log('found not editing cell.');
                }
            }, 1000);

            /*** DEFINED TABLE VARIABLE ***/
            var gridTable = document.getElementById('myGrid');


            /*** GET TABLE DATA FROM URL ***/

            //agGrid.simpleHttpRequest({ url: assetPath + 'data/ag-grid-data.json' }).then(function (data) {
            agGrid.simpleHttpRequest({
                url: '{{route('advisorys.list_summary',['id'=> app('request')->id])}}',
                header: {'csrf-token': '{{csrf_token()}}'}
            }).then(function (data) {

                gridOptions.api.setRowData(data);

            });

            /*** FILTER TABLE ***/
            function updateSearchQuery(val) {
                gridOptions.api.setQuickFilter(val);
            }

            $('.ag-grid-filter').on('keyup', function () {
                updateSearchQuery($(this).val());
            });

            /*** CHANGE DATA PER PAGE ***/
            function changePageSize(value) {

                gridOptions.api.paginationSetPageSize(Number(value));
            }


            $('.sort-dropdown .dropdown-item').on('click', function () {
                var $this = $(this);
                changePageSize($this.text());
                $('.filter-btn').text('1 - ' + $this.text() + ' of 500');
            });

            /*** EXPORT AS CSV BTN ***/
            $('.ag-grid-export-btn').on('click', function (params) {
                gridOptions.api.exportDataAsCsv();
            });

            /*** INIT TABLE ***/
            new agGrid.Grid(gridTable, gridOptions);

        });
    </script>

    <style>
        .aggrid {
            height: 500px !important;
            font-size: 1rem;
            color: #6e6b7b;
        }

        .ag-theme-material .ag-cell.ag-cell-inline-editing {
            padding: 0px !important;
            height: 30px;
        }

        .ag-theme-material .ag-cell {
            line-height: 20px !important;
            text-align: center !important;
            padding-left: 5px !important;
            padding-right: 24px !important;
            border: 1px solid transparent !important;
            padding-left: 5px !important;
            padding-right: 23px !important;
        }


    </style>
@endsection




