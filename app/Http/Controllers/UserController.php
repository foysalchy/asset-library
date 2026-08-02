<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Helpers\FileUploadHelper;
use App\Models\ActivityLog;
use App\Models\DownloadLog;

class UserController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog
    ) {}
    const LOGS_PER_PAGE = 10;


    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role_id')) {
            $query->whereHas('roles', fn($q) => $q->where('roles.id', $request->role_id));
        }

        $users = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $roles = Role::orderBy('label')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('label')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status'   => ['required', Rule::in(['active', 'inactive'])],
            'roles'    => ['nullable', 'array'],
            'roles.*'  => ['exists:roles,id'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = FileUploadHelper::uploadImage($request->file('avatar'), 'users/avatars');
        }

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'password'   => Hash::make($request->password),
            'status'     => $request->status,
            'avatar'     => $avatarPath,
            'created_by' => auth()->id(),
        ]);

        if ($request->filled('roles')) {
            $user->roles()->sync($request->roles);
        }

        $this->activityLog->log('created', $user, "Created user: {$user->name}");

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles.permissions');

        $downloadLogs = DownloadLog::where('user_id', $user->id)
            ->latest()
            ->limit(self::LOGS_PER_PAGE)
            ->get();
        $this->attachTitles($downloadLogs);
        $downloadLogsTotal = DownloadLog::where('user_id', $user->id)->count();

        $logs = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->limit(self::LOGS_PER_PAGE)
            ->get();

        $logsTotal = ActivityLog::where('user_id', $user->id)->count();

        return view('users.show', compact(
            'user',
            'logs',
            'logsTotal',
            'downloadLogs',
            'downloadLogsTotal'
        ));
    }

    public function loadMoreDownloadLogs(User $user, Request $request)
    {
        $offset = (int) $request->query('offset', self::LOGS_PER_PAGE);

        $downloadLogs = DownloadLog::where('user_id', $user->id)
            ->latest()
            ->skip($offset)
            ->take(self::LOGS_PER_PAGE)
            ->get();
        $this->attachTitles($downloadLogs);
        $total = DownloadLog::where('user_id', $user->id)->count();

        return response()->json([
            'html'    => view('users.partials.download-log-rows', compact('downloadLogs'))->render(),
            'hasMore' => ($offset + $downloadLogs->count()) < $total,
        ]);
    }

    public function loadMoreActivityLogs(User $user, Request $request)
    {
        $offset = (int) $request->query('offset', self::LOGS_PER_PAGE);

        $logs = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->skip($offset)
            ->take(self::LOGS_PER_PAGE)
            ->get();

        $total = ActivityLog::where('user_id', $user->id)->count();

        return response()->json([
            'html'    => view('users.partials.activity-log-rows', compact('logs'))->render(),
            'hasMore' => ($offset + $logs->count()) < $total,
        ]);
    }

    // UserController.php — helper method
    private function attachTitles($downloadLogs)
    {
        $downloadLogs->groupBy('model')->each(function ($logs, $modelName) {
            $class = "App\\Models\\{$modelName}";
            if (!class_exists($class)) return;

            $ids = $logs->pluck('model_id');
            $records = $class::whereIn('id', $ids)->get()->keyBy('id');

            $logs->each(function ($log) use ($records) {
                $related = $records->get($log->model_id);
                $log->setAttribute(
                    'title',
                    $related->title ?? $related->name ?? "{$log->model} #{$log->model_id}"
                );
            });
        });

        return $downloadLogs;
    }

    public function edit(User $user)
    {
        $roles        = Role::orderBy('label')->get();
        $assignedRoles = $user->roles->pluck('id')->toArray();

        return view('users.edit', compact('user', 'roles', 'assignedRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status'   => ['required', Rule::in(['active', 'inactive'])],
            'roles'    => ['nullable', 'array'],
            'roles.*'  => ['exists:roles,id'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'name'   => $request->name,
            'avatar'   => $request->avatar,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            FileUploadHelper::deleteImage($user->avatar);
            $data['avatar'] = FileUploadHelper::uploadImage($request->file('avatar'), 'users/avatars');
        } elseif ($request->boolean('remove_avatar')) {
            FileUploadHelper::deleteImage($user->avatar);
            $data['avatar'] = null;
        }

        $user->update($data);
        if (!$user->isSuperAdmin()) {
            $user->roles()->sync($request->roles ?? []);
        }

        $this->activityLog->log('updated', $user, "Updated user: {$user->name}");

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->isSuperAdmin()) {
            return redirect()->route('users.index')->with('error', 'Super admin cannot be deleted.');
        }

        FileUploadHelper::deleteImage($user->avatar);
        $this->activityLog->log('deleted', $user, "Deleted user: {$user->name}");
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', "User {$user->name} has been " . ($user->status === 'active' ? 'activated' : 'deactivated') . ".");
    }
}
