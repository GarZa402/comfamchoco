<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendario de Ausencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('calendario.index') }}" method="GET" class="flex flex-wrap gap-4">
                        <div class="w-full md:w-1/3">
                            <label for="departamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departamento</label>
                            <select name="departamento" id="departamento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos los departamentos</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento }}" {{ request('departamento') == $departamento ? 'selected' : '' }}>{{ $departamento }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-1/3">
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Permiso</label>
                            <select name="tipo" id="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos los tipos</option>
                                <option value="vacaciones" {{ request('tipo') == 'vacaciones' ? 'selected' : '' }}>Vacaciones</option>
                                <option value="licencia" {{ request('tipo') == 'licencia' ? 'selected' : '' }}>Licencia</option>
                                <option value="permiso" {{ request('tipo') == 'permiso' ? 'selected' : '' }}>Permiso</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/4 flex items-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex flex-wrap gap-4 mb-4">
                            <div class="flex items-center">
                                <span class="w-4 h-4 inline-block mr-1 bg-green-500 rounded"></span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Vacaciones</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-4 h-4 inline-block mr-1 bg-blue-500 rounded"></span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Licencia</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-4 h-4 inline-block mr-1 bg-orange-500 rounded"></span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Permiso</span>
                            </div>
                        </div>
                    </div>
                    <div id="calendario" class="bg-white dark:bg-gray-700 p-4 rounded-lg"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendario');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: @json($eventos),
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>