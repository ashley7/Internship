{{-- Procedure Entries Section --}}
{{-- Pass $procedures (collection) and optionally $existingProcedures (collection of ReportProcedure) --}}
<div class="col-12">
    <label class="form-label d-flex justify-content-between align-items-center">
        <span>Procedures Logged <small class="text-muted fw-normal">(add one row per procedure)</small></span>
        <button type="button" id="add-procedure-btn" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Procedure
        </button>
    </label>

    <div id="procedures-container">
        @if(isset($existingProcedures) && $existingProcedures->count())
            @foreach($existingProcedures as $i => $rp)
            <div class="procedure-row d-flex align-items-center gap-2 mb-2 p-3 border rounded-3 bg-white">
                <div class="flex-grow-1">
                    <select name="procedures[{{ $i }}][procedure_id]" class="form-select form-select-sm" required>
                        <option value="">— Select Procedure —</option>
                        @foreach($procedures as $proc)
                            <option value="{{ $proc->id }}" {{ $rp->procedure_id == $proc->id ? 'selected' : '' }}>
                                {{ $proc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width:170px;">
                    <select name="procedures[{{ $i }}][role]" class="form-select form-select-sm" required>
                        <option value="">— Role —</option>
                        <option value="observed"  {{ $rp->role === 'observed'  ? 'selected' : '' }}>Observed</option>
                        <option value="assisted"  {{ $rp->role === 'assisted'  ? 'selected' : '' }}>Assisted</option>
                        <option value="performed" {{ $rp->role === 'performed' ? 'selected' : '' }}>Performed</option>
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-procedure" title="Remove">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endforeach
        @else
            {{-- one empty row by default --}}
            <div class="procedure-row d-flex align-items-center gap-2 mb-2 p-3 border rounded-3 bg-white">
                <div class="flex-grow-1">
                    <select name="procedures[0][procedure_id]" class="form-select form-select-sm">
                        <option value="">— Select Procedure —</option>
                        @foreach($procedures as $proc)
                            <option value="{{ $proc->id }}">{{ $proc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width:170px;">
                    <select name="procedures[0][role]" class="form-select form-select-sm">
                        <option value="">— Role —</option>
                        <option value="observed">Observed</option>
                        <option value="assisted">Assisted</option>
                        <option value="performed">Performed</option>
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-procedure" title="Remove">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
(function () {
    const container = document.getElementById('procedures-container');
    const addBtn    = document.getElementById('add-procedure-btn');

    // Procedure options HTML (built from server-rendered select)
    const firstSelect = container.querySelector('select[name^="procedures"][name$="[procedure_id]"]');
    const procOptions = firstSelect ? firstSelect.innerHTML : '';

    let rowIndex = container.querySelectorAll('.procedure-row').length;

    addBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'procedure-row d-flex align-items-center gap-2 mb-2 p-3 border rounded-3 bg-white';
        row.innerHTML = `
            <div class="flex-grow-1">
                <select name="procedures[${rowIndex}][procedure_id]" class="form-select form-select-sm">
                    ${procOptions}
                </select>
            </div>
            <div style="min-width:170px;">
                <select name="procedures[${rowIndex}][role]" class="form-select form-select-sm">
                    <option value="">— Role —</option>
                    <option value="observed">Observed</option>
                    <option value="assisted">Assisted</option>
                    <option value="performed">Performed</option>
                </select>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-procedure" title="Remove">
                <i class="bi bi-x-lg"></i>
            </button>`;
        container.appendChild(row);
        rowIndex++;
        bindRemove(row.querySelector('.remove-procedure'));
    });

    function bindRemove(btn) {
        btn.addEventListener('click', () => {
            if (container.querySelectorAll('.procedure-row').length > 1) {
                btn.closest('.procedure-row').remove();
            } else {
                // Reset the last row instead of removing it
                btn.closest('.procedure-row').querySelectorAll('select').forEach(s => s.selectedIndex = 0);
            }
        });
    }

    container.querySelectorAll('.remove-procedure').forEach(bindRemove);
})();
</script>
@endpush
