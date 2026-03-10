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
                        <div class="text-tiny">Add product</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.product.store') }}">
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0"
                            value="{{ old('name') }}" aria-required="true" required="">
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div>
                    </fieldset>
                    @error('name')
                        <sapn class="alert alert-danger text-center">{{ $message }}
                        @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter product slug" name="slug"
                                tabindex="0" value="{{ old('slug') }}" aria-required="true" required="">
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
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                        aria-required="true" required="{{ old('short_description') }}"></textarea>
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
                                            required="{{ old('description') }}"></textarea>
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
                                        <div class="item" id="imgpreview" style="display:none">
                                            <img src="../../../localhost_8000/images/upload/upload-1.png" class="effect8"
                                                alt="">
                                        </div>
                                        <div id="upload-file" class="item up-load">
                                            <label class="uploadfile" for="myFile">
                                                <span class="icon">
                                                    <i class="icon-upload-cloud"></i>
                                                </span>
                                                <span class="body-text">Drop your images here or select <span
                                                        class="tf-color">click to browse</span></span>
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
                                            <!-- <div class="item">
                                    <img src="images/upload/upload-1.png" alt="">
                                </div>                                                 -->
                                            <div id="galUpload" class="item up-load">
                                                <label class="uploadfile" for="gFile">
                                                    <span class="icon">
                                                        <i class="icon-upload-cloud"></i>
                                                    </span>
                                                    <span class="text-tiny">Drop your images here or select <span
                                                            class="tf-color">click to browse</span></span>
                                                    <input type="file" id="gFile" name="images[]"
                                                        accept="image/*" multiple="">
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                    @error('images')
                                        <sapn class="alert alert-danger text-center">{{ $message }}
                                        @enderror

                                        <!-- variant manager: size, color, sku, qty, gallery only -->
                                        <fieldset class="variants">
                                            <div class="body-title mb-10">Variants (size / color / SKU / qty / gallery)</div>
                                            <p class="text-tiny text-secondary mb-2">Add at least one variant per size+colour combination. Each variant has its own SKU, quantity, and optional gallery images (you can add multiple gallery images at once per variant). Total quantity is the sum of all variant quantities.</p>
                                            <p class="text-tiny mb-2"><strong>Total quantity (from variants):</strong> <span id="variantTotalQty">0</span></p>
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
                                                    {{-- rows added via JS or leave blank --}}
                                                </tbody>
                                            </table>
                                        </fieldset>

                                        <div class="cols gap22">
                                            <fieldset class="name">
                                                <div class="body-title mb-10">Regular Price <span
                                                        class="tf-color-1">*</span></div>
                                                <input class="mb-10" type="text" placeholder="Enter regular price"
                                                    name="regular_price" tabindex="0"
                                                    value="{{ old('reqular_price') }}" aria-required="true"
                                                    required="">
                                            </fieldset>
                                            @error('regular_price')
                                                <sapn class="alert alert-danger text-center">{{ $message }}
                                                @enderror
                                                <fieldset class="name">
                                                    <div class="body-title mb-10">Sale Price <span
                                                            class="tf-color-1">*</span></div>
                                                    <input class="mb-10" type="text" placeholder="Enter sale price"
                                                        name="sale_price" tabindex="0" value="{{ old('sale_price') }}"
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
                                                        <option value="instock">InStock</option>
                                                        <option value="outofstock">Out of Stock</option>
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
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                                @error('featured')
                                                    <sapn class="alert alert-danger text-center">{{ $message }}
                                                    @enderror
                                        </div>
                                        <div class="cols gap10">
                                            <button class="tf-button w-full" type="submit">Add product</button>
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
        /* variant table helper: size, color, SKU, qty, image, gallery */
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
                        <td><input type="text" name="variants[${index}][SKU]" value="${data.SKU||''}" class="form-control form-control-sm" placeholder="SKU" /></td>
                        <td><input type="number" name="variants[${index}][quantity]" value="${data.quantity!==undefined?data.quantity:''}" class="form-control form-control-sm" min="0" placeholder="0" /></td>
                        <td class="variant-gallery-cell"><input type="hidden" name="variants[${index}][gallery_filenames]" value="" class="variant-gallery-filenames" /><div class="variant-gallery-thumbs mb-2 d-flex flex-wrap gap-1" style="min-height: 52px;"></div><input type="file" name="variants[${index}][images][]" accept="image/*" multiple class="form-control form-control-sm variant-gallery-upload" data-index="${index}" title="Select one or more images; they upload immediately" /><div class="text-tiny text-muted mt-1">Select images to upload into gallery (one at a time or multiple)</div></td>
                        <td><button type="button" class="btn btn-sm btn-danger removeVariant">−</button></td>
                    </tr>`;
            $('#variantTable tbody').append(row);
        }

        function updateVariantTotalQty() {
            var total = 0;
            $('#variantTable tbody tr').each(function() {
                var q = parseInt($(this).find('input[name*="[quantity]"]').val(), 10);
                if (!isNaN(q)) total += q;
            });
            $('#variantTotalQty').text(total);
        }

        $(document).on('click', '#addVariant', function() {
            addVariantRow();
            updateVariantTotalQty();
        });

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
