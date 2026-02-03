@extends('layouts.frontend.app')


@section('content')
<style>    @charset "UTF-8";

    .btn {
      background: var(--bg-primary-500);
      border-radius: 4px;
      font-weight: 500;
      color: var(--txt-white);
      border: 1px solid transparent
    }

    .container-fluid {
      width: 1300px;
      padding: 0 auto !important
    }

    a:hover {
      color: var(--txt-primary-500) !important
    }

    .form-control:focus {
      border: 1px solid var(--border-primary-500)
    }

    .authentic-wrapper {
      margin: 72px 0 0 0;
      height: 100vh
    }

    .login-register-form {
      width: 480px;
      margin: 0 auto;
      gap: 16px
    }

    .login-register-form form {
      width: 100%;
      margin: 20px 0
    }

    .form-fiel-icon {
      position: absolute;
      width: 24px;
      height: 24px;
      left: 14px;
      bottom: 5px
    }

    .login-register-form form .form-control {
      padding: .688rem .75rem .688rem 48px;
      background: #fff
    }

    @media (max-width:1366px) {
      .container-fluid {
        width: 100%;
        padding: 0 40px !important
      }
    }

    @media (max-width:768px) {
      h5 {
        font-size: 20px;
        line-height: 28px
      }

      p {
        font-size: 14px;
        line-height: 20px
      }
    }

    @media (max-width:540px) {
      .container-fluid {
        padding: 0 16px !important
      }
    }

    @media (max-width:480px) {

      .login-register-form {
        width: 100%;
        padding: 0
      }

      .authentic-wrapper {
        margin: 40px 0 0 0
      }
    }

    @media (max-width:425px) {
      .login-register-form .pass-field {
        flex-direction: column;
        gap: 0 !important
      }

      .login-register-form .pass-field .form-group {
        width: 100%
      }
    }

    .btn {
      background: var(--bg-primary-500);
      border-radius: 4px;
      font-weight: 500;
      color: var(--txt-white);
      border: 1px solid transparent
    }

    .form-control {
      padding: .788rem 3rem
    }

    /*!
 * Bootstrap  v5.3.2 (https://getbootstrap.com/)
 * Copyright 2011-2023 The Bootstrap Authors
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/main/LICENSE)
 */
    :root {
      --bs-blue: #0d6efd;
      --bs-indigo: #6610f2;
      --bs-purple: #6f42c1;
      --bs-pink: #d63384;
      --bs-red: #dc3545;
      --bs-orange: #fd7e14;
      --bs-yellow: #ffc107;
      --bs-green: #198754;
      --bs-teal: #20c997;
      --bs-cyan: #0dcaf0;
      --bs-black: #000;
      --bs-white: #fff;
      --bs-gray: #6c757d;
      --bs-gray-dark: #343a40;
      --bs-gray-100: #f8f9fa;
      --bs-gray-200: #e9ecef;
      --bs-gray-300: #dee2e6;
      --bs-gray-400: #ced4da;
      --bs-gray-500: #adb5bd;
      --bs-gray-600: #6c757d;
      --bs-gray-700: #495057;
      --bs-gray-800: #343a40;
      --bs-gray-900: #212529;
      --bs-primary: #0d6efd;
      --bs-secondary: #6c757d;
      --bs-success: #198754;
      --bs-info: #0dcaf0;
      --bs-warning: #ffc107;
      --bs-danger: #dc3545;
      --bs-light: #f8f9fa;
      --bs-dark: #212529;
      --bs-primary-rgb: 13, 110, 253;
      --bs-secondary-rgb: 108, 117, 125;
      --bs-success-rgb: 25, 135, 84;
      --bs-info-rgb: 13, 202, 240;
      --bs-warning-rgb: 255, 193, 7;
      --bs-danger-rgb: 220, 53, 69;
      --bs-light-rgb: 248, 249, 250;
      --bs-dark-rgb: 33, 37, 41;
      --bs-primary-text-emphasis: #052c65;
      --bs-secondary-text-emphasis: #2b2f32;
      --bs-success-text-emphasis: #0a3622;
      --bs-info-text-emphasis: #055160;
      --bs-warning-text-emphasis: #664d03;
      --bs-danger-text-emphasis: #58151c;
      --bs-light-text-emphasis: #495057;
      --bs-dark-text-emphasis: #495057;
      --bs-primary-bg-subtle: #cfe2ff;
      --bs-secondary-bg-subtle: #e2e3e5;
      --bs-success-bg-subtle: #d1e7dd;
      --bs-info-bg-subtle: #cff4fc;
      --bs-warning-bg-subtle: #fff3cd;
      --bs-danger-bg-subtle: #f8d7da;
      --bs-light-bg-subtle: #fcfcfd;
      --bs-dark-bg-subtle: #ced4da;
      --bs-primary-border-subtle: #9ec5fe;
      --bs-secondary-border-subtle: #c4c8cb;
      --bs-success-border-subtle: #a3cfbb;
      --bs-info-border-subtle: #9eeaf9;
      --bs-warning-border-subtle: #ffe69c;
      --bs-danger-border-subtle: #f1aeb5;
      --bs-light-border-subtle: #e9ecef;
      --bs-dark-border-subtle: #adb5bd;
      --bs-white-rgb: 255, 255, 255;
      --bs-black-rgb: 0, 0, 0;
      --bs-font-sans-serif: "Nunito", sans-serif;
      --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
      --bs-body-font-family: var(--bs-font-sans-serif);
      --bs-body-font-size: 0.9rem;
      --bs-body-font-weight: 400;
      --bs-body-line-height: 1.6;
      --bs-body-color: #212529;
      --bs-body-color-rgb: 33, 37, 41;
      --bs-body-bg: #f8fafc;
      --bs-body-bg-rgb: 248, 250, 252;
      --bs-emphasis-color: #000;
      --bs-emphasis-color-rgb: 0, 0, 0;
      --bs-secondary-color: rgba(33, 37, 41, 0.75);
      --bs-secondary-color-rgb: 33, 37, 41;
      --bs-secondary-bg: #e9ecef;
      --bs-secondary-bg-rgb: 233, 236, 239;
      --bs-tertiary-color: rgba(33, 37, 41, 0.5);
      --bs-tertiary-color-rgb: 33, 37, 41;
      --bs-tertiary-bg: #f8f9fa;
      --bs-tertiary-bg-rgb: 248, 249, 250;
      --bs-heading-color: inherit;
      --bs-link-color: #0d6efd;
      --bs-link-color-rgb: 13, 110, 253;
      --bs-link-decoration: underline;
      --bs-link-hover-color: #0a58ca;
      --bs-link-hover-color-rgb: 10, 88, 202;
      --bs-code-color: #d63384;
      --bs-highlight-color: #212529;
      --bs-highlight-bg: #fff3cd;
      --bs-border-width: 1px;
      --bs-border-style: solid;
      --bs-border-color: #dee2e6;
      --bs-border-color-translucent: rgba(0, 0, 0, 0.175);
      --bs-border-radius: 0.375rem;
      --bs-border-radius-sm: 0.25rem;
      --bs-border-radius-lg: 0.5rem;
      --bs-border-radius-xl: 1rem;
      --bs-border-radius-xxl: 2rem;
      --bs-border-radius-2xl: var(--bs-border-radius-xxl);
      --bs-border-radius-pill: 50rem;
      --bs-box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      --bs-box-shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      --bs-box-shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
      --bs-box-shadow-inset: inset 0 1px 2px rgba(0, 0, 0, 0.075);
      --bs-focus-ring-width: 0.25rem;
      --bs-focus-ring-opacity: 0.25;
      --bs-focus-ring-color: rgba(13, 110, 253, 0.25);
      --bs-form-valid-color: #198754;
      --bs-form-valid-border-color: #198754;
      --bs-form-invalid-color: #dc3545;
      --bs-form-invalid-border-color: #dc3545
    }

    *,
    :after,
    :before {
      box-sizing: border-box
    }

    @media (prefers-reduced-motion:no-preference) {
      :root {
        scroll-behavior: smooth
      }
    }

    body {
      margin: 0;
      font-family: var(--bs-body-font-family);
      font-size: var(--bs-body-font-size);
      font-weight: var(--bs-body-font-weight);
      line-height: var(--bs-body-line-height);
      color: var(--bs-body-color);
      text-align: var(--bs-body-text-align);
      background-color: var(--bs-body-bg);
      -webkit-text-size-adjust: 100%;
      -webkit-tap-highlight-color: transparent
    }

    h5 {
      margin-top: 0;
      margin-bottom: .5rem;
      font-weight: 500;
      line-height: 1.2;
      color: var(--bs-heading-color)
    }

    h5 {
      font-size: 1.125rem
    }

    p {
      margin-top: 0;
      margin-bottom: 1rem
    }

    a {
      color: rgba(var(--bs-link-color-rgb), var(--bs-link-opacity, 1));
      /*text-decoration: underline*/
    }

    a:hover {
      --bs-link-color-rgb: var(--bs-link-hover-color-rgb)
    }

    img {
      vertical-align: middle
    }

    button {
      border-radius: 0
    }

    button:focus:not(:focus-visible) {
      outline: 0
    }

    button,
    input,
    textarea {
      margin: 0;
      font-family: inherit;
      font-size: inherit;
      line-height: inherit
    }

    button {
      text-transform: none
    }

    [type=submit],
    button {
      -webkit-appearance: button
    }

    [type=button]:not(:disabled),
    [type=reset]:not(:disabled),
    [type=submit]:not(:disabled),
    button:not(:disabled) {
      cursor: pointer
    }

    ::-moz-focus-inner {
      padding: 0;
      border-style: none
    }

    textarea {
      resize: vertical
    }

    ::-webkit-datetime-edit-day-field,
    ::-webkit-datetime-edit-fields-wrapper,
    ::-webkit-datetime-edit-hour-field,
    ::-webkit-datetime-edit-minute,
    ::-webkit-datetime-edit-month-field,
    ::-webkit-datetime-edit-text,
    ::-webkit-datetime-edit-year-field {
      padding: 0
    }

    ::-webkit-inner-spin-button {
      height: auto
    }

    ::-webkit-search-decoration {
      -webkit-appearance: none
    }

    ::-webkit-color-swatch-wrapper {
      padding: 0
    }

    ::file-selector-button {
      font: inherit;
      -webkit-appearance: button
    }

    .container-fluid {
      --bs-gutter-x: 1.5rem;
      --bs-gutter-y: 0;
      width: 100%;
      padding-right: calc(var(--bs-gutter-x) * .5);
      padding-left: calc(var(--bs-gutter-x) * .5);
      margin-right: auto;
      margin-left: auto
    }

    :root {
      --bs-breakpoint-xs: 0;
      --bs-breakpoint-sm: 576px;
      --bs-breakpoint-md: 768px;
      --bs-breakpoint-lg: 992px;
      --bs-breakpoint-xl: 1200px;
      --bs-breakpoint-xxl: 1400px
    }

    .form-control {
      display: block;
      width: 100%;
      padding: .375rem .75rem;
      font-size: .9rem;
      font-weight: 400;
      line-height: 1.6;
      color: var(--bs-body-color);
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background-color: var(--bs-body-bg);
      background-clip: padding-box;
      border: var(--bs-border-width) solid var(--bs-border-color);
      border-radius: var(--bs-border-radius);
      transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out
    }

    @media (prefers-reduced-motion:reduce) {
      .form-control {
        transition: none
      }
    }

    .form-control[type=file]:not(:disabled):not([readonly]) {
      cursor: pointer
    }

    .form-control:focus {
      color: var(--bs-body-color);
      background-color: var(--bs-body-bg);
      border-color: #86b7fe;
      outline: 0;
      box-shadow: 0 0 0 .25rem #0d6efd40
    }

    .form-control::-webkit-date-and-time-value {
      min-width: 85px;
      height: 1.6em;
      margin: 0
    }

    .form-control::-webkit-datetime-edit {
      display: block;
      padding: 0
    }

    .form-control:disabled {
      background-color: var(--bs-secondary-bg);
      opacity: 1
    }

    .form-control::file-selector-button {
      padding: .375rem .75rem;
      margin: -.375rem -.75rem;
      margin-inline-end: .75rem;
      color: var(--bs-body-color);
      background-color: var(--bs-tertiary-bg);
      pointer-events: none;
      border-color: inherit;
      border-style: solid;
      border-width: 0;
      border-inline-end-width: var(--bs-border-width);
      border-radius: 0;
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out
    }

    @media (prefers-reduced-motion:reduce) {
      .form-control::file-selector-button {
        transition: none
      }
    }

    .form-control:hover:not(:disabled):not([readonly])::file-selector-button {
      background-color: var(--bs-secondary-bg)
    }

    .form-control-sm::file-selector-button {
      padding: .25rem .5rem;
      margin: -.25rem -.5rem;
      margin-inline-end: .5rem
    }

    textarea.form-control {
      min-height: calc(1.6em + .75rem + calc(var(--bs-border-width) * 2))
    }

    .input-group>.form-control:not(:focus).is-valid {
      z-index: 3
    }

    .input-group>.form-control:not(:focus).is-invalid {
      z-index: 4
    }

    .btn {
      --bs-btn-padding-x: 0.75rem;
      --bs-btn-padding-y: 0.375rem;
      --bs-btn-font-size: 0.9rem;
      --bs-btn-font-weight: 400;
      --bs-btn-line-height: 1.6;
      --bs-btn-color: var(--bs-body-color);
      --bs-btn-bg: transparent;
      --bs-btn-border-width: var(--bs-border-width);
      --bs-btn-border-color: transparent;
      --bs-btn-border-radius: var(--bs-border-radius);
      --bs-btn-hover-border-color: transparent;
      --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
      --bs-btn-disabled-opacity: 0.65;
      --bs-btn-focus-box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb), 0.5);
      display: inline-block;
      padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
      font-family: var(--bs-btn-font-family);
      font-size: var(--bs-btn-font-size);
      font-weight: var(--bs-btn-font-weight);
      line-height: var(--bs-btn-line-height);
      color: var(--bs-btn-color);
      text-align: center;
      text-decoration: none;
      vertical-align: middle;
      cursor: pointer;
      -webkit-user-select: none;
      user-select: none;
      border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
      border-radius: var(--bs-btn-border-radius);
      background-color: var(--bs-btn-bg);
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out
    }

    @media (prefers-reduced-motion:reduce) {
      .btn {
        transition: none
      }
    }

    .btn:hover {
      color: var(--bs-btn-hover-color);
      background-color: var(--bs-btn-hover-bg);
      border-color: var(--bs-btn-hover-border-color)
    }

    .btn:focus-visible {
      color: var(--bs-btn-hover-color);
      background-color: var(--bs-btn-hover-bg);
      border-color: var(--bs-btn-hover-border-color);
      outline: 0;
      box-shadow: var(--bs-btn-focus-box-shadow)
    }

    .btn:first-child:active {
      color: var(--bs-btn-active-color);
      background-color: var(--bs-btn-active-bg);
      border-color: var(--bs-btn-active-border-color)
    }

    .btn:first-child:active:focus-visible,
    :not(.btn-check)+.btn:active:focus-visible {
      box-shadow: var(--bs-btn-focus-box-shadow)
    }

    .btn:disabled {
      color: var(--bs-btn-disabled-color);
      pointer-events: none;
      background-color: var(--bs-btn-disabled-bg);
      border-color: var(--bs-btn-disabled-border-color);
      opacity: var(--bs-btn-disabled-opacity)
    }

    .d-flex {
      display: flex !important
    }

    .position-relative {
      position: relative !important
    }

    .w-100 {
      width: 100% !important
    }

    .w-auto {
      width: auto !important
    }

    .h-auto {
      height: auto !important
    }

    .flex-column {
      flex-direction: column !important
    }

    .justify-content-center {
      justify-content: center !important
    }

    .justify-content-between {
      justify-content: space-between !important
    }

    .align-items-center {
      align-items: center !important
    }

    .mt-2 {
      margin-top: .5rem !important
    }

    .mb-3 {
      margin-bottom: 1rem !important
    }

    .gap-2 {
      gap: .5rem !important
    }

    .gap-3 {
      gap: 1rem !important
    }
</style>
  <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Merchant Registration</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Merchant Registration</h6>
					</div>
				</div>
			</div>
		</div>
    </div>
    
    
    
            <!-- login and register form  -->
    <div class="authentic-wrapper">
        <div class="container-fluid">
            <div class="login-register-form d-flex justify-content-center align-items-center flex-column">
                <div class="favcon-icon">
                    <img src="{{ asset('uploads/pageContent/'.$merchantRegistrationPage->image) }}" alt="" style="width:150px">
                </div>
                <h5>Become a Merchant</h5>


                <form action="{{ route('frontend.confirmMerchantRegistration') }}" id="merchantRegistrationForm" role="form" method="POST">

                    <div class="form-group  mb-3">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="company_name" name="company_name" value=""
                                placeholder="Your Business Name">
                            <img src="{{ asset('image/auth/bitscase.svg') }}" alt="" class="form-fiel-icon">
                        </div>


                                            </div>
                    <div class="form-group mb-3">
                        <div class="position-relative">
                            <input type="text" class="form-control " id="name" name="name" value=""
                                placeholder="Your Name">
                            <img src="{{ asset('image/auth/user.svg') }} " alt="" class="form-fiel-icon">
                        </div>
                                            </div>
                    <div class="form-group  mb-3">
                        <div class="position-relative">
                            <textarea cols="1" rows="2"  name="address" id="address" class="form-control "
                                placeholder="Address of Your Pickup Location"></textarea>
                            <img src="{{ asset('image/auth/address.svg') }}" alt="" class="form-fiel-icon">
                        </div>
                                            </div>
                    <div class="form-group  mb-3">
                        <div class="position-relative">
                            <input type="number" class="form-control " id="contact_number" name="contact_number"  value=""
                                placeholder="Phone Number">
                            <img src="{{ asset('image/auth/cell.svg') }}" alt="" class="form-fiel-icon">
                        </div>
                                            </div>

                    <div class="form-group mb-3">
                        <div class="position-relative">
                            <input type="email" class="form-control "id="email" name="email" value=""
                                placeholder="Email">
                            <img src="{{ asset('image/auth/mail_sm.svg') }}" alt="" class="form-fiel-icon">
                        </div>
                                            </div>

                    <div class="w-100  gap-3 pass-field mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-group position-relative ">
                                <div class="position-relative">
                                    <input type="password" class="form-control "id="password" name="password" placeholder="Password"
                                        name="password" required>
                                    <img src="{{ asset('image/auth/password.svg') }}" alt="" class="form-fiel-icon">
                                    
                                </div>
                                
                            </div>
                            <div class="form-group position-relative ">
                                <input type="password" class="form-control " id="confirm_password" name="confirm_password" placeholder="Confirm Password"
                                    name="password_confirmation" required>
                                <img src="{{ asset('image/auth/password.svg') }}" alt="" class="form-fiel-icon">
                            </div>
                        </div>
                        
                    </div>
                    <div>

                    </div>
                    <div class="form-action-btn mt-2">
                        <button class="btn btn-md w-100" type="submit" name="submit" id="registrationBtn" style="
    background: red;
    color: white;
    font-weight: bold;
    font-size: 20px;
">Sign Up</button>
                    </div>
                </form>

                <p class="w-100 d-flex justify-content-center align-items-center gap-2"> Already have an account?
                    <a href="login" class="txt-primary">
                        Login </a> Here
                </p>
            </div>
        </div>
    </div>
    <!-- login and register form  -->
   

   <!-- Contact Area -->
	<!--<div class="contact-section section-padding">-->
	<!--	<div class="container registrationContainer">-->
	<!--		<div class="row">-->
	<!--			<div class="col-lg-4 col-md-12  col-sm-12" style="margin-top: 10px;">-->
 <!--                   @if ($merchantRegistrationPage->count() > 0)-->
 <!--                   <div class="about-left">-->
 <!--                       <img src="{{ asset('uploads/pageContent/'.$merchantRegistrationPage->image) }}" style="height: 600px; width:100%;" alt="">-->
 <!--                   </div>-->
 <!--                   @endif-->
	<!--			</div>-->
	<!--			<div class="col-lg-8 col-md-12  col-sm-12">-->
 <!--                   <div class="contact-form">-->
 <!--                       <div class="col-sm-12 text-center" >-->
 <!--                           <h3>Merchant Registration Form</h3>-->
 <!--                       </div>-->
 <!--                       <form name="contact-form" id="merchantRegistrationForm" action="{{ route('frontend.confirmMerchantRegistration') }}" method="POST">-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="company_name" class="col-sm-3 col-form-label">-->
 <!--                                   Company Name : <span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" >-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="name" class="col-sm-3 col-form-label">-->
 <!--                                   Name : <span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <input type="text" class="form-control" id="name" name="name" placeholder="Merchant Name" >-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="address" class="col-sm-3 col-form-label">-->
 <!--                                   Full Address : <span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <textarea class="form-control" name="address" id="address" cols="30" rows="3"  placeholder="Merchant Full Address" ></textarea>-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="business_address" class="col-sm-3 col-form-label">-->
 <!--                                   Business Address : <span style="font-weight: bold; color: red;"></span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <textarea class="form-control" name="business_address" id="business_address" cols="30" rows="3"  placeholder="Merchant Business Address"></textarea>-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="district_id" class="col-sm-3 col-form-label">-->
 <!--                                   Dist/Area : <span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9 row" style="padding-right: 0px;">-->
 <!--                                   <div class="col-md-6">-->
 <!--                                       <select name="district_id" id="district_id" class="form-control select2" style="width: 100%" >-->
 <!--                                           <option value="0">Select District</option>-->
 <!--                                           @if ($districts->count() > 0)-->
 <!--                                               @foreach ($districts as $district)-->
 <!--                                               <option value="{{ $district->id }}">{{ $district->name }}</option>-->
 <!--                                               @endforeach-->
 <!--                                           @endif-->
 <!--                                       </select>-->
 <!--                                   </div>-->
 <!--                                   {{-- <div class="col-md-4">-->
 <!--                                       <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >-->
 <!--                                           <option value="0">Select Upazila</option>-->
 <!--                                       </select>-->
 <!--                                   </div> --}}-->
 <!--                                   <div class="col-md-6">-->
 <!--                                       <select name="area_id" id="area_id" class="form-control select2" style="width: 100%" >-->
 <!--                                           <option value="0">Select Area</option>-->
 <!--                                       </select>-->
 <!--                                   </div>-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="contact_number" class="col-sm-3 col-form-label">-->
 <!--                                   Contact Number : <span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <div class="input-group mb-2">-->
 <!--                                       <div class="input-group-prepend">-->
 <!--                                         <div class="input-group-text">+88</div>-->
 <!--                                       </div>-->
 <!--                                       <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Merchant Contact Number">-->
 <!--                                   </div>-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="fb_url" class="col-sm-3 col-form-label">-->
 <!--                                   Facebook Business Page:  -->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <div class="input-group mb-2">-->
 <!--                                       <div class="input-group-prepend">-->
 <!--                                         <div class="input-group-text">http://</div>-->
 <!--                                       </div>-->
 <!--                                       <input type="text" class="form-control" id="fb_url" name="fb_url" placeholder=" Facebook Business Page Url" >-->
 <!--                                   </div>-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="web_url" class="col-sm-3 col-form-label">-->
 <!--                                   Website : <span style="font-weight: bold; color: red;"></span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <div class="input-group mb-2">-->
 <!--                                       <div class="input-group-prepend">-->
 <!--                                         <div class="input-group-text">http://</div>-->
 <!--                                       </div>-->
 <!--                                       <input type="text" class="form-control" id="web_url" name="web_url" placeholder="Merchant Website Url" >-->
 <!--                                   </div>-->
 <!--                               </div>-->
 <!--                           </div>-->
                            
                            
                            
                            <!--<div class="form-group row">-->
                            <!--    <label for="bank_account_name" class="col-sm-3 col-form-label">-->
                            <!--        Bank Info :-->
                            <!--    </label>-->
                            <!--    <div class="col-sm-9 row" style="padding-right: 0px;">-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bank_account_name" name="bank_account_name" placeholder="Account Name" >-->
                            <!--        </div>-->

                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bank_account_no" name="bank_account_no" placeholder="Account Number" >-->
                            <!--        </div>-->

                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" >-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="bkash_number" class="col-sm-3 col-form-label">-->
                            <!--        BKash/ Nagad/Rocket-->
                            <!--    </label>-->
                            <!--    <div class="col-sm-9 row" style="padding-right: 0px;">-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bkash_number" name="bkash_number" placeholder="BKash Number" >-->
                            <!--        </div>-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="nagad_number" name="nagad_number" placeholder="Nagad Number" >-->
                            <!--        </div>-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="rocket_name" name="rocket_name" placeholder="Rocket Number" >-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="nid_no" class="col-sm-3 col-form-label">-->
                            <!--        NID No :-->
                            <!--    </label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <input type="text" class="form-control" id="nid_no" name="nid_no" placeholder="NID NO" >-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="nid_card" class="col-sm-3 col-form-label">Upload NID Card (Both Side) </label>-->
                            <!--    <div class="col-md-9">-->
                            <!--        <input type="file" name="nid_card" id="nid_card"  class="form-control" accept="image/*">-->
                            <!--        <div id="preview_file" style="margin-top: 10px;"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="trade_license" class="col-sm-3 col-form-label">Trade License </label>-->
                            <!--    <div class="col-md-9">-->
                            <!--        <input type="file" name="trade_license" id="trade_license"  class="form-control" accept="image/*">-->
                            <!--        <div id="preview_file" style="margin-top: 10px;"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="tin_certificate" class="col-sm-3 col-form-label">TIN Certificate </label>-->
                            <!--    <div class="col-md-9">-->
                            <!--        <input type="file" name="tin_certificate" id="tin_certificate"  class="form-control" accept="image/*">-->
                            <!--        <div id="preview_file" style="margin-top: 10px;"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            
                            
                            
 <!--                           <div class="form-group row">-->
 <!--                               <label for="email" class="col-sm-3 col-form-label">-->
 <!--                                   Email: <span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <input type="email" class="form-control" id="email" name="email" placeholder="Merchant Email " >-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="password" class="col-sm-3 col-form-label">-->
 <!--                                   Password: <span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <input type="password" class="form-control" id="password" name="password" placeholder="Password" >-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <label for="confirm_password" class="col-sm-3 col-form-label">-->
 <!--                                   Confirm Password:<span style="font-weight: bold; color: red;">*</span>-->
 <!--                               </label>-->
 <!--                               <div class="col-sm-9">-->
 <!--                                   <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" >-->
 <!--                               </div>-->
 <!--                           </div>-->
 <!--                           <div class="form-group row">-->
 <!--                               <div class="col-sm-12 text-center">-->
 <!--                                   <button class="btn btn-primary submit" type="submit" name="submit" id="registrationBtn" >-->
 <!--                                       Submit-->
 <!--                                   </button>-->
 <!--                               </div>-->
 <!--                           </div>-->
	<!--					</form>-->
	<!--				</div>-->
	<!--			</div>-->
	<!--		</div>-->
	<!--	</div>-->
	<!--</div>-->


@endsection

@push('style_css')
    <style>
        #contactForm{
            font-size: 15px;
        }
        .contact-form{
            background-color: rgb(236, 236, 236);
            margin-top: 10px;
            padding: 16px 5px 16px 10px;
        }

        .contact-form input, .contact-form textarea{
            margin-bottom: 0px;
        }
        .form-control{
            padding: 8px 8px;
            font-size: 0.79rem;
            line-height: 1;
            border: 1px solid #c1c2c4;
        }
        .select2-results__option{
            padding: 1px;
        }
        .select2-results__options{
            font-size: 14px;
        }
        .btn-primary.submit:hover{
            background-color: #61B334;
            color: #fffdfd;
        }
        .btn-primary.submit{
            padding : 6px 16px;
        }

        @media (min-width:1200px) {
            .registrationContainer {
                max-width: 1300px !important;
            }
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered{
            font-size : 12px;
        }
    </style>
    <!--<link rel="stylesheet" href="{{ asset('assets/rescss/style.css') }}">-->
    <!--<link rel="stylesheet" href="{{ asset('assets/rescss/override.css') }}">-->
    <!--<link rel="stylesheet" href="{{ asset('assets/rescss/app-041e359a.css') }}">-->
    
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">


    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{--Sweet Alert--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.css" rel="stylesheet" type="text/css">

@endpush

 @push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.js"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function(){
            if ($(".select2").length > 0) $('.select2').select2();

            $('#district_id').on('change', function(){
                var district_id   = $("#district_id option:selected").val();
                $("#area_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache     : false,
                    type      : "POST",
                    dataType  : "JSON",
                    data      : {
                            district_id: district_id,
                            _token : "{{ csrf_token() }}"
                        },
                    error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    // url       : "{{ route('upazila.districtOption') }}",
                    url       : "{{ route('area.districtWiseAreaOption') }}",
                    success   : function(response){
                        // $("#upazila_id").html(response.option).attr('disabled', false);
                        $("#area_id").html(response.option).attr('disabled', false);
                    }
                })
            });


            $('#merchantRegistrationForm').on('submit',function(e){
                e.preventDefault();

                var district_id         = $("#district_id option:selected").val();
                var upazila_id          = $("#upazila_id option:selected").val();
                var area_id             = $("#area_id option:selected").val();
                var password            = $("#password").val();
                var confirm_password    = $("#confirm_password").val();
                var fb_url              = $("#fb_url").val();
                var company_name        = $("#company_name").val();
                var name                = $("#name").val();
                var address             = $("#address").val();
                var address             = $("#address").val();
                var email               = $("#email").val();
                var contact_number      = $("#contact_number").val();

                if(company_name == ''){
                    toastMessage('Please Enter Company Name', 'Error', 'error');
                    return false;
                }
                if(name == ''){
                    toastMessage('Please Enter Merchant Name', 'Error', 'error');
                    return false;
                }
                if(address == ''){
                    toastMessage('Please Enter Merchant Address', 'Error', 'error');
                    return false;
                }
                // if(district_id == '0'){
                //     toastMessage('Please Select District', 'Error', 'error');
                //     return false;
                // }
                // if(upazila_id == '0'){
                //     toastMessage('Please Select Upazila', 'Error', 'error');
                //     return false;
                // }

                // console.log(contact_number.length);
                if(contact_number.length != 11){
                    toastMessage('Please Enter Merchant Contact Number', 'Error', 'error');
                    return false;
                }

                // if(fb_url == ''){
                //     toastMessage('Please Enter Facebook Url', 'Error', 'error');
                //     return false;
                // }
                if(email == ''){
                    toastMessage('Please Enter Merchant Email', 'Error', 'error');
                    return false;
                }
                if(password.length < 5){
                    toastMessage('Password Length Must be 5 Digit', 'Error', 'error');
                    return false;
                }
                if(password != confirm_password){
                    toastMessage("Password and Confirm Password Does Not Match ", 'Error', 'error');
                    return false;
                }





                $.ajax({
                    cache       : false,
                    type        : "POST",
                    dataType    : "JSON",
                    headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data        : new FormData(this),
                    contentType: false,
                    processData: false,
                    error     : function(xhr){
                        console.log(xhr);
                    },
                    url       : this.action,
                    success   : function(response){
                        if(response.success){
                            
                          ///  window.location = "{{ route('frontend.otp_merchant_registration_login') }}";
                            $("#merchantRegistrationForm")[0].reset();
                        //    toastMessage(response.success, 'Success', 'success');
                            Swal.fire({
                                type: response.type,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: true,
                                timer: 4000
                            });
                            
                            
                            setTimeout(function(){
                                window.location = "{{ route('frontend.otp_merchant_registration_login') }}";
                              },4000);
                              
                            {{--setTimeout(function(){--}}
                                {{--window.location = "{{ route('frontend.otp_merchant_registration_check') }}";--}}
                            {{--},5000);--}}
                        }
                        else{
                            var getError = response.error;
                            var message = "";
                            if(getError.company_name){
                                message = getError.company_name[0];
                            }
                            if(getError.name){
                                message = getError.name[0];
                            }
                            if(getError.address){
                                message = getError.address[0];
                            }
                            if(getError.district_id){
                                message = getError.district_id[0];
                            }
                            if(getError.area_id){
                                message = getError.area_id[0];
                            }
                            if(getError.contact_number){
                                message = getError.contact_number[0];
                            }
                            if(getError.email){
                                message = getError.email[0];
                            }
                            if(getError.password){
                                message = getError.password[0];
                            }
                            if(getError.confirm_password){
                                message = getError.confirm_password[0];
                            }
                            if(getError.fb_url){
                                message = getError.fb_url[0];
                            }
                            toastMessage(message, 'Error', 'error');
                        }
                    }
                });
            });



        });
    </script>
 @endpush
