@extends('layouts/contentLayoutMaster')

@section('title', 'Stato Lavori')

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
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">

                        <div class="ag-grid-btns d-flex justify-content-between flex-wrap mb-1 ">
                            <div class="col-4">
                                <button class="btn btn-primary" onclick="showColumns(true)"><i data-feather='eye'></i></button>
                                <button class="btn btn-primary" onclick="showColumns(false)"><i data-feather='eye-off'></i></button>

                                <label style="margin-left: 20px;">
                                    Separator columns
                                    <input type="text" style="width: 20px;" id="columnSeparator"/>
                                </label>
                                <button class="btn btn-primary ag-grid-export-btn">Export as CSV</button>
                            </div>
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
                                    <a class="dropdown-item" href="javascript:void(0);">50</a>
                                    <a class="dropdown-item" href="javascript:void(0);">100</a>
                                    <a class="dropdown-item" href="javascript:void(0);">200</a>
                                    <a class="dropdown-item" href="javascript:void(0);">300</a>
                                    <a class="dropdown-item" href="javascript:void(0);">400</a>
                                    <a class="dropdown-item" href="javascript:void(0);">500</a>
                                </div>
                            </div>
                            <div class="ag-btns d-flex flex-wrap">
                                <input
                                        type="text"
                                        class="ag-grid-filter form-control w-50 mr-1 mb-1 mb-sm-0"
                                        id="filter-text-box"
                                        placeholder="Search...."
                                />

                                <div class="">

                                    <button class="btn btn-primary" onclick="Calcola('{{app('request')->id}}')">
                                        Calcola
                                    </button>
                                </div>
                                <div class="btn-export">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="myGrid" class="aggrid ag-theme-material"></div>
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
        let tem = '';
        $(function () {
            'use strict';

            function cloneObject(obj) {
                return JSON.parse(JSON.stringify(obj));
            }

            /*** COLUMN DEFINE ***/
            var columnDefs = [
                {
                    headerName: 'father',
                    field: 'father',
                    width: 10,
                    hide: true,

                },
                {
                    headerName: 'Order',
                    field: 'order',
                    width: 95,
                    tooltipField: 'Order',
                    tooltipComponentParams: {color: '#ececec'},
                    cellStyle: params => {
                        if (params.data.order && params.data.check_row) {
                            return {color: 'black', backgroundColor: 'Orange'};
                        }
                        return null;
                    }
                },
                {
                    headerName: 'Material',
                    field: 'material_1',
                    width: 95,
                    tooltipField: 'material_1',
                },
                {
                    headerName: 'Material Descr',
                    field: 'material_description_1',
                    width: 95,
                    tooltipField: 'material_description_1',
                },
                {
                    headerName: 'Total order quantity',
                    field: 'total_order_quantity',
                    width: 100,
                    valueFormatter: params => {
                        if (params.value === '0') {
                            return '-'
                        }
                    },
                    cellStyle: params => {
                        if (params.data.total_order_quantity > 0) {
                            return {color: 'black', backgroundColor: 'aquamarine'};
                        }
                        return null;
                    }
                },
                {
                    headerName: 'Quantity',
                    field: 'quantity_1',
                    width: 110,
                    valueFormatter: params => {
                        if (params.value === '0') {
                            return '-'
                        }
                    },
                    editable: params => {
                        if (params.data.order !== '') {
                            return true;
                        }

                    },
                    cellStyle: params => {
                        if (params.data.total_order_quantity > 0) {
                            return {color: 'black', backgroundColor: 'aquamarine'};
                        }
                        return null;
                    },
                    cellEditor: 'mySimpleCellEditor',
                },
                {
                    headerName: 'Fase',
                    field: 'operation_activity',
                    width: 70,
                    cellStyle: params => {
                        if (params.data.operation_activity !== '') {
                            return {color: 'black', backgroundColor: 'lemonchiffon'};
                        }
                        return null;
                    }
                },
                {
                    headerName: 'Work Center',
                    field: 'work_center',

                    width: 70,
                },
                {
                    headerName: 'Work center cost',
                    field: 'work_center_cost',

                    width: 90,
                },
                {
                    headerName: 'Confirmed quantity',
                    field: 'confirmed_quantity',

                    width: 100,
                    cellStyle: params => {
                        if (params.value !== '0') {
                            return {color: 'black', backgroundColor: 'aqua'};
                        } else
                            return null;

                    },
                    valueFormatter: params => {
                        if (params.value === '0') {
                            return ''
                        }
                    }
                },
                {
                    headerName: 'Activity machine hours',
                    field: 'activity_machine_hours',

                    width: 90,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return ''
                        }
                    },
                },
                {
                    headerName: 'Machine cost',
                    field: 'machine_cost',
                    width: 80,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return ''
                        } else {
                            var x = params.value;
                            if (x) {
                                var pattern = /(-?\d+)(\d{3})/;
                                while (pattern.test(x))
                                    x = x.replace(pattern, "$1,$2");
                                return x;
                            }
                        }
                    },
                },
                {
                    headerName: 'Activity Manpower hours',
                    field: 'activity_manpower_hours',

                    width: 90,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return ''
                        }
                    },
                },
                {
                    headerName: 'manpower cost',
                    field: 'manpower_cost',
                    width: 75,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return ''
                        } else {
                            var x = params.value;
                            if (x) {
                                var pattern = /(-?\d+)(\d{3})/;
                                while (pattern.test(x))
                                    x = x.replace(pattern, "$1,$2");
                                return '€ ' + x;
                            }

                        }
                    },
                },
                {
                    headerName: 'machine cost + manpower',
                    field: 'machine_cost_manpower',
                    cellClass: 'stringType',
                    width: 90,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return ''
                        } else {
                            var x = params.value;
                            if (x) {
                                var pattern = /(-?\d+)(\d{3})/;
                                while (pattern.test(x))
                                    x = x.replace(pattern, "$1,$2");
                                return '€ ' + x;
                            }

                        }
                    },

                },
                {
                    headerName: 'Result 1',
                    field: 'result_1',
                    width: 100,
                    cellClass: 'numberType',
                    cellEditor: 'mySimpleCellEditor',
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return ''
                        }
                        if (params.value !== null) {
                            var x = params.value;
                            if (x.toString() !== '0') {
                                var pattern = /(-?\d+)(\d{3})/;
                                while (pattern.test(x))
                                    x = x.replace(pattern, "$1,$2");
                                return '€ ' + x;
                            } else
                                return '';
                        }
                    },
                    cellStyle: params => {
                        if (params.data.result_1 === '0.00' && params.data.machine_cost_manpower !== '0.00') {
                            return {color: 'black', backgroundColor: 'Orange'};
                        }else if(params.data.result_1 > 0.00){
                            return {color: 'black', backgroundColor: 'aquamarine'};
                        }
                        return null;
                    }
                },
                {
                    headerName: 'Coefficiente',
                    field: 'calculation_1',
                    cellStyle: params => {
                        if (params.data.machine_cost_manpower > 0) {

                            return {color: 'black', backgroundColor: 'aqua'};
                        } else
                            return null;

                    },
                    editable: params => {
                        if (params.data.machine_cost_manpower !== '0.00') {
                            return true;
                        }

                    },
                    width: 100,
                    cellEditor: 'mySimpleCellEditor',
                },
                {
                    headerName: 'Material',
                    field: 'material_2',
                    width: 100,
                    tooltipField: 'material_2',
                },
                {
                    headerName: 'Material description 2',
                    field: 'material_description_2',
                    width: 100,
                    tooltipField: 'material_description_2',
                },
                {
                    headerName: 'Requirement quantity',
                    field: 'requirement_quantity',
                    width: 90,
                    valueFormatter: params => {
                        if (params.value === '0') {
                            return ''
                        }
                    }
                },
                {
                    headerName: 'UM',
                    field: 'base_unit_measure',
                    width: 60,
                },
                {
                    headerName: 'Quantity',
                    field: 'quantity_2',
                    editable: params => {
                        if (params.data.requirement_quantity !== null) {
                            return true;
                        }

                    },
                    width: 90,
                    cellEditor: 'mySimpleCellEditor'
                },
                {
                    headerName: 'material_cost',
                    field: 'material_cost',
                    width: 110,
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return '€ 0.00';
                        }
                        if (params.value !== null) {
                            var x = params.value.toString();
                            var pattern = /(-?\d+)(\d{3})/;
                            while (pattern.test(x))
                                x = x.replace(pattern, "$1,$2");
                            return '€ ' + x;
                        }
                    },
                    cellStyle: params => {
                        if (params.data.material_cost === '0.00') {
                            return {color: 'white', backgroundColor: 'lightcoral'};
                        }
                        return null;
                    }
                },
                {
                    headerName: 'Result 2',
                    field: 'result_2',
                    width: 100,
                    cellEditor: 'mySimpleCellEditor',
                    valueFormatter: params => {
                        if (params.value === '0.00') {
                            return ''
                        }
                        if (params.value !== null) {
                            var x = params.value;
                            if (x.toString() !== '0') {
                                var pattern = /(-?\d+)(\d{3})/;
                                while (pattern.test(x))
                                    x = x.replace(pattern, "$1,$2");
                                return '€ ' + x;
                            } else return '';
                        }
                    },
                    cellStyle: params => {
                        if(params.data.result_2 > 0.00){
                            return {color: 'black', backgroundColor: 'aquamarine'};
                        }
                        return null;
                    }
                },
                {
                    headerName: 'Coefficiente',
                    field: 'calculation_2',
                    cellStyle: params => {
                        if (params.data.material_cost > '0.00') {
                            return {color: 'black', backgroundColor: 'aqua'};
                        } else
                            return null;

                    },
                    editable: params => {
                        if (params.data.material_cost !== '0.00') {
                            return true;
                        }

                    },
                    width: 110,
                    cellEditor: 'mySimpleCellEditor'
                },
            ];

            var defaultColDef = {
                flex: 1,
                resizable: true,
                sortable: false,
                //wrapText: true,
                autoHeight: false,
                headerComponentParams: {
                    template:
                        '<div class="ag-cell-label-container" role="presentation">' +
                        '  <span ref="eMenu" class="ag-header-icon ag-header-cell-menu-button"></span>' +
                        '  <div ref="eLabel" class="ag-header-cell-label" role="presentation">' +
                        '    <span ref="eSortOrder" class="ag-header-icon ag-sort-order"></span>' +
                        '    <span ref="eSortAsc" class="ag-header-icon ag-sort-ascending-icon"></span>' +
                        '    <span ref="eSortDesc" class="ag-header-icon ag-sort-descending-icon"></span>' +
                        '    <span ref="eSortNone" class="ag-header-icon ag-sort-none-icon"></span>' +
                        '    <span ref="eText" class="ag-header-cell-text" role="columnheader" style="white-space: normal;"></span>' +
                        '    <span ref="eFilter" class="ag-header-icon ag-filter-icon"></span>' +
                        '  </div>' +
                        '</div>',
                },

            };

            /*** GRID OPTIONS ***/
            var gridOptions = {
                columnDefs: columnDefs,
                pagination: true,
                paginationPageSize: 60,
                rowHeight: 20,
                defaultColDef: defaultColDef,
                pivotPanelShow: 'always',
                colResizeDefault: 'shift',
                animateRows: true,
                resizable: true,
                rowSelection: 'single',
                enableRangeSelection: true,
                enableBrowserTooltips: true,
                onSelectionChanged: onSelectionChanged,
                components: {
                    mySimpleCellEditor: MySimpleCellEditor
                },
                onCellValueChanged: function (params) {
                    var colId = params.column.getId();
                    var setValue = null;

                    if (colId === 'calculation_1') {
                        var calculation_1 = parseFloat(params.data.calculation_1);
                        var machine_cost_manpower = parseFloat(params.data.machine_cost_manpower);
                        if (!isNaN(calculation_1)) {
                            setValue = machine_cost_manpower * calculation_1;
                            setValue = setValue.toFixed(2);
                            params.node.setDataValue('result_1', setValue);
                        } else {
                            params.node.setDataValue('result_1', null);
                        }
                    }
                    if (colId === 'calculation_2') {
                        var calculation_2 = parseFloat(params.data.calculation_2);
                        var material_cost = parseFloat(params.data.material_cost);
                        if (calculation_2 !== 0 && !isNaN(calculation_2)) {
                            setValue = material_cost * calculation_2;
                            setValue = setValue.toFixed(2);
                            params.node.setDataValue('result_2', setValue);
                        } else {
                            params.node.setDataValue('result_2', null);
                        }
                    }

                    if (colId === 'quantity_1') {
                        var quantity_1 = params.data.quantity_1;
                    }

                    $.ajax({
                        url: "{{route('workstatus.edit')}}",
                        type: "post",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {
                            column: params['colDef']['field'],
                            id: params['data']['id'],
                            value: params['newValue']
                        },
                        dataType: "json",
                        success: function (data) {
                            if (colId === 'quantity_2') {
                                let value = $.parseJSON(data.material_cost);
                                if (typeof (value) != "undefined" && value !== null)
                                    params.node.setDataValue('material_cost', value);
                            }
                        },
                        error: function (blob) {
                            alert('Errore di comunicazione con il server!');
                            console.log(blob);
                        }
                    })
                },
            };

            let KEY_BACKSPACE = 8;
            let KEY_F2 = 113;
            let KEY_DELETE = 46;

            function MySimpleCellEditor() {
            }

            MySimpleCellEditor.prototype.init = function (params) {
                this.gui = document.createElement('input');
                this.gui.type = 'text';
                //this.gui.classList.add('my-simple-editor');

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
                //document.querySelector('#selectedRows').innerHTML =
                //selectedRows.length === 1 ? selectedRows[0].athlete : '';
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
                url: '{{route('workstatus.list',['id'=> app('request')->id])}}',
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
                var params = {};
                let columnSeparator = document.querySelector('#columnSeparator').value;
                if (columnSeparator !== null) {
                    params = {columnSeparator: columnSeparator};
                }

                gridOptions.api.exportDataAsCsv(params);
                //gridOptions.api.exportDataAsCsv();
            });

            /*** INIT TABLE ***/
            new agGrid.Grid(gridTable, gridOptions);

            /*** SET OR REMOVE EMAIL AS PINNED DEPENDING ON DEVICE SIZE ***/

            if ($(window).width() < 768) {
                gridOptions.columnApi.setColumnPinned('order', null);
            } else {
                gridOptions.columnApi.setColumnPinned('order', 'left');
            }
            $(window).on('resize', function () {
                if ($(window).width() < 768) {
                    gridOptions.columnApi.setColumnPinned('order', null);
                } else {
                    gridOptions.columnApi.setColumnPinned('order', 'left');
                }
            });

            this.showColumns = function (show) {
                let colums = [
                    'material_1','material_description_1','work_center','material_description_2','work_center_cost','activity_machine_hours'
                    ,'activity_manpower_hours'      ];
                gridOptions.columnApi.setColumnsVisible(colums, show);
            }
        });

        function Calcola(id) {

            $.ajax({
                type: "POST",
                url: '{{route('workstatus.finalWork',['id'=>app('request')->id])}}',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    window.location = "/workstatus/index";
                },
            });
        }

    </script>

    <style>

        .custom-tooltip {
            position: absolute;
            width: 165px;
            height: 80px;
            border: 1px solid cornflowerblue;
            overflow: hidden;
            pointer-events: none;
            transition: opacity 1s;
            color: #ffffff !important;
            background-color: #ffffff !important;
        }

        .aggrid {
            height: 900px !important;
            font-size: 1rem;
            color: #6e6b7b;
        }

        .ag-theme-material .ag-cell.ag-cell-inline-editing {
            padding: 0px !important;
            height: 30px;
        }

        .ag-theme-material .ag-cell {
            line-height: 20px !important;
            text-align: left !important;
            padding-left: 5px !important;
            padding-right: 24px !important;
            border: 1px solid transparent !important;
            padding-left: 5px !important;
            padding-right: 23px !important;
        }

        .aggrid .ag-header-cell-text {
            font-size: 10px !important;

        }

        .ag-cell {
            text-overflow: clip !important;
        }

    </style>
@endsection




