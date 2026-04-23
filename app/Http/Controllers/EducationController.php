<?php

namespace App\Http\Controllers;

use App\Models\EducationAccessLog;
use App\Models\EducationContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $query = EducationContent::where('is_published', true);

        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('type'))     $query->where('type', $request->type);
        if ($request->filled('search'))   $query->where('title', 'like', '%'.$request->search.'%');

        $contents   = $query->latest()->paginate(12)->withQueryString();
        $categories = EducationContent::where('is_published', true)->distinct()->pluck('category');

        return view('education.index', compact('contents', 'categories'));
    }

    public function show(EducationContent $content)
    {
        abort_if(!$content->is_published && !auth()->user()->canManageCases(), 403);

        EducationAccessLog::create([
            'user_id'              => Auth::id(),
            'education_content_id' => $content->id,
            'accessed_at'          => now(),
        ]);

        $content->increment('view_count');

        return view('education.show', compact('content'));
    }

    public function create()
    {
        $this->authorize('create', EducationContent::class);
        return view('education.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', EducationContent::class);

        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'type'         => 'required|in:artikel,video,infografis,panduan',
            'category'     => 'required|string|max:100',
            'file'         => 'nullable|file|max:51200',
            'external_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $data = $request->only(['title', 'description', 'type', 'category', 'external_url', 'is_published']);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('education', 'public');
        }

        EducationContent::create($data);
        \App\Models\AuditLog::record('education_created');

        return redirect()->route('education.index')->with('success', 'Konten berhasil ditambahkan.');
    }

    public function edit(EducationContent $content)
    {
        $this->authorize('update', $content);
        return view('education.edit', compact('content'));
    }

    public function update(Request $request, EducationContent $content)
    {
        $this->authorize('update', $content);

        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'type'         => 'required|in:artikel,video,infografis,panduan',
            'category'     => 'required|string|max:100',
            'external_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $data = $request->only(['title', 'description', 'type', 'category', 'external_url', 'is_published']);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('education', 'public');
        }

        $content->update($data);
        return redirect()->route('education.index')->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(EducationContent $content)
    {
        $this->authorize('delete', $content);
        $content->delete();
        return redirect()->route('education.index')->with('success', 'Konten berhasil dihapus.');
    }
}
