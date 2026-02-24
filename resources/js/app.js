import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";

import "flyonui/src/vendor/fullcalendar.css";

function initCalendar(el) {
    if (!el) return;
    const calendarDefault = new FullCalendar.Calendar(
        document.getElementById("calendar-container"),
        {
            initialView: "dayGridMonth",
            events: "/calendar/events",
        },
    );
    calendarDefault.render();
}

