@if($configData["mainLayoutType"] === 'horizontal' && isset($configData["mainLayoutType"]))
    <nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center {{ $configData['navbarColor'] }}"
         data-nav="brand-center">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand" href="{{url('/')}}">
          <span class="brand-logo">
            <img src="{{asset('images/logo/logo1.ico')}}" height="24">
          </span>
                        <h2 class="brand-text mb-0">Metallurgica Bresciana</h2>
                    </a>
                </li>
            </ul>
        </div>
        @else
            <nav class="header-navbar navbar navbar-expand-lg align-items-center {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }}">
                @endif
                <div class="navbar-container d-flex content">
                    <div class="bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item d-none d-lg-block">
                                <a class="nav-link nav-link-style">
                                    <i class="ficon"
                                       data-feather="{{($configData['theme'] === 'dark') ? 'sun' : 'moon' }}"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav d-xl-none">
                            <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i
                                            class="ficon" data-feather="menu"></i></a></li>
                        </ul>
                    <!-- ul class="nav navbar-nav bookmark-icons">
                            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{url('app/email')}}"
                                                                      data-toggle="tooltip" data-placement="top"
                                                                      title="Email"><i class="ficon"
                                                                                       data-feather="mail"></i></a></li>
                            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{url('app/chat')}}"
                                                                      data-toggle="tooltip" data-placement="top"
                                                                      title="Chat"><i class="ficon"
                                                                                      data-feather="message-square"></i></a>
                            </li>
                            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{url('app/calendar')}}"
                                                                      data-toggle="tooltip" data-placement="top"
                                                                      title="Calendar"><i class="ficon"
                                                                                          data-feather="calendar"></i></a>
                            </li>
                            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{url('app/todo')}}"
                                                                      data-toggle="tooltip" data-placement="top"
                                                                      title="Todo"><i class="ficon"
                                                                                      data-feather="check-square"></i></a>
                            </li>
                        </ul -->
                        <!-- ul class="nav navbar-nav">
                            <li class="nav-item d-none d-lg-block">
                                <a class="nav-link bookmark-star">
                                    <i class="ficon text-warning" data-feather="star"></i>
                                </a>
                                <div class="bookmark-input search-input">
                                    <div class="bookmark-input-icon">
                                        <i data-feather="search"></i>
                                    </div>
                                    <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0"
                                           data-search="search">
                                    <ul class="search-list search-list-bookmark"></ul>
                                </div>
                            </li>
                        </ul -->
                    </div>
                    <ul class="nav navbar-nav align-items-center ml-auto">
                        <li class="nav-item dropdown dropdown-language">
                            <a class="nav-link dropdown-toggle" id="dropdown-flag" href="javascript:void(0);"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="flag-icon flag-icon-us"></i>
                                <span class="selected-language">English</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">
                                <a class="dropdown-item" href="{{url('lang/it')}}" data-language="it">
                                    <i class="flag-icon flag-icon-it"></i> Italiano
                                </a>
                                <a class="dropdown-item" href="{{url('lang/en')}}" data-language="en">
                                    <i class="flag-icon flag-icon-us"></i> English
                                </a>
                                <a class="dropdown-item" href="{{url('lang/fr')}}" data-language="fr">
                                    <i class="flag-icon flag-icon-fr"></i> French
                                </a>
                                <a class="dropdown-item" href="{{url('lang/de')}}" data-language="de">
                                    <i class="flag-icon flag-icon-de"></i> German
                                </a>
                                <a class="dropdown-item" href="{{url('lang/pt')}}" data-language="pt">
                                    <i class="flag-icon flag-icon-pt"></i> Portuguese
                                </a>
                            </div>
                        </li>
                        <?php
                        use Illuminate\Support\Facades\Auth;
                        $user = Auth::user();
                        ?>
                        <li class="nav-item dropdown dropdown-notification mr-25"><a class="nav-link"
                                                                                     href="javascript:void(0);"
                                                                                     data-toggle="dropdown"><i
                                        class="ficon" data-feather="bell"></i><span
                                        class="badge badge-pill badge-danger badge-up" id="notify">{{$user->unreadNotifications->count()}}</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header d-flex">
                                        <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                                        <div class="badge badge-pill badge-light-primary">{{$user->unreadNotifications->count()}}</div>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list" id="message"><a class="d-flex"
                                                                               href="javascript:void(0)">
                                        @forelse($user->unreadNotifications as $notification)
                                            <a class="d-flex" href="/{{$notification->data['route']}}">
                                                <div class="media d-flex align-items-start">
                                                    <div class="media-left">
                                                        <div class="avatar bg-light-danger">
                                                            <div class="avatar-content">{{$notification->data['op']}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="media-body">
                                                        <p class="media-heading"><span
                                                                    class="font-weight-bolder">{{$notification->data['title']}}</span>&nbsp;
                                                        </p><small
                                                                class="notification-text"> {{$notification->data['message']}}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="media d-flex align-items-center">
                                                <h6 class="font-weight-bolder mr-auto mb-0">Nessuna Notifica</h6>
                                            </div>
                                        @endforelse
                                    </a>
                                </li>
                                <li class="dropdown-menu-footer"><a class="btn btn-primary btn-block"
                                                                    href="javascript:void(0)">Read all notifications</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown dropdown-user">
                            <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user"
                               href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name font-weight-bolder">{{Auth::user()->firstname.' '.Auth::user()->lastname}}</span>
                                    <span class="user-status">{{Auth::user()->roles->first()->name}}</span>
                                </div>
                                <span class="avatar">
              <img class="round" src="{{asset('images/users/'.Auth::user()->image)}}" alt="avatar" height="40"
                   width="40">
              <span class="avatar-status-online"></span>
            </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="mr-50" data-feather="user"></i> Profile
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{route('user.account')}}">
                                    <i class="mr-50" data-feather="settings"></i> Settings
                                </a>

                                @auth()
                                    <a class="dropdown-item" href="/logout">
                                        <i class="mr-50" data-feather="power"></i> Logout
                                    </a>
                                @else
                                    <a class="dropdown-item" href="/login">
                                        <i class="mr-50" data-feather="power"></i> Login
                                    </a>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- Search Start Here --}}
            <ul class="main-search-list-defaultlist d-none">
                <li class="d-flex align-items-center">
                    <a href="javascript:void(0);">
                        <h6 class="section-label mt-75 mb-0">Files</h6>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between w-100"
                       href="{{url('app/file-manager')}}">
                        <div class="d-flex">
                            <div class="mr-75">
                                <img src="{{asset('images/icons/xls.png')}}" alt="png" height="32">
                            </div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">Two new item submitted</p>
                                <small class="text-muted">Marketing Manager</small>
                            </div>
                        </div>
                        <small class="search-data-size mr-50 text-muted">&apos;17kb</small>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between w-100"
                       href="{{url('app/file-manager')}}">
                        <div class="d-flex">
                            <div class="mr-75">
                                <img src="{{asset('images/icons/jpg.png')}}" alt="png" height="32">
                            </div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">52 JPG file Generated</p>
                                <small class="text-muted">FontEnd Developer</small>
                            </div>
                        </div>
                        <small class="search-data-size mr-50 text-muted">&apos;11kb</small>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between w-100"
                       href="{{url('app/file-manager')}}">
                        <div class="d-flex">
                            <div class="mr-75">
                                <img src="{{asset('images/icons/pdf.png')}}" alt="png" height="32">
                            </div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">25 PDF File Uploaded</p>
                                <small class="text-muted">Digital Marketing Manager</small>
                            </div>
                        </div>
                        <small class="search-data-size mr-50 text-muted">&apos;150kb</small>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between w-100"
                       href="{{url('app/file-manager')}}">
                        <div class="d-flex">
                            <div class="mr-75">
                                <img src="{{asset('images/icons/doc.png')}}" alt="png" height="32"></div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">Anna_Strong.doc</p>
                                <small class="text-muted">Web Designer</small>
                            </div>
                        </div>
                        <small class="search-data-size mr-50 text-muted">&apos;256kb</small>
                    </a>
                </li>
                <li class="d-flex align-items-center">

                    <a href="javascript:void(0);">
                        <h6 class="section-label mt-75 mb-0">Members</h6>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between py-50 w-100"
                       href="{{url('app/user/view')}}">
                        <div class="d-flex align-items-center">
                            <div class="avatar mr-75">
                                <img src="{{asset('images/portrait/small/avatar-s-8.jpg')}}" alt="png" height="32">
                            </div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">John Doe</p>
                                <small class="text-muted">UI designer</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between py-50 w-100"
                       href="{{url('app/user/view')}}">
                        <div class="d-flex align-items-center">
                            <div class="avatar mr-75">
                                <img src="{{asset('images/portrait/small/avatar-s-1.jpg')}}" alt="png" height="32">
                            </div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">Michal Clark</p>
                                <small class="text-muted">FontEnd Developer</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between py-50 w-100"
                       href="{{url('app/user/view')}}">
                        <div class="d-flex align-items-center">
                            <div class="avatar mr-75">
                                <img src="{{asset('images/portrait/small/avatar-s-14.jpg')}}" alt="png" height="32">
                            </div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">Milena Gibson</p>
                                <small class="text-muted">Digital Marketing Manager</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="auto-suggestion">
                    <a class="d-flex align-items-center justify-content-between py-50 w-100"
                       href="{{url('app/user/view')}}">
                        <div class="d-flex align-items-center">
                            <div class="avatar mr-75">
                                <img src="{{asset('images/portrait/small/avatar-s-6.jpg')}}" alt="png" height="32">
                            </div>
                            <div class="search-data">
                                <p class="search-data-title mb-0">Anna Strong</p>
                                <small class="text-muted">Web Designer</small>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>

            {{-- if main search not found! --}}
            <ul class="main-search-list-defaultlist-other-list d-none">
                <li class="auto-suggestion justify-content-between">
                    <a class="d-flex align-items-center justify-content-between w-100 py-50">
                        <div class="d-flex justify-content-start">
                            <span class="mr-75" data-feather="alert-circle"></span>
                            <span>No results found.</span>
                        </div>
                    </a>
                </li>
            </ul>
        {{-- Search Ends --}}
        <!-- END: Header-->
