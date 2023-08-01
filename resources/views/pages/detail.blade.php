@extends('layouts.app')

@section('title')
    Detail Page
@endsection

@section('content')
    <!-- Page Content -->
    <div class="page-content page-details">
        <section class="store-breadcrumbs" data-aos="fade-down" data-aos-delay="100">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Product Details
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <section class="store-gallery mb-3" id="gallery">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8" data-aos="zoom-in">
                        <transition name="slide-fade" mode="out-in">
                            <img
                                :src="photos[activePhoto].url"
                                :key="photos[activePhoto].id"
                                class="w-100 main-image"
                                alt=""
                            />
                        </transition>
                    </div>
                    <div class="col-lg-2">
                        <div class="row">
                            <div
                                class="col-3 col-lg-12 mt-2 mt-lg-0"
                                v-for="(photo, index) in photos"
                                :key="photo.id"
                                data-aos="zoom-in"
                                data-aos-delay="100"
                            >
                                <a href="#" @click="changeActive(index)">
                                    <img
                                        :src="photo.url"
                                        class="w-100 thumbnail-image"
                                        :class="{ active: index == activePhoto }"
                                        alt=""
                                    />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="store-details-container" data-aos="fade-up">
            <section class="store-heading">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <h1>{{ $product->name }}</h1>
                            <div class="owner">By {{ $product->user->store_name }}</div>
                            <div class="price">Rp. {{ number_format($product->price) }}</div>
                        </div>
                        <div class="col-lg-2" data-aos="zoom-in">
                            @auth
                                <form action="{{ route('detail-add', $product->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <button type="submit" class="btn btn-success px-4 text-white btn-block mb-3">
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                    class="btn btn-success px-4 text-white btn-block mb-3">
                                    Sign in to Add
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </section>

            <section class="store-description">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>
            </section>
           <section class="store-review">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 mt-3 mb-3">
                <h5>Customer Review</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="container">
                    {{-- <a href="{{ route('reviews.index', $product->slug) }}"></a>
                    <h1>Reviews for {{ $product->name }}</h1>
                    @if ($reviews->count() > 0)
                        <ul>
                            @foreach ($reviews as $review)
                                <li>
                                    <strong>{{ $review->name }}</strong> - Rating: {{ $review->rating }}
                                    <p>{{ $review->comment }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No reviews available for this product.</p>
                    @endif --}}

                    <form action="{{ route('reviews.store', ['slug' => $product->slug]) }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        {{-- <label for="user_id">Your Name:</label> --}}
                        <input type="hidden"name="user_id" id="user_id" >
                        <label for="rating">Rating (1-5):</label>
                        <input class="form-control" type="number" name="rating" id="rating" min="1" max="5" required>
                        <label for="comment">Comment:</label>
                        <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                        <button class="btn btn-success mt-4 px-4 btn-block mb-4" type="submit">Submit Review</button>
                    </form>
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <ul class="list-unstyled">
                            {{-- <li class="media">
                                <img
                                src="/images/icons-testimonial-1.png"
                                alt=""
                                class="mr-3 rounded-circle"
                                /> --}}
                                <div class="media-body">
                                @if (!is_null($product->reviews))
                                            @foreach ($product->reviews as $review)
                                            <li class="media">
                                                <img src="/images/icons-testimonial-2.png" alt="" class="mr-3 rounded-circle">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1">{{ $review->user->name }}</h5>
                                                    Rating: {{ $review->rating }}
                                                    <p>{{ $review->comment }}</p>
                                                </div>
                                            </li>
                                            @endforeach
                                        @endif
                                </div>
                            </ul>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    </div>
    </div>
@endsection

@push('addon-script')
    <script src="/vendor/vue/vue.js"></script>
    <script>
        var gallery = new Vue({
            el: "#gallery",
            mounted() {
                AOS.init();
            },
            data: {
                activePhoto: 0,
                photos: [
                    @foreach ($product->galleries as $gallery)
                        {
                            id: {{ $gallery->id }},
                            url: "{{ Storage::url($gallery->photos) }}",
                        },
                    @endforeach
                ],
            },
            methods: {
                changeActive(id) {
                    this.activePhoto = id;
                },
            },
        });
    </script>
@endpush
