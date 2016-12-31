function reminder(numMilestones) {
    if (document.getElementById("addReminder").checked) {
        if (confirm("You want to add a reminder?") == true) {
            numMilestones++;
            var hwName = document.getElementById("hwName").value;
            var due = document.getElementById("dueDate").value;
            var courseCode = document.getElementById("courseCode").value;
            var url = "addHW.php?courseCode=" + courseCode + "&numMilestones=" + numMilestones + "&hwTitle=" + hwName + "&due=" + due;
            window.location.href = url;
        } else {
            window.location.href = "manageCourses.php";
        }
    }
}

function confirmReservation(reservationID) {
    if (confirm("Confirm this reservation?")) {
            window.location.href = "confirmation.php?resID=" + reservationID;
    }
}