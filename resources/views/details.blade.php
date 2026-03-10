@extends('layouts.app')
@section('content')
    <style>
        .filled-heart {
            color: orange;
        }
        /* Low stock alert box – visible in light and dark mode */
        .low-stock-box {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .low-stock-box--yellow {
            background-color: #fef9e7;
            border-color: #d4a017;
            color: #7d5a00;
        }
        .low-stock-box--red {
            background-color: #fde8e8;
            border-color: #c92a2a;
            color: #a51111;
        }
        html[data-theme="dark"] .low-stock-box--yellow {
            background-color: rgba(212, 160, 23, 0.2);
            border-color: #d4a017;
            color: #f0c14b;
        }
        html[data-theme="dark"] .low-stock-box--red {
            background-color: rgba(201, 42, 42, 0.25);
            border-color: #e03131;
            color: #ff6b6b;
        }
    </style>
    <main class="pt-90">
        <div class="mb-md-1 pb-md-3"></div>
        <section class="product-single container">
            <div class="row">
                @php
                    $productImage = $product->image ? asset('uploads/products') . '/' . $product->image : '';
                    $productThumb = $product->image ? asset('uploads/products/thumbnails') . '/' . $product->image : '';
                    $productGallery = $product->images ? array_filter(array_map('trim', explode(',', $product->images))) : [];
                @endphp
                <div class="col-lg-7">
                    <div class="product-single__media" data-media-type="vertical-thumbnail" id="product-media">
                        <div class="product-single__image">
                            <div class="swiper-container" id="product-swiper-main">
                                <div class="swiper-wrapper" id="product-swiper-wrapper">
                                    {{-- Slides filled by JS when variant changes; initial state from product --}}
                                    <div class="swiper-slide product-single__image-item">
                                        <img loading="lazy" class="h-auto" id="main-img" width="674" height="674" alt=""
                                            src="{{ $productImage }}" />
                                        <a data-fancybox="gallery" href="{{ $productImage }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_zoom" /></svg>
                                        </a>
                                    </div>
                                    @foreach ($productGallery as $gimg)
                                        <div class="swiper-slide product-single__image-item">
                                            <img loading="lazy" class="h-auto" src="{{ asset('uploads/products') }}/{{ $gimg }}" width="674" height="674" alt="" />
                                            <a data-fancybox="gallery" href="{{ asset('uploads/products') }}/{{ $gimg }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_zoom" /></svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_sm" /></svg></div>
                                <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></div>
                            </div>
                        </div>
                        <div class="product-single__thumbnail">
                            <div class="swiper-container" id="product-swiper-thumb">
                                <div class="swiper-wrapper" id="product-swiper-thumb-wrapper">
                                    <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" id="thumb-img" src="{{ $productThumb }}" width="104" height="104" alt="" /></div>
                                    @foreach ($productGallery as $gimg)
                                        <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="{{ asset('uploads/products/thumbnails') }}/{{ $gimg }}" width="104" height="104" alt="" /></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex justify-content-between mb-4 pb-md-2">
                        <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
                        </div>

                        <div
                            class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                            <a href="#" class="text-uppercase fw-medium"><svg width="10" height="10"
                                    viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_prev_md" />
                                </svg><span class="menu-link menu-link_us-s">Prev</span></a>
                            <a href="#" class="text-uppercase fw-medium"><span
                                    class="menu-link menu-link_us-s">Next</span><svg width="10" height="10"
                                    viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_next_md" />
                                </svg></a>
                        </div>
                    </div>
                    <h1 class="product-single__name">{{ $product->name }}</h1>
                    @php
                        $reviewCount = $product->reviews->count();
                        $avgRating = $reviewCount > 0 ? round($product->reviews->avg('rating')) : 0;
                    @endphp
                    <div class="product-single__rating">
                        <div class="reviews-group d-flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"
                                    style="fill: {{ $i <= $avgRating ? '#ffc107' : '#ccc' }}">
                                    <use href="#icon_star" />
                                </svg>
                            @endfor
                        </div>
                        <span class="reviews-note text-lowercase text-secondary ms-1">
                            {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
                        </span>
                    </div>
                    <div class="product-single__price">
                        <span class="current-price">
                            @if ($product->sale_price)
                                <s>£{{ $product->regular_price }} </s> £{{ $product->sale_price }}
                            @else
                                £{{ $product->regular_price }}
                            @endif
                        </span>
                    </div>
                    <div class="product-single__short-desc">
                        <p>{{ $product->short_description }}</p>
                    </div>

                    @php
                        // if there are variants, consider product as in stock if any variant has quantity >0
                        $hasInStockVariant = $product->variants->contains(function($v){ return $v->quantity > 0; });
                        $stockQty = $product->variants->count() ? $product->variants->sum('quantity') : $product->quantity;
                        $showLowStock = $stockQty > 0 && $stockQty < 10;
                        $lowStockLevel = $stockQty > 0 && $stockQty <= 5 ? 'red' : 'yellow';
                    @endphp
                    @if($product->quantity > 0 || $hasInStockVariant)

                    <div id="low-stock-msg" class="low-stock-box low-stock-box--{{ $lowStockLevel }}" style="{{ $showLowStock ? '' : 'display:none;' }}" role="alert">Low stock – only <span id="low-stock-qty">{{ $stockQty }}</span> left.</div>

                    @php
                        $hasVariants = $product->variants->isNotEmpty();
                        $variantSizes = $hasVariants
                            ? array_values(array_unique(array_filter(array_map(function ($s) { return trim((string) $s); }, $product->variants->pluck('size')->all()))))
                            : (is_array($product->sizes ?? null) ? $product->sizes : []);
                        $variantColors = $hasVariants
                            ? array_values(array_unique(array_filter(array_map(function ($c) { return trim((string) $c); }, $product->variants->pluck('color')->all()))))
                            : (is_array($product->colors ?? null) ? $product->colors : []);
                        $bySize = [];
                        $byColor = [];
                        if ($hasVariants) {
                            foreach ($product->variants as $v) {
                                $s = $v->size ? trim((string) $v->size) : '';
                                $c = $v->color ? trim((string) $v->color) : '';
                                if ($s !== '') {
                                    if (!isset($bySize[$s])) $bySize[$s] = [];
                                    if ($c !== '' && !in_array($c, $bySize[$s])) $bySize[$s][] = $c;
                                }
                                if ($c !== '') {
                                    if (!isset($byColor[$c])) $byColor[$c] = [];
                                    if ($s !== '' && !in_array($s, $byColor[$c])) $byColor[$c][] = $s;
                                }
                            }
                        }
                        $uploadsBase = asset('uploads/products');
                        $uploadsThumb = asset('uploads/products/thumbnails');
                    @endphp
                    <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                        @csrf
                        <script>
                            window.productVariants = {!! json_encode($product->variants->map(function($v) use ($uploadsBase, $uploadsThumb) {
                                $gallery = $v->gallery_array ?? [];
                                $galleryUrls = array_map(function($f) use ($uploadsBase) { return $uploadsBase . '/' . $f; }, $gallery);
                                $galleryThumbs = array_map(function($f) use ($uploadsThumb) { return $uploadsThumb . '/' . $f; }, $gallery);
                                $first = count($gallery) ? $gallery[0] : null;
                                return [
                                    'id' => $v->id,
                                    'size' => $v->size,
                                    'color' => $v->color,
                                    'quantity' => (int) $v->quantity,
                                    'sku' => $v->SKU,
                                    'image' => $first ? $uploadsBase . '/' . $first : null,
                                    'thumb' => $first ? $uploadsThumb . '/' . $first : null,
                                    'gallery' => $galleryUrls,
                                    'galleryThumbs' => $galleryThumbs
                                ];
                            })) !!};
                            window.productFallback = {
                                image: "{{ $productImage }}",
                                thumb: "{{ $productThumb }}",
                                gallery: {!! json_encode(array_map(function($f) use ($uploadsBase) { return $uploadsBase . '/' . $f; }, $productGallery)) !!},
                                galleryThumbs: {!! json_encode(array_map(function($f) use ($uploadsThumb) { return $uploadsThumb . '/' . $f; }, $productGallery)) !!}
                            };
                            window.variantBySize = {!! json_encode($bySize) !!};
                            window.variantByColor = {!! json_encode($byColor) !!};
                            window.allVariantSizes = {!! json_encode($variantSizes) !!};
                            window.allVariantColors = {!! json_encode($variantColors) !!};
                            window.productTotalStock = {{ (int) $stockQty }};
                        </script>
                        <div class="product-single__addtocart">

                            <div class="qty-control position-relative">
                                <input type="number" name="quantity" value="1" min="1"
                                    class="qty-control__number text-center">
                                <div class="qty-control__reduce">-</div>
                                <div class="qty-control__increase">+</div>
                            </div>

                            <input type="hidden" name="id" value="{{ $product->id }}" />

                            {{-- Size: when variants exist, color options depend on size (and vice versa) --}}
                            @if (count($variantSizes) > 0)
                                <div class="mb-3">
                                    <label class="form-label fw-medium mb-1">Size <span class="text-danger">*</span></label>
                                    <select name="size" id="variant-size" class="form-select form-select-lg shadow-sm" {{ $hasVariants ? 'required' : '' }}>
                                        <option value="">Select size</option>
                                        @foreach ($variantSizes as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Color: options filled by JS from selected size only (no options in HTML so only valid combos possible) --}}
                            @if (count($variantColors) > 0)
                                <div class="mb-3">
                                    <label class="form-label fw-medium mb-1">Color <span class="text-danger">*</span></label>
                                    <select name="color" id="variant-color" class="form-select form-select-lg shadow-sm" {{ $hasVariants ? 'required' : '' }}>
                                        <option value="">Select size first</option>
                                    </select>
                                </div>
                            @endif

                            <input type="hidden" name="name" value="{{ $product->name }}" />
                            <input type="hidden" name="price"
                                value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />

                            <button type="submit" class="btn btn-primary btn-addtocart">
                                Add to Cart
                            </button>
                        </div>
                    </form>

                    <script>
                        function getSelectedVariant() {
                            if (!window.productVariants || window.productVariants.length === 0) return null;
                            var size = ($('#variant-size').length ? $('#variant-size').val() : '') || '';
                            var color = ($('#variant-color').length ? $('#variant-color').val() : '') || '';
                            if (!size || !color) return null;
                            for (var i = 0; i < window.productVariants.length; i++) {
                                var v = window.productVariants[i];
                                if (v.size === size && v.color === color) return v;
                            }
                            return null;
                        }
                        function filterColorBySize() {
                            var colorSelect = document.getElementById('variant-color');
                            if (!colorSelect) return;
                            var sizeSelect = document.getElementById('variant-size');
                            var size = sizeSelect ? (sizeSelect.value || '').trim() : '';
                            // Use PHP-built variantBySize (size -> [colors]) as source of truth
                            var opts = (size && window.variantBySize && window.variantBySize[size])
                                ? window.variantBySize[size]
                                : [];
                            var currentColor = (colorSelect.value || '').trim();
                            colorSelect.innerHTML = '';
                            if (opts.length === 0) {
                                colorSelect.appendChild(new Option('Select size first', '', true, true));
                                return;
                            }
                            colorSelect.appendChild(new Option('Select colour', '', true, currentColor === ''));
                            for (var i = 0; i < opts.length; i++) {
                                var col = opts[i];
                                colorSelect.appendChild(new Option(col, col, false, currentColor === col));
                            }
                        }
                        function escapeHtml(str) {
                            if (!str) return '';
                            var div = document.createElement('div');
                            div.appendChild(document.createTextNode(str));
                            return div.innerHTML;
                        }
                        function filterSizeByColor() {
                            if (!$('#variant-size').length) return;
                            var color = ($('#variant-color').val() || '').trim();
                            var $size = $('#variant-size');
                            var currentSize = $size.val();
                            var opts = color && window.variantByColor && window.variantByColor[color]
                                ? window.variantByColor[color]
                                : (window.allVariantSizes || []);
                            $size.empty().append('<option value="">Select size</option>');
                            for (var j = 0; j < opts.length; j++) {
                                $size.append('<option value="' + opts[j] + '">' + opts[j] + '</option>');
                            }
                            if (opts.indexOf(currentSize) !== -1) $size.val(currentSize);
                            else $size.val('');
                        }
                        function switchVariantMedia(variant) {
                            var fallback = window.productFallback;
                            // First image is always the product (original) image, same as shop thumbnail; then variant gallery (or product gallery when no variant selected)
                            var mainImg = fallback.image;
                            var mainThumb = fallback.thumb;
                            var gallery = [];
                            var galleryThumbs = [];
                            if (variant && variant.gallery && variant.gallery.length) {
                                gallery = variant.gallery;
                                galleryThumbs = (variant.galleryThumbs && variant.galleryThumbs.length) ? variant.galleryThumbs : variant.gallery;
                            } else {
                                gallery = fallback.gallery || [];
                                galleryThumbs = fallback.galleryThumbs || [];
                            }
                            var slides = [];
                            slides.push('<div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" id="main-img" width="674" height="674" alt="" src="' + mainImg + '" /><a data-fancybox="gallery" href="' + mainImg + '" data-bs-toggle="tooltip" title="Zoom"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_zoom" /></svg></a></div>');
                            for (var j = 0; j < gallery.length; j++) {
                                slides.push('<div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="' + gallery[j] + '" width="674" height="674" alt="" /><a data-fancybox="gallery" href="' + gallery[j] + '"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_zoom" /></svg></a></div>');
                            }
                            var thumbSlides = ['<div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" id="thumb-img" src="' + mainThumb + '" width="104" height="104" alt="" /></div>'];
                            for (var k = 0; k < galleryThumbs.length; k++) {
                                thumbSlides.push('<div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="' + galleryThumbs[k] + '" width="104" height="104" alt="" /></div>');
                            }
                            $('#product-swiper-wrapper').html(slides.join(''));
                            $('#product-swiper-thumb-wrapper').html(thumbSlides.join(''));
                            if (typeof Swiper !== 'undefined' && $('#product-swiper-main').data('swiper')) {
                                $('#product-swiper-main').data('swiper').update();
                                $('#product-swiper-thumb').data('swiper').update();
                            }
                        }
                        function updateVariantStock() {
                            filterColorBySize();
                            filterSizeByColor();
                            var variant = getSelectedVariant();
                            if (window.productVariants && window.productVariants.length > 0) {
                                if (!variant) {
                                    switchVariantMedia(null);
                                    $('.btn-addtocart').prop('disabled', true).addClass('btn-outofstock').text('Select size and colour');
                                    $('#sku-display').text('—');
                                    if (window.productTotalStock > 0 && window.productTotalStock < 10) {
                                        $('#low-stock-qty').text(window.productTotalStock);
                                        $('#low-stock-msg').removeClass('low-stock-box--red low-stock-box--yellow').addClass(window.productTotalStock <= 5 ? 'low-stock-box--red' : 'low-stock-box--yellow');
                                        $('#low-stock-msg').show();
                                    } else {
                                        $('#low-stock-msg').hide();
                                    }
                                    return;
                                }
                                switchVariantMedia(variant);
                                $('#sku-display').text(variant.sku || '—');
                                var qty = variant.quantity;
                                if (qty <= 0) {
                                    $('.btn-addtocart').prop('disabled', true).addClass('btn-outofstock').text('Out of stock');
                                    $('#low-stock-msg').hide();
                                } else {
                                    $('.btn-addtocart').prop('disabled', false).removeClass('btn-outofstock').text('Add to Cart');
                                    $('input[name=quantity]').attr('max', qty);
                                    if (qty < 10) {
                                        $('#low-stock-qty').text(qty);
                                        $('#low-stock-msg').removeClass('low-stock-box--red low-stock-box--yellow').addClass(qty <= 5 ? 'low-stock-box--red' : 'low-stock-box--yellow');
                                        $('#low-stock-msg').show();
                                    } else {
                                        $('#low-stock-msg').hide();
                                    }
                                }
                                return;
                            }
                            $('#sku-display').text('{{ $product->SKU }}');
                            switchVariantMedia(null);
                        }
                        (function initVariantDropdowns() {
                            var sizeEl = document.getElementById('variant-size');
                            var colorEl = document.getElementById('variant-color');
                            if (sizeEl) {
                                sizeEl.addEventListener('change', function() { filterColorBySize(); updateVariantStock(); });
                            }
                            if (colorEl) {
                                colorEl.addEventListener('change', function() { filterSizeByColor(); updateVariantStock(); });
                            }
                            if (sizeEl || colorEl) {
                                if (document.readyState === 'loading') {
                                    document.addEventListener('DOMContentLoaded', function() { filterColorBySize(); updateVariantStock(); });
                                } else {
                                    filterColorBySize();
                                    updateVariantStock();
                                }
                            }
                        })();
                        $(document).ready(function() {
                            if ($('#variant-size').length || $('#variant-color').length) {
                                $('#variant-size').off('change').on('change', function() { filterColorBySize(); updateVariantStock(); });
                                $('#variant-color').off('change').on('change', function() { filterSizeByColor(); updateVariantStock(); });
                            }
                            updateVariantStock();
                        });
                    </script>

                    @else

                        <div class="out-of-stock-box">
                            OUT OF STOCK
                        </div>
                    @endif
                    <div class="product-single__addtolinks">
                        @if (Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
                            <form method="POST"
                                action="{{ route('wishlist.item.remove', ['rowId' => Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId]) }}"
                                id="frm-remove-item">
                                @csrf
                                @method('DELETE')
                                <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist filled-heart"
                                    onclick="document.getElementById('frm-remove-item').submit();"><svg width="16"
                                        height="16" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_heart" />
                                    </svg><span>Remove from Wishlist</span></a>
                            </form>
                        @else
                            <form method="POST" action="{{ route('wishlist.add') }}" id="wishlist-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}" />
                                <input type="hidden" name="name" value="{{ $product->name }}" />
                                <input type="hidden" name="quantity" value="1" />
                                <input type="hidden" name="price"
                                    value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />
                                <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist"
                                    onclick="document.getElementById('wishlist-form').submit();"><svg width="16"
                                        height="16" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_heart" />
                                    </svg><span>Add to Wishlist</span></a>
                            </form>
                        @endif


                        <share-button class="share-button">
                            <button
                                class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center">
                                <svg width="16" height="19" viewBox="0 0 16 19" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_sharing" />
                                </svg>
                                <span>Share</span>
                            </button>
                            <details id="Details-share-template__main" class="m-1 xl:m-1.5" hidden="">
                                <summary class="btn-solid m-1 xl:m-1.5 pt-3.5 pb-3 px-5">+</summary>
                                <div id="Article-share-template__main"
                                    class="share-button__fallback flex items-center absolute top-full left-0 w-full px-2 py-4 bg-container shadow-theme border-t z-10">
                                    <div class="field grow mr-4">
                                        <label class="field__label sr-only" for="url">Link</label>
                                        <input type="text" class="field__input w-full" id="url"
                                            value="https://uomo-crystal.myshopify.com/blogs/news/go-to-wellness-tips-for-mental-health"
                                            placeholder="Link" onclick="this.select();" readonly="">
                                    </div>
                                    <button class="share-button__copy no-js-hidden">
                                        <svg class="icon icon-clipboard inline-block mr-1" width="11" height="13"
                                            fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            focusable="false" viewBox="0 0 11 13">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M2 1a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1V1H2zM1 2a1 1 0 00-1 1v9a1 1 0 001 1h7a1 1 0 001-1V3a1 1 0 00-1-1H1zm0 10V3h7v9H1z"
                                                fill="currentColor"></path>
                                        </svg>
                                        <span class="sr-only">Copy link</span>
                                    </button>
                                </div>
                            </details>
                        </share-button>
                        <script src="js/details-disclosure.html" defer="defer"></script>
                        <script src="js/share.html" defer="defer"></script>
                    </div>
                    <div class="product-single__meta-info">
                        <div class="meta-item">
                            <label>SKU:</label>
                            <span id="sku-display">{{ $product->SKU }}</span>
                        </div>
                        <div class="meta-item">
                            <label>Categories:</label>
                            <span>{{ $product->category->name }}</span>
                        </div>
                        <div class="meta-item">
                            <label>Tags:</label>
                            <span>NA</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-single__details-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
                            href="#tab-description" role="tab" aria-controls="tab-description"
                            aria-selected="true">Description</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore" id="tab-additional-info-tab" data-bs-toggle="tab"
                            href="#tab-additional-info" role="tab" aria-controls="tab-additional-info"
                            aria-selected="false">Additional Information</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore" id="tab-reviews-tab" data-bs-toggle="tab"
                            href="#tab-reviews" role="tab" aria-controls="tab-reviews" aria-selected="false">Reviews
                            ({{ $product->reviews->count() }})</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
                        aria-labelledby="tab-description-tab">
                        <div class="product-single__description">
                            {{ $product->description }}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-additional-info" role="tabpanel"
                        aria-labelledby="tab-additional-info-tab">
                        <div class="product-single__addtional-info">
                            <div class="item">
                                <label class="h6">Weight</label>
                                <span>1.25 kg</span>
                            </div>
                            <div class="item">
                                <label class="h6">Dimensions</label>
                                <span>90 x 60 x 90 cm</span>
                            </div>
                            <div class="item">
                                <label class="h6">Size</label>
                                <span>XS, S, M, L, XL</span>
                            </div>
                            <div class="item">
                                <label class="h6">Color</label>
                                <span>Black, Orange, White</span>
                            </div>
                            <div class="item">
                                <label class="h6">Storage</label>
                                <span>Relaxed fit shirt-style dress with a rugged</span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-tab">
                        <h2 class="product-single__reviews-title">Reviews</h2>
                        <div class="product-single__reviews-list">
                        @forelse($product->reviews as $review)
                        <div class="product-single__reviews-item">
                            <div class="customer-avatar">
                                <img src="{{asset('assets/images/avatar.jpg')}}" alt="" />
                            </div>
                            <div class="customer-review">
                                <div class="customer-name">
                                    <h6>{{ $review->name }}</h6>
                                    <div class="reviews-group d-flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"
                                                style="fill: {{ $i <= $review->rating ? '#ffc107' : '#ccc' }}">
                                                <use href="#icon_star" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <div class="review-date">{{ $review->created_at->format('F d, Y') }}</div>
                                <div class="review-text">
                                    <p>{{ $review->review }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>No reviews yet. Be the first to review this product.</p>
                        @endforelse
                        </div>
                        <div class="product-single__review-form">
                        <form method="POST" action="{{route('product.review.store')}}" class="review-form">
                            @csrf

                            <input type="hidden" name="product_id" value="{{$product->id}}">

                            <h5>Leave a Review</h5>

                            <div class="mb-3">
                                <label>Rating *</label>
                                <select name="rating" class="form-control">
                                    <option value="">Select Rating</option>
                                    <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Good</option>
                                    <option value="3">3 - Average</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="1">1 - Bad</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <textarea name="review" class="form-control" placeholder="Your Review" required></textarea>
                            </div>

                            <div class="mb-3">
                                <input name="name" class="form-control" placeholder="Your Name" required>
                            </div>

                            <div class="mb-3">
                                <input name="email" type="email" class="form-control" placeholder="Your Email" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="products-carousel container">
            <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">Related <strong>Products</strong></h2>

            <div id="related_products" class="position-relative">
                <div class="swiper-container js-swiper-slider"
                    data-settings='{
            "autoplay": false,
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "effect": "none",
            "loop": true,
            "pagination": {
              "el": "#related_products .products-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": "#related_products .products-carousel__next",
              "prevEl": "#related_products .products-carousel__prev"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 2,
                "slidesPerGroup": 2,
                "spaceBetween": 14
              },
              "768": {
                "slidesPerView": 3,
                "slidesPerGroup": 3,
                "spaceBetween": 24
              },
              "992": {
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "spaceBetween": 30
              }
            }
          }'>
                    <div class="swiper-wrapper">
                        @foreach ($rproducts as $rproduct)
                            <div class="swiper-slide product-card">
                                <div class="pc__img-wrapper">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $rproduct->slug]) }}">
                                        <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $rproduct->image }}"
                                            width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img">
                                        @foreach (explode(',', $rproduct->images) as $gimg)
                                            <img loading="lazy"
                                                src="{{ asset('uploads/products') }}/{{ $gimg }}" width="330"
                                                height="400" alt="{{ $rproduct->name }}"
                                                class="pc__img pc__img-second">
                                        @endforeach
                                    </a>
                                    
                                </div>

                                <div class="pc__info position-relative">
                                    <p class="pc__category">{{ $rproduct->category->name }}</p>
                                    <h6 class="pc__title"><a
                                            href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">{{ $rproduct->name }}</a>
                                    </h6>
                                    <div class="product-card__price d-flex">
                                        <span class="money price">
                                            @if ($product->sale_price)
                                                <s>£{{ $product->regular_price }} </s> £{{ $rproduct->sale_price }}
                                            @else
                                                £{{ $product->regular_price }}
                                            @endif
                                        </span>
                                    </div>

                                    <button
                                        class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                                        title="Add To Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_heart" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div
                    class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_prev_md" />
                    </svg>
                </div>
                <div
                    class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_next_md" />
                    </svg>
                </div>

                <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
                
            </div>

        </section>
    </main>
@endsection
