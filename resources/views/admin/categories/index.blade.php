@extends('layouts.admin')
@section('title', 'Categories')

@section('admin-content')
<div class="admin-grid-2">
    <div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th>Active</th><th></th></tr></thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="admin-cell-primary">{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="name" value="{{ $category->name }}">
                                    <input type="checkbox" name="is_active" value="1" @checked($category->is_active) onchange="this.form.requestSubmit()">
                                </form>
                            </td>
                            <td class="admin-table-actions">
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete category?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">
                            <div class="admin-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/></svg>
                                <p>No categories yet.</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-panel" style="margin-bottom:0;">
        <div class="admin-panel-head"><h3>Add Category</h3></div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-field"><label>Name</label><input type="text" name="name" required></div>
            <div class="form-field"><label>Description</label><textarea name="description"></textarea></div>
            <button type="submit" class="admin-btn admin-btn-dark" style="margin-top:0.5rem;">Add Category</button>
        </form>
    </div>
</div>
@endsection
