<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private UserService $userService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->buildDataTable();
        }

        return view('admin.users.index');
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(string $id): View
    {
        $user = $this->userRepository->findByIdOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        $user = $this->userRepository->findByIdOrFail($id);

        $this->userService->update($user, $request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $user = $this->userRepository->findByIdOrFail($id);

        $deleted = $this->userService->delete($user);

        if (!$deleted) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri'], 403);
            }
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }

    private function buildDataTable()
    {
        $data = $this->userRepository->getForDataTable();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                return $this->buildStatusBadge($row->is_active);
            })
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    private function buildStatusBadge(bool $isActive): string
    {
        $class = $isActive ? 'badge-active' : 'badge-inactive';
        $text = $isActive ? 'Active' : 'Inactive';
        return '<span class="badge-status ' . e($class) . '">' . e($text) . '</span>';
    }
}