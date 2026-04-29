{{-- resources/views/admin/rooms/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Ruangan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.rooms.index') }}"
           class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Edit Ruangan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $room->name }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="space-y-5">
            @csrf @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Ruangan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $room->name) }}"
                       class="w-full px-4 py-2.5 text-sm rounded-xl border @error('name') border-red-400 @else border-slate-200 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kode --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Kode Ruangan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code" id="code" value="{{ old('code', $room->code) }}"
                       class="w-full px-4 py-2.5 text-sm rounded-xl border @error('code') border-red-400 @else border-slate-200 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Kosongkan untuk generate ulang dari nama</p>
                @error('code')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Deskripsi
                    <span class="ml-1 text-xs font-normal text-slate-400 dark:text-slate-500">(opsional)</span>
                </label>
                <textarea name="description" rows="3"
                          placeholder="Keterangan tambahan tentang ruangan..."
                          class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('description', $room->description) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-700">
                <a href="{{ route('admin.rooms.show', $room) }}"
                   class="text-sm text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    Lihat detail
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.rooms.index') }}"
                       class="px-5 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');

    function generateCode(name) {
        return name
            .toUpperCase()
            .replace(/[^A-Z0-9\s]/g, '')
            .trim()
            .split(/\s+/)
            .filter(Boolean)
            .map(w => w.slice(0, 3))
            .join('-')
            .slice(0, 20);
    }

    codeInput.addEventListener('input', function () {
        this._cleared = this.value.trim() === '';
    });

    codeInput.addEventListener('blur', function () {
        if (this.value.trim() === '' && nameInput.value.trim() !== '') {
            this.value = generateCode(nameInput.value);
            this._cleared = false;
        }
    });

    nameInput.addEventListener('input', function () {
        if (codeInput._cleared || codeInput.value.trim() === '') {
            codeInput.value = generateCode(this.value);
        }
    });
</script>
@endpush