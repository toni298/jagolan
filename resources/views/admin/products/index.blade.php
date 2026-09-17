@extends('layouts.admin')

@section('title', 'Produk')
@section('breadcrumb', 'Produk')

@section('content')
    <div class="page-header">
        <div class="page-header-left">
            <h1>Produk</h1>
            <p>Kelola semua produk yang tersedia.</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    <div class="modern-table-card">
        <div class="modern-table-header">
            <div class="modern-table-header-left">
                <div class="modern-table-title">Daftar Produk</div>
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
                    <input type="text" id="searchInput" placeholder="Cari produk...">
                </div>
            </div>
        </div>
        <div class="modern-table-wrapper">
            <table id="productsTable" class="modern-table" style="display:none">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="70">Banner</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Slug</th>
                        <th width="120">Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div id="tablePlaceholder" style="padding:20px">
                <table class="modern-table" style="width:100%">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="70">Banner</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Slug</th>
                            <th width="120">Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (range(1, 5) as $i)
                            <tr>
                                <td>
                                    <div class="skeleton" style="width:20px;height:14px"></div>
                                </td>
                                <td>
                                    <div class="skeleton" style="width:48px;height:36px;border-radius:8px"></div>
                                </td>
                                <td>
                                    <div class="skeleton" style="width:120px;height:14px"></div>
                                </td>
                                <td>
                                    <div class="skeleton" style="width:80px;height:14px"></div>
                                </td>
                                <td>
                                    <div class="skeleton" style="width:90px;height:14px"></div>
                                </td>
                                <td>
                                    <div class="skeleton" style="width:100px;height:14px"></div>
                                </td>
                                <td>
                                    <div class="skeleton" style="width:70px;height:22px;border-radius:99px"></div>
                                </td>
                                <td>
                                    <div class="skeleton" style="width:60px;height:14px"></div>
                                </td>
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
                var pages = info.pages,
                    current = info.page,
                    $c = $('#paginationControls');
                $c.empty();
                $c.append('<button class="page-btn" data-page="prev" ' + (current === 0 ? 'disabled' : '') +
                    '><i class="fas fa-chevron-left"></i></button>');
                var start = Math.max(0, current - 2),
                    end = Math.min(pages, start + 5);
                if (end - start < 5) start = Math.max(0, end - 5);
                if (start > 0) {
                    $c.append('<button class="page-btn" data-page="0">1</button>');
                    if (start > 1) $c.append('<button class="page-btn" disabled>...</button>');
                }
                for (var i = start; i < end; i++) $c.append('<button class="page-btn' + (i === current ? ' active' :
                    '') + '" data-page="' + i + '">' + (i + 1) + '</button>');
                if (end < pages) {
                    if (end < pages - 1) $c.append('<button class="page-btn" disabled>...</button>');
                    $c.append('<button class="page-btn" data-page="' + (pages - 1) + '">' + pages + '</button>');
                }
                $c.append('<button class="page-btn" data-page="next" ' + (current === pages - 1 ? 'disabled' : '') +
                    '><i class="fas fa-chevron-right"></i></button>');
                $c.find('.page-btn:not([disabled])').on('click', function() {
                    var p = $(this).data('page');
                    if (p === 'prev') table.page('previous').draw('page');
                    else if (p === 'next') table.page('next').draw('page');
                    else table.page(p).draw('page');
                });
            }

            function updateInfo(s) {
                var api = new $.fn.dataTable.Api(s),
                    info = api.page.info();
                var t = 'Menampilkan <strong>' + (info.start + 1) + '</strong> - <strong>' + info.end +
                    '</strong> dari <strong>' + info.recordsDisplay + '</strong> data';
                if (info.recordsDisplay < info.recordsTotal) t += ' (difilter dari ' + info.recordsTotal +
                    ' total)';
                $('#tableInfo').html(t);
            }

            var editUrl = '{{ route('admin.products.edit', ':id') }}';
            var deleteUrl = '{{ route('admin.products.destroy', ':id') }}';

            var table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.products.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'banner_image',
                        name: 'banner_image',
                        orderable: false,
                        searchable: false,
                        render: function(d) {
                            if (d) return '<img src="/storage/' + d +
                                '" alt="Banner" class="table-cell-image">';
                            return '<div class="table-cell-placeholder"><i class="fas fa-image"></i></div>';
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(d) {
                            return '<div class="table-cell-name">' + d + '</div>';
                        }
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        orderable: false,
                        searchable: false,
                        render: function(d) {
                            return '<span class="table-cell-category">' + (d || '-') + '</span>';
                        }
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                        render: function(d) {
                            return '<div class="table-cell-slug">' + d + '</div>';
                        }
                    },
                    {
                        data: 'status_badge',
                        name: 'status'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<div class="table-cell-actions"><a href="' + editUrl.replace(
                                    ':id', row.id) +
                                '" class="btn-icon btn-icon-primary" title="Edit"><i class="fas fa-pen" style="font-size:13px"></i></a><button class="btn-icon btn-icon-danger" title="Hapus" onclick="deleteProduct(\'' +
                                row.id +
                                '\')"><i class="fas fa-trash" style="font-size:13px"></i></button></div>';
                        }
                    }
                ],
                dom: 'rt',
                pageLength: 10,
                language: {
                    zeroRecords: 'Tidak ada data yang cocok'
                },
                drawCallback: function(s) {
                    var api = this.api();
                    $('#tableCount').text(api.page.info().recordsDisplay);
                    $('#productsTable').show();
                    $('#tablePlaceholder').hide();
                    renderPagination(api.page.info(), api);
                    updateInfo(s);
                    // Mobile card rendering
                    renderMobileCards('#productsTable', function(row, idx) {
                        var imgHtml = row.banner_image ? '<img src="/storage/' + row
                            .banner_image + '" class="mobile-card-img" alt="">' :
                            '<div class="mobile-card-img-placeholder"><i class="fas fa-image"></i></div>';
                        var actions = '<div class="mobile-card-actions">' +
                            '<a href="' + editUrl.replace(':id', row.id) +
                            '" class="btn-icon btn-icon-primary"><i class="fas fa-pen"></i> Edit</a>' +
                            '<button class="btn-icon btn-icon-danger" onclick="deleteProduct(\'' +
                            row.id + '\')"><i class="fas fa-trash"></i> Hapus</button>' +
                            '</div>';
                        return '<div class="mobile-card-row-header">' + imgHtml +
                            '<div class="mobile-card-title">' + row.name + '</div></div>' +
                            '<div class="mobile-card-fields">' +
                            '<div class="mobile-card-field"><span class="mobile-card-field-label">Slug</span><span class="mobile-card-field-value" style="font-family:monospace;font-size:11px;color:var(--accent)">' +
                            row.slug + '</span></div>' +
                            '<div class="mobile-card-field"><span class="mobile-card-field-label">Kategori</span><span class="mobile-card-field-value">' +
                            (row.category_name || '-') + '</span></div>' +
                            '<div class="mobile-card-field"><span class="mobile-card-field-label">Status</span><span class="mobile-card-field-value">' +
                            (row.status_badge || '-') + '</span></div>' +
                            '</div>' + actions;
                    });
                }
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#lengthSelect').on('change', function() {
                table.page.len(parseInt(this.value)).draw();
            });

            window.deleteProduct = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                    $.ajax({
                        url: deleteUrl.replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        success: function(r) {
                            if (r.success) {
                                table.ajax.reload();
                                toastr.success(r.message, 'Berhasil');
                            }
                        },
                        error: function() {
                            toastr.error('Terjadi kesalahan saat menghapus data', 'Gagal');
                        }
                    });
                }
            };
        });
    </script>
@endpush
