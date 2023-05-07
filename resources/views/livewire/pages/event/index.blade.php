<div>
    <div wire:loading class="loading_overlay">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>

    <div class="flex flex-row justify-center items-center">
        <div class="calendar_card bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="flex flex-row justify-around items-center gap-0">
                <div class="calendar_form flex flex-row justify-center items-center">
                    <form wire:submit.prevent="saveEvent" class="">
                        <div class="w-full max-w-xs">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                                    Event
                                </label>
                                <input required wire:model.defer="eventForm.title"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    type="text" placeholder="Enter Event">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                                    Start
                                </label>
                                <input min="{{now()->format('Y-m-d')}}" required wire:model="eventForm.start"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    type="date" placeholder="Enter Event">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                                    End
                                </label>
                                <input min="{{$maxEnd}}" required wire:model.defer="eventForm.end"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    type="date" placeholder="Enter Event">
                            </div>

                            <div
                                class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    type="submit">
                                    Add
                                </button>
                                <button
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                                    type="button"
                                    wire:click="override">
                                    Override
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id='calendar' class="grow" wire:ignore></div>
            </div>
        </div>
    </div>


    <!-- Modal toggle -->
    <button id="modalBtn" data-modal-target="eventDetailsModal" data-modal-toggle="eventDetailsModal"
        class="hidden block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Toggle modal
    </button>

    <!-- Main modal -->
    <div id="eventDetailsModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] justify-center align-middle">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Events
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="eventDetailsModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    @foreach ($data as $key => $d)
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            <span>{{$key + 1}}. </span> <span>{{$d->title}}</span>
                        </p>
                    @endforeach

                    @if (count($data) <= 0)
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            No Events
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .calendar_card {
            margin: 100px;
            width: 100%;
            background-color: #02afa0;
        }

        #calendar {
            margin: 0;
            padding: 10px;
            height: 70vh;
            width: 100%;
            color: white;
        }

        .calendar_form {
            padding: 10vh;
            background-color: white;
            height: 100%;
        }

        .loading_overlay {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            position: fixed;
            background: rgba(34, 34, 34, 0.8);
            z-index: 99;
        }

        .overlay__inner {
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            position: absolute;
        }

        .overlay__content {
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .spinner {
            width: 75px;
            height: 75px;
            display: inline-block;
            border-width: 2px;
            border-color: rgba(255, 255, 255, 0.05);
            border-top-color: #fff;
            animation: spin 1s infinite linear;
            border-radius: 100%;
            border-style: solid;
        }

        .fc .fc-daygrid-day-frame {
            cursor: pointer;
        }

        .fc .fc-daygrid-day-frame:hover {
            background-color: #05caba;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initCalendar();
        });

        function initCalendar() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events),

                dateClick: function(info) {
                    showEvents(info.dateStr);
                }
            });
            calendar.render();
        }

        async function showEvents(dateString) {
            await @this.getEvents(dateString);
            $('#modalBtn').click();
            initCalendar();
        }
    </script>
</div>
