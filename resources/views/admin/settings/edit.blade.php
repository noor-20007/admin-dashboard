@extends('layouts.admin')

@section('title', __('messages.general_settings'))

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.edit_settings') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="langTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="ar-tab" data-bs-toggle="tab" data-bs-target="#ar" type="button" role="tab" aria-controls="ar" aria-selected="true">{{ __('messages.arabic') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="en-tab" data-bs-toggle="tab" data-bs-target="#en" type="button" role="tab" aria-controls="en" aria-selected="false">{{ __('messages.english') }}</button>
                    </li>
                </ul>

                <div class="tab-content mb-4" id="langTabContent">
                    <!-- Arabic Tab -->
                    <div class="tab-pane fade show active" id="ar" role="tabpanel" aria-labelledby="ar-tab">
                        
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.site_name') }} ({{ __('messages.arabic') }})</label>
                            <input type="text" class="form-control" name="site_name_ar" value="{{ old('site_name_ar', $setting->site_name_ar ?? '') }}">
                        </div>

                        <div class="mb-3">
                           <label class="form-label">{{ __('messages.address') }} ({{ __('messages.arabic') }})</label>
                           <input type="text" name="address_ar" class="form-control" value="{{ old('address_ar', $setting->address_ar ?? '') }}">
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.welcome_section') }} (Welcome)</h5>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.title') }}</label>
                            <textarea class="form-control" name="welcome_title_ar" rows="2">{{ old('welcome_title_ar', $setting->welcome_title_ar ?? '') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.button_1_text') }}</label>
                                <input type="text" class="form-control" name="welcome_btn_1_text_ar" value="{{ old('welcome_btn_1_text_ar', $setting->welcome_btn_1_text_ar ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.button_2_text') }}</label>
                                <input type="text" class="form-control" name="welcome_btn_2_text_ar" value="{{ old('welcome_btn_2_text_ar', $setting->welcome_btn_2_text_ar ?? '') }}">
                            </div>
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.about_section') }} (About)</h5>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.title') }}</label>
                            <input type="text" class="form-control" name="about_title_ar" value="{{ old('about_title_ar', $setting->about_title_ar ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.description') }}</label>
                            <textarea class="form-control" name="about_description_ar" rows="3">{{ old('about_description_ar', $setting->about_description_ar ?? '') }}</textarea>
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.services_section') }} (Services)</h5>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.main_title') }}</label>
                            <input type="text" class="form-control" name="services_title_ar" value="{{ old('services_title_ar', $setting->services_title_ar ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.main_description') }}</label>
                            <textarea class="form-control" name="services_description_ar" rows="3">{{ old('services_description_ar', $setting->services_description_ar ?? '') }}</textarea>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">{{ __('messages.subtitle') }}</label>
                            <input type="text" class="form-control" name="services_sub_title_ar" value="{{ old('services_sub_title_ar', $setting->services_sub_title_ar ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.sub_description') }}</label>
                            <textarea class="form-control" name="services_sub_description_ar" rows="3">{{ old('services_sub_description_ar', $setting->services_sub_description_ar ?? '') }}</textarea>
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.contact_us_cta') }}</h5>
                         <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact_title') }}</label>
                            <input type="text" class="form-control" name="contact_title_ar" value="{{ old('contact_title_ar', $setting->contact_title_ar ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact_description') }}</label>
                            <textarea class="form-control" name="contact_description_ar" rows="3">{{ old('contact_description_ar', $setting->contact_description_ar ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.cta_text') }}</label>
                            <textarea class="form-control" name="cta_text_ar" rows="2">{{ old('cta_text_ar', $setting->cta_text_ar ?? '') }}</textarea>
                        </div>
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.cta_button_1_text') }}</label>
                                <input type="text" class="form-control" name="cta_button1_text_ar" value="{{ old('cta_button1_text_ar', $setting->cta_button1_text_ar ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.cta_button_2_text') }}</label>
                                <input type="text" class="form-control" name="cta_button2_text_ar" value="{{ old('cta_button2_text_ar', $setting->cta_button2_text_ar ?? '') }}">
                            </div>
                        </div>

                    </div>

                    <!-- English Tab -->
                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab" dir="ltr">
                        
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.site_name') }} ({{ __('messages.english') }})</label>
                            <input type="text" class="form-control" name="site_name_en" value="{{ old('site_name_en', $setting->site_name_en ?? '') }}">
                        </div>

                        <div class="mb-3">
                           <label class="form-label">{{ __('messages.address') }} ({{ __('messages.english') }})</label>
                           <input type="text" name="address_en" class="form-control" value="{{ old('address_en', $setting->address_en ?? '') }}">
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.welcome_section') }}</h5>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.title') }}</label>
                            <textarea class="form-control" name="welcome_title_en" rows="2">{{ old('welcome_title_en', $setting->welcome_title_en ?? '') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.button_1_text') }}</label>
                                <input type="text" class="form-control" name="welcome_btn_1_text_en" value="{{ old('welcome_btn_1_text_en', $setting->welcome_btn_1_text_en ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.button_2_text') }}</label>
                                <input type="text" class="form-control" name="welcome_btn_2_text_en" value="{{ old('welcome_btn_2_text_en', $setting->welcome_btn_2_text_en ?? '') }}">
                            </div>
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.about_section') }}</h5>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.title') }}</label>
                            <input type="text" class="form-control" name="about_title_en" value="{{ old('about_title_en', $setting->about_title_en ?? '') }}">
                        </div>
                         <div class="mb-3">
                            <label class="form-label">{{ __('messages.description') }}</label>
                            <textarea class="form-control" name="about_description_en" rows="3">{{ old('about_description_en', $setting->about_description_en ?? '') }}</textarea>
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.services_section') }}</h5>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.main_title') }}</label>
                            <input type="text" class="form-control" name="services_title_en" value="{{ old('services_title_en', $setting->services_title_en ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.main_description') }}</label>
                            <textarea class="form-control" name="services_description_en" rows="3">{{ old('services_description_en', $setting->services_description_en ?? '') }}</textarea>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">{{ __('messages.subtitle') }}</label>
                            <input type="text" class="form-control" name="services_sub_title_en" value="{{ old('services_sub_title_en', $setting->services_sub_title_en ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.sub_description') }}</label>
                            <textarea class="form-control" name="services_sub_description_en" rows="3">{{ old('services_sub_description_en', $setting->services_sub_description_en ?? '') }}</textarea>
                        </div>

                        <h5 class="mt-4 text-primary">{{ __('messages.contact_us_cta') }}</h5>
                         <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact_title') }}</label>
                            <input type="text" class="form-control" name="contact_title_en" value="{{ old('contact_title_en', $setting->contact_title_en ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.contact_description') }}</label>
                            <textarea class="form-control" name="contact_description_en" rows="3">{{ old('contact_description_en', $setting->contact_description_en ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.cta_text') }}</label>
                            <textarea class="form-control" name="cta_text_en" rows="2">{{ old('cta_text_en', $setting->cta_text_en ?? '') }}</textarea>
                        </div>
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.cta_button_1_text') }}</label>
                                <input type="text" class="form-control" name="cta_button1_text_en" value="{{ old('cta_button1_text_en', $setting->cta_button1_text_en ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.cta_button_2_text') }}</label>
                                <input type="text" class="form-control" name="cta_button2_text_en" value="{{ old('cta_button2_text_en', $setting->cta_button2_text_en ?? '') }}">
                            </div>
                        </div>

                    </div>
                </div>

                <hr>
                <h4 class="mb-3">{{ __('messages.shared_settings') }}</h4>

                <div class="row">
                     <div class="col-md-6">
                        <div class="mb-3">
                            <label for="welcome_btn_1_link" class="form-label">{{ __('messages.button_1_link') }}</label>
                            <input type="text" class="form-control" id="welcome_btn_1_link" name="welcome_btn_1_link" value="{{ old('welcome_btn_1_link', $setting->welcome_btn_1_link ?? '#') }}">
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                            <label for="welcome_btn_2_link" class="form-label">{{ __('messages.button_2_link') }}</label>
                            <input type="text" class="form-control" id="welcome_btn_2_link" name="welcome_btn_2_link" value="{{ old('welcome_btn_2_link', $setting->welcome_btn_2_link ?? '#') }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.logo') }}</label>
                    @if($setting->logo)
                        <div class="mb-2"><img src="{{ asset($setting->logo) }}" width="100"></div>
                    @endif
                    <input type="file" name="logo" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.favicon') }}</label>
                    @if($setting->favicon)
                        <div class="mb-2"><img src="{{ asset($setting->favicon) }}" width="30"></div>
                    @endif
                    <input type="file" name="favicon" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.top_banner') }}</label>
                    @if($setting->top_banner)
                        <div class="mb-2"><img src="{{ asset($setting->top_banner) }}" width="100%"></div>
                    @endif
                    <input type="file" name="top_banner" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('messages.email') }}</label>
                        <input type="email" name="email" class="form-control" value="{{ $setting->email }}">
                    </div>
                    <div class="col-md-6 mb-3">
                         <label class="form-label">{{ __('messages.phone') }}</label>
                         <input type="text" name="phone" class="form-control" value="{{ $setting->phone }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">LinkedIn Link</label>
                    <input type="text" name="linkedin" class="form-control" value="{{ $setting->linkedin ?? '' }}">
                </div>

                <div class="row border p-3 rounded bg-light">
                     <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.news_ticker_bg_color') }}</label>
                            <input type="color" name="news_ticker_bg_color" class="form-control form-control-color" value="{{ $setting->news_ticker_bg_color ?? '#222222' }}" title="Choose your color">
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.news_ticker_text_color') }}</label>
                            <input type="color" name="news_ticker_text_color" class="form-control form-control-color" value="{{ $setting->news_ticker_text_color ?? '#ffffff' }}" title="Choose your color">
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.primary_color') }}</label>
                            <input type="color" name="primary_color" class="form-control form-control-color" value="{{ $setting->primary_color ?? '#e74c3c' }}" title="Choose your color">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> {{ __('messages.save_settings') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
