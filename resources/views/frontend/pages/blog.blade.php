@extends('frontend.layouts.master')

@section('title','Ecommerce Laravel || Blog Page')

@section('main-content')

    <section class="midium-banner">
        <img style="width:100%" src="https://cms-static.asics.com/media-libraries/86251/file.jpeg"/>
    </section>

    <!-- Breadcrumbs -->
    
   <!-- <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Blog Grid Sidebar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <!-- End Breadcrumbs -->

    <!-- Start Blog Single -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 offset-lg-1 col-12">
            <div class="row">
                <div class="col-lg-10 col-12">
                    <div class="main-sidebar">
                        <div class="single-widget search">
                            <form class="form" method="GET" action="{{route('blog.search')}}">
                                <input type="text" placeholder="Search Blog Here..." name="search">
                                <button class="button" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    
    <section class="blog-single shop-blog grid section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-12">
                <div class="row">
                    @foreach($posts as $post)
                    {{-- {{$post}} --}}
                        <div class="col-lg-4 col-12">
                            <!-- Start Single Blog  -->
                            <div class="shop-single-blog">
                                <img src="{{$post->photo}}" alt="{{$post->photo}}">
                                <div class="content">
                                    <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> {{$post->created_at->format('d M, Y. D')}}
                                        <span class="float-right">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            {{$post->author_info->name ?? 'Anonymous'}}
                                        </span>
                                    </p>
                                    <a href="{{route('blog.detail',$post->slug)}}" class="title">{{$post->title}}</a>
                                    <p>{!! html_entity_decode($post->summary) !!}</p>
                                    <a href="{{route('blog.detail',$post->slug)}}" class="more-btn">Continue Reading</a>
                                </div>
                            </div>
                            <!-- End Single Blog  -->
                        </div>
                    @endforeach
                    <div class="col-12">
                        <!-- Pagination -->
                        {{-- {{$posts->appends($_GET)->links()}} --}}
                        <!--/ End Pagination -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!--/ End Blog Single -->
@endsection
@push('styles')
    <style>
        .pagination{
            display:inline-flex;
        }
    </style>

@endpush
