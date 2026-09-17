@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('breadcrumb', 'Tambah Produk')

@php
    $pfBoot = null;
    if (isset($product) && $product) {
        $pfBoot = [
            'facilities' => $product->facilities->map(fn($f) => ['name' => $f->name, 'value' => $f->value])->values(),
            'gallery' => $product->images
                ->map(
                    fn($i) => [
                        'id' => $i->id,
                        'url' => image_url($i->image_path),
                        'is_primary' => (bool) $i->is_primary,
                    ],
                )
                ->values(),
            'variants' => $product->variants
                ->map(function ($v) {
                    $primary = $v->images->firstWhere('is_primary', true);
                    return [
                        'id' => $v->id,
                        'name' => $v->name,
                        'title' => $v->title,
                        'slug' => $v->slug,
                        'description' => $v->description,
                        'status' => $v->status,
                        'banner_url' => $primary ? image_url($primary->image_path) : null,
                        'banner_id' => $primary ? $primary->id : null,
                        'gallery' => $v->images
                            ->where('is_primary', false)
                            ->map(fn($i) => ['id' => $i->id, 'url' => image_url($i->image_path)])
                            ->values(),
                        'facilities' => $v->facilities
                            ->map(fn($f) => ['name' => $f->name, 'value' => $f->value])
                            ->values(),
                    ];
                })
                ->values(),
        ];
    }
@endphp

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Card Container */
        .pf-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 5px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        }

        .pf-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .pf-card-head h2 {
            font-size: 17px;
            font-weight: 700;
            color: #0d1d48;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pf-card-head h2 i {
            color: #2563eb;
        }

        .pf-card-sub {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 18px;
        }

        /* Labels & Hints */
        .pf-label {
            font-size: 13.5px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        .pf-hint {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 5px;
        }

        /* Upload Area */
        .pf-upload {
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            color: #2563eb;
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            transition: 0.2s;
            background: #fafbff;
        }

        .pf-upload:hover {
            background: #f0f6ff;
            border-color: #2563eb;
        }

        .pf-upload span {
            color: #9ca3af;
            font-weight: 500;
        }

        /* Banner Preview */
        .pf-banner-preview {
            width: 100%;
            height: 230px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 12px;
            display: none;
        }

        /* Gallery */
        .pf-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 14px 0;
        }

        .pf-thumb {
            position: relative;
            width: 110px;
            height: 90px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            cursor: grab;
        }

        .pf-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pf-thumb .pf-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 22px;
            height: 22px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            color: #ef4444;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pf-thumb .pf-star {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 22px;
            height: 22px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            color: #d1d5db;
            font-size: 12px;
            cursor: pointer;
        }

        .pf-thumb.is-primary .pf-star {
            color: #f59e0b;
        }

        /* Table */
        .pf-tbl {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .pf-tbl th {
            background: #f8fafc;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            padding: 11px 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .pf-tbl td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .pf-tbl .pf-drag {
            color: #cbd5e1;
            cursor: grab;
        }

        .pf-tbl input {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 13.5px;
            width: 100%;
        }

        .pf-del {
            border: none;
            background: none;
            color: #ef4444;
            font-size: 15px;
            cursor: pointer;
        }

        /* Tabs */
        .pf-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            border-bottom: 2px solid #eef2f7;
            margin-bottom: 20px;
        }

        .pf-tab {
            padding: 11px 18px;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            border: none;
            background: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pf-tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .pf-tab .pf-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
        }

        .pf-tab-add {
            color: #2563eb;
        }

        /* Variant Pane */
        .pf-variant-pane {
            display: none;
        }

        .pf-variant-pane.active {
            display: block;
        }

        .pf-variant-pane.active {
            padding: 0px 15px;
        }

        .pf-variant-pane .row.g-4 {
            --bs-gutter-x: 1.75rem;
            --bs-gutter-y: 1.75rem;
        }

        .pf-variant-pane .col-md-6 {
            padding: 22px;
            border: 1px solid #d4dbe6;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
        }

        .pf-variant-pane .col-md-12 {
            padding: 22px;
            border: 1px solid #d4dbe6;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
        }

        .pf-variant-pane .col-md-12 .pf-section-title {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }

        .pf-variant-pane .col-md-12 .d-flex {
            margin-top: 0 !important;
        }

        .pf-variant-pane .pf-label {
            margin-top: 14px;
        }

        .pf-variant-pane .col-md-6>.pf-label:first-child,
        .pf-variant-pane .col-md-6>.d-flex:first-child .pf-label {
            margin-top: 0;
        }

        .pf-variant-pane .form-control,
        .pf-variant-pane .form-select {
            margin-bottom: 2px;
        }

        .pf-variant-pane .pf-banner-preview {
            height: 200px;
            margin-bottom: 14px;
        }

        .pf-variant-pane .pf-section-title {
            border-top: 1px solid #e3e8f0;
            padding-top: 18px;
            margin-top: 20px;
        }

        .pf-variant-pane .pf-gallery {
            padding: 12px;
            border: 1px dashed #e5e7eb;
            border-radius: 10px;
            min-height: 60px;
            background: #fafbff;
        }

        /* Gallery Empty State */
        .pf-gallery-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            background: #fafbff;
            color: #2563eb;
            text-align: center;
            min-height: 120px;
            width: 100%;
        }

        .pf-gallery-empty i {
            font-size: 28px;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .pf-gallery-empty .pf-empty-title {
            font-size: 13.5px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 4px;
        }

        .pf-gallery-empty .pf-empty-hint {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        .pf-variant-pane .pf-tbl {
            margin-top: 6px;
        }

        .pf-tabs {
            gap: 8px;
        }

        .pf-tab {
            background: #f8fafc;
            border-radius: 10px 10px 0 0;
        }

        .pf-tab.active {
            background: #fff;
        }

        /* Section Title */
        .pf-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0d1d48;
            margin: 18px 0 12px;
        }

        /* Button Soft */
        .btn-soft {
            background: #eff4ff;
            color: #2563eb;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Sortable */
        .sortable-ghost {
            opacity: 0.4;
        }
    </style>
@endpush

@section('content')
    @php($isEdit = isset($product) && $product)
    <form id="productForm" action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif
        <div class="page-header">
            <div class="page-header-left">
                <h1>{{ $isEdit ? 'Edit Produk' : 'Tambah Produk' }}</h1>
                <p>Lengkapi informasi produk, fasilitas, dan variant produk.</p>
            </div>
            <div class="page-header-right d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </div>

        <div class="row g-1">
            <!-- LEFT COLUMN -->
            <div class="col-lg-6">
                <!-- Card 1: Informasi Produk -->
                <div class="pf-card">
                    <div class="pf-card-head">
                        <h2><i class="fas fa-circle-info"></i> 1. Informasi Produk</h2>
                    </div>
                    <div class="mb-3">
                        <label class="pf-label">Kategori Produk <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" placeholder="Pilih kategori...">
                            <option value="">Pilih kategori...</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected($isEdit && old('category_id', $product->category_id) === $cat->id)>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="pf-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $isEdit ? $product->name : '') }}" required
                            placeholder="Contoh: Aetheria">
                    </div>
                    <div class="mb-3">
                        <label class="pf-label">Title / Takeline</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $isEdit ? $product->title : '') }}"
                            placeholder="Contoh: Compact & Fungsional">
                        <div class="pf-hint">Tagline singkat produk (opsional), tampil di halaman detail.</div>
                    </div>
                    <div class="mb-3">
                        <label class="pf-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="{{ old('slug', $isEdit ? $product->slug : '') }}" placeholder="Contoh: aetheria">
                        <div class="pf-hint">URL akan otomatis dibuat jika dikosongkan.</div>
                    </div>
                    <div class="mb-3">
                        <label class="pf-label">Deskripsi Produk</label>
                        <textarea class="form-control" name="description" rows="6"
                            placeholder="Contoh: Compact & fungsional. Aetheria dirancang untuk keluarga modern yang menghargai fungsi dan estetika.">{{ old('description', $isEdit ? $product->description : '') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="pf-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" @selected($isEdit && $product->status === 'active')>Active (Produk akan ditampilkan di Website)
                            </option>
                            <option value="inactive" @selected($isEdit && $product->status === 'inactive')>Inactive (Produk disembunyikan dari
                                Website)</option>
                        </select>
                    </div>
                </div>

                <!-- Card 2: Banner Produk -->
                <div class="pf-card">
                    <div class="pf-card-head">
                        <h2><i class="fas fa-image"></i> 2. Banner Produk</h2>
                    </div>
                    <div class="pf-card-sub">Upload banner utama produk. Rekomendasi ukuran 1920 x 600 px.</div>
                    <img class="pf-banner-preview" id="bannerPreview"
                        @if ($isEdit && $product->banner_image) src="{{ image_url($product->banner_image) }}" style="display:block" @endif>
                    <input type="file" name="banner_image" id="bannerInput" accept="image/*" hidden>
                    <div class="pf-upload" id="bannerDrop"><i class="fas fa-cloud-arrow-up"></i> Upload Banner <span>atau
                            drag &amp; drop file di sini</span></div>
                </div>

                <!-- Card 3: Gallery Produk -->
                <div class="pf-card">
                    <div class="pf-card-head">
                        <h2><i class="fas fa-images"></i> 3. Gallery Produk</h2>
                    </div>
                    <div class="pf-card-sub">Upload beberapa gambar produk untuk ditampilkan di halaman detail.</div>
                    <button type="button" class="btn-soft pf-gallery-add" data-target="productGallery"><i
                            class="fas fa-plus"></i> Tambah Gambar</button>
                    <div class="pf-gallery" id="productGallery"></div>
                    <input type="file" class="pf-gallery-input" data-target="productGallery" name="gallery_images[]"
                        accept="image/*" multiple hidden>
                    <input type="hidden" name="gallery_primary" id="galleryPrimary">
                    <div class="pf-hint">Klik <i class="fas fa-star text-warning"></i> pada salah satu gambar untuk
                        menjadikannya banner utama.</div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-lg-6">
                <!-- Card 4: Fasilitas Umum -->
                <div class="pf-card">
                    <div class="pf-card-head">
                        <h2><i class="fas fa-list-check"></i> 4. Fasilitas Umum Produk</h2>
                        <button type="button" class="btn-soft" id="addFacility"><i class="fas fa-plus"></i> Tambah
                            Fasilitas</button>
                    </div>
                    <div class="pf-card-sub">Isi fasilitas yang tersedia untuk tipe produk ini.</div>
                    <table class="pf-tbl">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th width="40">No</th>
                                <th>Fasilitas</th>
                                <th>Keterangan</th>
                                <th width="50">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="facilityBody"></tbody>
                    </table>
                </div>

                <!-- Card 5: Variant Produk -->
                <div class="pf-card">
                    <div class="pf-card-head">
                        <h2><i class="fas fa-layer-group"></i> 5. Variant Tipe Produk</h2>
                        <button type="button" class="btn btn-primary btn-sm" id="addVariant"><i
                                class="fas fa-plus"></i> Tambah Variant</button>
                    </div>
                    <div class="pf-card-sub">Kelola variant atau tipe dari produk ini.</div>
                    <div class="pf-tabs" id="variantTabs"></div>
                    <div id="variantPanes"></div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @if ($pfBoot)
        <script>
            window.PF_BOOTSTRAP = @json($pfBoot);
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---------- Tom Select ----------
            new TomSelect('#category_id', {
                create: false
            });

            // ---------- TinyMCE ----------
            // (textarea biasa, tanpa rich editor)

            // ---------- Slug auto ----------
            const slugify = s => s.toString().toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g,
                '');
            const nameEl = document.getElementById('name'),
                slugEl = document.getElementById('slug');
            let slugTouched = false;
            slugEl.addEventListener('input', () => slugTouched = true);
            nameEl.addEventListener('input', () => {
                if (!slugTouched) slugEl.value = slugify(nameEl.value);
            });

            // ---------- Single banner upload ----------
            function bindBanner(dropEl, inputEl, previewEl) {
                dropEl.addEventListener('click', () => inputEl.click());
                inputEl.addEventListener('change', () => {
                    const f = inputEl.files[0];
                    if (f) {
                        previewEl.src = URL.createObjectURL(f);
                        previewEl.style.display = 'block';
                    }
                });
                ['dragover', 'dragenter'].forEach(ev => dropEl.addEventListener(ev, e => {
                    e.preventDefault();
                    dropEl.style.background = '#eef6ff';
                }));
                ['dragleave', 'drop'].forEach(ev => dropEl.addEventListener(ev, e => {
                    e.preventDefault();
                    dropEl.style.background = '';
                }));
                dropEl.addEventListener('drop', e => {
                    e.preventDefault();
                    if (e.dataTransfer.files.length) {
                        inputEl.files = e.dataTransfer.files;
                        inputEl.dispatchEvent(new Event('change'));
                    }
                });
            }
            bindBanner(document.getElementById('bannerDrop'), document.getElementById('bannerInput'), document
                .getElementById('bannerPreview'));

            // ---------- Gallery manager ----------
            const galleries = {}; // id -> { files: [], primaryIdx, withStar }
            function rebuildInput(input, files) {
                const dt = new DataTransfer();
                files.filter(f => f instanceof File).forEach(f => dt.items.add(f));
                input.files = dt.files;
            }

            function existingIds(files) {
                return files.filter(f => f && f.existing).map(f => f.id);
            }

            function appendHidden(form, name, val) {
                const i = document.createElement('input');
                i.type = 'hidden';
                i.name = name;
                i.value = val;
                form.appendChild(i);
            }

            function renderGallery(id) {
                const g = galleries[id];
                g.container.innerHTML = '';
                if (g.files.length === 0) {
                    g.container.innerHTML =
                        '<div class="pf-gallery-empty">' +
                        '<i class="fas fa-images"></i>' +
                        '<div class="pf-empty-title">Belum ada gambar</div>' +
                        '<div class="pf-empty-hint">Klik tombol "Tambah Gambar" untuk upload</div>' +
                        '</div>';
                    return;
                }
                g.files.forEach((file, idx) => {
                    const div = document.createElement('div');
                    div.className = 'pf-thumb' + (g.withStar && g.primaryIdx === idx ? ' is-primary' : '');
                    div.dataset.idx = idx;
                    const src = file.existing ? file.url : URL.createObjectURL(file);
                    div.innerHTML =
                        '<img src="' + src + '">' +
                        (g.withStar ?
                            '<button type="button" class="pf-star"><i class="fas fa-star"></i></button>' :
                            '') +
                        '<button type="button" class="pf-remove"><i class="fas fa-times"></i></button>';
                    div.querySelector('.pf-remove').addEventListener('click', () => {
                        g.files.splice(idx, 1);
                        if (g.primaryIdx >= g.files.length) g.primaryIdx = 0;
                        renderGallery(id);
                    });
                    if (g.withStar) div.querySelector('.pf-star').addEventListener('click', () => {
                        g.primaryIdx = idx;
                        renderGallery(id);
                    });
                    g.container.appendChild(div);
                });
                Sortable.create(g.container, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onEnd: e => {
                        const moved = g.files.splice(e.oldIndex, 1)[0];
                        g.files.splice(e.newIndex, 0, moved);
                        if (g.withStar) {
                            if (g.primaryIdx === e.oldIndex) g.primaryIdx = e.newIndex;
                        }
                        renderGallery(id);
                    }
                });
            }

            function setupGallery(containerEl, inputEl, withStar) {
                const id = containerEl.id || ('g' + Math.random().toString(36).slice(2));
                containerEl.id = id;
                galleries[id] = {
                    files: [],
                    primaryIdx: 0,
                    withStar: !!withStar,
                    container: containerEl,
                    input: inputEl
                };
                inputEl.addEventListener('change', () => {
                    Array.from(inputEl.files).forEach(f => galleries[id].files.push(f));
                    inputEl.value = '';
                    renderGallery(id);
                });
                return id;
            }
            // product gallery (with star)
            const pgSetupId = setupGallery(document.getElementById('productGallery'), document.querySelector(
                '.pf-gallery-input[data-target="productGallery"]'), true);
            renderGallery(pgSetupId); // show empty state initially
            document.querySelectorAll('.pf-gallery-add').forEach(btn => {
                btn.addEventListener('click', () => document.querySelector(
                    '.pf-gallery-input[data-target="' + btn.dataset.target + '"]').click());
            });

            // ---------- Facilities table ----------
            function facilityRow(prefix) {
                const tr = document.createElement('tr');
                tr.innerHTML =
                    (prefix === 'spec' ? '' : '<td><i class="fas fa-grip-vertical pf-drag"></i></td>') +
                    '<td class="pf-no"></td>' +
                    '<td><input type="text" class="f-name" placeholder="Contoh: Lantai"></td>' +
                    '<td><input type="text" class="f-value" placeholder="2 Lantai"></td>' +
                    '<td><button type="button" class="pf-del"><i class="fas fa-trash"></i></button></td>';
                tr.querySelector('.pf-del').addEventListener('click', () => {
                    tr.remove();
                    renumber(tr.closest('tbody'));
                });
                tr._prefix = prefix;
                return tr;
            }

            function renumber(tbody) {
                tbody.querySelectorAll('tr').forEach((tr, i) => tr.querySelector('.pf-no').textContent = i + 1);
            }

            function setupFacilityTable(tbody) {
                Sortable.create(tbody, {
                    handle: '.pf-drag',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onEnd: () => renumber(tbody)
                });
            }
            const facilityBody = document.getElementById('facilityBody');

            function addFacility(tbody, prefix) {
                tbody.appendChild(facilityRow(prefix));
                renumber(tbody);
            }
            document.getElementById('addFacility').addEventListener('click', () => addFacility(facilityBody,
                'facilities'));
            setupFacilityTable(facilityBody);
            addFacility(facilityBody, 'facilities'); // start with 1 row

            // ---------- Variants (dynamic tabs) ----------
            let variantSeq = 0;
            const tabsEl = document.getElementById('variantTabs');
            const panesEl = document.getElementById('variantPanes');

            function selectVariant(uid) {
                tabsEl.querySelectorAll('.pf-tab').forEach(t => t.classList.toggle('active', t.dataset.uid ===
                    uid));
                panesEl.querySelectorAll('.pf-variant-pane').forEach(p => p.classList.toggle('active', p.dataset
                    .uid === uid));
            }

            function addVariant() {
                const uid = 'v' + (variantSeq++);
                // tab
                const tab = document.createElement('button');
                tab.type = 'button';
                tab.className = 'pf-tab';
                tab.dataset.uid = uid;
                tab.innerHTML =
                    '<span class="pf-dot"></span> <span class="pf-tab-label">Variant Baru</span> <i class="fas fa-times-circle ms-1 pf-tab-close" style="color:#cbd5e1"></i>';
                tab.addEventListener('click', e => {
                    if (e.target.classList.contains('pf-tab-close')) {
                        removeVariant(uid);
                    } else {
                        selectVariant(uid);
                    }
                });
                tabsEl.appendChild(tab);
                // pane
                const pane = document.createElement('div');
                pane.className = 'pf-variant-pane';
                pane.dataset.uid = uid;
                pane.innerHTML =
                    '<div class="row g-4"><div class="col-md-6">' +
                    '<label class="pf-label">Nama Variant <span class="text-danger">*</span></label>' +
                    '<input type="text" class="form-control v-name mb-3" placeholder="Contoh: Emerelle">' +
                    '<label class="pf-label">Slug</label>' +
                    '<input type="text" class="form-control v-slug mb-3" placeholder="Contoh: emerelle (otomatis dari nama)">' +
                    '<label class="pf-label">Judul / Tagline</label>' +
                    '<input type="text" class="form-control v-title mb-3" placeholder="Contoh: Bold & architectural">' +
                    '<label class="pf-label">Deskripsi Variant</label>' +
                    '<textarea class="v-desc form-control" rows="4" placeholder="Contoh: Emerelle menghadirkan karakter arsitektur yang kuat dengan bukaan kaca lebar."></textarea>' +
                    '<label class="pf-label mt-3">Status</label>' +
                    '<select class="form-select v-status"><option value="active">Active (Variant akan ditampilkan di Website)</option><option value="inactive">Inactive (Variant disembunyikan dari Website)</option></select>' +
                    '</div><div class="col-md-6">' +
                    '<label class="pf-label">Banner Variant</label>' +
                    '<img class="pf-banner-preview v-banner-prev">' +
                    '<input type="file" class="v-banner-input" accept="image/*" hidden>' +
                    '<div class="pf-upload v-banner-drop mb-3"><i class="fas fa-cloud-arrow-up"></i> Upload Banner <span>atau drag &amp; drop</span></div>' +
                    '<div class="d-flex justify-content-between align-items-center"><label class="pf-label mb-0">Gallery Variant</label>' +
                    '<button type="button" class="btn-soft v-gal-add"><i class="fas fa-plus"></i> Tambah Gambar</button></div>' +
                    '<div class="pf-gallery v-gallery"></div>' +
                    '<input type="file" class="v-gal-input" accept="image/*" multiple hidden>' +
                    '</div><div class="col-md-12">' +
                    '<div class="d-flex justify-content-between align-items-center mt-2"><div class="pf-section-title mb-0">Fasilitas Yang Tersedia</div>' +
                    '<button type="button" class="btn-soft v-spec-add"><i class="fas fa-plus"></i> Tambah Spesifikasi</button></div>' +
                    '<div class="table-responsive"><table class="pf-tbl"><thead><tr><th width="40">No</th><th>Fasilitas</th><th>Keterangan</th><th width="50">Aksi</th></tr></thead><tbody class="v-spec-body"></tbody></table></div>' +
                    '</div></div>';
                panesEl.appendChild(pane);

                // wire pane
                const nameI = pane.querySelector('.v-name'),
                    slugI = pane.querySelector('.v-slug'),
                    labelEl = tab.querySelector('.pf-tab-label');
                let vSlugTouched = false;
                slugI.addEventListener('input', () => vSlugTouched = true);
                nameI.addEventListener('input', () => {
                    labelEl.textContent = nameI.value || 'Variant Baru';
                    if (!vSlugTouched) slugI.value = slugify(nameI.value);
                });

                bindBanner(pane.querySelector('.v-banner-drop'), pane.querySelector('.v-banner-input'), pane
                    .querySelector('.v-banner-prev'));
                const galId = setupGallery(pane.querySelector('.v-gallery'), pane.querySelector('.v-gal-input'),
                    false);
                renderGallery(galId); // show empty state initially
                pane.querySelector('.v-gal-add').addEventListener('click', () => pane.querySelector('.v-gal-input')
                    .click());
                const specBody = pane.querySelector('.v-spec-body');
                setupFacilityTable(specBody);
                pane.querySelector('.v-spec-add').addEventListener('click', () => addFacility(specBody, 'spec'));
                addFacility(specBody, 'spec');
                pane._galId = galId;
                selectVariant(uid);
                return pane;
            }

            function ensureId(el) {
                if (!el.id) el.id = 'rt' + Math.random().toString(36).slice(2);
                return el.id;
            }

            function removeVariant(uid) {
                const pane = panesEl.querySelector('.pf-variant-pane[data-uid="' + uid + '"]');

                if (pane) pane.remove();
                const tab = tabsEl.querySelector('.pf-tab[data-uid="' + uid + '"]');
                if (tab) tab.remove();
                const first = tabsEl.querySelector('.pf-tab');
                if (first) selectVariant(first.dataset.uid);
            }
            document.getElementById('addVariant').addEventListener('click', addVariant);

            // ---------- Hydration (edit mode) ----------
            const BOOT = window.PF_BOOTSTRAP || null;
            if (BOOT) {
                // facilities
                facilityBody.innerHTML = '';
                (BOOT.facilities || []).forEach(f => {
                    const tr = facilityRow('facilities');
                    tr.querySelector('.f-name').value = f.name || '';
                    tr.querySelector('.f-value').value = f.value || '';
                    facilityBody.appendChild(tr);
                });
                if (!facilityBody.children.length) addFacility(facilityBody, 'facilities');
                else renumber(facilityBody);
                // product gallery existing
                const pgid = document.getElementById('productGallery').id;
                (BOOT.gallery || []).forEach((img, i) => {
                    galleries[pgid].files.push({
                        existing: true,
                        id: img.id,
                        url: img.url
                    });
                    if (img.is_primary) galleries[pgid].primaryIdx = i;
                });
                renderGallery(pgid);
                // variants
                (BOOT.variants || []).forEach(v => {
                    const pane = addVariant();
                    pane.dataset.vid = v.id;
                    const nameI = pane.querySelector('.v-name');
                    nameI.value = v.name || '';
                    nameI.dispatchEvent(new Event('input'));
                    pane.querySelector('.v-slug').value = v.slug || '';
                    pane.querySelector('.v-title').value = v.title || '';
                    pane.querySelector('.v-status').value = v.status || 'active';
                    var ta = pane.querySelector('.v-desc');
                    if (ta) ta.value = v.description || '';
                    if (v.banner_url) {
                        const bp = pane.querySelector('.v-banner-prev');
                        bp.src = v.banner_url;
                        bp.style.display = 'block';
                        pane._bannerExistingId = v.banner_id;
                    }
                    const gid = pane._galId;
                    (v.gallery || []).forEach(img => galleries[gid].files.push({
                        existing: true,
                        id: img.id,
                        url: img.url
                    }));
                    renderGallery(gid);
                    const sb = pane.querySelector('.v-spec-body');
                    sb.innerHTML = '';
                    (v.facilities || []).forEach(f => {
                        const tr = facilityRow('spec');
                        tr.querySelector('.f-name').value = f.name || '';
                        tr.querySelector('.f-value').value = f.value || '';
                        sb.appendChild(tr);
                    });
                    if (!sb.children.length) addFacility(sb, 'spec');
                    else renumber(sb);
                });
                // remove the initial empty variant created above if extra
            } else {
                addVariant(); // start with one variant (create mode)
            }

            // ---------- Submit: reindex names + rebuild file inputs ----------
            document.getElementById('productForm').addEventListener('submit', function() {


                // product facilities
                Array.from(facilityBody.querySelectorAll('tr')).forEach((tr, i) => {
                    tr.querySelector('.f-name').name = 'facilities[' + i + '][name]';
                    tr.querySelector('.f-value').name = 'facilities[' + i + '][value]';
                });
                // product gallery
                const formEl = this;
                const pg = galleries[document.getElementById('productGallery').id];
                rebuildInput(pg.input, pg.files);
                existingIds(pg.files).forEach(id => appendHidden(formEl, 'gallery_existing[]', id));
                document.getElementById('galleryPrimary').value = '';

                // variants
                Array.from(panesEl.querySelectorAll('.pf-variant-pane')).forEach((pane, vi) => {
                    const p = 'variants[' + vi + ']';
                    pane.querySelector('.v-name').name = p + '[name]';
                    pane.querySelector('.v-slug').name = p + '[slug]';
                    pane.querySelector('.v-title').name = p + '[title]';
                    pane.querySelector('.v-desc').name = p + '[description]';
                    pane.querySelector('.v-status').name = p + '[status]';
                    pane.querySelector('.v-banner-input').name = p + '[banner_image]';
                    const gInput = pane.querySelector('.v-gal-input');
                    gInput.name = p + '[gallery_images][]';
                    const vg = galleries[pane._galId];
                    rebuildInput(gInput, vg.files);
                    existingIds(vg.files).forEach(id => appendHidden(formEl, p +
                        '[images_existing][]', id));
                    if (pane._bannerExistingId) appendHidden(formEl, p + '[images_existing][]', pane
                        ._bannerExistingId);
                    if (pane.dataset.vid) appendHidden(formEl, p + '[id]', pane.dataset.vid);
                    Array.from(pane.querySelectorAll('.v-spec-body tr')).forEach((tr, si) => {
                        tr.querySelector('.f-name').name = p + '[facilities][' + si +
                            '][name]';
                        tr.querySelector('.f-value').name = p + '[facilities][' + si +
                            '][value]';
                    });
                });
            });
        });
    </script>
@endpush
