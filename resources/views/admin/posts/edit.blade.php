@extends('layouts.admin')

@section('title', 'Edit Postingan')
@section('breadcrumb', 'Edit Postingan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Edit Postingan</h1>
        <p>Perbarui konten dan pengaturan artikel ini.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" form="editForm" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </div>
</div>

<form id="editForm" action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="published">
    <input type="hidden" name="content" id="contentHidden">

    <div class="row">
        <div class="col-lg-8">
            <!-- Info Banner -->
            <div class="edit-product-info-banner mb-4">
                <div class="edit-product-info-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <div class="edit-product-info-content">
                    <h5 class="edit-product-info-title">Editor Artikel</h5>
                    <p class="edit-product-info-desc">Edit konten artikel dengan TipTap rich text editor. Gunakan toolbar untuk memformat teks, menambahkan heading, daftar, dan styling.</p>
                </div>
            </div>

            <!-- Card 1: Informasi Artikel -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-edit"></i> Informasi Artikel</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $post->title) }}" required
                               placeholder="Contoh: Tips Memilih Hunian Nyaman untuk Keluarga">
                        <div class="form-text">Judul yang menarik membantu artikel ditemukan di mesin pencari.</div>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug_preview" class="form-label">Slug (URL)</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ url('/blog') }}/</span>
                            <input type="text" class="form-control" id="slug_preview" 
                                   value="{{ $post->slug }}" disabled>
                        </div>
                        <div class="form-text">URL permanen artikel dibuat otomatis dari judul.</div>
                    </div>

                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk Terkait</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" name="product_id">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-banner="{{ $product->banner_image ? asset('storage/' . $product->banner_image) : '' }}"
                                        {{ old('product_id', $post->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Hubungkan artikel dengan produk untuk konteks yang relevan.</div>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="contact_phone" class="form-label">Nomor Kontak</label>
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                               id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $post->contact_phone) }}"
                               placeholder="Contoh: 08123456789">
                        <div class="form-text">Nomor telepon yang bisa dihubungi untuk informasi lebih lanjut.</div>
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card 2: Deskripsi (TipTap Editor) -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-align-left"></i> Deskripsi Artikel</div>
                </div>
                <div class="card-body p-0">
                    <!-- TipTap Toolbar -->
                    <div class="tiptap-toolbar" id="tiptapToolbar">
                        <button type="button" class="tiptap-btn" data-action="toggleBold" title="Bold">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" class="tiptap-btn" data-action="toggleItalic" title="Italic">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" class="tiptap-btn" data-action="toggleStrike" title="Strikethrough">
                            <i class="fas fa-strikethrough"></i>
                        </button>
                        <button type="button" class="tiptap-btn" data-action="toggleCode" title="Code">
                            <i class="fas fa-code"></i>
                        </button>
                        <span class="tiptap-divider"></span>
                        <button type="button" class="tiptap-btn" data-action="toggleHeading" data-level="1" title="Heading 1">
                            H1
                        </button>
                        <button type="button" class="tiptap-btn" data-action="toggleHeading" data-level="2" title="Heading 2">
                            H2
                        </button>
                        <button type="button" class="tiptap-btn" data-action="toggleHeading" data-level="3" title="Heading 3">
                            H3
                        </button>
                        <span class="tiptap-divider"></span>
                        <button type="button" class="tiptap-btn" data-action="toggleBulletList" title="Bullet List">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <button type="button" class="tiptap-btn" data-action="toggleOrderedList" title="Ordered List">
                            <i class="fas fa-list-ol"></i>
                        </button>
                        <button type="button" class="tiptap-btn" data-action="toggleBlockquote" title="Blockquote">
                            <i class="fas fa-quote-right"></i>
                        </button>
                        <button type="button" class="tiptap-btn" data-action="setHorizontalRule" title="Horizontal Rule">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="tiptap-divider"></span>
                        <button type="button" class="tiptap-btn" data-action="undo" title="Undo">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button type="button" class="tiptap-btn" data-action="redo" title="Redo">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                    <!-- TipTap Editor Container -->
                    <div id="tiptapEditor" style="min-height:400px;padding:16px 20px;font-size:15px;line-height:1.7;color:var(--gray-800);"></div>
                    @error('content')
                        <div class="text-danger small" style="padding:0 20px 12px;">{{ $message }}</div>
                    @enderror
                    <div style="padding:0 20px 16px;">
                        <div class="form-text"><span id="contentWordCount">0</span> kata &bull; <span id="contentCharCount">0</span> karakter &bull; ±<span id="contentReadTime">1</span> menit baca</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Card 4: Featured Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-image"></i> Featured Image</div>
                </div>
                <div class="card-body">
                    <!-- Status Banner -->
                    <div id="featuredStatusBanner" class="edit-product-info-banner mb-4" style="padding:12px 16px;display:{{ $post->featured_image ? 'flex' : 'none' }};">
                        <div class="edit-product-info-icon" style="width:32px;height:32px;font-size:14px;border-radius:8px;">
                            <i class="fas fa-link"></i>
                        </div>
                        <div class="edit-product-info-content">
                            <p class="edit-product-info-desc" style="margin:0;font-size:12px;" id="featuredStatusText">
                                @if($post->featured_image)
                                    Gambar featured yang sedang digunakan.
                                @else
                                    Banner produk akan digunakan sebagai featured image.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Preview Area -->
                    <label class="form-label fw-semibold">Preview</label>
                    <div class="banner-preview-box" id="featuredPreviewBox" style="min-height:180px;">
                        @if($post->featured_image)
                            <img id="featuredPreviewImg" src="{{ asset('storage/' . $post->featured_image) }}" alt="Preview" style="max-width:100%;max-height:180px;border-radius:8px;object-fit:contain;">
                            <div class="banner-empty-state" id="featuredEmptyState" style="display:none;">
                                <i class="fas fa-image"></i>
                                <span>Pilih Produk Terkait untuk menampilkan banner</span>
                            </div>
                        @else
                            <div class="banner-empty-state" id="featuredEmptyState">
                                <i class="fas fa-image"></i>
                                <span>Pilih Produk Terkait untuk menampilkan banner</span>
                            </div>
                            <img id="featuredPreviewImg" src="" alt="Preview" style="display:none;max-width:100%;max-height:180px;border-radius:8px;object-fit:contain;">
                        @endif
                    </div>

                    <!-- Upload Area -->
                    <label class="form-label fw-semibold mt-3">Upload Custom</label>
                    <div class="banner-upload-box" id="featuredUploadBox" style="min-height:100px;padding:12px;">
                        <div class="banner-upload-placeholder" id="featuredPlaceholder" style="gap:4px;">
                            <i class="fas fa-cloud-upload-alt" style="font-size:20px;"></i>
                            <span style="font-size:13px;">Klik atau seret gambar untuk mengganti</span>
                        </div>
                        <input type="file" class="banner-file-input" 
                               id="featured_image" name="featured_image" 
                               accept="image/jpeg,image/png,image/webp">
                    </div>
                    <div class="form-text text-center">Format: JPG, PNG, WEBP. Maks 2MB.</div>
                    @error('featured_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Card 5: SEO Preview -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-search"></i> SEO Preview</div>
                </div>
                <div class="card-body">
                    <div class="edit-product-info-banner mb-3" style="padding:12px 16px;">
                        <div class="edit-product-info-icon" style="width:32px;height:32px;font-size:14px;border-radius:8px;">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="edit-product-info-content">
                            <p class="edit-product-info-desc" style="margin:0;font-size:12px;">Pratinjau bagaimana artikel ini muncul di hasil pencarian Google. Deskripsi diambil dari konten artikel (1.500–2.500 karakter).</p>
                        </div>
                    </div>

                    <div class="seo-preview-box">
                        <div class="seo-preview-title" id="seoTitle">{{ $post->title }}</div>
                        <div class="seo-preview-url">{{ url('/blog') }}/<span id="seoSlug">{{ $post->slug }}</span></div>
                        <div class="seo-preview-desc" id="seoDesc">{!! Str::limit(strip_tags($post->content), 160) !!}</div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Karakter deskripsi SEO:</small>
                            <small class="fw-semibold" id="seoCharCount">{{ Str::length(strip_tags($post->content)) }} / 1.600</small>
                        </div>
                        <div class="progress" style="height:6px;border-radius:3px;">
                            @php
                                $charLen = Str::length(strip_tags($post->content));
                                $pct = min(100, ($charLen / 2500) * 100);
                                $barClass = 'bg-success';
                                if ($charLen < 1500) $barClass = 'bg-warning';
                                elseif ($charLen > 2500) $barClass = 'bg-danger';
                            @endphp
                            <div class="progress-bar {{ $barClass }}" id="seoProgressBar" role="progressbar" style="width:{{ $pct }}%;border-radius:3px;"></div>
                        </div>
                        <small class="text-muted">Ideal: 1.500–2.500 karakter untuk SEO optimal.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script type="module">
import { Editor } from 'https://esm.sh/@tiptap/core@2.6.6';
import StarterKit from 'https://esm.sh/@tiptap/starter-kit@2.6.6';

// Initialize TipTap Editor with existing content
const editor = new Editor({
    element: document.querySelector('#tiptapEditor'),
    extensions: [StarterKit],
    content: `{!! old('content', $post->content) !!}`,
    editorProps: {
        attributes: {
            class: 'tiptap-content',
        },
    },
    onUpdate: ({ editor }) => {
        document.getElementById('contentHidden').value = editor.getHTML();
        updateContentStats(editor);
        updateSeoFromContent(editor);
    },
});

// Initial sync
document.getElementById('contentHidden').value = editor.getHTML();

// Toolbar button handlers
document.querySelectorAll('.tiptap-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const action = btn.dataset.action;
        const level = btn.dataset.level;
        
        switch(action) {
            case 'toggleBold': editor.chain().focus().toggleBold().run(); break;
            case 'toggleItalic': editor.chain().focus().toggleItalic().run(); break;
            case 'toggleStrike': editor.chain().focus().toggleStrike().run(); break;
            case 'toggleCode': editor.chain().focus().toggleCode().run(); break;
            case 'toggleHeading': editor.chain().focus().toggleHeading({ level: parseInt(level) }).run(); break;
            case 'toggleBulletList': editor.chain().focus().toggleBulletList().run(); break;
            case 'toggleOrderedList': editor.chain().focus().toggleOrderedList().run(); break;
            case 'toggleBlockquote': editor.chain().focus().toggleBlockquote().run(); break;
            case 'setHorizontalRule': editor.chain().focus().setHorizontalRule().run(); break;
            case 'undo': editor.chain().focus().undo().run(); break;
            case 'redo': editor.chain().focus().redo().run(); break;
        }
        
        updateToolbarActive(editor);
    });
});

// Update toolbar active states
function updateToolbarActive(editor) {
    document.querySelectorAll('.tiptap-btn').forEach(btn => {
        const action = btn.dataset.action;
        const level = btn.dataset.level;
        let isActive = false;
        
        switch(action) {
            case 'toggleBold': isActive = editor.isActive('bold'); break;
            case 'toggleItalic': isActive = editor.isActive('italic'); break;
            case 'toggleStrike': isActive = editor.isActive('strike'); break;
            case 'toggleCode': isActive = editor.isActive('code'); break;
            case 'toggleHeading': isActive = editor.isActive('heading', { level: parseInt(level) }); break;
            case 'toggleBulletList': isActive = editor.isActive('bulletList'); break;
            case 'toggleOrderedList': isActive = editor.isActive('orderedList'); break;
            case 'toggleBlockquote': isActive = editor.isActive('blockquote'); break;
        }
        
        btn.classList.toggle('active', isActive);
    });
}

// Content stats
function updateContentStats(editor) {
    const text = editor.state.doc.textContent;
    const words = text.trim() ? text.trim().split(/\s+/).length : 0;
    const chars = text.length;
    const readMin = Math.max(1, Math.ceil(words / 200));
    
    document.getElementById('contentWordCount').textContent = words;
    document.getElementById('contentCharCount').textContent = chars;
    document.getElementById('contentReadTime').textContent = readMin;
}

// SEO from content (1500-2500 chars)
function updateSeoFromContent(editor) {
    const text = editor.state.doc.textContent;
    let desc = text.substring(0, 2000).trim();
    if (desc.length > 1600) desc = desc.substring(0, 1600) + '...';
    
    document.getElementById('seoDesc').textContent = desc || 'Deskripsi artikel akan muncul di sini sebagai deskripsi di hasil pencarian Google.';
    
    const charCount = desc.length;
    document.getElementById('seoCharCount').textContent = charCount.toLocaleString('id-ID') + ' / 1.600';
    
    const pct = Math.min(100, (charCount / 2500) * 100);
    const bar = document.getElementById('seoProgressBar');
    bar.style.width = pct + '%';
    bar.className = 'progress-bar';
    if (charCount >= 1500 && charCount <= 2500) {
        bar.classList.add('bg-success');
    } else if (charCount < 1500) {
        bar.classList.add('bg-warning');
    } else {
        bar.classList.add('bg-danger');
    }
}

// Initial stats
updateContentStats(editor);

    // jQuery-dependent code
$(document).ready(function() {
    // Tom Select for product
    var hasCustomImage = {{ $post->featured_image ? 'true' : 'false' }};
    var hasUserUploadedFile = false;
    var productSelect = new TomSelect('#product_id', {
        placeholder: 'Pilih atau cari produk...',
        allowEmptyOption: true,
        dropdownParent: 'body',
        onChange: function(value) {
            if (!value) {
                // No product selected — check if there's a custom image or uploaded file
                if (!hasCustomImage && !hasUserUploadedFile) {
                    $('#featuredPreviewImg').hide().attr('src', '');
                    $('#featuredEmptyState').show();
                    $('#featuredStatusBanner').hide();
                } else if (hasUserUploadedFile) {
                    $('#featuredStatusBanner').show();
                    $('#featuredStatusText').text('Gambar custom yang diunggah digunakan sebagai featured image.');
                } else {
                    // Keep showing existing DB custom image
                    $('#featuredStatusBanner').show();
                    $('#featuredStatusText').text('Gambar featured yang sedang digunakan.');
                }
                return;
            }
            
            // If user has uploaded a new file in this session, don't override it
            if (hasUserUploadedFile) {
                $('#featuredStatusBanner').show();
                $('#featuredStatusText').text('Gambar custom yang diunggah digunakan sebagai featured image.');
                return;
            }
            
            // Get selected option's data-banner attribute
            var selectedOption = document.querySelector('#product_id option[value="' + value + '"]');
            if (selectedOption) {
                var bannerUrl = selectedOption.getAttribute('data-banner');
                if (bannerUrl) {
                    $('#featuredPreviewImg').attr('src', bannerUrl).show();
                    $('#featuredEmptyState').hide();
                    $('#featuredStatusBanner').show();
                    $('#featuredStatusText').text('Banner produk digunakan sebagai featured image. Upload custom untuk mengganti.');
                } else {
                    $('#featuredPreviewImg').hide().attr('src', '');
                    $('#featuredEmptyState').html('<i class="fas fa-image"></i><span>Produk ini belum memiliki banner</span>');
                    $('#featuredEmptyState').show();
                    $('#featuredStatusBanner').show();
                    $('#featuredStatusText').text('Produk ini belum memiliki banner. Silakan upload gambar custom.');
                }
            }
        }
    });
    
    // Trigger onChange on init if product already selected (to show product banner)
    var initProductId = productSelect.getValue();
    if (initProductId) {
        // Directly show product banner on initial load
        var initOption = document.querySelector('#product_id option[value="' + initProductId + '"]');
        if (initOption) {
            var initBanner = initOption.getAttribute('data-banner');
            if (initBanner && !hasCustomImage) {
                $('#featuredPreviewImg').attr('src', initBanner).show();
                $('#featuredEmptyState').hide();
                $('#featuredStatusBanner').show();
                $('#featuredStatusText').text('Banner produk digunakan sebagai featured image. Upload custom untuk mengganti.');
            }
        }
    }
    
    // Live slug preview from title
    $('#title').on('input', function() {
        var title = $(this).val();
        var slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $('#slug_preview').val(slug || '{{ $post->slug }}');
        $('#seoSlug').text(slug || '{{ $post->slug }}');
        $('#seoTitle').text(title || 'Judul Artikel');
    });
    
    // Featured image preview
    var featuredInput = document.getElementById('featured_image');
    featuredInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('featuredPreviewImg');
                img.src = e.target.result;
                img.style.display = 'block';
                document.getElementById('featuredEmptyState').style.display = 'none';
                
                // Mark as custom image uploaded in this session — won't be overridden by product banner
                hasUserUploadedFile = true;
                $('#featuredStatusBanner').show();
                $('#featuredStatusText').text('Gambar custom yang diunggah digunakan sebagai featured image.');
                
                var placeholder = document.getElementById('featuredPlaceholder');
                placeholder.innerHTML = '<img src="' + e.target.result + '" alt="Featured" style="max-height:120px;border-radius:8px;object-fit:cover;">' +
                    '<span style="font-size:12px;color:var(--gray-500);margin-top:6px;">Klik untuk ganti gambar</span>';
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Sync hidden content before form submit
    document.getElementById('editForm').addEventListener('submit', function() {
        document.getElementById('contentHidden').value = editor.getHTML();
    });
});
</script>
@endpush