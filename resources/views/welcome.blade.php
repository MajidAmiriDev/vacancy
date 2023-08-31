<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vacancy</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>
    <div class="container mt-5" style="max-width: 700px">
        <h2 class="h2 text-center mb-5 border-bottom pb-3"> Vacancy  </h2>
        <div id='full_calendar_events'></div>



            Start Date*<input type="date" id="start_date" name="start_date" value=""><br/>
            End Date*<input type="date" id="end_date" name="end_date" value=""><br/>
            Plus Adult <input type="number" id="adult" name="adult" value=""><br/>
            Plus Child <input type="number" id="child"  name="child" value=""><br/>
            Plus Baby<input type="number" id="baby" name="baby" value=""><br/>
            <input type="hidden" name="vacancy_id" value="<?=$vacancy_id?>"><br/>
            <button type="submit" id="submitVacancy" name="update">Calculate</button><br/>

    </div>
    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            var SITEURL = "{{ url('/') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $( "#submitVacancy" ).on( "click", function() {


                    $.ajax({
                        url: SITEURL + '/api/v1/vacancy/<?=$vacancy_id?>/price/calculate',
                        data: {
                            start: document.getElementById("start_date").value,
                            end: document.getElementById("end_date").value,
                            adult: document.getElementById("adult").value,
                            child: document.getElementById("child").value,
                            baby: document.getElementById("baby").value,
                        },
                        type: "POST",
                        success: function (response) {
                            alert(response.data.price)
                        },
                        error: function (response) {
                            alert(response.responseJSON.message)
                        },
                    });
            } );
            
            var calendar = $('#full_calendar_events').fullCalendar({
                editable: true,
                events: SITEURL + "/api/v1/vacancy/<?=$vacancy_id?>/events",
                displayEventTime: true,
                eventRender: function (event, element, view) {
                    
                    if (event.allDay === 'true') {
                            event.allDay = true;
                    } else {
                            event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function (event_start, event_end, allDay) {
                        var event_start = $.fullCalendar.formatDate(event_start, "Y-MM-DD");
                        var event_end = $.fullCalendar.formatDate(event_end, "Y-MM-DD");
                        document.getElementById("start_date").value= event_start;
                        document.getElementById("end_date").value= event_end;
                },
                eventDrop: function (event) {
                    event.preventDefault();
                    var event_start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                    var event_end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

                    $.ajax({
                        url: SITEURL + '/api/v1/vacancy/<?=$vacancy_id?>/price/calculate',
                        data: {
                            start: document.getElementById("start_date").value,
                            end: document.getElementById("end_date").value,
                            adult: document.getElementById("adult").value,
                            child: document.getElementById("child").value,
                            baby: document.getElementById("baby").value,
                        },
                        type: "POST",
                        success: function (response) {
                        }
                    });
                },
                eventClick: function (event) {

                }
            });
        });
        function displayMessage(message) {

            toastr.success(message, 'Event');            
        }
    </script>
</body>
</html>