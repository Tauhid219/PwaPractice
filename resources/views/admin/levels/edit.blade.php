<x-admin-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Level</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.levels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Edit Details: <strong>{{ $level->name }}</strong></h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('admin.levels.update', $level->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $level->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Level Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Level 1" value="{{ old('name', $level->name) }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Order</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $level->order) }}" required min="1">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="required_score_to_unlock">Required Score to Unlock</label>
                            <input type="number" name="required_score_to_unlock" id="required_score_to_unlock" class="form-control @error('required_score_to_unlock') is-invalid @enderror" value="{{ old('required_score_to_unlock', $level->required_score_to_unlock) }}" required min="0">
                            <small class="form-text text-muted">Minimum score needed in previous level to unlock this one.</small>
                            @error('required_score_to_unlock')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="is_free" value="0">
                                <input type="checkbox" name="is_free" class="custom-control-input" id="is_free" value="1" {{ old('is_free', $level->is_free) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_free">Is Free?</label>
                            </div>
                            @error('is_free')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-sync-alt mr-1"></i> Update Level
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</x-admin-layout>
