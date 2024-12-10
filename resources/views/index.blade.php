@extends('layouts.app')
@section('content')

<section class="sc-1">
    <div class="fl-1">
        <img src="{{ asset('assets/img/18.png') }}" alt="" data-aos="fade-down" data-aos-duration="1000">
    </div>
    <div class="fl-2 animated-2" data-aos="zoom-in" data-aos-duration="1500">
        <img src="{{ asset('assets/img/7.png') }}" alt="">
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 offset-md-1">
                <div class="sc-cont">
                    <h1 data-aos="fade" data-aos-duration="2000">enjoy our <br>
                        delicious <span class="c-y">food</span>
                    </h1>
                    <div class="free" data-aos="fade" data-aos-duration="2200">
                        <span>buy one. get one</span>
                        <span class="btn-free">FREE</span>
                    </div>
                    <div class="price" data-aos="fade" data-aos-duration="2300">
                        Price: <span>$10.50</span>
                    </div>
                    <div class="delivery" data-aos="fade" data-aos-duration="2400">
                        <div class="dl-img">
                            <img src="{{ asset('assets/img/delivery.png') }}" alt="">
                        </div>
                        <div class="dl-cont">
                            <p>delivery order num.</p>
                            <h6>123-58998945</h6>
                        </div>
                        <div class="btn-sc">
                            <a href="#">
                                <button class="btn-prime">
                                    Try It Now
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pizza-home ">
                    <div class="fl-7 animated-4">
                        <img src="{{ asset('assets/img/3.png') }}" alt="" data-aos="zoom-in-up"
                            data-aos-duration="1000">
                    </div>
                    <div class="fl-8 animated-4">
                        <img src="{{ asset('assets/img/4.png') }}" alt="" data-aos="zoom-in-up"
                            data-aos-duration="1500">
                    </div>
                    <div class="fl-9 animated-4">
                        <img src="{{ asset('assets/img/81.png') }}" alt="" data-aos="zoom-in-up"
                            data-aos-duration="2000">
                    </div>
                    <img src="{{ asset('assets/img/piazza.svg') }}" class="img-fluid pizza-img animated" alt=""
                        data-aos="zoom-in" data-aos-duration="1000">
                </div>
            </div>
        </div>
    </div>
    <div class="fl-3" data-aos="fade-right" data-aos-duration="1200">
        <img src="{{ asset('assets/img/26.png') }}" alt="">
    </div>
    <div class="fl-4" data-aos="fade-right" data-aos-duration="1200">
        <img src="{{ asset('assets/img/5.png') }}" alt="">
    </div>
    <div class="fl-5 animated-2" data-aos="zoom-in-up" data-aos-duration="1200">
        <img src="{{ asset('assets/img/2.png') }}" alt="">
    </div>
    <div class="fl-6" data-aos="zoom-in-up" data-aos-duration="1300">
        <img src="{{ asset('assets/img/9.png') }}" alt="">
    </div>
</section>
<section class="sc-2">
    <div class="container">
        <div class="booking-box">
            <div class="fl-9" data-aos="fade" data-aos-duration="1200">
                <img src="{{ asset('assets/img/fl-9') }}.svg" alt="">
            </div>
            <div class="fl-10" data-aos="fade" data-aos-duration="1200">
                <img src="{{ asset('assets/img/fl-10') }}.svg" alt="">
            </div>
            <div class="book-sc">
                <h3 class="top-cont-1">Online Booking</h3>
                <h2 class="top-cont-2">Table <span>Booking</span></h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="book-inp">
                            <input class="form-control" placeholder="Guest" type="number">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="book-inp">
                            <input class="form-control" placeholder="Date" type="date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="book-inp">
                            <input class="form-control" placeholder="Time" type="time">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="book-inp">
                            <div class="btn-sc text-left">
                                <a href="#">
                                    <button class="btn-prime">
                                        Find A Table
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fl-11" data-aos="fade" data-aos-duration="1200">
                <img src="{{ asset('assets/img/fl-11') }}.svg" alt="">
            </div>
            <div class="fl-12" data-aos="fade" data-aos-duration="1200">
                <img src="{{ asset('assets/img/fl-12') }}.svg" alt="">
            </div>
            <div class="fl-14" data-aos="fade" data-aos-duration="1200">
                <img src="{{ asset('assets/img/fl-14') }}.svg" alt="">
            </div>
        </div>
    </div>
</section>
<section class="sec-3">
    <div class="fl-15" data-aos="fade" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-15') }}.svg" alt="">
    </div>
    <div class="fl-16" data-aos="fade" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-16') }}.svg" alt="">
    </div>
    <div class="fl-17" data-aos="fade" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-17') }}.svg" alt="">
    </div>
    <div class="container">
        <div class="fod-head">
            <h3 class="top-cont-1">food items</h3>
            <h2 class="top-cont-2">popular <span>dishes</span></h2>
        </div>
        <div class="product-sc">
            <div class="row">
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="prod-box">
                        <div class="prod-img">
                            <img src="{{ asset('assets/img/prod-item') }}.svg" alt="">
                        </div>
                        <div class="prod-cont">
                            <a href="#">
                                <h3 class="prod-tittle">Garlic Burger</h3>
                            </a>
                            <p class="prod-disc">It is a long established fact that a reader BBQ food Chicken.</p>
                            <h6 class="prod-price">price :<span>$15.00</span></h6>
                            <a href="#" class="prod-btn">
                                + Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sec-4">
    <div class="fl-21" data-aos="fade-right" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-21') }}.svg" alt="">
    </div>
    <div class="fl-22" data-aos="fade-left" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-22') }}.svg" alt="">
    </div>
    <div class="container">
        <div class="sc-head text-center mb-5">
            <h3 class="top-cont-1" data-aos="fade" data-aos-duration="1200">Why Choose US?</h3>
            <h2 class="top-cont-2 mb-3" data-aos="fade" data-aos-duration="1200">Best Quality <span>item <br>
                    Ingredients</span></h2>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="pd-dt-bx" data-aos="fade" data-aos-duration="1200">
                    <h3>Moist Sour Cream Bread</h3>
                    <p>Shrimp, Squid, Pineapple</p>
                    <h6>price :$15.00</h6>
                </div>

                <div class="pd-dt-bx" data-aos="fade" data-aos-duration="1200">
                    <h3>Moist Sour Cream Bread</h3>
                    <p>Shrimp, Squid, Pineapple</p>
                    <h6>price :$15.00</h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="burger-dt">
                    <img src="{{ asset('assets/img/burger-center') }}.svg" alt="" data-aos="fade"
                        data-aos-duration="1200">

                    <div class="plus-icon plus-1">
                        <img src="{{ asset('assets/img/plus-1') }}.svg" class="" alt="" data-aos="fade-left"
                            data-aos-duration="1200">
                    </div>
                    <div class="plus-icon plus-2">
                        <img src="{{ asset('assets/img/plus-2') }}.svg" class="" alt="" data-aos="fade-right"
                            data-aos-duration="1200">
                    </div>
                    <div class="plus-icon plus-3">
                        <img src="{{ asset('assets/img/plus-1') }}.svg" class="" alt="" data-aos="fade-left"
                            data-aos-duration="1200">
                    </div>
                    <div class="plus-icon plus-4">
                        <img src="{{ asset('assets/img/plus-2') }}.svg" class="" alt="" data-aos="fade-right"
                            data-aos-duration="1200">
                    </div>



                </div>
            </div>
            <div class="col-md-2">
                <div class="pd-dt-bx" data-aos="fade" data-aos-duration="1200">
                    <h3>Moist Sour Cream Bread</h3>
                    <p>Shrimp, Squid, Pineapple</p>
                    <h6>price :$15.00</h6>
                </div>

                <div class="pd-dt-bx" data-aos="fade" data-aos-duration="1200">
                    <h3>Moist Sour Cream Bread</h3>
                    <p>Shrimp, Squid, Pineapple</p>
                    <h6>price :$15.00</h6>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="sec-5">
    <div class="fl-18" data-aos="fade" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-18') }}.svg" alt="">
    </div>
    <div class="fl-19" data-aos="fade" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-19') }}.svg" alt="">
    </div>
    <div class="fl-20" data-aos="fade" data-aos-duration="1200">
        <img src="{{ asset('assets/img/fl-20') }}.svg" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="sc-5-img">
                    <img src="{{ asset('assets/img/food-item') }}.svg" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="sc-5-cont">
                    <h3 class="top-cont-1">delivery</h3>
                    <h2 class="top-cont-2 mb-3">A Moments of <br> Delivered <span>On Right <br>Time & Place</span>
                    </h2>
                    <p>Food Khan is a restaurant, bar and coffee roastery located on a busy corner site in
                        Farringdon's
                        Exmouth Market. With glazed frontage
                        on two sides of the building, overlooking the market and a bustling London inteon.
                    </p>
                    <div class="delivery" data-aos="fade" data-aos-duration="2400">
                        <div class="dl-img">
                            <img src="{{ asset('assets/img/delivery.png') }}" alt="">
                        </div>
                        <div class="dl-cont">
                            <p>delivery order num.</p>
                            <h6>123-58998945</h6>
                        </div>
                        <div class="btn-sc">
                            <a href="#">
                                <button class="btn-prime">
                                    Try It Now
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection