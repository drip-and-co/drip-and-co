@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Add Product</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Products</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit product</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.product.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $product->id }}" />
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0"
                            value="{{ $product->name }}" aria-required="true" required="">
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div>
                    </fieldset>
                    @error('name')
                        <sapn class="alert alert-danger text-center">{{ $message }}
                        @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter product slug" name="slug"
                                tabindex="0" value="{{ $product->slug }}" aria-required="true" required="">
                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                product name.</div>
                        </fieldset>
                        @error('slug')
                            <sapn class="alert alert-danger text-center">{{ $message }}
                            @enderror

                            <div class="gap22 cols">
                                <fieldset class="category">
                                    <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                                    </div>
                                    <div class="select">
                                        <select class="" name="category_id">
                                            <option>Choose category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </fieldset>
                                @error('category_id')
                                    <sapn class="alert alert-danger text-center">{{ $message }}
                                    @enderror
                                    <fieldset class="brand">
                                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                                        </div>
                                        <div class="select">
                                            <select class="" name="brand_id">
                                                <option>Choose Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </fieldset>
                                    @error('brand_id')
                                        <sapn class="alert alert-danger text-center">{{ $message }}
                                        @enderror
                            </div>
                            <fieldset class="shortdescription">
                                <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                                <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0"
                                    aria-required="true" required="">{{ $product->short_description }}</textarea>
                                <div class="text-tiny">Do not exceed 100 characters when entering the
                                    product name.</div>
                            </fieldset>
                            @error('short_description')
                                <sapn class="alert alert-danger text-center">{{ $message }}
                                @enderror

                                <fieldset class="description">
                                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                                    </div>
                                    <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true"
                                        required="">{{ $product->description }}</textarea>
                                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.
                                    </div>
                                </fieldset>
                                @error('description')
                                    <sapn class="alert alert-danger text-center">{{ $message }}
                                    @enderror
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload images <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            @if ($product->image)
                                <div class="item" id="imgpreview">
                                    <img src="{{ asset('uploads/products') }}/{{ $product->image }}" class="effect8"
                                        alt="">
                                </div>
                            @endif
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <sapn class="alert alert-danger text-center">{{ $message }}
                        @enderror

                        <fieldset>
                            <div class="body-title mb-10">Upload Gallery Images</div>
                            <div class="upload-image mb-16">
                                @if ($product->images)
                                    @foreach (explode(',', $product->images) as $img)
                                        <div class="item gitems">
                                            <img src="{{ asset('uploads/products') }}/{{ trim($img) }}"
                                                alt="">
                                        </div>
                                    @endforeach
                                @endif
                                <div id="galUpload" class="item up-load">
                                    <label class="uploadfile" for="gFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="text-tiny">Drop your images here or select <span
                                                class="tf-color">click to browse</span></span>
                                        <input type="file" id="gFile" name="images[]" accept="image/*"
                                            multiple="">
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        @error('images')
                            <sapn class="alert alert-danger text-center">{{ $message }}
                            @enderror

                            <!-- variant manager: size, color, SKU, qty, gallery only -->
                            <fieldset class="variants">
                                <div class="body-title mb-10">Variants (size / color / SKU / qty / gallery)</div>
                                <p class="text-tiny text-secondary mb-2">Each variant is a size+colour combination with its own SKU, quantity, and optional gallery images. Total quantity is the sum of all variant quantities.</p>
                                <p class="text-tiny mb-2"><strong>Total quantity (from variants):</strong> <span id="variantTotalQty">{{ $product->variants->sum('quantity') }}</span></p>
                                <table class="table table-bordered" id="variantTable">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Color</th>
                                            <th>SKU</th>
                                            <th>Qty</th>
                                            <th>Gallery</th>
                                            <th><button type="button" class="btn btn-sm btn-success" id="addVariant">+</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $v)
                                            <tr>
                                                <td><select name="variants[{{ $loop->index }}][size]" class="form-select form-select-sm">
                                                    <option value="">—</option>
                                                    <option value="S" {{ $v->size == 'S' ? 'selected' : '' }}>S</option>
                                                    <option value="M" {{ $v->size == 'M' ? 'selected' : '' }}>M</option>
                                                    <option value="L" {{ $v->size == 'L' ? 'selected' : '' }}>L</option>
                                                    <option value="XL" {{ $v->size == 'XL' ? 'selected' : '' }}>XL</option>
                                                </select></td>
                                                <td><select name="variants[{{ $loop->index }}][color]" class="form-select form-select-sm">
                                                    <option value="">—</option>
                                                    <option value="White" {{ $v->color == 'White' ? 'selected' : '' }}>White</option>
                                                    <option value="Black" {{ $v->color == 'Black' ? 'selected' : '' }}>Black</option>
                                                    <option value="Grey" {{ $v->color == 'Grey' ? 'selected' : '' }}>Grey</option>
                                                    <option value="Green" {{ $v->color == 'Green' ? 'selected' : '' }}>Green</option>
                                                    <option value="Pink" {{ $v->color == 'Pink' ? 'selected' : '' }}>Pink</option>
                                                </select></td>
                                                <td><input type="text" name="variants[{{ $loop->index }}][SKU]" value="{{ $v->SKU }}" class="form-control form-control-sm" /></td>
                                                <td><input type="number" name="variants[{{ $loop->index }}][quantity]" value="{{ $v->quantity }}" class="form-control form-control-sm" min="0" /></td>
                                                <td class="variant-gallery-cell">
                                                    <input type="hidden" name="variants[{{ $loop->index }}][gallery_filenames]" value="{{ $v->images }}" class="variant-gallery-filenames" />
                                                    <div class="variant-gallery-thumbs mb-2 d-flex flex-wrap gap-1" style="min-height: 52px;">
                                                        @if($v->gallery_array)
                                                            @foreach($v->gallery_array as $gimg)
                                                                <img src="{{ asset('uploads/products/thumbnails/' . $gimg) }}" alt="" width="52" height="52" class="rounded border" style="object-fit: cover;" />
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <input type="file" name="variants[{{ $loop->index }}][images][]" accept="image/*" multiple class="form-control form-control-sm variant-gallery-upload" data-index="{{ $loop->index }}" title="Select one or more images; they upload immediately" />
                                                    <div class="text-tiny text-muted mt-1">Select images to upload into gallery (one at a time or multiple)</div>
                                                </td>
                                                <td><button type="button" class="btn btn-sm btn-danger removeVariant">−</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </fieldset>

                            <div class="cols gap22">
                                <fieldset class="name">
                                    <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                                    <input class="mb-10" type="text" placeholder="Enter regular price"
                                        name="regular_price" tabindex="0" value="{{ $product->regular_price }}"
                                        aria-required="true" required="">
                                </fieldset>
                                @error('regular_price')
                                    <sapn class="alert alert-danger text-center">{{ $message }}
                                    @enderror
                                    <fieldset class="name">
                                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                                        <input class="mb-10" type="text" placeholder="Enter sale price"
                                            name="sale_price" tabindex="0" value="{{ $product->sale_price }}"
                                            aria-required="true" required="">
                                    </fieldset>
                                    @error('sale_price')
                                        <sapn class="alert alert-danger text-center">{{ $message }}
                                        @enderror
                            </div>


                            <div class="cols gap22">
                                <fieldset class="name">
                                    <div class="body-title mb-10">Stock</div>
                                    <div class="select mb-10">
                                        <select class="" name="stock_status">
                                            <option value="instock"
                                                {{ $product->stock_status == 'instock' ? 'selected' : '' }}>InStock</option>
                                            <option value="outofstock"
                                                {{ $product->stock_status == 'outofstock' ? 'selected' : '' }}>Out of Stock
                                            </option>
                                        </select>
                                    </div>
                                </fieldset>
                                @error('stock_status')
                                    <sapn class="alert alert-danger text-center">{{ $message }}
                                    @enderror
                                    <fieldset class="name">
                                        <div class="body-title mb-10">Featured</div>
                                        <div class="select mb-10">
                                            <select class="" name="featured">
                                                <option value="0" {{ $product->featured == '0' ? 'selected' : '' }}>No
                                                </option>
                                                <option value="1" {{ $product->featured == '1' ? 'selected' : '' }}>Yes
                                                </option>
                                            </select>
                                        </div>
                                    </fieldset>
                                    @error('featured')
                                        <sapn class="alert alert-danger text-center">{{ $message }}
                                        @enderror
                            </div>
                            <div class="cols gap10">
                                <button class="tf-button w-full" type="submit">Update product</button>
                            </div>
                </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection

@push('scripts')
    <script>
        window.uploadVariantGalleryUrl = "{{ route('admin.product.upload.variant.gallery') }}";
        window.csrfToken = "{{ csrf_token() }}";
        $(function() {
            $("#myFile").on("change", function(e) {
                const photoInp = $("#myFile");
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            $("#gFile").on("change", function(e) {
                const photoInp = $("#gFile");
                const gphotos = this.files;
                $.each(gphotos, function(key, val) {
                    $("#galUpload").prepend(
                        `<div class="item gitems"><img src="${URL.createObjectURL(val)}"/></div>`
                        );
                });
            });

            $("input[name='name']").on("change", function() {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

        function StringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/[^\w ]+/g, "")
                .replace(/ +/g, "-");
        }
        function addVariantRow(data = {}) {
            const index = $('#variantTable tbody tr').length;
            const row = `<tr>
                            <td><select name="variants[${index}][size]" class="form-select form-select-sm">
                                <option value="">—</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select></td>
                            <td><select name="variants[${index}][color]" class="form-select form-select-sm">
                                <option value="">—</option>
                                <option value="White">White</option>
                                <option value="Black">Black</option>
                                <option value="Grey">Grey</option>
                                <option value="Green">Green</option>
                                <option value="Pink">Pink</option>
                            </select></td>
                            <td><input type="text" name="variants[${index}][SKU]" value="${data.SKU||''}" class="form-control form-control-sm" /></td>
                            <td><input type="number" name="variants[${index}][quantity]" value="${data.quantity!==undefined?data.quantity:''}" class="form-control form-control-sm" min="0" /></td>
                            <td class="variant-gallery-cell"><input type="hidden" name="variants[${index}][gallery_filenames]" value="" class="variant-gallery-filenames" /><div class="variant-gallery-thumbs mb-2 d-flex flex-wrap gap-1" style="min-height: 52px;"></div><input type="file" name="variants[${index}][images][]" accept="image/*" multiple class="form-control form-control-sm variant-gallery-upload" data-index="${index}" title="Select one or more images; they upload immediately" /><div class="text-tiny text-muted mt-1">Select images to upload into gallery (one at a time or multiple)</div></td>
                            <td><button type="button" class="btn btn-sm btn-danger removeVariant">−</button></td>
                        </tr>`;
            $('#variantTable tbody').append(row);
        }

        $(document).on('click', '#addVariant', function() {
            addVariantRow();
            updateVariantTotalQty();
        });

        function updateVariantTotalQty() {
            var total = 0;
            $('#variantTable tbody tr').each(function() {
                var q = parseInt($(this).find('input[name*="[quantity]"]').val(), 10);
                if (!isNaN(q)) total += q;
            });
            $('#variantTotalQty').text(total);
        }

        $(document).on('click', '.removeVariant', function() {
            $(this).closest('tr').remove();
            updateVariantTotalQty();
        });

        $(document).on('input', '#variantTable input[name*="[quantity]"]', updateVariantTotalQty);

        $(document).on('change', '.variant-gallery-upload', function() {
            var input = this;
            var files = input.files;
            if (!files || !files.length) return;
            var $row = $(input).closest('tr');
            var $thumbs = $row.find('.variant-gallery-thumbs');
            var $hidden = $row.find('.variant-gallery-filenames');
            var formData = new FormData();
            formData.append('_token', window.csrfToken);
            for (var i = 0; i < files.length; i++) formData.append('images[]', files[i]);
            $.ajax({
                url: window.uploadVariantGalleryUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.filenames && res.filenames.length) {
                        var current = ($hidden.val() || '').trim();
                        var added = res.filenames.join(',');
                        $hidden.val(current ? current + ',' + added : added);
                        var base = "{{ asset('uploads/products/thumbnails') }}".replace(/\/?$/, '') + '/';
                        $.each(res.filenames, function(_, fn) {
                            $thumbs.append('<img src="' + base + fn + '" alt="" width="52" height="52" class="rounded border" style="object-fit: cover;" />');
                        });
                    }
                    input.value = '';
                },
                error: function(xhr) {
                    alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Upload failed. Please try again.');
                    input.value = '';
                }
            });
        });
    </script>
@endpush
