document.addEventListener("DOMContentLoaded", function() {
    // Function to convert a two-dimensional array (table data) to a CSV string
    function convertToCSV(dataArray) {
        var csv = "";
        for (var i = 0; i < dataArray.length; i++) {
            var row = dataArray[i].join(",");
            csv += row + "\n";
        }
        return csv;
    }

    // Function to collect all data from the table and export to CSV
    function exportAllToCSV() {
        // Get the table element by its ID
        var table = document.getElementById("booksTable");

        // Initialize an array to hold the table data
        var dataArray = [];

        // Loop through the table rows and cells to extract data
        for (var i = 0; i < table.rows.length; i++) {
            var row = [];
            for (var j = 0; j < table.rows[i].cells.length; j++) {
                row.push(table.rows[i].cells[j].innerText);
            }
            dataArray.push(row);
        }

        // Convert the table data to a CSV string
        var csvContent = convertToCSV(dataArray);

        // Create a Blob (Binary Large Object) with the CSV content
        var blob = new Blob([csvContent], { type: "text/csv" });

        // Create a URL for the Blob
        var url = window.URL.createObjectURL(blob);

        // Create a temporary anchor element to trigger the download
        var a = document.createElement("a");
        a.style.display = "none";
        a.href = url;
        a.download = "library_report.csv";

        // Trigger the click event of the anchor element
        document.body.appendChild(a);
        a.click();

        // Clean up
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }

    // Attach the exportAllToCSV function to the "Export All to CSV" button click event
    var exportAllButton = document.getElementById("exportAllButton");
    exportAllButton.addEventListener("click", exportAllToCSV);
});
