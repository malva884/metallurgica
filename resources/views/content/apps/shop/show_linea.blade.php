@extends('layouts.detachedLayoutMaster')

@section('title', 'Shop')

@section('vendor-style')
    <!-- Vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/nouislider.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sliders.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
    <script>
        function redirect(id) {
            id = $('#category').val();
            window.location.href = '?&cid=' + id;
        }
    </script>
@endsection
@include('content/apps/shop/sidebar',['categories'=> $categories])
@section('content')
    <!-- E-commerce Content Section Starts -->

    <section id="ecommerce-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="ecommerce-header-items">
                    <div class="result-toggler">
                        <button class="navbar-toggler shop-sidebar-toggler" type="button" data-toggle="collapse">
                            <span class="navbar-toggler-icon d-block d-lg-none"><i data-feather="menu"></i></span>
                        </button>
                        <div class="search-results">{{$count}} results found</div>
                    </div>
                    <div class="view-options d-flex">
                        <div class="btn-group dropdown-sort">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle mr-1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="active-sorting">Featured</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">Featured</a>
                                <a class="dropdown-item" href="javascript:void(0);">Lowest</a>
                                <a class="dropdown-item" href="javascript:void(0);">Highest</a>
                            </div>
                        </div>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-icon btn-outline-primary view-btn grid-view-btn">
                                <input type="radio" name="radio_options" id="radio_option1" checked/>
                                <i data-feather="grid" class="font-medium-3"></i>
                            </label>
                            <label class="btn btn-icon btn-outline-primary view-btn list-view-btn">
                                <input type="radio" name="radio_options" id="radio_option2"/>
                                <i data-feather="list" class="font-medium-3"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- E-commerce Content Section Starts -->

    <!-- background Overlay when sidebar is shown  starts-->
    <div class="body-content-overlay"></div>
    <!-- background Overlay when sidebar is shown  ends-->

    <!-- E-commerce Search Bar Starts -->
    <section id="ecommerce-searchbar" class="ecommerce-searchbar">
        <div class="row mt-1">
            <div class="col-sm-12">
                <div class="input-group input-group-merge">
                    <input type="text" class="form-control search-product" id="shop-search" placeholder="Search Product"
                           aria-label="Search..." aria-describedby="shop-search"/>
                    <div class="input-group-append">
                        <span class="input-group-text"><i data-feather="search" class="text-muted"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- E-commerce Search Bar Ends -->

    <!-- E-commerce Products Starts -->
    <section id="ecommerce-products" class="grid-view">
        @foreach($products as $product)
            <div class="card ecommerce-card">
                <div class="item-img text-center">
                    <a href="{{url('app/ecommerce/details')}}">
                        <img class="img-fluid card-img-top" src="{{ asset('images/pages/eCommerce/1.png') }}"
                             alt="img-placeholder"/></a>
                </div>
                <div class="card-body">
                    <div class="item-wrapper">
                        <div class="item-rating">
                            <ul class="unstyled-list list-inline">
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                            </ul>
                        </div>
                        <div>
                            <h6 class="item-price">$339.99</h6>
                        </div>
                    </div>
                    <h6 class="item-name">
                        <a class="text-body" href="{{url('app/ecommerce/details')}}">{{$product->name.' '.$product->cartInList()}}</a>
                        <span class="card-text item-company">By <a href="javascript:void(0)"
                                                                   class="company-name">Apple</a></span>
                    </h6>
                    <p class="card-text item-description">
                       {{$product->description}}
                    </p>
                </div>
                <div class="item-options text-center">
                    <div class="item-quantity">
                    <div class="item-wrapper">
                        <div class="item-cost">
                            <h4 class="item-price">$339.99</h4>
                        </div>
                    </div>
                    </div>
                    <a href="javascript:void(0)" class="btn btn-light btn-wishlist">
                        <div class="input-group quantity-counter-wrapper">
                            Qty:   <input type="text" id="{{$product->id}}" value="1" class="quantity-counter" onchange="" />
                        </div>

                    </a>
                    <a href="javascript:void(0)" class="btn btn-{{ ($product->cartInList() > 0 ? 'warning': 'primary')}} btn-cart" id="add" data-id="{{ $product->id }}">
                        <i data-feather="shopping-cart"></i>
                        <span class="{{ ($product->cartInList() > 0 ? 'view-in-cart': 'add-to-cart')}}">{{ ($product->cartInList() > 0 ? 'Nel Carrello': 'Add to cart')}}</span>
                    </a>
                </div>
            </div>
        @endforeach
    </section>
    <!-- E-commerce Products Ends -->

    <!-- E-commerce Pagination Starts -->
    <section id="ecommerce-pagination">
        <div class="row">
            <div class="col-sm-12">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center mt-2">
                        <li class="page-item prev-item"><a class="page-link" href="javascript:void(0);"></a></li>
                        <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                        <li class="page-item" aria-current="page"><a class="page-link" href="javascript:void(0);">4</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">5</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">6</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">7</a></li>
                        <li class="page-item next-item"><a class="page-link" href="javascript:void(0);"></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <!-- E-commerce Pagination Ends -->
@endsection

@section('vendor-script')
    <!-- Vendor js files -->
    <script src="{{ asset(mix('vendors/js/extensions/wNumb.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/nouislider.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

@endsection
@section('page-script')
    <!-- Page js files -->

    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/pages/app-ecommerce-checkout.js')) }}"></script>
    <script>
        $(function () {
            'use strict';

            var productsSwiper = $('.swiper-responsive-breakpoints'),
                productOption = $('.product-color-options li'),
                btnCart = $('.btn-cart'),

                checkout = 'app-ecommerce-checkout.html';

            // Init Swiper
            if (productsSwiper.length) {
                new Swiper('.swiper-responsive-breakpoints', {
                    slidesPerView: 5,
                    spaceBetween: 55,
                    // init: false,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    },
                    breakpoints: {
                        1600: {
                            slidesPerView: 4,
                            spaceBetween: 55
                        },
                        1300: {
                            slidesPerView: 3,
                            spaceBetween: 55
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 55
                        },
                        320: {
                            slidesPerView: 1,
                            spaceBetween: 55
                        }
                    }
                });
            }
            // On cart & view cart btn click to v
            if (btnCart.length) {
                btnCart.on('click', function (e) {
                    var $this = $(this),
                        $id = $(this).data("id"),
                        addToCart = $this.find('.add-to-cart');

                    if (addToCart.length > 0) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: '{{ route('cart.add') }}',
                            method: "GET",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'qty': $('#'+$id).val(),
                                'id': $(this).data("id"),
                            },
                            success: function (response) {
                                e.preventDefault();
                                addToCart.text('{{__('locale.InTheCart')}}').removeClass('add-to-cart').addClass('view-in-cart');
                                addToCart.removeClass('btn btn-primary btn-cart').addClass('btn btn-warning btn-cart');
                                $('span#totalshop').html(response);
                                toastr['success']('', '{{__('locale.AddedItemCart')}}', {
                                    closeButton: true,
                                    tapToDismiss: false
                                });

                            },
                            error: function (xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");
                                toastr['error']('', '{{__('locale.DaGestire')}}', {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            }
                        });

                        // $this.attr('href', checkout);

                    }
                });
            }

            // Product color options
            if (productOption.length) {
                productOption.on('click', function () {
                    $(this).addClass('selected').siblings().removeClass('selected');
                });
            }
        });

    </script>
@endsection
