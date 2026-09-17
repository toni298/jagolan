@extends('layouts.admin')

@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Users</h1>
        <p>Kelola akun pengguna panel admin.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
</div>

<div class="modern-table-card">
    <div class="modern-table-header">
        <div class="modern-table-header-left">
            <div class="modern-table-title">Daftar Users</div>
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
                <input type="text" id="searchInput" placeholder="Cari user...">
            </div>
        </div>
    </div>
    <div class="modern-table-wrapper">
        <table id="usersTable" class="modern-table" style="display:none">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="50">Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="100">Role</th>
                    <th width="120">Status</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div id="tablePlaceholder" style="padding:20px">
            <table class="modern-table" style="width:100%">
                <thead><tr><th width="50">No</th><th width="50">Foto</th><th>Nama</th><th>Email</th><th width="100">Role</th><th width="120">Status</th><th width="100">Aksi</th></tr></thead>
                <tbody>
                    @foreach(range(1,5) as $i)
                    <tr>
                        <td><div class="skeleton" style="width:20px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:34px;height:34px;border-radius:8px"></div></td>
                        <td><div class="skeleton" style="width:110px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:160px;height:14px"></div></td>
                        <td><div class="skeleton" style="width:60px;height:22px;border-radius:99px"></div></td>
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

    var table = $('#usersTable').DataTable({
        processing: true, serverSide: true,
        ajax: "{{ route('admin.users.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'avatar', name: 'avatar', orderable: false, searchable: false, render: function(d, type, row) { if (d) return '<img src="/storage/' + d + '" alt="Avatar" class="table-cell-avatar">'; return '<img src="https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&background=3B82F6&color=fff&size=68" alt="Avatar" class="table-cell-avatar">'; } },
            { data: 'name', name: 'name', render: function(d) { return '<div class="table-cell-name">' + d + '</div>'; } },
            { data: 'email', name: 'email', render: function(d) { return '<span style="color:var(--gray-500)">' + d + '</span>'; } },
            { data: 'role', name: 'role', render: function(d) { return '<span class="badge-status badge-admin">' + d.toUpperCase() + '</span>'; } },
            { data: 'status_badge', name: 'is_active' },
            { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type, row) { return '<div class="table-cell-actions"><a href="/admin/users/' + row.id + '/edit" class="btn-icon btn-icon-primary" title="Edit"><i class="fas fa-pen" style="font-size:13px"></i></a><button class="btn-icon btn-icon-danger" title="Hapus" onclick="deleteUser(\'' + row.id + '\')"><i class="fas fa-trash" style="font-size:13px"></i></button></div>'; } }
        ],
        dom: 'rt', pageLength: 10, language: { zeroRecords: 'Tidak ada data yang cocok' },
        drawCallback: function(s) { var api = this.api(); $('#tableCount').text(api.page.info().recordsDisplay); $('#usersTable').show(); $('#tablePlaceholder').hide(); renderPagination(api.page.info(), api); updateInfo(s);
            // Mobile card rendering
            renderMobileCards('#usersTable', function(row, idx) {
                var imgHtml = row.avatar ? '<img src="/storage/' + row.avatar + '" class="mobile-card-img" alt="">' : '<img src="https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&background=3B82F6&color=fff&size=80" class="mobile-card-img" alt="">';
                var actions = '<div class="mobile-card-actions">' +
                    '<a href="/admin/users/' + row.id + '/edit" class="btn-icon btn-icon-primary"><i class="fas fa-pen"></i> Edit</a>' +
                    '<button class="btn-icon btn-icon-danger" onclick="deleteUser(\'' + row.id + '\')"><i class="fas fa-trash"></i> Hapus</button>' +
                '</div>';
                return '<div class="mobile-card-row-header">' + imgHtml + '<div class="mobile-card-title">' + row.name + '<br><span style="font-size:12px;color:var(--gray-500);font-weight:400">' + row.email + '</span></div></div>' +
                    '<div class="mobile-card-fields">' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Role</span><span class="mobile-card-field-value">' + (row.role ? '<span class="badge-status badge-admin">' + row.role.toUpperCase() + '</span>' : '-') + '</span></div>' +
                        '<div class="mobile-card-field"><span class="mobile-card-field-label">Status</span><span class="mobile-card-field-value">' + (row.status_badge || '-') + '</span></div>' +
                    '</div>' + actions;
            });
        }
    });

    $('#searchInput').on('keyup', function() { table.search(this.value).draw(); });
    $('#lengthSelect').on('change', function() { table.page.len(parseInt(this.value)).draw(); });

    window.deleteUser = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
            $.ajax({ url: '/admin/users/' + id, type: 'POST', data: { _token: $('meta[name="csrf-token"]').attr('content'), _method: 'DELETE' },
                success: function(r) { if (r.success) { table.ajax.reload(); toastr.success(r.message, 'Berhasil'); } },
                error: function(xhr) { if (xhr.responseJSON && xhr.responseJSON.message) toastr.error(xhr.responseJSON.message, 'Gagal'); else toastr.error('Terjadi kesalahan saat menghapus data', 'Gagal'); }
            });
        }
    };
});
</script>
@endpush
