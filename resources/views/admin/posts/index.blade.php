@extends('layouts.admin')

@section('title', 'Postingan')
@section('breadcrumb', 'Postingan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Postingan</h1>
        <p>Kelola semua postingan blog.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Postingan
        </a>
    </div>
</div>

<div class="modern-table-card">
    <div class="modern-table-header">
        <div class="modern-table-header-left">
            <div class="modern-table-title">Daftar Postingan</div>
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
                <input type="text" id="searchInput" placeholder="Cari postingan...">
            </div>
        </div>
    </div>
    <div class="modern-table-wrapper">
        <table id="postsTable" class="modern-table" style="display:none">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="70">Gambar</th>
                    <th>Judul</th>
                    <th>Produk</th>
                    <th>Kontak</th>
                    <th>Penulis</th>
                    <th width="120">Status</th>
                    <th width="120">Tanggal</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div id="tablePlaceholder" style="padding:20px">
            <table class="modern-table" style="width:100%">
                <thead><tr><th width="50">No</th><th width="70">Gambar</th><th>Judul</th><th>Produk</th><th>Kontak</th><th>Penulis</th><th width="120">Status</th><th width="120">Tanggal</th><th width="100">Aksi</th></tr></thead>
                <tbody>
                    @foreach(range(1,5) as $i)
                    <tr>
                        <td><div class="skeleton" style="width:20px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:48px;height:36px;border-radius:8px"></div></td>
                        <td><div class="skeleton" style="width:140px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:90px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:80px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:80px;height:22px;border-radius:99px"></div></td>
                        <td><div class="skeleton" style="width:90px;height:14px"></div></td>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function renderPagination(info, table) {
        var pages = info.pages, current = info.page, $c = $('#paginationControls');
        $c.empty();
        $c.append('<button class="page-btn" data-page="prev" ' + (current === 0 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>');
        var start = Math.max(0, current - 2), end = Math.min(pages, start + 5);
        if (end - start < 5) start = Math.max(0, end - 5);
        if (start > 0) { $c.append('<button class="page-btn" data-page="0">1</button>'); if (start > 1) $c.append('<button class="page-btn" disabled>...</button>'); }
        for (var i = start; i < end; i++) $c.append('<button class="page-btn' + (i === current ? ' active' : '') + '" data-page="' + i + '">' + (i + 1) + '</button>');
        if (end < pages) { if (end < pages - 1) $c.append('<button class="page-btn" disabled>...</button>'); $c.append('<button class="page-btn" data-page="' + (pages - 1) + '">' + pages + '</button>'); }
        $c.append('<button class="page-btn" data-page="next" ' + (current === pages - 1 ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>');
        $c.find('.page-btn:not([disabled])').on('click', function() { var p = $(this).data('page'); if (p === 'prev') table.page('previous').draw('page'); else if (p === 'next') table.page('next').draw('page'); else table.page(p).draw('page'); });
    }
    function updateInfo(s) { var api = new $.fn.dataTable.Api(s), info = api.page.info(); var t = 'Menampilkan <strong>' + (info.start + 1) + '</strong> - <strong>' + info.end + '</strong> dari <strong>' + info.recordsDisplay + '</strong> data'; if (info.recordsDisplay < info.recordsTotal) t += ' (difilter dari ' + info.recordsTotal + ' total)'; $('#tableInfo').html(t); }

    var table = $('#postsTable').DataTable({
        processing: true, serverSide: true,
        ajax: "{{ route('admin.posts.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'display_image', name: 'display_image', orderable: false, searchable: false, render: function(d, type, row) { if (d) { var src = '/storage/' + d; var tip = row.image_source === 'product' ? 'Banner Produk' : 'Gambar Custom'; return '<img src="' + src + '" alt="Featured" class="table-cell-image" title="' + tip + '">'; } return '<div class="table-cell-placeholder"><i class="fas fa-image"></i></div>'; } },
            { data: 'title', name: 'title', render: function(d) { return '<div class="table-cell-name">' + d + '</div>'; } },
            { data: 'product_name', name: 'product_name', render: function(d) { return '<span style="color:var(--gray-500)">' + (d || '-') + '</span>'; } },
            { data: 'contact_phone', name: 'contact_phone', render: function(d) { return d ? '<span style="font-size:13px">' + d + '</span>' : '<span style="color:var(--gray-400)">-</span>'; } },
            { data: 'user.name', name: 'user.name' },
            { data: 'status_badge', name: 'status' },
            { data: 'published_at', name: 'published_at', render: function(d) { if (!d) return '<span style="color:var(--gray-400)">-</span>'; return '<span style="font-size:13px;color:var(--gray-600)">' + new Date(d).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) + '</span>'; } },
            { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type, row) { return '<div class="table-cell-actions"><a href="/admin/posts/' + row.id + '/edit" class="btn-icon btn-icon-primary" title="Edit"><i class="fas fa-pen" style="font-size:13px"></i></a><button class="btn-icon btn-icon-danger" title="Hapus" onclick="deletePost(\'' + row.id + '\')"><i class="fas fa-trash" style="font-size:13px"></i></button></div>'; } }
        ],
        dom: 'rt', pageLength: 10, language: { zeroRecords: 'Tidak ada data yang cocok' },
        drawCallback: function(s) { var api = this.api(); $('#tableCount').text(api.page.info().recordsDisplay); $('#postsTable').show(); $('#tablePlaceholder').hide(); renderPagination(api.page.info(), api); updateInfo(s);
            // Mobile card rendering
            renderMobileCards('#postsTable', function(row, idx) {
                var imgHtml = row.display_image ? '<img src="/storage/' + row.display_image + '" class="mobile-card-img" alt="">' : '<div class="mobile-card-img-placeholder"><i class="fas fa-image"></i></div>';
                var actions = '<div class="mobile-card-actions">' +
                    '<a href="/admin/posts/' + row.id + '/edit" class="btn-icon btn-icon-primary"><i class="fas fa-pen"></i> Edit</a>' +
                    '<button class="btn-icon btn-icon-danger" onclick="deletePost(\'' + row.id + '\')"><i class="fas fa-trash"></i> Hapus</button>' +
                '</div>';
                return '<div class="mobile-card-row-header">' + imgHtml + '<div class="mobile-card-title">' + row.title + '</div></div>' +
                    '<div class="mobile-card-fields">' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Produk</span><span class="mobile-card-field-value">' + (row.product_name || '-') + '</span></div>' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Kontak</span><span class="mobile-card-field-value">' + (row.contact_phone || '-') + '</span></div>' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Penulis</span><span class="mobile-card-field-value">' + (row.user ? row.user.name : '-') + '</span></div>' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Status</span><span class="mobile-card-field-value">' + (row.status_badge || '-') + '</span></div>' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Tanggal</span><span class="mobile-card-field-value">' + (row.published_at ? new Date(row.published_at).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'}) : '-') + '</span></div>' +
                    '</div>' + actions;
            });
        }
    });

    $('#searchInput').on('keyup', function() { table.search(this.value).draw(); });
    $('#lengthSelect').on('change', function() { table.page.len(parseInt(this.value)).draw(); });

    window.deletePost = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus postingan ini?')) {
            $.ajax({ url: '/admin/posts/' + id, type: 'POST', data: { _token: $('meta[name="csrf-token"]').attr('content'), _method: 'DELETE' },
                success: function(r) { if (r.success) { table.ajax.reload(); toastr.success(r.message, 'Berhasil'); } },
                error: function() { toastr.error('Terjadi kesalahan saat menghapus data', 'Gagal'); }
            });
        }
    };
});
</script>
@endpush
