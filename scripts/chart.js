document.addEventListener("DOMContentLoaded", function () {
    // Pie Chart (Civilians vs. Taskers)
    const ctxPie = document.getElementById("userPieChart").getContext("2d");

    new Chart(ctxPie, {
        type: "pie",
        data: {
            labels: ["Citizens", "Taskers"],
            datasets: [
                {
                    data: [civiliansPercentage, taskersPercentage],
                    backgroundColor: ["#4CAF50", "#2196F3"], // Green for Civilians, Blue for Taskers
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    });

    // Bar Chart (Verification Requests for Civilians)
    const ctxBar = document.getElementById("verificationBarChart").getContext("2d");

    new Chart(ctxBar, {
        type: "bar",
        data: {
            labels: ["Pending", "Rejected", "Approved"],
            datasets: [
                {
                    label: "Civilian Verification Requests",
                    data: [pendingCivilians, rejectedCivilians, approvedCivilians],
                    backgroundColor: ["#26abff", "#FF0000", "#008000"], // Orange, Red, Green
                    borderColor: ["#CC8400", "#CC0000", "#006400"],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
});
