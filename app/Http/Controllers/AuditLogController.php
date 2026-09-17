<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = AuditLog::with('user')->latest('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->whereLike('action', "%{$search}%", caseSensitive: false)
                    ->orWhereLike('model_type', "%{$search}%", caseSensitive: false)
                    ->orWhereLike('description', "%{$search}%", caseSensitive: false);
            });
        }

        return view('audit.index', ['logs' => $query->paginate(25)->withQueryString()]);
    }
}
