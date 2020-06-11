<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">

    <title>
        {{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(3))->replace('-', ' ') ) ?? '' }} {{ " - " . ucwords( Str::of(Request::segment(2))->replace('-', ' ') ) ?? '' }}
    </title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin/>

    <script src="{{ asset('js/vfs_fonts.js') }}" defer></script>

    <!--
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js" defer></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer></script>

    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.js" defer></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.js" defer></script



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer></script>


    <script src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.js" defer></script>
-->

    <!-- Scripts -->
    <!--<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>-->
    <!--<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer></script>-->

 
    <!-- Datatable Buttons -->
    <!--
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.js" defer></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.js" defer></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.js" defer></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.js" defer></script>
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.js" defer></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.js" defer></script>
    -->

    <!-- Select Options 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js" defer></script>
    -->
   

    <!--DataTables Styles -->
    <!--
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.css" rel="stylesheet" />
-->

    <!--Select Options Styles-->
    <!--
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.css" rel="stylesheet" />
-->
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">

                <a class="navbar-brand" href="{{ url( route('admin.dashboard.index') ) }}">
                    {{ config('app.name', 'My Tutor') }}
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest

                            <!--
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                            -->
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ ucfirst(Auth::user()->first_name) }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('admin.logout') }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="">
            @yield('content')
        </main>
    </div>

    <script type="text/javascript">
        window.addEventListener('load', function () {
            $(function () {

                console.log("admin panel has been loaded");

                //sidebar menu
                $('.dropdown-toggle').on('click', function () {
                    dropdownToggleIcon($(this))
                });

                //tableData
                $("#selectAll").change(function() {
                    if ($(this).prop("checked")) { 
                        $('.buttons-select-all').trigger('click');
                    } else {
                        $('.buttons-select-none').trigger('click');
                    }
                });

                //selection dropdown
                $('.select-all').click(function () {
                  let $select2 = $(this).parent().siblings('.select2')
                  $select2.find('option').prop('selected', 'selected')
                  $select2.trigger('change')
                })

                $('.deselect-all').click(function () {
                  let $select2 = $(this).parent().siblings('.select2')
                  $select2.find('option').prop('selected', '')
                  $select2.trigger('change')
                })

                $('.select2').select2();


            });

            function dropdownToggleIcon(element) {
                if (element.parent().hasClass('.dropup')) {
                    console.log('This is firing');
                    element.parent().removeClass('dropup');

                } else {
                    element.parent().removeClass("dropup");
                }
            }
        });
    </script>

    <script type="text/javascript">
        window.addEventListener('load', function () 
        {
            let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
            let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
            let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
            let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
            let printButtonTrans = '{{ trans('global.datatables.print') }}'
            let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'

              let languages = {
                'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
              };

              $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
              $.extend(true, $.fn.dataTable.defaults, {
                language: {
                  url: languages['{{ app()->getLocale() }}']
                },
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {
                    orderable: false,
                    searchable: false,
                    targets: -1
                }],
                select: {
                  style:    'multi+shift',
                  selector: 'td:first-child'
                },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [
                 {
                    extend: 'selectAll',
                    className: 'btn btn-default d-none',
                    text: "select all",
                    exportOptions: {
                      columns: ':hidden'
                    }
                  },
                  {
                    extend: 'selectNone',
                    className: 'btn btn-default d-none',
                    text: "select none",
                    exportOptions: {
                      columns: ':hidden'
                    }
                  },
                  {
                    extend: 'copy',
                    className: 'btn btn-default ml-2',
                    text: copyButtonTrans,
                    exportOptions: {
                      columns: ':visible'
                    }
                  },
                  {
                    extend: 'csv',
                    className: 'btn btn-default',
                    text: csvButtonTrans,
                    exportOptions: {
                      columns: ':visible'
                    }
                  },
                  {
                    extend: 'excel',
                    className: 'btn btn-default',
                    text: excelButtonTrans,
                    exportOptions: {
                      columns: ':visible'
                    }
                  },
                  {
                    extend: 'pdf',
                    className: 'btn btn-default',
                    text: pdfButtonTrans,
                    exportOptions: {
                      columns: ':visible'
                    }
                  },
                  {
                    extend: 'print',
                    className: 'btn btn-default',
                    text: printButtonTrans,
                    exportOptions: {
                      columns: ':visible'
                    }
                  },
                  {
                    extend: 'colvis',
                    className: 'btn btn-default',
                    text: colvisButtonTrans,
                    exportOptions: {
                      columns: ':visible'
                    }
                  }
                ]
              });
            $.fn.dataTable.ext.classes.sPageButton = '';
        });
    </script>

    @yield('scripts')
</body>

</html>
