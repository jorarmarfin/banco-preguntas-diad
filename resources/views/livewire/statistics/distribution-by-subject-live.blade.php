<div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">Distribución por asignatura</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Resumen por dificultad y aprobadas</p>
                </div>
                <div class="flex items-center gap-2">
                    <label for="subject-select" class="sr-only">Asignatura</label>
                    <select id="subject-select" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-neutral-400 focus:outline-none focus:ring-0 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" wire:model.live="subjectId">
                        <option value="">Seleccione asignatura</option>
                        @foreach($subjects as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <!-- Dificultad -->
                <div class="rounded-lg border border-dashed border-neutral-300 p-4 dark:border-neutral-700">
                    <h4 class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Por dificultad</h4>
                    <div class="mt-3 grid grid-cols-3 gap-2">
                        <div class="rounded-lg bg-emerald-50 p-3 dark:bg-emerald-900/20">
                            <div class="text-[10px] font-medium uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Fácil</div>
                            <div class="mt-1 text-xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $difficultyCounts['easy'] ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg bg-neutral-100 p-3 dark:bg-neutral-800">
                            <div class="text-[10px] font-medium uppercase tracking-wide text-neutral-700 dark:text-neutral-300">Normal</div>
                            <div class="mt-1 text-xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $difficultyCounts['normal'] ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg bg-rose-50 p-3 dark:bg-rose-900/20">
                            <div class="text-[10px] font-medium uppercase tracking-wide text-rose-700 dark:text-rose-300">Difícil</div>
                            <div class="mt-1 text-xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $difficultyCounts['hard'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <!-- Aprobadas -->
                <div class="rounded-lg border border-dashed border-neutral-300 p-4 dark:border-neutral-700">
                    <h4 class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Aprobadas</h4>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-green-50 p-3 dark:bg-green-900/20">
                            <div class="text-[10px] font-medium uppercase tracking-wide text-green-700 dark:text-green-300">Total aprobadas</div>
                            <div class="mt-1 text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $approvedCount }}</div>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                            <div class="text-[10px] font-medium uppercase tracking-wide text-blue-700 dark:text-blue-300">% del total</div>
                            <div class="mt-1 text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $totalCount > 0 ? round(($approvedCount / $totalCount) * 100) : 0 }}%</div>
                        </div>
                    </div>
                    <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-800">
                        <div class="h-full rounded-full bg-green-500 transition-all dark:bg-green-400" style="width: {{ $totalCount > 0 ? min(100, max(0, round(($approvedCount / $totalCount) * 100))) : 0 }}%"></div>
                    </div>
                    <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">Proporción de preguntas aprobadas dentro de la asignatura</div>
                </div>
            </div>
        </div>
