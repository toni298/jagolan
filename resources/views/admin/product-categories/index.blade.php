@extends('layouts.admin')

@section('title', 'Kategori Produk')
@section('breadcrumb', 'Kategori Produk')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Kategori Produk</h1>
        <p>Kelola semua kategori produk yang tersedia.</p>
    </div>
    <div class="page-header-actions">
        <button class="btn btn-primary" onclick="showCreateModal()">
            <i class="fas fa-plus"></i> Tambah Kategori Produk
        </button>
    </div>
</div>

<div class="modern-table-card">
    <div class="modern-table-header">
        <div class="modern-table-header-left">
            <div class="modern-table-title">Daftar Kategori Produk</div>
        </div>
        <div class="modern-table-header-right">
            <div class="table-length">
                <select id="lengthSelect">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="table-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari kategori produk...">
            </div>
        </div>
    </div>
    <div class="modern-table-wrapper">
        <table id="productCategorysTable" class="modern-table" style="display:none">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th width="120">Status</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- Empty / Loading State -->
        <div id="tablePlaceholder" style="padding:20px">
            <table class="modern-table" style="width:100%">
                <thead>
                    <tr>
                        <th width="50">No</th><th>Nama</th><th>Slug</th><th width="120">Status</th><th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1,5) as $i)
                    <tr>
                        <td><div class="skeleton" style="width:20px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:120px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:100px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:180px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:70px;height:22px;border-radius:99px"></div></td>
                        <td><div class="skeleton" style="width:60px;height:14px"></div></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Custom Footer -->
    <div class="modern-table-footer">
        <div class="modern-table-info" id="tableInfo">Menampilkan 0 data</div>
        <div class="pagination-controls" id="paginationControls"></div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="productCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="productCategoryForm">
                @csrf
                <input type="hidden" id="method" name="_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kategori Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama kategori produk" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 mt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_default" value="Default" checked>
                                <label class="form-check-label" for="status_default">Default</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_sewaan" value="Sewaan">
                                <label class="form-check-label" for="status_sewaan">Sewaan</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-check"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Custom pagination renderer
    function renderPagination(info, table) {
        var pages = info.pages;
        var current = info.page;
        var $controls = $('#paginationControls');
        $controls.empty();

        // Prev button
        $controls.append('<button class="page-btn" data-page="prev" ' + (current === 0 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>');

        // Page buttons (max 5 visible)
        var start = Math.max(0, current - 2);
        var end = Math.min(pages, start + 5);
        if (end - start < 5) start = Math.max(0, end - 5);

        if (start > 0) {
            $controls.append('<button class="page-btn" data-page="0">1</button>');
            if (start > 1) $controls.append('<button class="page-btn" disabled>...</button>');
        }

        for (var i = start; i < end; i++) {
            $controls.append('<button class="page-btn' + (i === current ? ' active' : '') + '" data-page="' + i + '">' + (i + 1) + '</button>');
        }

        if (end < pages) {
            if (end < pages - 1) $controls.append('<button class="page-btn" disabled>...</button>');
            $controls.append('<button class="page-btn" data-page="' + (pages - 1) + '">' + pages + '</button>');
        }

        // Next button
        $controls.append('<button class="page-btn" data-page="next" ' + (current === pages - 1 ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>');

        // Click handler
        $controls.find('.page-btn:not([disabled])').on('click', function() {
            var page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else table.page(page).draw('page');
        });
    }

    function updateInfo(settings) {
        var api = new $.fn.dataTable.Api(settings);
        var info = api.page.info();
        var text = 'Menampilkan <strong>' + (info.start + 1) + '</strong> - <strong>' + info.end + '</strong> dari <strong>' + info.recordsDisplay + '</strong> data';
        if (info.recordsDisplay < info.recordsTotal) {
            text += ' (difilter dari ' + info.recordsTotal + ' total)';
        }
        $('#tableInfo').html(text);
    }

    var table = $('#productCategorysTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.product-categories.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name', render: function(d) { return '<div class="table-cell-name">' + d + '</div>'; } },
            { data: 'slug', name: 'slug', render: function(d) { return '<div class="table-cell-slug">' + d + '</div>'; } },
            { data: 'status_badge', name: 'status' },
            {
                data: 'id', name: 'id', orderable: false, searchable: false,
                render: function(data, type, row) {
                    return '<div class="table-cell-actions">' +
                        '<button class="btn-icon btn-icon-primary" onclick="editProductCategory(\'' + row.id + '\')" title="Edit"><i class="fas fa-pen" style="font-size:13px"></i></button>' +
                        '<button class="btn-icon btn-icon-danger" onclick="deleteProductCategory(\'' + row.id + '\')" title="Hapus"><i class="fas fa-trash" style="font-size:13px"></i></button>' +
                    '</div>';
                }
            }
        ],
        dom: 'rt',
        pageLength: 10,
        language: { zeroRecords: 'Tidak ada data yang cocok' },
        drawCallback: function(settings) {
            var api = this.api();
            $('#tableCount').text(api.page.info().recordsDisplay);
            $('#productCategorysTable').show();
            $('#tablePlaceholder').hide();
            renderPagination(api.page.info(), api);
            updateInfo(settings);
            // Mobile card rendering
            renderMobileCards('#productCategorysTable', function(row, idx) {
                var actions = '<div class="mobile-card-actions">' +
                    '<button class="btn-icon btn-icon-primary" onclick="editProductCategory(\'' + row.id + '\')"><i class="fas fa-pen"></i> Edit</button>' +
                    '<button class="btn-icon btn-icon-danger" onclick="deleteProductCategory(\'' + row.id + '\')"><i class="fas fa-trash"></i> Hapus</button>' +
                '</div>';
                return '<div class="mobile-card-row-header"><div class="mobile-card-title">' + row.name + '</div></div>' +
                    '<div class="mobile-card-fields">' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Slug</span><span class="mobile-card-field-value" style="font-family:monospace;font-size:11px;color:var(--accent)">' + row.slug + '</span></div>' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Status</span><span class="mobile-card-field-value">' + (row.status_badge || '-') + '</span></div>' +
                    '</div>' + actions;
            });
        }
    });

    // Custom search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Length selector
    $('#lengthSelect').on('change', function() {
        table.page.len(parseInt(this.value)).draw();
    });

    window.showCreateModal = function() {
        $('#modalTitle').text('Tambah Kategori Produk');
        $('#productCategoryForm').attr('action', "{{ route('admin.product-categories.store') }}");
        $('#method').val('POST');
        $('#productCategoryForm')[0].reset();
        $('#productCategoryModal').modal('show');
    };

    window.editProductCategory = function(id) {
        $.get('/admin/product-categories/' + id, function(data) {
            $('#modalTitle').text('Edit Kategori Produk');
            $('#productCategoryForm').attr('action', '/admin/product-categories/' + id);
            $('#method').val('PUT');
            $('#name').val(data.name);
            $('input[name="status"][value="' + data.status + '"]').prop('checked', true);
            $('#productCategoryModal').modal('show');
        });
    };

    $('#productCategoryForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#productCategoryModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message, 'Berhasil');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function(key, val) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key + 'Error').text(val[0]);
                    });
                } else {
                    toastr.error('Terjadi kesalahan saat menyimpan data', 'Gagal');
                }
            }
        });
    });

    window.deleteProductCategory = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus kategori produk ini?')) {
            $.ajax({
                url: '/admin/product-categories/' + id,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                success: function(response) {
                    if (response.success) { table.ajax.reload(); toastr.success(response.message, 'Berhasil'); }
                },
                error: function() { toastr.error('Terjadi kesalahan saat menghapus data', 'Gagal'); }
            });
        }
    };

    $('#productCategoryModal').on('hidden.bs.modal', function() {
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
    });
});
</script>
@endpush