@extends('layouts.app')
@section('content')
<section class="sc-1 inner">
   <div class="fl-1">
      <img src="{{ asset('assets/img/18.png') }}" alt="" data-aos="fade-down" data-aos-duration="1000">
   </div>
   <div class="fl-2 animated-2" data-aos="zoom-in" data-aos-duration="1500">
      <img src="{{ asset('assets/img/7.svg') }}" alt="">
   </div>
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="inner-content">
               <h1>Table Bookings</h1>
               <p>HOME / Table Bookings</p>
            </div>
         </div>

      </div>
   </div>


   <div class="fl-5 animated-2" data-aos="zoom-in-up" data-aos-duration="1200">
      <img src="{{ asset('assets/img/2.png') }}" alt="">
   </div>
   <div class="fl-6" data-aos="zoom-in-up" data-aos-duration="1300">
      <img src="{{ asset('assets/img/9.png') }}" alt="">
   </div>
   <div class="fl-26" data-aos="zoom-in-up" data-aos-duration="1300">
      <img src="{{ asset('assets/img/fl-26') }}.svg" alt="">
   </div>
</section>
<section class="sc-2">
   <div class="container">
      <div class="booking-box">
         <div class="fl-9 aos-init aos-animate" data-aos="fade" data-aos-duration="1200">
            <img src="{{ asset('assets/img/fl-9.svg') }}" alt="">
         </div>
         <div class="fl-10 aos-init aos-animate" data-aos="fade" data-aos-duration="1200">
            <img src="{{ asset('assets/img/fl-10.svg') }}" alt="">
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
         <div class="fl-11 aos-init aos-animate" data-aos="fade" data-aos-duration="1200">
            <img src="{{ asset('assets/img/fl-11.svg') }}" alt="">
         </div>
         <div class="fl-12 aos-init aos-animate" data-aos="fade" data-aos-duration="1200">
            <img src="{{ asset('assets/img/fl-12.svg') }}" alt="">
         </div>
         <div class="fl-14 aos-init aos-animate" data-aos="fade" data-aos-duration="1200">
            <img src="{{ asset('assets/img/fl-14.svg') }}" alt="">
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

      <div class="add-to-sc">
         <div class="row"></div>
      </div>

   </div>
</section>



@endsection