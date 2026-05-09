@csrf

@vite(['resources/js/discounts_form.js'])
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="codigo" class="form-label fw-semibold">{{ __('admin/discounts/_form.code_label') }}</label>
                        <input id="codigo" name="codigo" value="{{ old('codigo', $discount->codigo) }}"
                               class="form-control @error('codigo') is-invalid @enderror"
                               placeholder="{{ __('admin/discounts/_form.code_placeholder') }}" required>
                        <div class="form-text">{{ __('admin/discounts/_form.code_hint') }}</div>
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tipo" class="form-label fw-semibold">{{ __('admin/discounts/_form.type_label') }}</label>
                        <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="" disabled selected>{{ __('admin/discounts/_form.type_placeholder') }}</option>
                            <option value="porcentaje" {{ old('tipo', $discount->tipo) == 'porcentaje' ? 'selected' : '' }}>{{ __('admin/discounts/_form.type_percentage') }}</option>
                            <option value="fijo" {{ old('tipo', $discount->tipo) == 'fijo' ? 'selected' : '' }}>{{ __('admin/discounts/_form.type_fixed') }}</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="valor" class="form-label fw-semibold">{{ __('admin/discounts/_form.value_label') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" id="valor" name="valor"
                                   value="{{ old('valor', $discount->valor) }}"
                                   class="form-control @error('valor') is-invalid @enderror"
                                   placeholder="0.00" required>
                            <span class="input-group-text" id="valor-addon">{{ __('admin/discounts/_form.value_addon') }}</span>
                        </div>
                        @error('valor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="fecha_fin" class="form-label fw-semibold">{{ __('admin/discounts/_form.expiry_label') }}</label>
                        <input type="datetime-local" id="fecha_fin" name="fecha_fin"
                               value="{{ old('fecha_fin', $discount->fecha_fin ? $discount->fecha_fin->format('Y-m-d\TH:i') : '') }}"
                               class="form-control @error('fecha_fin') is-invalid @enderror" required>
                        @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary fw-bold flex-grow-1" type="submit">
                        <i class="bi bi-save me-1"></i> {{ $discount->exists ? __('admin/discounts/_form.update') : __('admin/discounts/_form.create') }}
                    </button>
                    <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-dark">{{ __('admin/discounts/_form.cancel') }}</a>
                </div>

            </div>
        </div>
    </div>
</div>
