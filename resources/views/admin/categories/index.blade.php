@extends('layouts.admin')
@section('title', 'Categories')

@section('admin-content')
<div class="admin-table-wrap" style="margin-bottom:2rem;">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th>Active</th><th></th></tr></thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
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
                <tr><td colspan="5" class="empty-state">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="admin-form-card" style="max-width:480px;">
    <h3 style="margin-bottom:1rem;">Add Category</h3>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="form-field"><label>Name</label><input type="text" name="name" required></div>
        <div class="form-field"><label>Description</label><textarea name="description"></textarea></div>
        <button type="submit" class="btn btn-dark" style="margin-top:0.5rem;">Add Category</button>
    </form>
</div>
@endsection
