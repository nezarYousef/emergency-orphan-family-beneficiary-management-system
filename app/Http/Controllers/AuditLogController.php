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
                $builder->where('action', 'ilike', "%{$search}%")
                    ->orWhere('model_type', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        return view('audit.index', ['logs' => $query->paginate(25)->withQueryString()]);
    }
}
