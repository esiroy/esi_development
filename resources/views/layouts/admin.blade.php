<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="Description" content="{{ config('app.name', 'My Tutor')}} {{'- ' . ucwords(Request::segment(3)) ?? '' }} ">
    <title>
    {{ config('app.name', 'My Tutor') }} {{ ":: " . ucwords( Str::of(Request::segment(3))->replace('-', ' ') ) ?? '' }} {{ " - " . ucwords( Str::of(Request::segment(2))->replace('-', ' ') ) ?? '' }}
    </title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />
    <link rel="preconnect" href="//cdn.datatables.net" rel="preconnect" crossorigin/>
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/admin.js') }}" defer></script>
    <script src="{{ asset('js/vfs_fonts.js') }}" defer></script>
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>
</head>

<body class="bg-gray">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <a class="navbar-brand" href="{{ url( route('admin.dashboard.index') ) }}">
                   <img src="{{ url("images/mytutor_logo.png") }}" alt="{{ config('app.name', 'My Tutor') }}" alt="{{ config('app.name', 'My Tutor') }} administratrion panel">
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

    
        <div class="bg-lightblue">
          <div class="container px-0">
            <nav class="nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/lesson') }}">My Page</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/member') }}">User</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('admin/questionnaires') }}">Manage</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/report') }}">Report</a>
            </nav>
          </div>
        </div>


        <main class="main-container">
            @yield('content')
        </main>


        <footer class="container py-4 px-0 bg-light">
            <div class="container border-top">
                <div class="row">
                    <div class="col-12 text-center py-3">
                        <a class="navbar-brand" href="{{ url( route('admin.dashboard.index') ) }}">
                            {{ config('app.name', 'My Tutor') }}
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script type="text/javascript">
        window.addEventListener('load', function () {
            $(function () {
                //sidebar menu
                $('list-group-item.dropdown-toggle').on('click', function () {
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
                if (element.parent().hasClass('dropup')) {
                    element.parent().removeClass('dropup');
                } else {
                    element.parent().addClass("dropup");
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
